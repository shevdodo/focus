<?php
/**
 * Laporan Rekam Medis & Resep Optik View - OPTIK FOCUS
 */

if (!function_exists('formatRupiah')) {
    function formatRupiah(float $amount): string {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
}

if (!function_exists('formatExcelRx')) {
    function formatExcelRx(array $rec): string {
        $rxStr = '';
        
        // Right eye (OD / R)
        $rParts = [];
        if (isset($rec['od_sph']) && (float)$rec['od_sph'] != 0) {
            $rParts[] = ((float)$rec['od_sph'] > 0 ? '+' : '') . sprintf('%.2f', $rec['od_sph']);
        }
        if (isset($rec['od_cyl']) && (float)$rec['od_cyl'] != 0) {
            $rParts[] = 'C:' . ((float)$rec['od_cyl'] > 0 ? '+' : '') . sprintf('%.2f', $rec['od_cyl']);
        }
        if (!empty($rec['od_axis']) && (int)$rec['od_axis'] != 0) {
            $rParts[] = 'X' . $rec['od_axis'];
        }
        if (!empty($rParts)) {
            $rxStr .= 'R:' . implode(' ', $rParts);
        }
        
        // Left eye (OS / L)
        $lParts = [];
        if (isset($rec['os_sph']) && (float)$rec['os_sph'] != 0) {
            $lParts[] = ((float)$rec['os_sph'] > 0 ? '+' : '') . sprintf('%.2f', $rec['os_sph']);
        }
        if (isset($rec['os_cyl']) && (float)$rec['os_cyl'] != 0) {
            $lParts[] = 'C:' . ((float)$rec['os_cyl'] > 0 ? '+' : '') . sprintf('%.2f', $rec['os_cyl']);
        }
        if (!empty($rec['os_axis']) && (int)$rec['os_axis'] != 0) {
            $lParts[] = 'X' . $rec['os_axis'];
        }
        if (!empty($lParts)) {
            $rxStr .= ($rxStr ? ' ' : '') . 'L:' . implode(' ', $lParts);
        }

        // Add
        if (!empty($rec['od_add']) && (float)$rec['od_add'] != 0) {
            $rxStr .= ' ADD:' . ((float)$rec['od_add'] > 0 ? '+' : '') . sprintf('%.2f', $rec['od_add']);
        } elseif (!empty($rec['os_add']) && (float)$rec['os_add'] != 0) {
            $rxStr .= ' ADD:' . ((float)$rec['os_add'] > 0 ? '+' : '') . sprintf('%.2f', $rec['os_add']);
        }

        return $rxStr ?: 'Plano';
    }
}

if (!function_exists('extractBpjsClassNum')) {
    function extractBpjsClassNum($classStr): string {
        if (!$classStr) return '-';
        if (strpos($classStr, '1') !== false) return '1';
        if (strpos($classStr, '2') !== false) return '2';
        if (strpos($classStr, '3') !== false) return '3';
        return '-';
    }
}
?>

<div class="report-wrapper animate-fade-in">
    <!-- Top Filter Bar & Toolbar -->
    <div class="card-widget report-filter-card mb-4 no-print">
        <div class="report-filter-header" style="flex-wrap: wrap; gap: 1rem; align-items: center; justify-content: space-between;">
            <div class="month-selector-container" style="display: flex; align-items: center; gap: 0.6rem;">
                <label style="font-weight: 700; font-size: 0.88rem; color: var(--color-dark); display: flex; align-items: center; gap: 0.4rem; margin: 0;">
                    <ion-icon name="calendar-outline" style="color: var(--color-primary); font-size: 1.1rem;"></ion-icon>
                    <span>Filter Periode:</span>
                </label>
                <form action="<?= baseUrl('reports') ?>" method="GET" class="month-picker-form" id="monthForm" style="margin: 0;">
                    <div class="month-selector-group">
                        <div class="select-wrapper">
                            <input type="month" name="month" value="<?= htmlspecialchars($_GET['month'] ?? date('Y-m')) ?>" class="form-control select-month-input" onchange="document.getElementById('monthForm').submit();" style="padding-left: 0.75rem;">
                        </div>
                    </div>
                </form>
            </div>

            <!-- Action controls & View Switcher -->
            <div class="report-controls" style="flex-wrap: wrap; gap: 0.5rem; align-items: center;">
                <!-- Mode Switcher -->
                <div style="display: flex; background: rgba(15, 23, 42, 0.06); border-radius: 8px; padding: 3px; gap: 3px;">
                    <button type="button" id="btnModeStandard" onclick="switchReportMode('standard')" 
                            style="padding: 0.4rem 0.8rem; border-radius: 6px; font-size: 0.82rem; font-weight: 700; border: none; cursor: pointer; background: var(--color-primary); color: #fff; transition: all 0.2s;">
                        <ion-icon name="grid-outline"></ion-icon> Standard
                    </button>
                    <button type="button" id="btnModeExcel" onclick="switchReportMode('excel')" 
                            style="padding: 0.4rem 0.8rem; border-radius: 6px; font-size: 0.82rem; font-weight: 700; border: none; cursor: pointer; background: transparent; color: var(--text-muted); transition: all 0.2s;">
                        <ion-icon name="stats-chart-outline"></ion-icon> Grid Excel
                    </button>
                </div>

                <button onclick="exportToExcelCSV()" class="btn btn-secondary no-print" style="background: rgba(16, 185, 129, 0.1); color: #047857; border: 1px solid #a7f3d0;">
                    <ion-icon name="download-outline"></ion-icon>
                    <span>Export .CSV</span>
                </button>

                <button onclick="window.print()" class="btn btn-primary btn-print no-print">
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

    <!-- Standard Report View Container (Cards & Statistics) -->
    <div id="standardViewSection" class="no-print">
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

        <!-- Detailed Medical Records Table -->
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

    <!-- EXCEL GRID SPREADSHEET TABLE (EXACT EXCEL MATRIX FORMAT) -->
    <div id="excelViewSection" class="excel-sheet-container mb-4" style="display: none; background: #ffffff; border: 1px solid #b0b0b0; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 15px rgba(0,0,0,0.08); font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;">
        
        <!-- Print Header Title (Only appears on printed output) -->
        <div class="print-only-header" style="display: none; padding: 10px 0; text-align: center; border-bottom: 2px solid #000; margin-bottom: 10px;">
            <h2 style="font-size: 14pt; margin: 0; font-weight: 800; letter-spacing: 1px;">OPTIK FOCUS</h2>
            <p style="font-size: 10pt; margin: 4px 0 0 0; font-weight: 700;">LAPORAN REKAPITULASI REKAM MEDIS & KLAIM KACAMATA PASIEN</p>
            <p style="font-size: 9pt; margin: 2px 0 0 0;">Periode: <?= date('F Y', strtotime(($selectedMonth ?? date('Y-m')) . '-01')) ?></p>
        </div>
        
        <!-- Excel Header Bar -->
        <div style="background: #107c41; color: #ffffff; padding: 0.6rem 1rem; display: flex; justify-content: space-between; align-items: center;" class="no-print">
            <div style="display: flex; align-items: center; gap: 0.5rem; font-weight: 700; font-size: 0.9rem;">
                <ion-icon name="document-text" style="font-size: 1.2rem;"></ion-icon>
                <span>Format Lembar Kerja Excel Rekapitulasi Optik</span>
            </div>
            <div style="display: flex; gap: 0.5rem;">
                <button onclick="exportToExcelCSV()" class="btn btn-sm" style="background: #ffffff; color: #107c41; font-weight: 700; font-size: 0.8rem; border-radius: 4px; border: none; padding: 0.3rem 0.75rem;">
                    <ion-icon name="download-outline"></ion-icon> Export .CSV
                </button>
                <button onclick="window.print()" class="btn btn-sm" style="background: rgba(255,255,255,0.2); color: #ffffff; font-weight: 700; font-size: 0.8rem; border-radius: 4px; border: none; padding: 0.3rem 0.75rem;">
                    <ion-icon name="print-outline"></ion-icon> Cetak Excel Grid
                </button>
            </div>
        </div>

        <!-- Excel Table Grid -->
        <div style="overflow-x: auto;">
            <table id="excelReportTable" style="width: 100%; border-collapse: collapse; font-size: 11px; color: #000000; background: #ffffff;">
                <thead>
                    <!-- Data Header Row -->
                    <tr style="background: #f2f2f2; font-weight: bold; text-align: center; border-bottom: 2px solid #a0a0a0;">
                        <th style="border: 1px solid #c0c0c0; width: 40px; padding: 6px;">No</th>
                        <th style="border: 1px solid #c0c0c0; width: 85px; padding: 6px;">Tanggal</th>
                        <th style="border: 1px solid #c0c0c0; width: 110px; padding: 6px;">No. RM</th>
                        <th style="border: 1px solid #c0c0c0; width: 120px; padding: 6px;">No. BPJS</th>
                        <th style="border: 1px solid #c0c0c0; width: 140px; padding: 6px;">Nama Pasien</th>
                        <th style="border: 1px solid #c0c0c0; width: 200px; padding: 6px;">Alamat & Telepon</th>
                        <th style="border: 1px solid #c0c0c0; width: 85px; padding: 6px;">Kode Frame</th>
                        <th style="border: 1px solid #c0c0c0; width: 95px; padding: 6px;">Jenis Lensa</th>
                        <th style="border: 1px solid #c0c0c0; width: 240px; padding: 6px;">Ukuran Refraksi (Resep)</th>
                        <th style="border: 1px solid #c0c0c0; width: 45px; padding: 6px;">Kelas</th>
                        <th style="border: 1px solid #c0c0c0; width: 95px; padding: 6px;">Nominal (Rp)</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($records)): ?>
                        <tr><td colspan="11" style="text-align: center; padding: 15px; border: 1px solid #c0c0c0; color: #888;">Belum ada data rekam medis pada periode ini.</td></tr>
                    <?php else: ?>
                        <?php $no = 1; foreach ($records as $rec): ?>
                            <?php 
                                $bpjsNum = $rec['patient_bpjs_number'] ?? $rec['bpjs_number'] ?? '';
                                $bpjsClass = $rec['patient_bpjs_class'] ?? $rec['bpjs_class'] ?? '';
                            ?>
                            <tr style="border-bottom: 1px solid #d9d9d9;">
                                <td style="border: 1px solid #c0c0c0; padding: 5px; text-align: center; font-weight: bold;"><?= $no ?></td>
                                <td style="border: 1px solid #c0c0c0; padding: 5px; text-align: center;"><?= date('d/m/Y', strtotime($rec['exam_date'])) ?></td>
                                <td style="border: 1px solid #c0c0c0; padding: 5px; font-family: monospace; font-size: 10px; text-align: center; font-weight: 600; color: var(--color-primary);"><?= htmlspecialchars($rec['mr_number']) ?></td>
                                <td style="border: 1px solid #c0c0c0; padding: 5px; font-family: monospace; font-size: 10px; text-align: center;"><?= htmlspecialchars($bpjsNum ?: '-') ?></td>
                                <td style="border: 1px solid #c0c0c0; padding: 5px; font-weight: 600;"><?= htmlspecialchars($rec['patient_name']) ?></td>
                                <td style="border: 1px solid #c0c0c0; padding: 5px; font-size: 10.5px; word-break: break-word;">
                                    <?= htmlspecialchars($rec['patient_address'] ?: '-') ?> <?= !empty($rec['patient_phone']) ? '. ' . htmlspecialchars($rec['patient_phone']) : '' ?>
                                </td>
                                <td style="border: 1px solid #c0c0c0; padding: 5px; text-align: center;"><?= htmlspecialchars($rec['frame_code'] ?: '-') ?></td>
                                <td style="border: 1px solid #c0c0c0; padding: 5px;"><?= htmlspecialchars($rec['lens_type']) ?></td>
                                <td style="border: 1px solid #c0c0c0; padding: 5px; font-family: monospace; font-size: 10px; font-weight: 600;"><?= formatExcelRx($rec) ?></td>
                                <td style="border: 1px solid #c0c0c0; padding: 5px; text-align: center; font-weight: bold;"><?= extractBpjsClassNum($bpjsClass) ?></td>
                                <td style="border: 1px solid #c0c0c0; padding: 5px; text-align: right; font-weight: bold;"><?= formatRupiah($rec['total_price']) ?></td>
                            </tr>
                        <?php $no++; endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Excel Bottom Sheet Tab -->
        <div style="background: #f3f3f3; border-top: 1px solid #c0c0c0; padding: 0.35rem 1rem; display: flex; align-items: center; justify-content: space-between; font-size: 11px;" class="no-print">
            <div style="display: flex; align-items: center; gap: 0.4rem;">
                <span style="background: #ffffff; border: 1px solid #c0c0c0; border-bottom: 2px solid #107c41; padding: 0.2rem 0.8rem; font-weight: 700; color: #107c41; border-radius: 4px 4px 0 0;">
                    Sheet1
                </span>
                <span style="color: #666; font-size: 10.5px;">Tampilan Lembar Kerja Excel Optik</span>
            </div>
            <div style="color: #666; font-weight: 600; font-size: 10.5px;">
                Total <?= count($records) ?> Baris Transaksi
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    /* Hide all layout elements, navigation, sidebars, buttons, and standard cards */
    .header, .sidebar, .no-print, .report-filter-card, .dashboard-grid, .card-widget, nav, header, footer, #standardViewSection {
        display: none !important;
    }
    
    body, .app-layout, .main-content, .report-wrapper {
        background: #ffffff !important;
        padding: 0 !important;
        margin: 0 !important;
        width: 100% !important;
    }

    /* Force display ONLY the Excel Grid Table Section */
    #excelViewSection {
        display: block !important;
        border: none !important;
        box-shadow: none !important;
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    .print-only-header {
        display: block !important;
    }

    #excelReportTable {
        width: 100% !important;
        border-collapse: collapse !important;
        font-size: 8.5pt !important;
        background: #ffffff !important;
    }

    #excelReportTable th, #excelReportTable td {
        border: 1px solid #000000 !important;
        padding: 4px 5px !important;
        color: #000000 !important;
    }

    @page {
        size: A4 landscape;
        margin: 0.6cm;
    }
}
</style>

<script>
function switchReportMode(mode) {
    const btnStd = document.getElementById('btnModeStandard');
    const btnXls = document.getElementById('btnModeExcel');
    const excelSec = document.getElementById('excelViewSection');
    const stdSec = document.getElementById('standardViewSection');

    if (mode === 'excel') {
        excelSec.style.display = 'block';
        stdSec.style.display = 'none';
        btnXls.style.background = 'var(--color-primary)';
        btnXls.style.color = '#ffffff';
        btnStd.style.background = 'transparent';
        btnStd.style.color = 'var(--text-muted)';
    } else {
        stdSec.style.display = 'block';
        excelSec.style.display = 'none';
        btnStd.style.background = 'var(--color-primary)';
        btnStd.style.color = '#ffffff';
        btnXls.style.background = 'transparent';
        btnXls.style.color = 'var(--text-muted)';
    }
}

function exportToExcelCSV() {
    const records = <?= json_encode($records ?? []) ?>;
    if (!records.length) {
        alert('Tidak ada data rekam medis untuk diexport.');
        return;
    }

    let csvContent = "data:text/csv;charset=utf-8,";
    csvContent += "No,Tanggal,No. RM,No. BPJS,Nama Pasien,Alamat & Telepon,Kode Frame,Jenis Lensa,Ukuran Refraksi (Resep),Kelas BPJS,Nominal (Rp)\n";

    records.forEach((r, idx) => {
        const tgl = r.exam_date;
        const no = idx + 1;
        const rm = `"${(r.mr_number || '').replace(/"/g, '""')}"`;
        const bpjs = `"${(r.patient_bpjs_number || r.bpjs_number || '-').replace(/"/g, '""')}"`;
        const nama = `"${(r.patient_name || '').replace(/"/g, '""')}"`;
        const alamat = `"${((r.patient_address || '') + ' ' + (r.patient_phone || '')).replace(/"/g, '""')}"`;
        const frame = `"${(r.frame_code || '-').replace(/"/g, '""')}"`;
        const lensa = `"${(r.lens_type || '').replace(/"/g, '""')}"`;
        
        let rxStr = '';
        if (r.od_sph) rxStr += `R:${r.od_sph} `;
        if (r.od_cyl) rxStr += `C:${r.od_cyl} `;
        if (r.od_axis) rxStr += `X:${r.od_axis} `;
        if (r.os_sph) rxStr += `L:${r.os_sph} `;
        if (r.os_cyl) rxStr += `C:${r.os_cyl} `;
        if (r.os_axis) rxStr += `X:${r.os_axis} `;
        if (r.od_add || r.os_add) rxStr += `ADD:${r.od_add || r.os_add}`;
        
        const rx = `"${(rxStr.trim() || 'Plano').replace(/"/g, '""')}"`;
        const kelas = r.patient_bpjs_class || r.bpjs_class || '-';
        const nominal = r.total_price || 0;

        csvContent += `${no},${tgl},${rm},${bpjs},${nama},${alamat},${frame},${lensa},${rx},${kelas},${nominal}\n`;
    });

    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", `Laporan_Optik_Focus_${new Date().toISOString().slice(0,10)}.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>
