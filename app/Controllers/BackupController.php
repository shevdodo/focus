<?php
namespace App\Controllers;

use App\Config\Database;
use PDO;
use PDOException;

class BackupController {

    private function getBackupDir(): string {
        $backupDir = dirname(__DIR__, 2) . '/database/backups';
        if (!is_dir($backupDir)) {
            mkdir($backupDir, 0755, true);
        }
        return $backupDir;
    }

    private function formatBytes(int $bytes, int $precision = 2): string {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);
        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    private function verifyAdminAccess(): void {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            setFlash('error', 'Akses Ditolak! Modul Backup & Restore hanya dapat diakses oleh Administrator.');
            header('Location: ' . baseUrl('/'));
            exit;
        }
    }

    /**
     * Display the Backup & Restore dashboard
     */
    public function index(): void {
        $this->verifyAdminAccess();

        $db = Database::getConnection();
        $dbPath = Database::getDatabasePath();

        // 1. Gather database statistics
        $dbSize = file_exists($dbPath) ? filesize($dbPath) : 0;
        $dbFormattedSize = $this->formatBytes($dbSize);
        $dbLastModified = file_exists($dbPath) ? date('d M Y H:i:s', filemtime($dbPath)) : '-';

        $totalPatients = (int)$db->query("SELECT COUNT(*) FROM patients")->fetchColumn();
        $totalRecords = (int)$db->query("SELECT COUNT(*) FROM medical_records")->fetchColumn();
        $totalLenses = (int)$db->query("SELECT COUNT(*) FROM lenses")->fetchColumn();
        $totalFrames = (int)$db->query("SELECT COUNT(*) FROM frames")->fetchColumn();
        $totalAdmins = (int)$db->query("SELECT COUNT(*) FROM users WHERE role = 'admin'")->fetchColumn();
        $totalStaff = (int)$db->query("SELECT COUNT(*) FROM users WHERE role != 'admin'")->fetchColumn();

        // 2. Scan backup directory for existing snapshots
        $backupDir = $this->getBackupDir();
        $backupFiles = [];

        if (is_dir($backupDir)) {
            $files = scandir($backupDir);
            foreach ($files as $file) {
                if ($file === '.' || $file === '..' || $file === '.htaccess' || $file === 'index.html') {
                    continue;
                }

                $filePath = $backupDir . '/' . $file;
                if (is_file($filePath) && preg_match('/\.(db|sqlite)$/i', $file)) {
                    $fileMtime = filemtime($filePath);
                    $fileSize = filesize($filePath);
                    $isAuto = strpos($file, 'pre_') === 0 || strpos($file, 'auto_') === 0;

                    $backupFiles[] = [
                        'name' => $file,
                        'path' => $filePath,
                        'size' => $this->formatBytes($fileSize),
                        'size_bytes' => $fileSize,
                        'date' => date('d M Y H:i:s', $fileMtime),
                        'timestamp' => $fileMtime,
                        'is_auto' => $isAuto
                    ];
                }
            }

            // Sort newest first
            usort($backupFiles, function ($a, $b) {
                return $b['timestamp'] <=> $a['timestamp'];
            });
        }

        $title = "Backup & Restore Database";
        $subtitle = "Cadangkan, pulihkan, dan kelola integritas basis data sistem Klinik OPTIK FOCUS";

        require dirname(__DIR__, 2) . '/views/templates/header.php';
        require dirname(__DIR__, 2) . '/views/backup/index.php';
        require dirname(__DIR__, 2) . '/views/templates/footer.php';
    }

    /**
     * Download the live active database directly (.db)
     */
    public function download(): void {
        $this->verifyAdminAccess();

        $dbPath = Database::getDatabasePath();

        if (!file_exists($dbPath)) {
            setFlash('error', 'File database tidak ditemukan!');
            header('Location: ' . baseUrl('backup'));
            exit;
        }

        // Flush any active write-ahead log to main DB file
        try {
            $db = Database::getConnection();
            $db->exec('PRAGMA wal_checkpoint(FULL);');
        } catch (\Throwable $e) {}

        $downloadFilename = 'backup_optik_focus_' . date('Y-m-d_His') . '.db';

        // Clear output buffer
        while (ob_get_level()) {
            ob_end_clean();
        }

        header('Content-Description: File Transfer');
        header('Content-Type: application/x-sqlite3');
        header('Content-Disposition: attachment; filename="' . $downloadFilename . '"');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($dbPath));

        readfile($dbPath);
        exit;
    }

    /**
     * Create a server-side snapshot in database/backups/
     */
    public function createSnapshot(): void {
        $this->verifyAdminAccess();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . baseUrl('backup'));
            exit;
        }

        $dbPath = Database::getDatabasePath();
        $backupDir = $this->getBackupDir();

        if (!file_exists($dbPath)) {
            setFlash('error', 'File database aktif tidak ditemukan!');
            header('Location: ' . baseUrl('backup'));
            exit;
        }

        try {
            $db = Database::getConnection();
            $db->exec('PRAGMA wal_checkpoint(FULL);');
        } catch (\Throwable $e) {}

        $note = trim($_POST['snapshot_note'] ?? '');
        $safeNote = preg_replace('/[^a-zA-Z0-9_-]/', '_', $note);
        $noteSuffix = !empty($safeNote) ? '_' . substr($safeNote, 0, 20) : '';

        $snapshotName = 'backup_optik_focus_' . date('Y-m-d_His') . $noteSuffix . '.db';
        $destPath = $backupDir . '/' . $snapshotName;

        if (copy($dbPath, $destPath)) {
            setFlash('success', 'Cadangan snapshot server berhasil dibuat: <strong>' . htmlspecialchars($snapshotName) . '</strong>');
        } else {
            setFlash('error', 'Gagal membuat file cadangan di server!');
        }

        header('Location: ' . baseUrl('backup'));
        exit;
    }

    /**
     * Download a specific snapshot file
     */
    public function downloadSnapshot(): void {
        $this->verifyAdminAccess();

        $filename = basename($_GET['file'] ?? '');
        $backupDir = $this->getBackupDir();
        $filePath = $backupDir . '/' . $filename;

        if (empty($filename) || !file_exists($filePath) || !preg_match('/\.(db|sqlite)$/i', $filename)) {
            setFlash('error', 'File cadangan tidak ditemukan atau tidak valid!');
            header('Location: ' . baseUrl('backup'));
            exit;
        }

        while (ob_get_level()) {
            ob_end_clean();
        }

        header('Content-Description: File Transfer');
        header('Content-Type: application/x-sqlite3');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Transfer-Encoding: binary');
        header('Expires: 0');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filePath));

        readfile($filePath);
        exit;
    }

    /**
     * Delete a specific snapshot file
     */
    public function deleteSnapshot(): void {
        $this->verifyAdminAccess();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . baseUrl('backup'));
            exit;
        }

        $filename = basename($_POST['filename'] ?? '');
        $backupDir = $this->getBackupDir();
        $filePath = $backupDir . '/' . $filename;

        if (empty($filename) || !file_exists($filePath) || !preg_match('/\.(db|sqlite)$/i', $filename)) {
            setFlash('error', 'File cadangan tidak ditemukan!');
            header('Location: ' . baseUrl('backup'));
            exit;
        }

        if (unlink($filePath)) {
            setFlash('success', 'File cadangan <strong>' . htmlspecialchars($filename) . '</strong> berhasil dihapus.');
        } else {
            setFlash('error', 'Gagal menghapus file cadangan.');
        }

        header('Location: ' . baseUrl('backup'));
        exit;
    }

    /**
     * Restore database by uploading a backup .db or .sqlite file
     */
    public function restore(): void {
        $this->verifyAdminAccess();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . baseUrl('backup'));
            exit;
        }

        if (!isset($_FILES['backup_file']) || $_FILES['backup_file']['error'] !== UPLOAD_ERR_OK) {
            $errorMsg = 'Gagal mengunggah file cadangan. ';
            if (isset($_FILES['backup_file']['error'])) {
                switch ($_FILES['backup_file']['error']) {
                    case UPLOAD_ERR_INI_SIZE:
                    case UPLOAD_ERR_FORM_SIZE:
                        $errorMsg .= 'Ukuran file melebihi batas maksimal server.';
                        break;
                    case UPLOAD_ERR_NO_FILE:
                        $errorMsg .= 'Silakan pilih file database yang akan diunggah.';
                        break;
                    default:
                        $errorMsg .= 'Kode error upload: ' . $_FILES['backup_file']['error'];
                }
            }
            setFlash('error', $errorMsg);
            header('Location: ' . baseUrl('backup'));
            exit;
        }

        $uploadedFile = $_FILES['backup_file']['tmp_name'];
        $originalName = $_FILES['backup_file']['name'];
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));

        if (!in_array($ext, ['db', 'sqlite', 'sqlite3'])) {
            setFlash('error', 'Format file tidak didukung! Harap unggah file database SQLite (.db atau .sqlite).');
            header('Location: ' . baseUrl('backup'));
            exit;
        }

        // Validate SQLite Magic Header: First 16 bytes must be "SQLite format 3\000"
        $handle = fopen($uploadedFile, 'rb');
        $header = fread($handle, 16);
        fclose($handle);

        if ($header !== "SQLite format 3\000") {
            setFlash('error', 'File yang diunggah bukan file database SQLite yang valid atau file mengalami kerusakan (corrupted)!');
            header('Location: ' . baseUrl('backup'));
            exit;
        }

        // Validate that the uploaded database can be opened with PDO and has expected tables
        try {
            $testPdo = new PDO('sqlite:' . $uploadedFile);
            $testPdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $tables = $testPdo->query("SELECT name FROM sqlite_master WHERE type='table'")->fetchAll(PDO::FETCH_COLUMN);

            if (!in_array('users', $tables)) {
                setFlash('error', 'File database tidak kompatibel: Struktur tabel pengguna (users) tidak ditemukan di dalam file.');
                header('Location: ' . baseUrl('backup'));
                exit;
            }
            $testPdo = null; // Close handle
        } catch (\Throwable $e) {
            setFlash('error', 'File database tidak dapat dibaca: ' . $e->getMessage());
            header('Location: ' . baseUrl('backup'));
            exit;
        }

        $dbPath = Database::getDatabasePath();
        $backupDir = $this->getBackupDir();

        // 1. Create an automated safety backup of the current database before replacing
        if (file_exists($dbPath)) {
            $autoBackupName = 'pre_restore_auto_backup_' . date('Y-m-d_His') . '.db';
            copy($dbPath, $backupDir . '/' . $autoBackupName);
        }

        // 2. Close active database connection to release file lock on Windows
        Database::closeConnection();
        gc_collect_cycles();

        // 3. Replace live database file
        if (copy($uploadedFile, $dbPath)) {
            // Test connection with the newly restored DB
            try {
                $newDb = Database::getConnection();
                $adminCount = $newDb->query("SELECT COUNT(*) FROM users WHERE role = 'admin'")->fetchColumn();
                setFlash('success', 'Database berhasil dipulihkan dari file <strong>' . htmlspecialchars($originalName) . '</strong>! Cadangan pengaman pra-pemulihan telah tersimpan otomatis.');
            } catch (\Throwable $e) {
                setFlash('warning', 'Database dipulihkan namun terjadi peringatan saat inisialisasi: ' . $e->getMessage());
            }
        } else {
            setFlash('error', 'Gagal menimpa file database aktif saat proses pemulihan.');
        }

        header('Location: ' . baseUrl('backup'));
        exit;
    }

    /**
     * Restore database from an existing snapshot file on the server
     */
    public function restoreFromSnapshot(): void {
        $this->verifyAdminAccess();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . baseUrl('backup'));
            exit;
        }

        $filename = basename($_POST['filename'] ?? '');
        $backupDir = $this->getBackupDir();
        $snapshotPath = $backupDir . '/' . $filename;

        if (empty($filename) || !file_exists($snapshotPath) || !preg_match('/\.(db|sqlite)$/i', $filename)) {
            setFlash('error', 'File snapshot tidak ditemukan!');
            header('Location: ' . baseUrl('backup'));
            exit;
        }

        // Test SQLite file validity
        try {
            $testPdo = new PDO('sqlite:' . $snapshotPath);
            $testPdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $tables = $testPdo->query("SELECT name FROM sqlite_master WHERE type='table'")->fetchAll(PDO::FETCH_COLUMN);
            if (!in_array('users', $tables)) {
                setFlash('error', 'Snapshot tidak kompatibel: Tabel pengguna tidak ditemukan.');
                header('Location: ' . baseUrl('backup'));
                exit;
            }
            $testPdo = null;
        } catch (\Throwable $e) {
            setFlash('error', 'Snapshot tidak dapat dibaca: ' . $e->getMessage());
            header('Location: ' . baseUrl('backup'));
            exit;
        }

        $dbPath = Database::getDatabasePath();

        // 1. Auto safety backup
        if (file_exists($dbPath)) {
            $autoBackupName = 'pre_restore_auto_backup_' . date('Y-m-d_His') . '.db';
            copy($dbPath, $backupDir . '/' . $autoBackupName);
        }

        // 2. Close connection
        Database::closeConnection();
        gc_collect_cycles();

        // 3. Copy snapshot to active database
        if (copy($snapshotPath, $dbPath)) {
            Database::getConnection(); // Reconnect
            setFlash('success', 'Database berhasil dipulihkan dari snapshot <strong>' . htmlspecialchars($filename) . '</strong>!');
        } else {
            setFlash('error', 'Gagal memulihkan database dari snapshot.');
        }

        header('Location: ' . baseUrl('backup'));
        exit;
    }

    /**
     * Kosongkan Database: Hapus seluruh rekam medis, pasien, dan user non-admin (Menyisakan Administrator saja)
     */
    public function resetDatabase(): void {
        $this->verifyAdminAccess();

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . baseUrl('backup'));
            exit;
        }

        $adminPassword = $_POST['admin_password'] ?? '';
        $confirmationText = trim($_POST['confirmation_text'] ?? '');
        $wipeMaster = !empty($_POST['wipe_master']);

        // 1. Validate confirmation text
        if (strtoupper($confirmationText) !== 'KOSONGKAN') {
            setFlash('error', 'Konfirmasi dibatalkan! Kata konfirmasi tidak cocok. Anda harus mengetik kata "KOSONGKAN".');
            header('Location: ' . baseUrl('backup'));
            exit;
        }

        // 2. Validate current admin's password
        $db = Database::getConnection();
        $adminId = (int)($_SESSION['user']['id'] ?? 0);

        $stmtAdmin = $db->prepare("SELECT id, password, name FROM users WHERE id = :id AND role = 'admin' LIMIT 1");
        $stmtAdmin->execute([':id' => $adminId]);
        $currentAdmin = $stmtAdmin->fetch();

        if (!$currentAdmin || !password_verify($adminPassword, $currentAdmin['password'])) {
            setFlash('error', 'Otorisasi Gagal! Password Administrator yang Anda masukkan salah. Proses pengosongan dibatalkan demi keamanan.');
            header('Location: ' . baseUrl('backup'));
            exit;
        }

        // 3. Create an automatic emergency backup before purging data
        $dbPath = Database::getDatabasePath();
        $backupDir = $this->getBackupDir();
        $emergencyBackupName = 'pre_reset_auto_backup_' . date('Y-m-d_His') . '.db';

        try {
            $db->exec('PRAGMA wal_checkpoint(FULL);');
            copy($dbPath, $backupDir . '/' . $emergencyBackupName);
        } catch (\Throwable $e) {}

        // 4. Execute atomic database wipe
        try {
            $db->beginTransaction();

            // Disable foreign keys during purge
            $db->exec('PRAGMA foreign_keys = OFF;');

            // A. Wipe all medical records
            $db->exec("DELETE FROM medical_records;");

            // B. Wipe all patients
            $db->exec("DELETE FROM patients;");

            // C. Wipe all non-administrator users (Staff, optometrist, etc.), keep ALL admins!
            $db->exec("DELETE FROM users WHERE role != 'admin';");

            // D. Wipe or preserve master data
            if ($wipeMaster) {
                $db->exec("DELETE FROM lenses;");
                $db->exec("DELETE FROM frames;");
                $db->exec("DELETE FROM sqlite_sequence WHERE name IN ('medical_records', 'patients', 'lenses', 'frames');");
            } else {
                $db->exec("DELETE FROM sqlite_sequence WHERE name IN ('medical_records', 'patients');");
            }

            // Re-enable foreign keys
            $db->exec('PRAGMA foreign_keys = ON;');

            $db->commit();

            // 5. Shrink database file size with VACUUM
            try {
                $db->exec('VACUUM;');
            } catch (\Throwable $e) {}

            $msg = '<strong>Database berhasil dikosongkan!</strong> Seluruh rekam medis, pasien, dan pengguna non-admin telah dihapus.';
            if ($wipeMaster) {
                $msg .= ' Master data lensa & frame juga telah dikosongkan.';
            } else {
                $msg .= ' Master data lensa & frame tetap dipertahankan.';
            }
            $msg .= ' Data akun Administrator tetap aman dan utuh. Cadangan darurat otomatis tersimpan: <em>' . htmlspecialchars($emergencyBackupName) . '</em>.';

            setFlash('success', $msg);

        } catch (PDOException $e) {
            if ($db->inTransaction()) {
                $db->rollBack();
            }
            setFlash('error', 'Terjadi kesalahan saat mengosongkan database: ' . $e->getMessage());
        }

        header('Location: ' . baseUrl('backup'));
        exit;
    }
}
