<?php
namespace App\Config;

use PDO;
use PDOException;

class Database {
    private static ?PDO $instance = null;

    public static function getConnection(): PDO {
        if (self::$instance === null) {
            try {
                $dbPath = dirname(__DIR__, 2) . '/database/optik_focus.db';
                $dbDir = dirname($dbPath);
                
                // Create database directory if it doesn't exist
                if (!is_dir($dbDir)) {
                    mkdir($dbDir, 0755, true);
                }

                $isNew = !file_exists($dbPath);

                self::$instance = new PDO("sqlite:" . $dbPath);
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$instance->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
                
                // Enable foreign keys
                self::$instance->exec('PRAGMA foreign_keys = ON;');

                self::initializeDatabase(self::$instance, $isNew);
            } catch (PDOException $e) {
                die("Koneksi database gagal: " . $e->getMessage());
            }
        }
        return self::$instance;
    }

    private static function initializeDatabase(PDO $db, bool $isNew): void {
        // 1. Create users table
        $db->exec("
            CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                username TEXT UNIQUE NOT NULL,
                password TEXT NOT NULL,
                name TEXT NOT NULL,
                role TEXT NOT NULL DEFAULT 'optometris'
            );
        ");

        // Seed default users
        $adminPass = password_hash('admin', PASSWORD_DEFAULT);
        $optometrisPass = password_hash('optometris', PASSWORD_DEFAULT);
        
        $stmtUser = $db->prepare("INSERT OR IGNORE INTO users (username, password, name, role) VALUES (?, ?, ?, ?)");
        $stmtUser->execute(['admin', $adminPass, 'dr. Hendra Optometris', 'admin']);
        $stmtUser->execute(['optometris', $optometrisPass, 'Aulia Putri, A.Md.RO', 'optometris']);

        // 2. Create patients table (Data Pasien Optik + BPJS)
        $db->exec("
            CREATE TABLE IF NOT EXISTS patients (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                mr_number TEXT UNIQUE NOT NULL,
                name TEXT NOT NULL,
                gender TEXT NOT NULL CHECK(gender IN ('L', 'P')),
                dob TEXT,
                phone TEXT,
                address TEXT,
                bpjs_class TEXT DEFAULT 'Non-BPJS',
                bpjs_number TEXT,
                created_at TEXT NOT NULL
            );
        ");

        // Run auto-migration for existing databases
        try {
            $db->exec("ALTER TABLE patients ADD COLUMN bpjs_class TEXT DEFAULT 'Non-BPJS';");
        } catch (\PDOException $e) {}

        try {
            $db->exec("ALTER TABLE patients ADD COLUMN bpjs_number TEXT;");
        } catch (\PDOException $e) {}

        try {
            $db->exec("ALTER TABLE medical_records ADD COLUMN diagnosis TEXT;");
        } catch (\PDOException $e) {}

        // 3. Create medical_records table (Pemeriksaan Refraksi & Resep Kacamata)
        $db->exec("
            CREATE TABLE IF NOT EXISTS medical_records (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                patient_id INTEGER NOT NULL,
                record_number TEXT NOT NULL,
                exam_date TEXT NOT NULL,
                examiner_name TEXT NOT NULL,
                
                -- Mata Kanan (OD - Oculi Dextra)
                od_sph REAL DEFAULT 0.00,
                od_cyl REAL DEFAULT 0.00,
                od_axis INTEGER DEFAULT 0,
                od_add REAL DEFAULT 0.00,
                od_va TEXT DEFAULT '6/6',
                
                -- Mata Kiri (OS - Oculi Sinistra)
                os_sph REAL DEFAULT 0.00,
                os_cyl REAL DEFAULT 0.00,
                os_axis INTEGER DEFAULT 0,
                os_add REAL DEFAULT 0.00,
                os_va TEXT DEFAULT '6/6',
                
                -- Fitting & Frame
                pd REAL DEFAULT 62.0,
                lens_type TEXT NOT NULL,
                frame_code TEXT,
                diagnosis TEXT,
                notes TEXT,
                total_price REAL DEFAULT 0.00,
                created_at TEXT NOT NULL,
                FOREIGN KEY (patient_id) REFERENCES patients(id) ON DELETE CASCADE
            );
        ");

        if ($isNew) {
            // Seed Sample Patients with BPJS information
            $patients = [
                ['RM-2026-000001', 'Budi Santoso', 'L', '1992-05-14', '081234567890', 'Jl. Merdeka No. 45, Bandung', 'Kelas 1', '0001827491023', date('Y-m-d H:i:s')],
                ['RM-2026-000002', 'Siti Rahmawati', 'P', '1998-08-22', '085712345678', 'Jl. Asia Afrika No. 12, Bandung', 'Kelas 2', '0002719283011', date('Y-m-d H:i:s')],
                ['RM-2026-000003', 'Hendra Wijaya', 'L', '1981-11-03', '081987654321', 'Jl. Dago Elos No. 88, Bandung', 'Non-BPJS', null, date('Y-m-d H:i:s')],
                ['RM-2026-000004', 'Dewi Lestari', 'P', '1974-03-30', '082134567899', 'Jl. Setiabudi No. 102, Bandung', 'Kelas 3', '0003928172635', date('Y-m-d H:i:s')],
                ['RM-2026-000005', 'Ahmad Fauzi', 'L', '2004-09-18', '083890123456', 'Jl. Riau No. 15, Bandung', 'Non-BPJS', null, date('Y-m-d H:i:s')]
            ];

            $stmtPatient = $db->prepare("INSERT INTO patients (mr_number, name, gender, dob, phone, address, bpjs_class, bpjs_number, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
            foreach ($patients as $p) {
                $stmtPatient->execute($p);
            }

            // Seed Sample Medical Records
            $currentMonth = date('Y-m');
            $prevMonth = date('Y-m', strtotime('-1 month'));

            $records = [
                [
                    1, 'REC-2026-001', "$currentMonth-02", 'dr. Hendra Optometris',
                    -2.25, -0.50, 90, 0.00, '6/6',
                    -1.75, -0.75, 180, 0.00, '6/6',
                    63.0, 'Photocromic Bluecut', 'Ray-Ban RB5228',
                    'Klaim BPJS Kacamata Kelas 1. Mata sering pegal di depan laptop (Asthenopia).', 850000, date('Y-m-d H:i:s')
                ],
                [
                    2, 'REC-2026-002', "$currentMonth-05", 'Aulia Putri, A.Md.RO',
                    -1.25, 0.00, 0, 0.00, '6/6',
                    -1.50, 0.00, 0, 0.00, '6/6',
                    61.0, 'Single Vision Antiradiasi', 'Oakley OX8046',
                    'Klaim BPJS Kelas 2. Kacamata untuk aktivitas perkuliahan sehari-hari.', 550000, date('Y-m-d H:i:s')
                ],
                [
                    3, 'REC-2026-003', "$currentMonth-08", 'dr. Hendra Optometris',
                    +1.50, -1.00, 75, +2.00, '6/9',
                    +1.75, -0.50, 105, +2.00, '6/9',
                    64.5, 'Progressive Office', 'Silhouette 5515',
                    'Pasien Umum Non-BPJS. Presbiopia dan Astigmatisme.', 1750000, date('Y-m-d H:i:s')
                ]
            ];

            $stmtRec = $db->prepare("
                INSERT INTO medical_records (
                    patient_id, record_number, exam_date, examiner_name,
                    od_sph, od_cyl, od_axis, od_add, od_va,
                    os_sph, os_cyl, os_axis, os_add, os_va,
                    pd, lens_type, frame_code, notes, total_price, created_at
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
            ");
            foreach ($records as $r) {
                $stmtRec->execute($r);
            }
        }
    }
}
