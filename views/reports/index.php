<?php
/**
 * Laporan Rekam Medis & Resep Optik View - OPTIK FOCUS
 */

if (!function_exists('formatRupiah')) {
    function formatRupiah(float $amount): string {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
}
?>

<div class="report-wrapper animate-fade-in">
    <!-- Top Filter Bar & Toolbar -->
    <div class="card-widget report-filter-card mb-4">
        <div class="report-filter-header">
            <div class="report-title-section">
                <div class="report-icon-badge" style="background-color: rgba(99, 102, 241, 0.1); color: #6366f1;">
                    <ion-icon name="document-text-outline"></ion-icon>
                </div>
                <div>
                    <h2 class="report-title">Laporan Rekam Medis & Rekap Optik</h2>
                    <p class="text-muted text-sm">
                        Laporan Rekapitulasi Pemeriksaan Pasien & Resep Kacamata Klinik <strong>OPTIK FOCUS</strong>
                    </p>
                </div>
            </div>

            <!-- Action controls & Month selection -->
            <div class="report-controls">
                <form action="<?= baseUrl('reports') ?>" method="GET" class="month-picker-form" id="monthForm">
                    <div class="month-selector-group">
                        <div class="select-wrapper">
                            <ion-icon name="calendar-outline" class="select-icon"></ion-icon>
                            <input type="month" name="month" value="<?= htmlspecialchars($_GET['month'] ?? date('Y-m')) ?>" class="form-control select-month-input" onchange="document.getElementById('monthForm').submit();">
                        </div>
                    </div>
                </form>

                <button onclick="window.print()" class="btn btn-secondary btn-print no-print">
                    <ion-icon name="print-outline"></ion-icon>
                    <span>Cetak Laporan</span>
                </button>
            </div>
        </div>

        <!-- Budget Period Info Banner -->
        <div class="period-info-banner mt-3">
            <div class="period-badge-item">
                <ion-icon name="time-outline" class="text-primary"></ion-icon>
                <span>Periode: <strong><?= date('F Y', strtotime(($selectedMonth ?? date('Y-m')) . '-01')) ?></strong></span>
            </div>
            <div class="period-badge-divider"></div>
            <div class="period-badge-item">
                <ion-icon name="eye-outline" class="text-emerald"></ion-icon>
                <span>Pemeriksaan Tercatat: <strong><?= count($records) ?> Rekam Medis</strong></span>
            </div>
            <div class="period-badge-divider"></div>
            <div class="period-badge-item">
                <ion-icon name="cash-outline" class="text-primary"></ion-icon>
                <span>Nilai Transaksi: <strong><?= formatRupiah($summary['total_revenue']) ?></strong></span>
            </div>
        </div>
    </div>

    <!-- Summary Statistics Grid -->
    <div class="dashboard-grid mb-4">
        <!-- Card 1: Total Exams -->
        <div class="stat-card balance-card">
            <div class="stat-icon-wrapper">
                <ion-icon name="eye-outline"></ion-icon>
            </div>
            <div class="stat-details">
                <span class="stat-label">Total Pemeriksaan</span>
                <h2 class="stat-value text-primary"><?= $summary['total_exams'] ?> <small style="font-size: 0.9rem;">Pemeriksaan</small></h2>
                <span class="stat-subtext">Hasil refraksi tersimpan</span>
            </div>
            <div class="card-glow" style="background: radial-gradient(circle at right, rgba(99, 102, 241, 0.15), transparent 60%);"></div>
        </div>

        <!-- Card 2: Patients -->
        <div class="stat-card income-card">
            <div class="stat-icon-wrapper">
                <ion-icon name="people-outline"></ion-icon>
            </div>
            <div class="stat-details">
                <span class="stat-label">Pasien Terdaftar</span>
                <h2 class="stat-value text-emerald"><?= $summary['total_patients'] ?> <small style="font-size: 0.9rem;">Pasien</small></h2>
                <span class="stat-subtext">Database rekam medis aktif</span>
            </div>
            <div class="card-glow" style="background: radial-gradient(circle at right, rgba(16, 185, 129, 0.15), transparent 60%);"></div>
        </div>

        <!-- Card 3: Revenue -->
        <div class="stat-card expense-card" style="border-left: 4px solid #8b5cf6;">
            <div class="stat-icon-wrapper" style="background-color: rgba(139, 92, 246, 0.1); color: #8b5cf6;">
                <ion-icon name="cash-outline"></ion-icon>
            </div>
            <div class="stat-details">
                <span class="stat-label">Total Transaksi Lensa</span>
                <h2 class="stat-value" style="color: #8b5cf6;"><?= formatRupiah($summary['total_revenue']) ?></h2>
                <span class="stat-subtext">Pemasukan resep kacamata</span>
            </div>
            <div class="card-glow" style="background: radial-gradient(circle at right, rgba(139, 92, 246, 0.15), transparent 60%);"></div>
        </div>
    </div>

    <!-- Lens Distribution Table -->
    <div class="card-widget mb-4">
        <div class="card-widget-header">
            <h3>Rekapitulasi Penggunaan Jenis Lensa</h3>
            <span class="text-muted text-xs">Statistik distribusi lensa kacamata yang diresepkan</span>
        </div>

        <div class="table-responsive">
            <table class="table" style="width: 100%;">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Jenis Lensa</th>
                        <th class="text-center">Jumlah Resep</th>
                        <th class="text-right">Total Transaksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($lensDistribution)): ?>
                        <tr><td colspan="4" class="text-center text-muted py-4">Belum ada data resep lensa.</td></tr>
                    <?php else: ?>
                        <?php $no=1; foreach ($lensDistribution as $lens): ?>
                            <tr>
                                <td><?= $no++ ?></td>
                                <td style="font-weight: 600; color: var(--color-dark);"><?= htmlspecialchars($lens['lens_type']) ?></td>
                                <td class="text-center font-weight-bold"><?= $lens['total_count'] ?> Resep</td>
                                <td class="text-right text-emerald font-weight-bold"><?= formatRupiah((float)$lens['total_amount']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Detailed Medical Records Table for Print / View -->
    <div class="card-widget mb-4">
        <div class="card-widget-header">
            <div>
                <h3>Rincian Rekam Medis & Resep Pasien</h3>
                <p class="text-muted text-xs">Daftar lengkap rekam medis pemeriksaan refraksi pasien.</p>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table" style="width: 100%; font-size: 0.85rem;">
                <thead>
                    <tr style="background-color: rgba(15, 23, 42, 0.04);">
                        <th>Tgl / No. Exam</th>
                        <th>Pasien / No. RM</th>
                        <th>OD (Mata Kanan)</th>
                        <th>OS (Mata Kiri)</th>
                        <th>PD</th>
                        <th>Lensa & Frame</th>
                        <th class="text-right">Biaya</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($records)): ?>
                        <tr><td colspan="7" class="text-center text-muted py-4">Belum ada rekam medis pada periode ini.</td></tr>
                    <?php else: ?>
                        <?php foreach ($records as $rec): ?>
                            <tr>
                                <td>
                                    <strong><?= date('d M Y', strtotime($rec['exam_date'])) ?></strong><br>
                                    <small class="text-muted"><?= htmlspecialchars($rec['record_number']) ?></small>
                                </td>
                                <td>
                                    <strong style="color: var(--color-dark);"><?= htmlspecialchars($rec['patient_name']) ?></strong><br>
                                    <small class="text-muted"><?= htmlspecialchars($rec['mr_number']) ?></small>
                                </td>
                                <td>
                                    S: <?= sprintf('%+.2f', $rec['od_sph']) ?> | C: <?= sprintf('%+.2f', $rec['od_cyl']) ?><br>
                                    A: <?= $rec['od_axis'] ?>° | Add: <?= sprintf('%+.2f', $rec['od_add']) ?>
                                </td>
                                <td>
                                    S: <?= sprintf('%+.2f', $rec['os_sph']) ?> | C: <?= sprintf('%+.2f', $rec['os_cyl']) ?><br>
                                    A: <?= $rec['os_axis'] ?>° | Add: <?= sprintf('%+.2f', $rec['os_add']) ?>
                                </td>
                                <td style="font-weight: 700;"><?= $rec['pd'] ?> mm</td>
                                <td>
                                    <span class="badge-category-pill" style="background: rgba(16, 185, 129, 0.1); color: #10b981;">
                                        <?= htmlspecialchars($rec['lens_type']) ?>
                                    </span><br>
                                    <small class="text-muted">Frame: <?= htmlspecialchars($rec['frame_code'] ?: '-') ?></small>
                                </td>
                                <td class="text-right font-weight-bold text-primary">
                                    <?= formatRupiah($rec['total_price']) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
