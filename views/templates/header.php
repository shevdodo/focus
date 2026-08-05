<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'Pencatatan Rekam Medis Optik Focus' ?></title>
    <!-- Google Fonts - Outfit & Plus Jakarta Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <!-- Ionicons CDN -->
    <script type="module" src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.esm.js"></script>
    <script nomodule src="https://unpkg.com/ionicons@7.1.0/dist/ionicons/ionicons.js"></script>
    <!-- App Styling -->
    <link rel="stylesheet" href="<?= baseUrl('css/style.css?v=' . time()) ?>">
</head>
<body>
    <div class="app-container">
        <!-- Sidebar Navigation -->
        <aside class="sidebar">
            <div class="sidebar-brand">
                <div class="brand-logo">
                    <ion-icon name="glasses-outline"></ion-icon>
                </div>
                <div class="brand-info">
                    <h2>OPTIK FOCUS</h2>
                    <span>Rekam Medis Optik</span>
                </div>
            </div>
            
            <nav class="sidebar-menu">
                <ul>
                    <?php 
                    $currentUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
                    $projectBase = getProjectBasePath();
                    $isDashboardActive = ($currentUri === $projectBase . '/' || $currentUri === $projectBase . '/index.php' || $currentUri === $projectBase);
                    $isInputActive = (strpos($currentUri, '/records/create') !== false || strpos($currentUri, '/transactions/create') !== false);
                    $isRecordsActive = ((strpos($currentUri, '/transactions') !== false || strpos($currentUri, '/records') !== false) && !$isInputActive);
                    $isReportsActive = (strpos($currentUri, '/reports') !== false || strpos($currentUri, '/laporan') !== false);
                    ?>
                    <li class="<?= $isDashboardActive ? 'active' : '' ?>">
                        <a href="<?= baseUrl('/') ?>">
                            <ion-icon name="grid-outline"></ion-icon>
                            <span>Dashboard</span>
                        </a>
                    </li>
                    <li class="<?= $isInputActive ? 'active' : '' ?>">
                        <a href="<?= baseUrl('records/create') ?>">
                            <ion-icon name="add-circle-outline"></ion-icon>
                            <span>Input Rekam Medis</span>
                        </a>
                    </li>
                    <li class="<?= $isRecordsActive ? 'active' : '' ?>">
                        <a href="<?= baseUrl('transactions') ?>">
                            <ion-icon name="eye-outline"></ion-icon>
                            <span>Daftar Rekam Medis</span>
                        </a>
                    </li>
                    <li class="<?= $isReportsActive ? 'active' : '' ?>">
                        <a href="<?= baseUrl('reports') ?>">
                            <ion-icon name="document-text-outline"></ion-icon>
                            <span>Laporan Optik</span>
                        </a>
                    </li>
                    <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
                        <?php $isUsersActive = (strpos($currentUri, '/users') !== false); ?>
                        <li class="<?= $isUsersActive ? 'active' : '' ?>">
                            <a href="<?= baseUrl('users') ?>">
                                <ion-icon name="people-outline"></ion-icon>
                                <span>Kelola Pengguna</span>
                            </a>
                        </li>
                    <?php endif; ?>
                    <li>
                        <a href="<?= baseUrl('logout') ?>" onclick="return confirm('Apakah Anda yakin ingin keluar dari sistem?');" style="color: #f87171;">
                            <ion-icon name="log-out-outline"></ion-icon>
                            <span>Keluar</span>
                        </a>
                    </li>
                </ul>
            </nav>
            
            <div class="sidebar-footer">
                <div class="user-profile">
                    <div class="avatar">
                        <ion-icon name="person-circle-outline"></ion-icon>
                    </div>
                    <div class="user-info">
                        <h3><?= htmlspecialchars($_SESSION['user']['name'] ?? 'Optometris', ENT_QUOTES, 'UTF-8') ?></h3>
                        <span>@<?= htmlspecialchars($_SESSION['user']['username'] ?? 'admin', ENT_QUOTES, 'UTF-8') ?> (<?= htmlspecialchars($_SESSION['user']['role'] ?? 'optometris', ENT_QUOTES, 'UTF-8') ?>)</span>
                    </div>
                </div>
            </div>
        </aside>

        <!-- Mobile Floating Navigation -->
        <nav class="bottom-nav">
            <a href="<?= baseUrl('/') ?>" class="bottom-nav-item <?= $isDashboardActive ? 'active' : '' ?>">
                <ion-icon name="<?= $isDashboardActive ? 'grid' : 'grid-outline' ?>"></ion-icon>
                <span>Dashboard</span>
            </a>
            <a href="<?= baseUrl('records/create') ?>" class="bottom-nav-item <?= $isInputActive ? 'active' : '' ?>">
                <ion-icon name="<?= $isInputActive ? 'add-circle' : 'add-circle-outline' ?>"></ion-icon>
                <span>Input RM</span>
            </a>
            <a href="<?= baseUrl('transactions') ?>" class="bottom-nav-item <?= $isRecordsActive ? 'active' : '' ?>">
                <ion-icon name="<?= $isRecordsActive ? 'eye' : 'eye-outline' ?>"></ion-icon>
                <span>Rekam Medis</span>
            </a>
            <a href="<?= baseUrl('reports') ?>" class="bottom-nav-item <?= $isReportsActive ? 'active' : '' ?>">
                <ion-icon name="<?= $isReportsActive ? 'document-text' : 'document-text-outline' ?>"></ion-icon>
                <span>Laporan</span>
            </a>
            <?php if (isset($_SESSION['user']) && $_SESSION['user']['role'] === 'admin'): ?>
                <?php $isUsersActive = (strpos($currentUri, '/users') !== false); ?>
                <a href="<?= baseUrl('users') ?>" class="bottom-nav-item <?= $isUsersActive ? 'active' : '' ?>">
                    <ion-icon name="<?= $isUsersActive ? 'people' : 'people-outline' ?>"></ion-icon>
                    <span>Pengguna</span>
                </a>
            <?php endif; ?>
            <a href="<?= baseUrl('logout') ?>" class="bottom-nav-item" onclick="return confirm('Apakah Anda yakin ingin keluar dari sistem?');" style="color: #f87171;">
                <ion-icon name="log-out-outline"></ion-icon>
                <span>Keluar</span>
            </a>
        </nav>

        <!-- Main Content Wrapper -->
        <main class="main-content">
            <!-- Top Navigation Bar -->
            <header class="topbar">
                <div class="topbar-title">
                    <h1><?= $title ?? 'Dashboard' ?></h1>
                    <p class="text-muted text-sm">Pencatatan Rekam Medis & Pemeriksaan Mata - Klinik OPTIK FOCUS</p>
                </div>
                <div class="topbar-actions">
                    <div class="current-date-badge">
                        <ion-icon name="calendar-outline"></ion-icon>
                        <span><?= date('d M Y') ?></span>
                    </div>
                </div>
            </header>

            <!-- Inner Page Content -->
            <div class="content-body">
                <!-- Flash Notification Banner -->
                <?php if ($success = getFlash('success')): ?>
                    <div class="alert alert-success animate-fade-in">
                        <ion-icon name="checkmark-circle-outline" class="alert-icon"></ion-icon>
                        <div class="alert-content">
                            <strong>Berhasil!</strong>
                            <p><?= $success ?></p>
                        </div>
                        <button class="alert-close" onclick="this.parentElement.remove();">&times;</button>
                    </div>
                <?php endif; ?>

                <?php if ($error = getFlash('error')): ?>
                    <div class="alert alert-danger animate-fade-in">
                        <ion-icon name="warning-outline" class="alert-icon"></ion-icon>
                        <div class="alert-content">
                            <strong>Peringatan!</strong>
                            <p><?= $error ?></p>
                        </div>
                        <button class="alert-close" onclick="this.parentElement.remove();">&times;</button>
                    </div>
                <?php endif; ?>
