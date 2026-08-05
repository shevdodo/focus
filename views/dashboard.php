<?php
/**
 * Dashboard View - Optik Focus Medical Records
 */

if (!function_exists('formatRupiah')) {
    function formatRupiah(float $amount): string {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
}
?>

<div class="dashboard-grid">
    <!-- Stat Card 1: Patients -->
    <div class="stat-card balance-card">
        <div class="stat-icon-wrapper">
            <ion-icon name="people-outline"></ion-icon>
        </div>
        <div class="stat-details">
            <span class="stat-label">Total Pasien Terdaftar</span>
            <h2 class="stat-value"><?= number_format($stats['total_patients']) ?> <small style="font-size: 1rem; font-weight: 500;">Pasien</small></h2>
            <div class="stat-badge badge-success">
                <ion-icon name="shield-checkmark-outline"></ion-icon>
                <span>Database Pasien Aktif</span>
            </div>
        </div>
        <div class="card-glow" style="background: radial-gradient(circle at right, rgba(99, 102, 241, 0.15), transparent 60%);"></div>
    </div>

    <!-- Stat Card 2: Examinations -->
    <div class="stat-card income-card">
        <div class="stat-icon-wrapper">
            <ion-icon name="eye-outline"></ion-icon>
        </div>
        <div class="stat-details">
            <span class="stat-label">Total Rekam Medis Refraksi</span>
            <h2 class="stat-value"><?= number_format($stats['total_exams']) ?> <small style="font-size: 1rem; font-weight: 500;">Pemeriksaan</small></h2>
            <span class="stat-subtext">Hari ini: <?= $stats['exams_today'] ?> Pemeriksaan</span>
        </div>
        <div class="card-glow" style="background: radial-gradient(circle at right, rgba(16, 185, 129, 0.15), transparent 60%);"></div>
    </div>

    <!-- Stat Card 3: Revenue / Transaksi -->
    <div class="stat-card expense-card" style="border-left: 4px solid #8b5cf6;">
        <div class="stat-icon-wrapper" style="background-color: rgba(139, 92, 246, 0.1); color: #8b5cf6;">
            <ion-icon name="cash-outline"></ion-icon>
        </div>
        <div class="stat-details">
            <span class="stat-label">Omzet Lensa & Frame</span>
            <h2 class="stat-value"><?= formatRupiah($stats['total_revenue']) ?></h2>
            <span class="stat-subtext">Kumulatif seluruh resep</span>
        </div>
        <div class="card-glow" style="background: radial-gradient(circle at right, rgba(139, 92, 246, 0.15), transparent 60%);"></div>
    </div>
</div>

<!-- Secondary Stats: Monthly Summary -->
<div class="monthly-breakdown-card">
    <div class="card-header-clean">
        <div class="header-icon-title">
            <ion-icon name="calendar-number-outline" class="text-primary"></ion-icon>
            <div>
                <h3>Ringkasan Pemeriksaan Bulan Ini (<?= date('F Y') ?>)</h3>
                <p class="text-muted text-xs">Pencatatan statistik pemeriksaan mata pasien & resep kacamata terbaru.</p>
            </div>
        </div>
    </div>
    <div class="monthly-grid">
        <div class="monthly-stat-item">
            <span class="monthly-label">Pemeriksaan Bulan Ini</span>
            <h4 class="text-primary font-weight-bold"><?= $monthlyStats['total_exams'] ?> Rekam Medis</h4>
        </div>
        <div class="monthly-separator"></div>
        <div class="monthly-stat-item">
            <span class="monthly-label">Pemeriksaan Hari Ini</span>
            <h4 class="text-emerald font-weight-bold"><?= $stats['exams_today'] ?> Pasien</h4>
        </div>
        <div class="monthly-separator"></div>
        <div class="monthly-stat-item">
            <span class="monthly-label">Nilai Resep Bulan Ini</span>
            <h4 class="text-emerald font-weight-bold"><?= formatRupiah($monthlyStats['total_revenue']) ?></h4>
        </div>
    </div>
</div>

<div class="row-layout">
    <!-- Recent Examination Cards -->
    <div class="col-8 card-widget">
        <div class="card-widget-header">
            <h3>5 Pemeriksaan & Rekam Medis Terkini</h3>
            <a href="<?= baseUrl('transactions') ?>" class="btn-link">
                <span>Lihat Semua Rekam Medis</span>
                <ion-icon name="arrow-forward-outline"></ion-icon>
            </a>
        </div>
        
        <div class="tx-cards-container">
            <?php if (empty($recentRecords)): ?>
                <div class="text-center py-4 text-muted">
                    <ion-icon name="document-text-outline" style="font-size: 2rem;"></ion-icon>
                    <p class="mt-2">Belum ada rekam medis tercatat.</p>
                </div>
            <?php else: ?>
                <div class="tx-cards-list">
                    <?php foreach ($recentRecords as $rec): ?>
                        <div class="tx-card-item">
                            <!-- Top Row -->
                            <div class="tx-card-header-row">
                                <div class="tx-card-title-group">
                                    <div class="tx-icon-avatar" style="background-color: rgba(99, 102, 241, 0.12); color: #6366f1;">
                                        <ion-icon name="glasses-outline"></ion-icon>
                                    </div>
                                    <div>
                                        <strong class="tx-description-text" style="font-size: 1rem; color: var(--color-dark); display: block;">
                                            <?= htmlspecialchars($rec['patient_name']) ?>
                                            <span style="font-size: 0.75rem; font-weight: 600; background-color: var(--color-light); color: var(--color-primary); padding: 0.15rem 0.4rem; border-radius: 4px; margin-left: 0.35rem;">
                                                <?= htmlspecialchars($rec['mr_number']) ?>
                                            </span>
                                        </strong>
                                        <span class="text-muted text-xs">
                                            Optometris: <?= htmlspecialchars($rec['examiner_name']) ?> | Frame: <?= htmlspecialchars($rec['frame_code'] ?: '-') ?>
                                        </span>
                                    </div>
                                </div>

                                <span class="tx-amount-value text-primary font-weight-bold">
                                    <?= formatRupiah($rec['total_price']) ?>
                                </span>
                            </div>

                            <!-- Prescription Brief Badge -->
                            <div style="background: rgba(15, 23, 42, 0.03); border: 1px solid var(--color-border); border-radius: 8px; padding: 0.6rem 0.85rem; margin: 0.6rem 0; font-size: 0.8rem; display: flex; flex-wrap: wrap; justify-content: space-between; gap: 0.5rem;">
                                <div>
                                    <strong style="color: var(--color-primary);">OD (Kanan):</strong> 
                                    SPH: <?= sprintf('%+.2f', $rec['od_sph']) ?> | CYL: <?= sprintf('%+.2f', $rec['od_cyl']) ?> | AXIS: <?= $rec['od_axis'] ?>° | ADD: <?= sprintf('%+.2f', $rec['od_add']) ?>
                                </div>
                                <div>
                                    <strong style="color: #ec4899;">OS (Kiri):</strong> 
                                    SPH: <?= sprintf('%+.2f', $rec['os_sph']) ?> | CYL: <?= sprintf('%+.2f', $rec['os_cyl']) ?> | AXIS: <?= $rec['os_axis'] ?>° | ADD: <?= sprintf('%+.2f', $rec['os_add']) ?>
                                </div>
                            </div>

                            <!-- Bottom Row -->
                            <div class="tx-card-footer-row">
                                <div class="tx-sub-meta">
                                    <span class="tx-date-badge">
                                        <ion-icon name="calendar-outline"></ion-icon>
                                        <?= date('d M Y', strtotime($rec['exam_date'])) ?>
                                    </span>
                                    <span class="badge-category-pill" style="background-color: rgba(16, 185, 129, 0.1); color: #10b981;">
                                        <ion-icon name="disc-outline"></ion-icon>
                                        <span><?= htmlspecialchars($rec['lens_type']) ?></span>
                                    </span>
                                    <span class="badge-category-pill" style="background-color: rgba(99, 102, 241, 0.1); color: #6366f1;">
                                        <ion-icon name="resize-outline"></ion-icon>
                                        <span>PD: <?= $rec['pd'] ?> mm</span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Lens Distribution Chart/Bars -->
    <div class="col-4 card-widget">
        <div class="card-widget-header">
            <h3>Distribusi Jenis Lensa</h3>
        </div>
        
        <div class="category-bars-list">
            <?php 
            $totalLenses = array_sum(array_column($lensDistribution, 'total_count'));
            $colors = ['#6366f1', '#10b981', '#3b82f6', '#f59e0b', '#ec4899', '#8b5cf6', '#06b6d4'];
            if (empty($lensDistribution)): 
            ?>
                <p class="text-center py-4 text-muted">Belum ada data resep lensa.</p>
            <?php else: ?>
                <?php foreach ($lensDistribution as $idx => $lens): 
                    $percent = $totalLenses > 0 ? round(($lens['total_count'] / $totalLenses) * 100) : 0;
                    $color = $colors[$idx % count($colors)];
                ?>
                    <div class="category-bar-item animate-fade-in">
                        <div class="bar-details">
                            <span class="bar-name">
                                <span class="bar-dot" style="background-color: <?= $color ?>;"></span>
                                <?= htmlspecialchars($lens['lens_type']) ?>
                            </span>
                            <span class="bar-value"><?= $lens['total_count'] ?> Resep <small class="text-muted">(<?= $percent ?>%)</small></span>
                        </div>
                        <div class="progress-track">
                            <div class="progress-fill" style="background-color: <?= $color ?>; width: <?= $percent ?>%;"></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>
