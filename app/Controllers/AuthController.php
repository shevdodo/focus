<?php
namespace App\Controllers;

use App\Config\Database;
use PDO;

class AuthController {
    /**
     * Display the login page
     */
    public function login(): void {
        // If already logged in, redirect to dashboard
        if (isset($_SESSION['user'])) {
            header('Location: ' . baseUrl('/'));
            exit;
        }

        $title = "Masuk - OPTIK FOCUS";
        
        // Render login view directly (does not require standard header/footer layout)
        require dirname(__DIR__, 2) . '/views/login.php';
    }

    /**
     * Handle user authentication POST request
     */
    public function authenticate(): void {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = trim($_POST['username'] ?? '');
            $password = $_POST['password'] ?? '';

            if (empty($username) || empty($password)) {
                setFlash('error', 'Username dan kata sandi wajib diisi!');
                header('Location: ' . baseUrl('login'));
                exit;
            }

            $db = Database::getConnection();
            $stmt = $db->prepare("SELECT * FROM users WHERE username = :username LIMIT 1");
            $stmt->execute([':username' => $username]);
            $user = $stmt->fetch();

            if ($user && password_verify($password, $user['password'])) {
                // Login successful, store in session
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'name' => $user['name'],
                    'role' => $user['role']
                ];
                setFlash('success', 'Selamat datang kembali, ' . htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8') . '!');
                header('Location: ' . baseUrl('/'));
                exit;
            } else {
                setFlash('error', 'Username atau kata sandi Anda salah.');
                header('Location: ' . baseUrl('login'));
                exit;
            }
        }

        header('Location: ' . baseUrl('login'));
        exit;
    }

    /**
     * Terminate the session
     */
    public function logout(): void {
        // Clear session array
        $_SESSION = [];
        
        // Destroy session cookie
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        session_destroy();
        
        // Restart session for flash notice
        session_start();
        setFlash('success', 'Anda telah berhasil keluar dari akun.');
        header('Location: ' . baseUrl('login'));
        exit;
    }
}
