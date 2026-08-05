<?php
namespace App\Controllers;

use App\Config\Database;
use PDO;
use PDOException;

class UserController {
    /**
     * Display a listing of all users
     */
    public function index(): void {
        $db = Database::getConnection();
        
        // Fetch all users
        $stmt = $db->query("SELECT id, username, name, role FROM users ORDER BY name ASC");
        $users = $stmt->fetchAll();

        $title = "Kelola Pengguna Sistem";
        $subtitle = "Manajemen akun pengguna & hak akses staf Klinik OPTIK FOCUS";
        
        require dirname(__DIR__, 2) . '/views/templates/header.php';
        require dirname(__DIR__, 2) . '/views/users/index.php';
        require dirname(__DIR__, 2) . '/views/templates/footer.php';
    }

    /**
     * Store a newly created user in the database
     */
    public function store(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = trim($_POST['name'] ?? '');
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            $role = $_POST['role'] ?? 'user';

            // Validation
            if (empty($name) || empty($username) || empty($password)) {
                setFlash('error', 'Semua kolom input wajib diisi!');
                header('Location: ' . baseUrl('users'));
                exit;
            }

            // Check role value validity
            if (!in_array($role, ['admin', 'user'])) {
                $role = 'user';
            }

            $db = Database::getConnection();

            // Check if username already exists
            $stmtCheck = $db->prepare("SELECT id FROM users WHERE username = :username LIMIT 1");
            $stmtCheck->execute([':username' => $username]);
            if ($stmtCheck->fetch()) {
                setFlash('error', 'Username "' . htmlspecialchars($username) . '" sudah digunakan oleh pengguna lain!');
                header('Location: ' . baseUrl('users'));
                exit;
            }

            try {
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $db->prepare("INSERT INTO users (name, username, password, role) VALUES (:name, :username, :password, :role)");
                $stmt->execute([
                    ':name' => $name,
                    ':username' => $username,
                    ':password' => $hashedPassword,
                    ':role' => $role
                ]);

                setFlash('success', 'Pengguna baru "' . htmlspecialchars($name) . '" berhasil ditambahkan.');
            } catch (PDOException $e) {
                setFlash('error', 'Gagal menambahkan pengguna: ' . $e->getMessage());
            }
        }

        header('Location: ' . baseUrl('users'));
        exit;
    }

    /**
     * Update the specified user in the database
     */
    public function update(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);
            $name = trim($_POST['name'] ?? '');
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';
            $role = $_POST['role'] ?? 'user';

            if ($id <= 0 || empty($name) || empty($username)) {
                setFlash('error', 'Data perubahan tidak lengkap!');
                header('Location: ' . baseUrl('users'));
                exit;
            }

            $db = Database::getConnection();

            // Self-demote protection guard
            if ($id === (int)$_SESSION['user']['id'] && $role !== 'admin') {
                setFlash('error', 'Keamanan: Anda tidak diperbolehkan menurunkan hak akses (demote) akun admin Anda sendiri!');
                header('Location: ' . baseUrl('users'));
                exit;
            }

            // Check username uniqueness
            $stmtCheck = $db->prepare("SELECT id FROM users WHERE username = :username AND id != :id LIMIT 1");
            $stmtCheck->execute([':username' => $username, ':id' => $id]);
            if ($stmtCheck->fetch()) {
                setFlash('error', 'Username "' . htmlspecialchars($username) . '" sudah digunakan oleh pengguna lain!');
                header('Location: ' . baseUrl('users'));
                exit;
            }

            try {
                if (!empty($password)) {
                    // Update username, name, role AND password
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                    $stmt = $db->prepare("UPDATE users SET name = :name, username = :username, password = :password, role = :role WHERE id = :id");
                    $stmt->execute([
                        ':name' => $name,
                        ':username' => $username,
                        ':password' => $hashedPassword,
                        ':role' => $role,
                        ':id' => $id
                    ]);
                } else {
                    // Update username, name, role without changing password
                    $stmt = $db->prepare("UPDATE users SET name = :name, username = :username, role = :role WHERE id = :id");
                    $stmt->execute([
                        ':name' => $name,
                        ':username' => $username,
                        ':role' => $role,
                        ':id' => $id
                    ]);
                }

                // If updating own credentials, synchronize session
                if ($id === (int)$_SESSION['user']['id']) {
                    $_SESSION['user']['name'] = $name;
                    $_SESSION['user']['username'] = $username;
                    $_SESSION['user']['role'] = $role;
                }

                setFlash('success', 'Data pengguna "' . htmlspecialchars($name) . '" berhasil diperbarui.');
            } catch (PDOException $e) {
                setFlash('error', 'Gagal memperbarui pengguna: ' . $e->getMessage());
            }
        }

        header('Location: ' . baseUrl('users'));
        exit;
    }

    /**
     * Remove the specified user from the database
     */
    public function destroy(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = (int)($_POST['id'] ?? 0);

            if ($id <= 0) {
                setFlash('error', 'ID pengguna tidak valid!');
                header('Location: ' . baseUrl('users'));
                exit;
            }

            // Self-delete protection guard
            if ($id === (int)$_SESSION['user']['id']) {
                setFlash('error', 'Keamanan: Anda tidak dapat menghapus akun Anda sendiri yang sedang aktif digunakan!');
                header('Location: ' . baseUrl('users'));
                exit;
            }

            try {
                $db = Database::getConnection();
                
                // Get user name for confirmation message
                $stmtInfo = $db->prepare("SELECT name FROM users WHERE id = :id LIMIT 1");
                $stmtInfo->execute([':id' => $id]);
                $user = $stmtInfo->fetch();
                $userName = $user ? $user['name'] : 'Pengguna';

                $stmt = $db->prepare("DELETE FROM users WHERE id = :id");
                $stmt->execute([':id' => $id]);

                setFlash('success', 'Pengguna "' . htmlspecialchars($userName) . '" berhasil dihapus dari sistem.');
            } catch (PDOException $e) {
                setFlash('error', 'Gagal menghapus pengguna: ' . $e->getMessage());
            }
        }

        header('Location: ' . baseUrl('users'));
        exit;
    }
}
