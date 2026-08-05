<?php
/**
 * ==========================================================================
 * IRT FINANSIAL - BOOTSTRAPPER & SINGLE ENTRY POINT
 * Antigravity Native PHP Core Engine
 * ==========================================================================
 */

// 1. Initialize Global Session Handler
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// 2. Register Custom PSR-4 Namespace Autoloader
spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = dirname(__DIR__) . '/app/';

    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return; // Move to other registered autoloaders
    }

    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});

// 3. Define Global Helper Functions

/**
 * Dynamically extract the base project folder (e.g. "/irt" in XAMPP htdocs)
 */
function getProjectBasePath(): string {
    $scriptName = $_SERVER['SCRIPT_NAME'];
    $base = dirname(dirname($scriptName));
    if ($base === '/' || $base === '\\') {
        $base = '';
    }
    return $base;
}

/**
 * Generate a clean and fully qualified URL link for assets or pages
 */
function baseUrl(string $path = ''): string {
    $basePath = getProjectBasePath();
    return $basePath . '/' . ltrim($path, '/');
}

/**
 * Record a temporary session-based flash notification
 */
function setFlash(string $key, string $message): void {
    $_SESSION['flash'][$key] = $message;
}

/**
 * Read and clear a session-based flash notification
 */
function getFlash(string $key): ?string {
    if (isset($_SESSION['flash'][$key])) {
        $message = $_SESSION['flash'][$key];
        unset($_SESSION['flash'][$key]);
        return $message;
    }
    return null;
}

/**
 * Check if a specific flash notice exists
 */
function hasFlash(string $key): bool {
    return isset($_SESSION['flash'][$key]);
}

/**
 * Get the budget period date range and month name based on the salary date (28th of each month)
 * Period runs from the 28th of the previous month to the 27th of the current month.
 * @param string|null $date Reference date (YYYY-MM-DD), defaults to today
 * @return array ['start_date' => 'YYYY-MM-DD', 'end_date' => 'YYYY-MM-DD', 'label' => 'Month YYYY']
 */
function getBudgetPeriod(?string $date = null): array {
    try {
        $dateTime = new \DateTime($date ?? 'now');
    } catch (\Exception $e) {
        $dateTime = new \DateTime('now');
    }
    
    $day = (int)$dateTime->format('d');
    $month = (int)$dateTime->format('m');
    $year = (int)$dateTime->format('Y');

    if ($day >= 28) {
        // E.g. 2026-05-28 -> starts 2026-05-28, ends 2026-06-27 (represents the "Juni 2026" budget cycle)
        $start = sprintf('%04d-%02d-28', $year, $month);
        
        $endDateTime = new \DateTime($start);
        $endDateTime->modify('+1 month -1 day');
        $end = $endDateTime->format('Y-m-d');
        
        $labelMonth = $endDateTime->format('F');
        $labelYear = $endDateTime->format('Y');
    } else {
        // E.g. 2026-06-02 -> starts 2026-05-28, ends 2026-06-27 (represents the "Juni 2026" budget cycle)
        $startDateTime = new \DateTime(sprintf('%04d-%02d-28', $year, $month));
        $startDateTime->modify('-1 month');
        $start = $startDateTime->format('Y-m-d');
        
        $end = sprintf('%04d-%02d-27', $year, $month);
        
        $labelMonth = $dateTime->format('F');
        $labelYear = $dateTime->format('Y');
    }

    // Localize month name to Indonesian
    $monthsInId = [
        'January' => 'Januari',
        'February' => 'Februari',
        'March' => 'Maret',
        'April' => 'April',
        'May' => 'Mei',
        'June' => 'Juni',
        'July' => 'Juli',
        'August' => 'Agustus',
        'September' => 'September',
        'October' => 'Oktober',
        'November' => 'November',
        'December' => 'Desember'
    ];
    $indonesianMonth = $monthsInId[$labelMonth] ?? $labelMonth;
    $label = $indonesianMonth . ' ' . $labelYear;

    return [
        'start_date' => $start,
        'end_date' => $end,
        'label' => $label,
        'year_month' => sprintf('%04d-%02d', $labelYear, array_search($indonesianMonth, $monthsInId) ? date('m', strtotime("$labelYear-$labelMonth-01")) : $month)
    ];
}

/**
 * Get budget period date range for a specific month (YYYY-MM) starting on the 28th
 */
function getBudgetPeriodByMonth(?string $yearMonth = null): array {
    if (empty($yearMonth)) {
        return getBudgetPeriod();
    }

    $parts = explode('-', $yearMonth);
    if (count($parts) !== 2) {
        return getBudgetPeriod();
    }

    $year = (int)$parts[0];
    $month = (int)$parts[1];

    if ($year < 2000 || $year > 2100 || $month < 1 || $month > 12) {
        return getBudgetPeriod();
    }

    // Target end date is 27th of the selected month
    $endDateTime = new \DateTime(sprintf('%04d-%02d-27', $year, $month));
    $end = $endDateTime->format('Y-m-d');

    // Start date is 28th of previous month
    $startDateTime = clone $endDateTime;
    $startDateTime->modify('-1 month');
    $start = sprintf('%04d-%02d-28', (int)$startDateTime->format('Y'), (int)$startDateTime->format('m'));

    $monthEnglish = $endDateTime->format('F');
    $labelYear = $endDateTime->format('Y');

    $monthsInId = [
        'January' => 'Januari',
        'February' => 'Februari',
        'March' => 'Maret',
        'April' => 'April',
        'May' => 'Mei',
        'June' => 'Juni',
        'July' => 'Juli',
        'August' => 'Agustus',
        'September' => 'September',
        'October' => 'Oktober',
        'November' => 'November',
        'December' => 'Desember'
    ];
    $indonesianMonth = $monthsInId[$monthEnglish] ?? $monthEnglish;
    $label = $indonesianMonth . ' ' . $labelYear;

    return [
        'start_date' => $start,
        'end_date' => $end,
        'label' => $label,
        'year_month' => sprintf('%04d-%02d', $year, $month)
    ];
}

/**
 * Get list of available budget months for dropdown selection
 * Includes past 12 months up to current month + any additional months recorded in database
 */
function getAvailableBudgetMonths(?string $selectedMonth = null): array {
    $months = [];
    
    // Always include past 12 months up to current active budget period (hides future empty months)
    $currentBudgetPeriod = getBudgetPeriod();
    $currentDt = new \DateTime($currentBudgetPeriod['end_date']);
    
    for ($i = 0; $i < 12; $i++) {
        $dt = (clone $currentDt)->modify("-$i months");
        $val = $dt->format('Y-m');
        $months[$val] = true;
    }

    if (!empty($selectedMonth)) {
        $months[$selectedMonth] = true;
    }

    // Include any older transaction dates that exist in the database
    try {
        $db = \App\Core\Database::getInstance()->getConnection();
        $stmt = $db->query("SELECT DISTINCT date FROM transactions WHERE date IS NOT NULL AND date != '0000-00-00'");
        $dates = $stmt->fetchAll(\PDO::FETCH_COLUMN);

        foreach ($dates as $dateStr) {
            if (!$dateStr) continue;
            $dt = new \DateTime($dateStr);
            $day = (int)$dt->format('d');
            if ($day >= 28) {
                // Transactions on 28th or later belong to next month's salary period
                $dt->modify('+1 month');
            }
            $val = $dt->format('Y-m');
            $months[$val] = true;
        }
    } catch (\Throwable $e) {
        // Fallback silently if DB error occurs
    }

    $result = [];
    foreach (array_keys($months) as $val) {
        $periodInfo = getBudgetPeriodByMonth($val);
        $result[$val] = [
            'value' => $val,
            'label' => $periodInfo['label'],
            'start_date' => $periodInfo['start_date'],
            'end_date' => $periodInfo['end_date'],
            'range' => date('d M Y', strtotime($periodInfo['start_date'])) . ' - ' . date('d M Y', strtotime($periodInfo['end_date']))
        ];
    }
    
    krsort($result); // Sort newest first (current month at top, past months below)
    return $result;
}

/**
 * Format a YYYY-MM-DD date into a standard, readable Indonesian date format
 */
function formatIndonesianDate(string $dateStr): string {
    if (empty($dateStr)) return '';
    $timestamp = strtotime($dateStr);
    $day = date('d', $timestamp);
    $monthEnglish = date('F', $timestamp);
    $year = date('Y', $timestamp);

    $monthsInId = [
        'January' => 'Januari',
        'February' => 'Februari',
        'March' => 'Maret',
        'April' => 'April',
        'May' => 'Mei',
        'June' => 'Juni',
        'July' => 'Juli',
        'August' => 'Agustus',
        'September' => 'September',
        'October' => 'Oktober',
        'November' => 'November',
        'December' => 'Desember'
    ];
    $monthId = $monthsInId[$monthEnglish] ?? $monthEnglish;
    return (int)$day . ' ' . $monthId . ' ' . $year;
}

// 4. Initialize Database Connection (Automatically seeds SQLite if file does not exist)
try {
    \App\Config\Database::getConnection();
} catch (\Exception $e) {
    die("<div style='padding: 20px; font-family: sans-serif; color: red;'><strong>Gagal menginisialisasi database:</strong> " . htmlspecialchars($e->getMessage()) . "</div>");
}

// 5. Instantiate Routing System & Define Application Routes
use App\Core\Router;
use App\Controllers\DashboardController;
use App\Controllers\TransactionController;
use App\Controllers\AuthController;
use App\Controllers\UserController;
use App\Controllers\ReportController;

// Secure Authentication Guard Check
$currentUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$projectBase = getProjectBasePath();
$cleanedPath = str_replace($projectBase, '', $currentUri);
$cleanedPath = parse_url($cleanedPath, PHP_URL_PATH);
if (empty($cleanedPath)) {
    $cleanedPath = '/';
}

// Redirect unauthenticated requests to login page
if ($cleanedPath !== '/login' && !isset($_SESSION['user'])) {
    header('Location: ' . baseUrl('login'));
    exit;
}

// Secure Authorization Guard: Only 'admin' role can access '/users' management
if (strpos($cleanedPath, '/users') === 0) {
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
        setFlash('error', 'Akses Ditolak! Menu kelola pengguna hanya diperuntukkan bagi Administrator.');
        header('Location: ' . baseUrl('/'));
        exit;
    }
}

$router = new Router();

// Map Authentication Routes
$router->get('/login', [AuthController::class, 'login']);
$router->post('/login', [AuthController::class, 'authenticate']);
$router->get('/logout', [AuthController::class, 'logout']);

// Map Home / Dashboard
$router->get('/', [DashboardController::class, 'index']);
$router->get('/index.php', [DashboardController::class, 'index']);

// Map Transaction & Rekam Medis CRUD
$router->get('/transactions', [TransactionController::class, 'index']);
$router->get('/transactions/create', [TransactionController::class, 'create']);
$router->post('/transactions/create', [TransactionController::class, 'store']);
$router->post('/transactions/edit', [TransactionController::class, 'update']);
$router->post('/transactions/delete', [TransactionController::class, 'destroy']);

$router->get('/records', [TransactionController::class, 'index']);
$router->get('/records/create', [TransactionController::class, 'create']);
$router->post('/records/create', [TransactionController::class, 'store']);
$router->post('/records/edit', [TransactionController::class, 'update']);
$router->post('/records/delete', [TransactionController::class, 'destroy']);

// Map Reports & Analytics
$router->get('/reports', [ReportController::class, 'index']);
$router->get('/laporan', [ReportController::class, 'index']);

// Map User CRUD (Administrator Only)
$router->get('/users', [UserController::class, 'index']);
$router->post('/users/create', [UserController::class, 'store']);
$router->post('/users/edit', [UserController::class, 'update']);
$router->post('/users/delete', [UserController::class, 'destroy']);

// 6. Run the Dispatcher!
$router->dispatch();
