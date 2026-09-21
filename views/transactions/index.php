<?php
/**
 * Daftar Rekam Medis & Resep Optik View - OPTIK FOCUS
 */

if (!function_exists('formatRupiah')) {
    function formatRupiah(float $amount): string {
        return 'Rp ' . number_format($amount, 0, ',', '.');
    }
}
?>

<style>
@media print {
    @page {
        size: A4 portrait !important;
        margin: 8mm 12mm 0 12mm !important;
    }
    .sidebar, .bottom-nav, .topbar, .no-print {
        display: none !important;
    }
    .main-content, .content-body {
        margin: 0 !important;
        padding: 0 !important;
        width: 100% !important;
    }
    body.is-printing-prescription {
        background: #ffffff !important;
        margin: 0 !important;
        padding: 0 !important;
        color: #0f172a !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
    }
    body.is-printing-prescription .app-container > *:not(.main-content),
    body.is-printing-prescription .main-content > *:not(.content-body),
    body.is-printing-prescription .content-body > *:not(#printPrescriptionModal) {
        display: none !important;
    }
    body.is-printing-prescription #printPrescriptionModal {
        display: block !important;
        position: static !important;
        background: transparent !important;
        backdrop-filter: none !important;
        padding: 0 !important;
        margin: 0 !important;
        width: 100% !important;
        box-shadow: none !important;
        z-index: 99999 !important;
    }
    body.is-printing-prescription #printableCard {
        box-shadow: none !important;
        border: none !important;
        border-radius: 0 !important;
        padding: 0 !important;
        width: 100% !important;
        max-width: 180mm !important;
        max-height: 135mm !important;
        margin: 0 auto !important;
        background: #ffffff !important;
        page-break-inside: avoid !important;
        break-inside: avoid !important;
        font-size: 10px !important;
        line-height: 1.25 !important;
    }
    body.is-printing-prescription #printableCard .rx-kop-section {
        text-align: center !important;
        padding-bottom: 2px !important;
        margin-bottom: 4px !important;
        border-bottom: 2px solid #0f172a !important;
        position: relative !important;
    }
    body.is-printing-prescription #printableCard .rx-kop-image {
        width: 100% !important;
        max-width: 130mm !important;
        height: auto !important;
        display: block !important;
        margin: 0 auto !important;
    }
    body.is-printing-prescription #printableCard .rx-no-exam {
        font-size: 10px !important;
        position: absolute !important;
        top: 0 !important;
        right: 0 !important;
    }
    body.is-printing-prescription #printableCard .rx-meta-grid {
        display: grid !important;
        grid-template-columns: 1fr 1fr !important;
        gap: 2px 20px !important;
        margin-bottom: 4px !important;
        font-size: 10px !important;
        line-height: 1.2 !important;
    }
    body.is-printing-prescription #printableCard .rx-section-title {
        font-size: 10px !important;
        font-weight: 800 !important;
        margin: 0 0 2px 0 !important;
        letter-spacing: 0.02em !important;
    }
    body.is-printing-prescription #printableCard .rx-refraction-table {
        font-size: 10px !important;
        margin-bottom: 4px !important;
        border-collapse: collapse !important;
    }
    body.is-printing-prescription #printableCard .rx-refraction-table th {
        font-weight: 700 !important;
        padding: 2px 3px !important;
        font-size: 10px !important;
        line-height: 1.2 !important;
    }
    body.is-printing-prescription #printableCard .rx-refraction-table td {
        padding: 2px 3px !important;
        font-size: 10px !important;
        line-height: 1.2 !important;
    }
    body.is-printing-prescription #printableCard #rxPd {
        font-size: 10px !important;
        font-weight: 800 !important;
        color: #7c3aed !important;
    }
    body.is-printing-prescription #printableCard .rx-lens-frame-row {
        font-size: 10px !important;
        margin-bottom: 4px !important;
        line-height: 1.2 !important;
    }
    body.is-printing-prescription #printableCard .rx-diagnosis-box {
        font-size: 9.5px !important;
        margin-bottom: 4px !important;
        padding-left: 5px !important;
        border-left: 2.5px solid #4f46e5 !important;
        line-height: 1.25 !important;
    }
    body.is-printing-prescription #printableCard .rx-billing-table {
        font-size: 10px !important;
        margin-bottom: 4px !important;
        line-height: 1.2 !important;
    }
    body.is-printing-prescription #printableCard .rx-billing-table td {
        padding: 0.5px 0 !important;
    }
    body.is-printing-prescription #printableCard #rxNetPayable {
        font-weight: 800 !important;
    }
    body.is-printing-prescription #printableCard .rx-signatures-row {
        font-size: 10px !important;
        margin-top: 2px !important;
        line-height: 1.2 !important;
    }
    body.is-printing-prescription #printableCard .rx-signature-space {
        margin-top: 18px !important;
        font-weight: 700 !important;
    }
    body.is-printing-prescription #printableCard .print-half-a4-cutline {
        display: block !important;
        margin-top: 10px !important;
        padding-top: 4px !important;
        border-top: 1px dashed #94a3b8 !important;
        border-bottom: none !important;
        font-size: 8.5px !important;
        color: #64748b !important;
        text-align: center !important;
        letter-spacing: 0.05em !important;
    }
}
</style>

<div class="animate-fade-in mb-4">
    <!-- Action Bar -->
    <div style="display: flex; justify-content: flex-end; align-items: center; margin-bottom: 1.25rem;">
        <a href="<?= baseUrl('records/create') ?>" class="btn btn-primary px-4 py-2.5 rounded-pill shadow-sm" style="font-weight: 700;">
            <ion-icon name="add-circle-outline" class="mr-2" style="font-size: 1.25rem;"></ion-icon>
            <span>+ Input Rekam Medis Baru</span>
        </a>
    </div>

    <!-- Filters Card -->
    <div class="card-widget mb-4">
        <div class="card-widget-header">
            <h3>Pencarian & Filter Rekam Medis</h3>
        </div>
        
        <form method="GET" action="<?= baseUrl('transactions') ?>" class="filter-form">
            <div class="filter-grid" style="grid-template-columns: repeat(4, 1fr);">
                <!-- Search Input -->
                <div class="form-group" style="grid-column: span 2;">
                    <label for="filter-search">Cari Nama Pasien / No. RM / Kode Frame</label>
                    <input type="text" name="search" id="filter-search" class="form-control" placeholder="Contoh: Budi, RM-2026-001, Ray-Ban..." value="<?= htmlspecialchars($filters['search'] ?? '') ?>">
                </div>

                <!-- Lens Type Filter -->
                <div class="form-group">
                    <label for="filter-lens">Jenis Lensa</label>
                    <select name="lens_type" id="filter-lens" class="form-control">
                        <option value="">Semua Jenis Lensa</option>
                        <option value="-" <?= ($filters['lens_type'] ?? '') === '-' ? 'selected' : '' ?>>Tanpa Lensa / Ganti Frame</option>
                        <?php if (!empty($groupedLenses)): ?>
                            <?php foreach ($groupedLenses as $groupLabel => $lensGroup): ?>
                                <optgroup label="<?= htmlspecialchars($groupLabel) ?>">
                                    <?php foreach ($lensGroup as $l): ?>
                                        <option value="<?= htmlspecialchars($l['name']) ?>" <?= ($filters['lens_type'] ?? '') === $l['name'] ? 'selected' : '' ?>><?= htmlspecialchars($l['name']) ?></option>
                                    <?php endforeach; ?>
                                </optgroup>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <optgroup label="Single Vision - Oriental">
                                <option value="SV Ori 1.56" <?= ($filters['lens_type'] ?? '') === 'SV Ori 1.56' ? 'selected' : '' ?>>SV Ori 1.56</option>
                                <option value="SV Ori 1.56 UV 420" <?= ($filters['lens_type'] ?? '') === 'SV Ori 1.56 UV 420' ? 'selected' : '' ?>>SV Ori 1.56 UV 420</option>
                                <option value="SV Ori Bluecut 1.56" <?= ($filters['lens_type'] ?? '') === 'SV Ori Bluecut 1.56' ? 'selected' : '' ?>>SV Ori Bluecut 1.56</option>
                                <option value="SV Ori 1.61" <?= ($filters['lens_type'] ?? '') === 'SV Ori 1.61' ? 'selected' : '' ?>>SV Ori 1.61</option>
                                <option value="SV Ori 1.61 UV 420" <?= ($filters['lens_type'] ?? '') === 'SV Ori 1.61 UV 420' ? 'selected' : '' ?>>SV Ori 1.61 UV 420</option>
                            </optgroup>
                            <optgroup label="Single Vision - Leinz">
                                <option value="SV Plastik 1.56" <?= ($filters['lens_type'] ?? '') === 'SV Plastik 1.56' ? 'selected' : '' ?>>SV Plastik 1.56</option>
                                <option value="SV Plastik 1.56 UV 420" <?= ($filters['lens_type'] ?? '') === 'SV Plastik 1.56 UV 420' ? 'selected' : '' ?>>SV Plastik 1.56 UV 420</option>
                                <option value="SV Plastik 1.56 UV 400 Trans" <?= ($filters['lens_type'] ?? '') === 'SV Plastik 1.56 UV 400 Trans' ? 'selected' : '' ?>>SV Plastik 1.56 UV 400 Trans</option>
                                <option value="SV Plastik 1.56 UV 420 Trans" <?= ($filters['lens_type'] ?? '') === 'SV Plastik 1.56 UV 420 Trans' ? 'selected' : '' ?>>SV Plastik 1.56 UV 420 Trans</option>
                                <option value="SV Plastik 1.61" <?= ($filters['lens_type'] ?? '') === 'SV Plastik 1.61' ? 'selected' : '' ?>>SV Plastik 1.61</option>
                                <option value="SV Plastik 1.61 UV 420" <?= ($filters['lens_type'] ?? '') === 'SV Plastik 1.61 UV 420' ? 'selected' : '' ?>>SV Plastik 1.61 UV 420</option>
                                <option value="SV Plastik 1.67" <?= ($filters['lens_type'] ?? '') === 'SV Plastik 1.67' ? 'selected' : '' ?>>SV Plastik 1.67</option>
                                <option value="SV Plastik 1.67 UV 420" <?= ($filters['lens_type'] ?? '') === 'SV Plastik 1.67 UV 420' ? 'selected' : '' ?>>SV Plastik 1.67 UV 420</option>
                            </optgroup>
                            <optgroup label="Bifokal">
                                <option value="Rx 1.50 Round segment" <?= ($filters['lens_type'] ?? '') === 'Rx 1.50 Round segment' ? 'selected' : '' ?>>Rx 1.50 Round segment</option>
                                <option value="Rx 1.50 Flattop" <?= ($filters['lens_type'] ?? '') === 'Rx 1.50 Flattop' ? 'selected' : '' ?>>Rx 1.50 Flattop</option>
                                <option value="Ecosoft Eco 1.56" <?= ($filters['lens_type'] ?? '') === 'Ecosoft Eco 1.56' ? 'selected' : '' ?>>Ecosoft Eco 1.56</option>
                                <option value="Ecosoft Lite U-Blue 1.56" <?= ($filters['lens_type'] ?? '') === 'Ecosoft Lite U-Blue 1.56' ? 'selected' : '' ?>>Ecosoft Lite U-Blue 1.56</option>
                                <option value="Ecosoft Lite Photosun Gr/Br 1.56" <?= ($filters['lens_type'] ?? '') === 'Ecosoft Lite Photosun Gr/Br 1.56' ? 'selected' : '' ?>>Ecosoft Lite Photosun Gr/Br 1.56</option>
                                <option value="Ecosoft Photo U-Blue 1.56" <?= ($filters['lens_type'] ?? '') === 'Ecosoft Photo U-Blue 1.56' ? 'selected' : '' ?>>Ecosoft Photo U-Blue 1.56</option>
                                <option value="Kr. Ori" <?= ($filters['lens_type'] ?? '') === 'Kr. Ori' ? 'selected' : '' ?>>Kr. Ori</option>
                            </optgroup>
                        <?php endif; ?>
                    </select>
                </div>

                <!-- Date Filter -->
                <div class="form-group">
                    <label for="filter-start-date">Dari Tanggal</label>
                    <input type="date" name="start_date" id="filter-start-date" class="form-control" value="<?= $filters['start_date'] ?? '' ?>">
                </div>
            </div>

            <div class="filter-actions mt-3" style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                <?php if (!empty($filters['search']) || !empty($filters['lens_type']) || !empty($filters['start_date'])): ?>
                    <a href="<?= baseUrl('transactions') ?>" class="btn btn-secondary px-3">
                        Reset Filter
                    </a>
                <?php endif; ?>
                <button type="submit" class="btn btn-primary px-4">
                    <ion-icon name="funnel-outline" class="mr-1"></ion-icon>
                    Terapkan Filter
                </button>
            </div>
        </form>
    </div>



    <!-- Full-Width Medical Records Cards List -->
    <div class="card-widget">
        <div class="card-widget-header">
            <div>
                <h3>Riwayat Hasil Pemeriksaan Pasien</h3>
                <p class="text-muted text-xs">Data rekam medis refraksi dan resep kacamata Optik Focus.</p>
            </div>
            <span class="badge-category" style="background-color: #e0e7ff; color: #4f46e5;">
                <ion-icon name="eye-outline"></ion-icon>
                <span><?= count($records) ?> Catatan</span>
            </span>
        </div>
        
        <div class="tx-cards-container">
            <?php if (empty($records)): ?>
                <div class="text-center py-5 text-muted">
                    <ion-icon name="document-text-outline" style="font-size: 2.5rem; color: var(--text-muted); opacity: 0.6;"></ion-icon>
                    <p class="mt-2">Tidak ditemukan rekam medis yang sesuai dengan filter pencarian.</p>
                    <a href="<?= baseUrl('records/create') ?>" class="btn btn-primary mt-2">
                        + Input Rekam Medis Baru
                    </a>
                </div>
            <?php else: ?>
                <div class="tx-cards-list">
                    <?php foreach ($records as $rec): ?>
                        <div class="tx-card-item">
                            <!-- Card Header: Patient Info & Price -->
                            <div class="tx-card-header-row">
                                <div class="tx-card-title-group">
                                    <div class="tx-icon-avatar" style="background-color: rgba(99, 102, 241, 0.12); color: #6366f1;">
                                        <ion-icon name="glasses-outline"></ion-icon>
                                    </div>
                                    <div class="tx-patient-info-body">
                                        <div class="tx-patient-name-container">
                                            <strong class="patient-name-text"><?= htmlspecialchars($rec['patient_name']) ?></strong>
                                            <span class="mr-badge">
                                                <?= htmlspecialchars($rec['mr_number']) ?>
                                            </span>
                                            <?php 
                                            $recBpjs = !empty($rec['bpjs_class']) ? $rec['bpjs_class'] : 'Non-BPJS';
                                            $recBpjsNo = $rec['bpjs_number'] ?? '';
                                            if ($recBpjs !== 'Non-BPJS'): 
                                            ?>
                                                <span class="bpjs-badge" style="background: rgba(16, 185, 129, 0.12); color: #047857; border: 1px solid #a7f3d0; border-radius: 50px; padding: 0.15rem 0.65rem; font-size: 0.75rem; font-weight: 700; display: inline-flex; align-items: center; gap: 0.3rem;">
                                                    <ion-icon name="card-outline"></ion-icon>
                                                    BPJS <?= htmlspecialchars($recBpjs) ?> <?= !empty($recBpjsNo) ? '(' . htmlspecialchars($recBpjsNo) . ')' : '' ?>
                                                </span>
                                            <?php else: ?>
                                                <span class="bpjs-badge-mandiri" style="background: rgba(100, 116, 139, 0.1); color: #475569; border: 1px solid #cbd5e1; border-radius: 50px; padding: 0.15rem 0.65rem; font-size: 0.75rem; font-weight: 700; display: inline-flex; align-items: center; gap: 0.3rem;">
                                                    <ion-icon name="person-outline"></ion-icon> Pasien Umum (Mandiri)
                                                </span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="patient-sub-info">
                                            <span>JK: <?= $rec['patient_gender'] === 'L' ? 'Laki-laki' : 'Perempuan' ?></span>
                                            <span>&bull; No HP: <?= htmlspecialchars($rec['patient_phone'] ?: '-') ?></span>
                                            <span>&bull; Pemeriksa: <?= htmlspecialchars($rec['examiner_name']) ?></span>
                                        </div>
                                    </div>
                                </div>

                                <div class="tx-card-price-block">
                                    <span class="tx-amount-value text-primary">
                                        <?= formatRupiah($rec['total_price']) ?>
                                    </span>
                                    <span class="tx-exam-no">No. Exam: <?= htmlspecialchars($rec['record_number']) ?></span>
                                </div>
                            </div>

                            <!-- Mobile Prescription Brief Badge Box (Dashboard Style) -->
                            <div class="rx-badge-mobile-box">
                                <div>
                                    <strong style="color: var(--color-primary);">OD (Kanan):</strong> 
                                    SPH: <?= sprintf('%+.2f', $rec['od_sph']) ?> | CYL: <?= sprintf('%+.2f', $rec['od_cyl']) ?> | AXIS: <?= $rec['od_axis'] ?>° | ADD: <?= sprintf('%+.2f', $rec['od_add']) ?>
                                </div>
                                <div>
                                    <strong style="color: #ec4899;">OS (Kiri):</strong> 
                                    SPH: <?= sprintf('%+.2f', $rec['os_sph']) ?> | CYL: <?= sprintf('%+.2f', $rec['os_cyl']) ?> | AXIS: <?= $rec['os_axis'] ?>° | ADD: <?= sprintf('%+.2f', $rec['os_add']) ?>
                                </div>
                            </div>

                            <!-- Prescription Matrix Table -->
                            <div class="table-responsive my-2 prescription-table-wrapper">
                                <table class="table prescription-table">
                                    <thead>
                                        <tr>
                                            <th style="text-align: left; padding-left: 0.75rem;">MATA</th>
                                            <th>SPH</th>
                                            <th>CYL</th>
                                            <th>AXIS</th>
                                            <th>ADD</th>
                                            <th>VISUS (VA)</th>
                                            <th>PD (MM)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr style="border-bottom: 1px solid var(--color-border);">
                                            <td style="text-align: left; font-weight: 700; color: var(--color-primary); padding-left: 0.75rem;">OD (Kanan)</td>
                                            <td><?= sprintf('%+.2f', $rec['od_sph']) ?></td>
                                            <td><?= sprintf('%+.2f', $rec['od_cyl']) ?></td>
                                            <td><?= $rec['od_axis'] ?>°</td>
                                            <td><?= sprintf('%+.2f', $rec['od_add']) ?></td>
                                            <td><?= htmlspecialchars($rec['od_va']) ?></td>
                                            <td rowspan="2" class="pd-cell">
                                                <?= $rec['pd'] ?> mm
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="text-align: left; font-weight: 700; color: #ec4899; padding-left: 0.75rem;">OS (Kiri)</td>
                                            <td><?= sprintf('%+.2f', $rec['os_sph']) ?></td>
                                            <td><?= sprintf('%+.2f', $rec['os_cyl']) ?></td>
                                            <td><?= $rec['os_axis'] ?>°</td>
                                            <td><?= sprintf('%+.2f', $rec['os_add']) ?></td>
                                            <td><?= htmlspecialchars($rec['os_va']) ?></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <?php if (!empty($rec['diagnosis']) || !empty($rec['notes'])): ?>
                                <div class="tx-card-medical-info">
                                    <?php if (!empty($rec['diagnosis'])): ?>
                                        <div class="medical-info-box diagnosis-box">
                                            <strong class="info-label"><ion-icon name="medical-outline"></ion-icon> Diagnosa Refraksi:</strong>
                                            <span class="info-text"><?= htmlspecialchars($rec['diagnosis']) ?></span>
                                        </div>
                                    <?php endif; ?>

                                    <?php if (!empty($rec['notes'])): ?>
                                        <div class="medical-info-box notes-box">
                                            <strong class="info-label"><ion-icon name="document-text-outline"></ion-icon> Anamnesa / Catatan Medis:</strong>
                                            <span class="info-text"><?= htmlspecialchars($rec['notes']) ?></span>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <!-- Bottom Meta & Actions -->
                            <div class="tx-card-footer-row">
                                <div class="tx-sub-meta">
                                    <span class="tx-date-badge">
                                        <ion-icon name="calendar-outline"></ion-icon>
                                        <?= date('d M Y', strtotime($rec['exam_date'])) ?>
                                    </span>
                                    <span class="badge-category-pill pill-emerald">
                                        <ion-icon name="disc-outline"></ion-icon>
                                        <span><?= htmlspecialchars($rec['lens_type']) ?></span>
                                    </span>
                                    <?php if (!empty($rec['frame_code'])): ?>
                                        <span class="badge-category-pill pill-purple">
                                            <ion-icon name="pricetag-outline"></ion-icon>
                                            <span>Frame: <?= htmlspecialchars($rec['frame_code']) ?></span>
                                        </span>
                                    <?php endif; ?>
                                </div>

                                <div class="tx-card-actions">
                                    <!-- Riwayat Pasien Button -->
                                    <button type="button" class="btn btn-history-action" 
                                            onclick="openPatientHistoryModal('<?= htmlspecialchars($rec['mr_number'], ENT_QUOTES) ?>', '<?= htmlspecialchars(addslashes($rec['patient_name']), ENT_QUOTES) ?>')" 
                                            title="Lihat Histori Periksa Pasien Ini">
                                        <ion-icon name="time-outline" class="mr-1"></ion-icon>
                                        Riwayat Pasien
                                    </button>

                                    <!-- Cetak Resep Button -->
                                    <button type="button" class="btn btn-print-action" 
                                            onclick="printPrescriptionById(<?= $rec['id'] ?>)" 
                                            title="Cetak Resep Kacamata">
                                        <ion-icon name="print-outline" class="mr-1"></ion-icon>
                                        Cetak Resep
                                    </button>

                                    <!-- Edit Button -->
                                    <button type="button" class="btn-action-icon btn-action-edit" 
                                            onclick="editRecordById(<?= $rec['id'] ?>)" 
                                            title="Ubah Rekam Medis">
                                        <ion-icon name="create-outline"></ion-icon>
                                    </button>

                                    <!-- Delete Button -->
                                    <form method="POST" action="<?= baseUrl('transactions/delete') ?>" onsubmit="return confirm('Apakah Anda yakin ingin menghapus rekam medis pasien ini?');" style="margin: 0; padding: 0; display: inline-block;">
                                        <input type="hidden" name="id" value="<?= $rec['id'] ?>">
                                        <button type="submit" class="btn-action-icon btn-action-delete" title="Hapus Rekam Medis">
                                            <ion-icon name="trash-outline"></ion-icon>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- MODAL UBAH REKAM MEDIS & PRINT MODAL -->
<div id="editRecordModal" class="modal-overlay" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(15, 23, 42, 0.6); backdrop-filter: blur(8px); z-index: 1000; align-items: center; justify-content: center; padding: 1.5rem;">
    <div class="monthly-breakdown-card" style="width: 100%; max-width: 760px; max-height: 90vh; overflow-y: auto; margin-bottom: 0; border-radius: var(--radius-lg);">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--color-border); padding-bottom: 1rem; margin-bottom: 1.5rem;">
            <h3 style="font-size: 1.15rem; font-weight: 700; color: var(--color-dark);">Ubah Rekam Medis Pasien</h3>
            <button onclick="closeEditRecordModal()" style="background: none; border: none; font-size: 1.5rem; color: var(--text-muted); cursor: pointer;">
                <ion-icon name="close-outline"></ion-icon>
            </button>
        </div>

        <form method="POST" action="<?= baseUrl('transactions/edit') ?>">
            <input type="hidden" name="id" id="editRecordId">

            <div style="display: flex; gap: 0.75rem;" class="mb-3">
                <div class="form-group" style="flex: 1;">
                    <label for="edit_exam_date">Tanggal Periksa</label>
                    <input type="date" name="exam_date" id="edit_exam_date" class="form-control" required>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label for="edit_examiner_name">Pemeriksa</label>
                    <input type="text" name="examiner_name" id="edit_examiner_name" class="form-control" required>
                </div>
            </div>

            <!-- Prescription Grid OD & OS -->
            <div style="background: rgba(15, 23, 42, 0.02); border: 1px solid var(--color-border); border-radius: 10px; padding: 0.85rem; margin-bottom: 1rem;">
                <div style="font-weight: 700; font-size: 0.78rem; color: var(--color-primary);" class="mb-1">OD (Mata Kanan):</div>
                <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 0.35rem;" class="mb-2">
                    <input type="number" step="0.25" name="od_sph" id="edit_od_sph" placeholder="SPH" class="form-control" style="font-size: 0.8rem; padding: 0.35rem;">
                    <input type="number" step="0.25" name="od_cyl" id="edit_od_cyl" placeholder="CYL" class="form-control" style="font-size: 0.8rem; padding: 0.35rem;">
                    <input type="number" step="1" name="od_axis" id="edit_od_axis" placeholder="AXIS" class="form-control" style="font-size: 0.8rem; padding: 0.35rem;">
                    <input type="number" step="0.25" name="od_add" id="edit_od_add" placeholder="ADD" class="form-control" style="font-size: 0.8rem; padding: 0.35rem;">
                    <input type="text" name="od_va" id="edit_od_va" placeholder="VA" class="form-control" style="font-size: 0.8rem; padding: 0.35rem;">
                </div>

                <div style="font-weight: 700; font-size: 0.78rem; color: #ec4899;" class="mb-1">OS (Mata Kiri):</div>
                <div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 0.35rem;" class="mb-2">
                    <input type="number" step="0.25" name="os_sph" id="edit_os_sph" placeholder="SPH" class="form-control" style="font-size: 0.8rem; padding: 0.35rem;">
                    <input type="number" step="0.25" name="os_cyl" id="edit_os_cyl" placeholder="CYL" class="form-control" style="font-size: 0.8rem; padding: 0.35rem;">
                    <input type="number" step="1" name="os_axis" id="edit_os_axis" placeholder="AXIS" class="form-control" style="font-size: 0.8rem; padding: 0.35rem;">
                    <input type="number" step="0.25" name="os_add" id="edit_os_add" placeholder="ADD" class="form-control" style="font-size: 0.8rem; padding: 0.35rem;">
                    <input type="text" name="os_va" id="edit_os_va" placeholder="VA" class="form-control" style="font-size: 0.8rem; padding: 0.35rem;">
                </div>

                <div style="display: flex; align-items: center; gap: 0.5rem; margin-top: 0.5rem;">
                    <label for="edit_pd" style="font-size: 0.8rem; font-weight: 600; min-width: 70px;">PD (mm):</label>
                    <input type="number" step="0.5" name="pd" id="edit_pd" class="form-control" style="font-size: 0.8rem; padding: 0.4rem;">
                </div>
            </div>

            <!-- Diagnosa & Catatan Medis (2 Kolom Sama Rata) -->
            <div class="row-layout" style="display: flex; gap: 1rem; margin-bottom: 1rem;">
                <!-- Pilihan Diagnosa Multi-Choice -->
                <div class="col-6" style="flex: 1;">
                    <div class="form-group" style="height: 100%;">
                        <label style="font-weight: 700; color: var(--color-dark); display: flex; align-items: center; gap: 0.4rem;">
                            <ion-icon name="medical-outline" style="color: var(--color-primary); font-size: 1.1rem;"></ion-icon>
                            <span>Diagnosa Refraksi (Dapat Pilih Banyak)</span>
                        </label>
                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.6rem; background: rgba(15, 23, 42, 0.02); border: 1px solid var(--color-border); border-radius: 12px; padding: 0.85rem; height: calc(100% - 28px);">
                            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; padding: 0.5rem 0.75rem; background: #ffffff; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.88rem; font-weight: 600; transition: all 0.2s ease;">
                                <input type="checkbox" name="diagnosis[]" id="edit_diag_miopia" value="Miopia (-)" style="accent-color: var(--color-primary); width: 16px; height: 16px;">
                                <span>Miopia (-)</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; padding: 0.5rem 0.75rem; background: #ffffff; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.88rem; font-weight: 600; transition: all 0.2s ease;">
                                <input type="checkbox" name="diagnosis[]" id="edit_diag_hipermetropia" value="Hipermetropia (+)" style="accent-color: var(--color-primary); width: 16px; height: 16px;">
                                <span>Hipermetropia (+)</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; padding: 0.5rem 0.75rem; background: #ffffff; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.88rem; font-weight: 600; transition: all 0.2s ease;">
                                <input type="checkbox" name="diagnosis[]" id="edit_diag_astigmatisme" value="Astigmatisme (cyl)" style="accent-color: var(--color-primary); width: 16px; height: 16px;">
                                <span>Astigmatisme (cyl)</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; padding: 0.5rem 0.75rem; background: #ffffff; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.88rem; font-weight: 600; transition: all 0.2s ease;">
                                <input type="checkbox" name="diagnosis[]" id="edit_diag_presbiopi" value="Presbiopi (add)" style="accent-color: var(--color-primary); width: 16px; height: 16px;">
                                <span>Presbiopi (add)</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Input Anamnesa / Catatan Medis -->
                <div class="col-6" style="flex: 1;">
                    <div class="form-group" style="height: 100%;">
                        <label for="edit_notes" style="font-weight: 700; color: var(--color-dark); display: flex; align-items: center; gap: 0.4rem;">
                            <ion-icon name="document-text-outline" style="color: var(--color-primary); font-size: 1.1rem;"></ion-icon>
                            <span>Anamnesa / Catatan Medis</span>
                        </label>
                        <textarea name="notes" id="edit_notes" class="form-control" rows="3" placeholder="Keluhan fisik pasien & saran..." style="border-radius: 12px; height: calc(100% - 28px); min-height: 105px;"></textarea>
                    </div>
                </div>
            </div>

            <div style="display: flex; gap: 0.75rem;" class="mb-3">
                <div class="form-group" style="flex: 1;">
                    <label for="edit_lens_type_select">Jenis Lensa <span class="text-muted" style="font-weight: normal; font-size: 0.8rem;">(Opsional)</span></label>
                    <select id="edit_lens_type_select" class="form-control" onchange="handleLensTypeChange('edit')">
                        <option value="">-- Tanpa Lensa (Hanya Ganti Frame) --</option>
                        <?php if (!empty($groupedLenses)): ?>
                            <?php foreach ($groupedLenses as $groupLabel => $lensGroup): ?>
                                <optgroup label="<?= htmlspecialchars($groupLabel) ?>">
                                    <?php foreach ($lensGroup as $l): ?>
                                        <option value="<?= htmlspecialchars($l['name']) ?>"><?= htmlspecialchars($l['name']) ?></option>
                                    <?php endforeach; ?>
                                </optgroup>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <optgroup label="Single Vision - Oriental">
                                <option value="SV Ori 1.56">SV Ori 1.56</option>
                                <option value="SV Ori 1.56 UV 420">SV Ori 1.56 UV 420</option>
                                <option value="SV Ori Bluecut 1.56">SV Ori Bluecut 1.56</option>
                                <option value="SV Ori 1.61">SV Ori 1.61</option>
                                <option value="SV Ori 1.61 UV 420">SV Ori 1.61 UV 420</option>
                            </optgroup>
                            <optgroup label="Single Vision - Leinz">
                                <option value="SV Plastik 1.56">SV Plastik 1.56</option>
                                <option value="SV Plastik 1.56 UV 420">SV Plastik 1.56 UV 420</option>
                                <option value="SV Plastik 1.56 UV 400 Trans">SV Plastik 1.56 UV 400 Trans</option>
                                <option value="SV Plastik 1.56 UV 420 Trans">SV Plastik 1.56 UV 420 Trans</option>
                                <option value="SV Plastik 1.61">SV Plastik 1.61</option>
                                <option value="SV Plastik 1.61 UV 420">SV Plastik 1.61 UV 420</option>
                                <option value="SV Plastik 1.67">SV Plastik 1.67</option>
                                <option value="SV Plastik 1.67 UV 420">SV Plastik 1.67 UV 420</option>
                            </optgroup>
                            <optgroup label="Bifokal">
                                <option value="Rx 1.50 Round segment">Rx 1.50 Round segment</option>
                                <option value="Rx 1.50 Flattop">Rx 1.50 Flattop</option>
                                <option value="Ecosoft Eco 1.56">Ecosoft Eco 1.56</option>
                                <option value="Ecosoft Lite U-Blue 1.56">Ecosoft Lite U-Blue 1.56</option>
                                <option value="Ecosoft Lite Photosun Gr/Br 1.56">Ecosoft Lite Photosun Gr/Br 1.56</option>
                                <option value="Ecosoft Photo U-Blue 1.56">Ecosoft Photo U-Blue 1.56</option>
                                <option value="Kr. Ori">Kr. Ori</option>
                            </optgroup>
                        <?php endif; ?>
                        <option value="CUSTOM">✏️ Custom (Ketik Manual)</option>
                    </select>

                    <input type="hidden" name="lens_type" id="edit_lens_type" value="">
                    
                    <div id="edit_custom_lens_wrapper" style="display: none; margin-top: 0.5rem;">
                        <input type="text" id="edit_lens_type_custom" class="form-control" placeholder="Ketik jenis lensa manual..." oninput="updateFinalLensType('edit')">
                    </div>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label for="edit_frame_code">Kode Frame</label>
                    <input type="text" name="frame_code" id="edit_frame_code" class="form-control" list="master_frame_suggestions_edit">
                    <datalist id="master_frame_suggestions_edit">
                        <?php if (!empty($masterFrames)): ?>
                            <?php foreach ($masterFrames as $f): ?>
                                <option value="<?= htmlspecialchars($f['name'] . ' (' . $f['code'] . ')') ?>"></option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </datalist>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 0.75rem;" class="mb-3">
                <div class="form-group">
                    <label for="edit_lens_price">Biaya Lensa (Rp)</label>
                    <input type="number" name="lens_price" id="edit_lens_price" class="form-control" placeholder="0" oninput="recalcEditModalTotal()">
                </div>
                <div class="form-group">
                    <label for="edit_frame_price">Biaya Frame (Rp)</label>
                    <input type="number" name="frame_price" id="edit_frame_price" class="form-control" placeholder="0" oninput="recalcEditModalTotal()">
                </div>
                <div class="form-group">
                    <label for="edit_total_price">Total Biaya (Rp)</label>
                    <input type="number" name="total_price" id="edit_total_price" class="form-control" placeholder="0">
                </div>
            </div>

            <div style="display: flex; gap: 0.75rem; justify-content: flex-end; border-top: 1px solid var(--color-border); padding-top: 1.25rem;">
                <button type="button" class="btn btn-secondary" onclick="closeEditRecordModal()">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL PRINT RESEP KACAMATA (OPTIK FOCUS PRESCRIPTION CARD) -->
<div id="printPrescriptionModal" class="modal-overlay" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(15, 23, 42, 0.75); backdrop-filter: blur(8px); z-index: 1100; align-items: flex-start; justify-content: center; padding: 1.5rem 1.25rem; overflow-y: auto;">
    <div style="background: #ffffff; color: #0f172a; width: 100%; max-width: 520px; border-radius: 10px; padding: 1.2rem 1.4rem 1rem 1.4rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.35); font-family: 'Plus Jakarta Sans', 'Inter', -apple-system, sans-serif; position: relative; -webkit-font-smoothing: antialiased; margin: auto;" id="printableCard" class="printable-prescription-card">
        
        <!-- HEADER KOP RESEP (OFFICIAL KOP.PNG DARI CLIENT) -->
        <div class="rx-kop-section" style="text-align: center; padding-bottom: 0.35rem; margin-bottom: 0.55rem; border-bottom: 2px solid #0f172a; position: relative;">
            <span class="rx-no-exam" style="position: absolute; top: -4px; right: 0; font-size: 0.76rem; font-weight: 800; color: #2563eb; letter-spacing: 0.02em;" id="rxNoExam">REC-20260807-512</span>
            <img src="<?= baseUrl('images/kop.png') ?>?v=<?= file_exists(__DIR__ . '/../../public/images/kop.png') ? filemtime(__DIR__ . '/../../public/images/kop.png') : time() ?>" alt="Optik Focus" class="rx-kop-image" style="width: 100%; max-width: 440px; height: auto; display: block; margin: 0 auto;">
        </div>

        <!-- METADATA PASIEN & PEMERIKSAAN (2 KOLOM BERSIH) -->
        <div class="rx-meta-grid" style="display: grid; grid-template-columns: 1fr 1.15fr; gap: 0.3rem 1rem; font-size: 0.8rem; margin-bottom: 0.6rem; color: #1e293b;">
            <div>
                <strong style="color: #0f172a; font-weight: 700;">Nama Pasien:</strong> 
                <span id="rxPatientName" style="font-weight: 500; color: #334155;">-</span>
            </div>
            <div>
                <strong style="color: #0f172a; font-weight: 700;">No. RM:</strong> 
                <span id="rxMrNumber" style="font-weight: 600; color: #334155;">-</span>
            </div>
            <div>
                <strong style="color: #0f172a; font-weight: 700;">Tanggal:</strong> 
                <span id="rxExamDate" style="font-weight: 500; color: #334155;">-</span>
            </div>
            <div>
                <strong style="color: #0f172a; font-weight: 700;">Pemeriksa:</strong> 
                <span id="rxExaminer" style="font-weight: 500; color: #334155;">-</span>
            </div>
        </div>

        <!-- TABEL UKURAN REFRAKSI MATA -->
        <h4 class="rx-section-title" style="font-size: 0.78rem; font-weight: 800; color: #0f172a; margin: 0 0 0.3rem 0; letter-spacing: 0.02em; text-transform: uppercase;">UKURAN REFRAKSI MATA</h4>
        
        <table class="rx-refraction-table" style="width: 100%; border-collapse: collapse; text-align: center; font-size: 0.76rem; margin-bottom: 0.5rem; border: 1px solid #cbd5e1;">
            <thead>
                <tr style="background: #ffffff; font-weight: 700; color: #0f172a;">
                    <th style="padding: 0.3rem 0.4rem; border: 1px solid #cbd5e1; text-align: left; width: 22%;">MATA</th>
                    <th style="padding: 0.3rem 0.2rem; border: 1px solid #cbd5e1; width: 13%;">SPH</th>
                    <th style="padding: 0.3rem 0.2rem; border: 1px solid #cbd5e1; width: 13%;">CYL</th>
                    <th style="padding: 0.3rem 0.2rem; border: 1px solid #cbd5e1; width: 13%;">AXIS</th>
                    <th style="padding: 0.3rem 0.2rem; border: 1px solid #cbd5e1; width: 13%;">ADD</th>
                    <th style="padding: 0.3rem 0.2rem; border: 1px solid #cbd5e1; width: 12%;">VA</th>
                    <th style="padding: 0.3rem 0.2rem; border: 1px solid #cbd5e1; width: 14%;">PD</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="padding: 0.3rem 0.4rem; border: 1px solid #cbd5e1; font-weight: 700; color: #2563eb; text-align: left;">OD (Kanan)</td>
                    <td style="padding: 0.3rem 0.2rem; border: 1px solid #cbd5e1; color: #1e293b; font-weight: 500;" id="rxOdSph">+1.00</td>
                    <td style="padding: 0.3rem 0.2rem; border: 1px solid #cbd5e1; color: #1e293b; font-weight: 500;" id="rxOdCyl">+0.00</td>
                    <td style="padding: 0.3rem 0.2rem; border: 1px solid #cbd5e1; color: #1e293b; font-weight: 500;" id="rxOdAxis">0°</td>
                    <td style="padding: 0.3rem 0.2rem; border: 1px solid #cbd5e1; color: #1e293b; font-weight: 500;" id="rxOdAdd">+2.00</td>
                    <td style="padding: 0.3rem 0.2rem; border: 1px solid #cbd5e1; color: #1e293b; font-weight: 500;" id="rxOdVa">6/6</td>
                    <td rowspan="2" style="padding: 0.3rem 0.2rem; border: 1px solid #cbd5e1; vertical-align: middle; font-weight: 800; color: #7c3aed; font-size: 0.82rem;" id="rxPd">0 mm</td>
                </tr>
                <tr>
                    <td style="padding: 0.3rem 0.4rem; border: 1px solid #cbd5e1; font-weight: 700; color: #db2777; text-align: left;">OS (Kiri)</td>
                    <td style="padding: 0.3rem 0.2rem; border: 1px solid #cbd5e1; color: #1e293b; font-weight: 500;" id="rxOsSph">-1.50</td>
                    <td style="padding: 0.3rem 0.2rem; border: 1px solid #cbd5e1; color: #1e293b; font-weight: 500;" id="rxOsCyl">+0.00</td>
                    <td style="padding: 0.3rem 0.2rem; border: 1px solid #cbd5e1; color: #1e293b; font-weight: 500;" id="rxOsAxis">0°</td>
                    <td style="padding: 0.3rem 0.2rem; border: 1px solid #cbd5e1; color: #1e293b; font-weight: 500;" id="rxOsAdd">+2.00</td>
                    <td style="padding: 0.3rem 0.2rem; border: 1px solid #cbd5e1; color: #1e293b; font-weight: 500;" id="rxOsVa">6/6</td>
                </tr>
            </tbody>
        </table>

        <!-- JENIS LENSA & KODE FRAME -->
        <div class="rx-lens-frame-row" style="font-size: 0.78rem; color: #1e293b; margin-bottom: 0.5rem; display: flex; justify-content: space-between; align-items: center;">
            <div>
                <strong style="color: #0f172a; font-weight: 700;">Jenis Lensa:</strong> 
                <span id="rxLensType" style="color: #334155; font-weight: 500;">-</span>
            </div>
            <div>
                <strong style="color: #0f172a; font-weight: 700;">Kode Frame:</strong> 
                <span id="rxFrameCode" style="color: #334155; font-weight: 500;">-</span>
            </div>
        </div>

        <!-- DIAGNOSA & ANAMNESA DENGAN AKSEN GARIS BIRU DI KIRI -->
        <div class="rx-diagnosis-box" style="font-size: 0.76rem; color: #1e293b; margin-bottom: 0.6rem; border-left: 3px solid #4f46e5; padding-left: 0.55rem; line-height: 1.4;">
            <div style="margin-bottom: 0.15rem;">
                <strong style="color: #0f172a; font-weight: 700;">Diagnosa Refraksi:</strong> 
                <span id="rxDiagnosis" style="font-weight: 600; color: #1e40af;">-</span>
            </div>
            <div>
                <strong style="color: #0f172a; font-weight: 700;">Anamnesa / Catatan:</strong> 
                <span id="rxNotes" style="color: #334155; font-weight: 500;">-</span>
            </div>
        </div>

        <!-- RINCIAN BIAYA & SUBSIDI BPJS (ITEMIZED BILLING) -->
        <div style="font-size: 0.78rem; color: #0f172a; margin-bottom: 0.75rem; font-family: inherit;">
            <table class="rx-billing-table" style="width: 100%; border-collapse: collapse; border: none;">
                <tbody>
                    <tr>
                        <td style="padding: 0.1rem 0; width: 42%; text-align: left; vertical-align: top; font-weight: 600; color: #334155;">1. Lensa</td>
                        <td style="padding: 0.1rem 0; width: 22%; text-align: left;"></td>
                        <td style="padding: 0.1rem 0; width: 36%; text-align: left; white-space: nowrap; font-weight: 600; color: #0f172a;">
                            <span style="display: inline-block; width: 10px; font-weight: 500;">:</span>
                            <span id="rxLensPrice">200.000,-</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 0.1rem 0; text-align: left; vertical-align: top; font-weight: 600; color: #334155;">2. Frame</td>
                        <td style="padding: 0.1rem 0; text-align: left;"></td>
                        <td style="padding: 0.1rem 0; text-align: left; white-space: nowrap; font-weight: 600; color: #0f172a;">
                            <span style="display: inline-block; width: 10px; font-weight: 500;">:</span>
                            <span id="rxFramePrice">100.000,-</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 0.1rem 0;"></td>
                        <td style="padding: 0.1rem 0; text-align: left; white-space: nowrap; font-weight: 600; color: #334155;">Total</td>
                        <td style="padding: 0.1rem 0; text-align: left; white-space: nowrap; font-weight: 600; color: #0f172a;">
                            <span style="display: inline-block; width: 10px; font-weight: 500;">:</span>
                            <span id="rxTotalPrice">300.000,-</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 0.1rem 0;"></td>
                        <td style="padding: 0.1rem 0; text-align: left; white-space: nowrap; font-weight: 600; color: #334155;">Di bayar BPJS</td>
                        <td style="padding: 0.1rem 0; text-align: left; white-space: nowrap; font-weight: 600; color: #0f172a;">
                            <span style="display: inline-block; width: 10px; font-weight: 500;">:</span>
                            <span id="rxBpjsCover">165.000,-</span>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding: 0.1rem 0;"></td>
                        <td style="padding: 0.1rem 0; text-align: left; white-space: nowrap; font-weight: 700; color: #0f172a;">Total bayar</td>
                        <td style="padding: 0.1rem 0; text-align: left; white-space: nowrap; font-weight: 800; color: #0f172a;">
                            <span style="display: inline-block; width: 10px; font-weight: 700;">:</span>
                            <span id="rxNetPayable">135.000,-</span>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>

        <!-- TANDA TANGAN (PESERTA & OPTIC FOCUS SRAGEN) -->
        <div class="rx-signatures-row" style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; font-size: 0.78rem; color: #0f172a; margin-top: 0.4rem;">
            <!-- Kolom Kiri: Peserta -->
            <div style="text-align: left; display: flex; flex-direction: column; justify-content: space-between;">
                <div>
                    <div style="font-weight: 600; color: #0f172a;">Peserta</div>
                </div>
                <div class="rx-signature-space" style="margin-top: 1.5rem; font-weight: 700; color: #0f172a;">
                    ( <span id="rxSignPatient">Sri Partini</span> )
                </div>
            </div>

            <!-- Kolom Kanan: Sragen & OPTIC FOCUS -->
            <div style="text-align: left; display: flex; flex-direction: column; justify-content: space-between; padding-left: 1.25rem;">
                <div>
                    <div id="rxSignDatePlace" style="font-weight: 500; color: #475569;">Sragen, 07 Agustus 2026</div>
                    <div style="font-weight: 800; color: #0f172a; margin-top: 0.12rem; letter-spacing: 0.03em;">OPTIC FOCUS</div>
                </div>
                <div class="rx-signature-space" style="margin-top: 1.5rem; font-weight: 700; color: #0f172a;">
                    ( <span id="rxSignStaff">Atik DH</span> )
                </div>
            </div>
        </div>

        <!-- GARIS PANDUAN POTONG SETENGAH A4 (HANYA MUNCUL DI PRINT PADA KERTAS A4) -->
        <div class="print-half-a4-cutline" style="display: none; border-bottom: 1px dashed #94a3b8; margin-top: 1rem; padding-bottom: 0.25rem; text-align: center; font-size: 0.65rem; color: #94a3b8; letter-spacing: 0.05em;">
            ✂ &ndash;&ndash;&ndash;&ndash;&ndash;&ndash;&ndash;&ndash;&ndash;&ndash; Batas Potong Setengah Kertas A4 &ndash;&ndash;&ndash;&ndash;&ndash;&ndash;&ndash;&ndash;&ndash;&ndash; ✂
        </div>

        <!-- PANEL PENGATURAN CETAK CEPAT (HANYA DITAMPILKAN DI LAYAR / NO-PRINT) -->
        <div class="no-print" style="margin-top: 1.75rem; border-top: 1px dashed #cbd5e1; padding-top: 1rem; background: #f8fafc; border-radius: 8px; padding: 0.85rem;">
            <div style="font-size: 0.8rem; font-weight: 700; color: #475569; margin-bottom: 0.6rem; display: flex; align-items: center; justify-content: space-between;">
                <span>⚙️ Penyesuaian Nilai Biaya &amp; Tanda Tangan Cetak:</span>
                <span style="font-size: 0.72rem; color: #64748b; font-weight: normal;">*Dapat disesuaikan sebelum dicetak</span>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 0.5rem; font-size: 0.78rem; margin-bottom: 0.5rem;">
                <div>
                    <label style="font-weight: 600; color: #334155;">1. Biaya Lensa (Rp)</label>
                    <input type="number" id="cfgLensPrice" class="form-control" style="font-size: 0.78rem; padding: 0.25rem 0.5rem; height: auto;" oninput="recalcPrintBilling()">
                </div>
                <div>
                    <label style="font-weight: 600; color: #334155;">2. Biaya Frame (Rp)</label>
                    <input type="number" id="cfgFramePrice" class="form-control" style="font-size: 0.78rem; padding: 0.25rem 0.5rem; height: auto;" oninput="recalcPrintBilling()">
                </div>
                <div>
                    <label style="font-weight: 600; color: #334155;">Di bayar BPJS (Rp)</label>
                    <input type="number" id="cfgBpjsCover" class="form-control" style="font-size: 0.78rem; padding: 0.25rem 0.5rem; height: auto;" oninput="recalcPrintBilling()">
                </div>
            </div>
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.5rem; font-size: 0.78rem;">
                <div>
                    <label style="font-weight: 600; color: #334155;">Kota &amp; Tanggal Tanda Tangan</label>
                    <input type="text" id="cfgSignDatePlace" class="form-control" style="font-size: 0.78rem; padding: 0.25rem 0.5rem; height: auto;" oninput="document.getElementById('rxSignDatePlace').textContent = this.value">
                </div>
                <div>
                    <label style="font-weight: 600; color: #334155;">Nama Petugas Optik Focus</label>
                    <input type="text" id="cfgSignStaff" class="form-control" style="font-size: 0.78rem; padding: 0.25rem 0.5rem; height: auto;" value="Atik DH" oninput="document.getElementById('rxSignStaff').textContent = this.value">
                </div>
            </div>
        </div>

        <!-- ACTION BUTTONS (NO-PRINT) -->
        <div class="no-print" style="display: flex; gap: 0.75rem; justify-content: flex-end; margin-top: 1.25rem; border-top: 1px solid #e2e8f0; padding-top: 1rem;">
            <button type="button" class="btn btn-secondary" onclick="closePrintModal()">Tutup</button>
            <button type="button" class="btn btn-primary" onclick="triggerPrintPrescription()">
                <ion-icon name="print-outline" class="mr-1"></ion-icon>
                Cetak Dokumen
            </button>
        </div>
    </div>
</div>

<!-- MODAL RIWAYAT REKAM MEDIS PASIEN -->
<div id="patientHistoryModal" class="modal-overlay" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(15, 23, 42, 0.7); backdrop-filter: blur(8px); z-index: 1100; align-items: center; justify-content: center; padding: 1.5rem;">
    <div style="background: #ffffff; color: var(--color-dark); width: 100%; max-width: 840px; max-height: 90vh; overflow-y: auto; border-radius: 16px; padding: 1.75rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid var(--color-primary); padding-bottom: 1rem; margin-bottom: 1.25rem;">
            <div>
                <h3 style="margin: 0; font-size: 1.3rem; font-weight: 800; color: var(--color-dark); display: flex; align-items: center; gap: 0.5rem;">
                    <ion-icon name="time-outline" style="color: var(--color-primary);"></ion-icon>
                    <span>Histori Rekam Medis Pasien</span>
                </h3>
                <p class="text-muted text-sm" style="margin: 0.2rem 0 0 0;" id="historyModalPatientSub">
                    Daftar seluruh pemeriksaan refraksi mata
                </p>
            </div>
            <button type="button" onclick="closePatientHistoryModal()" style="background: none; border: none; font-size: 1.5rem; color: var(--text-muted); cursor: pointer;">
                <ion-icon name="close-outline"></ion-icon>
            </button>
        </div>

        <div id="patientHistoryContent" style="display: flex; flex-direction: column; gap: 1rem;">
            <!-- History items generated via JS -->
        </div>

        <div style="display: flex; justify-content: flex-end; margin-top: 1.5rem; border-top: 1px solid var(--color-border); padding-top: 1rem;">
            <button type="button" class="btn btn-secondary" onclick="closePatientHistoryModal()">Tutup</button>
        </div>
    </div>
</div>

<script>
const allRecordsData = <?= json_encode($records ?? []) ?>;

function escapeHtml(str) {
    if (str === null || str === undefined) return '';
    return String(str)
        .replace(/&/g, '&amp;')
        .replace(/</g, '&lt;')
        .replace(/>/g, '&gt;')
        .replace(/"/g, '&quot;')
        .replace(/'/g, '&#039;');
}

function setInputValue(id, val) {
    const el = document.getElementById(id);
    if (el) el.value = (val !== null && val !== undefined) ? val : '';
}

function setCheckboxChecked(id, checked) {
    const el = document.getElementById(id);
    if (el) el.checked = Boolean(checked);
}

function editRecordById(id) {
    const rec = allRecordsData.find(r => String(r.id) === String(id));
    if (rec) {
        openEditRecordModal(rec);
    } else {
        console.warn('Record not found for ID:', id);
    }
}

function printPrescriptionById(id) {
    const rec = allRecordsData.find(r => String(r.id) === String(id));
    if (rec) {
        printPrescription(rec);
    }
}

function openEditRecordModal(rec) {
    if (!rec) return;

    setInputValue('editRecordId', rec.id);
    setInputValue('edit_exam_date', rec.exam_date);
    setInputValue('edit_examiner_name', rec.examiner_name);
    setInputValue('edit_od_sph', rec.od_sph);
    setInputValue('edit_od_cyl', rec.od_cyl);
    setInputValue('edit_od_axis', rec.od_axis);
    setInputValue('edit_od_add', rec.od_add);
    setInputValue('edit_od_va', rec.od_va);
    setInputValue('edit_os_sph', rec.os_sph);
    setInputValue('edit_os_cyl', rec.os_cyl);
    setInputValue('edit_os_axis', rec.os_axis);
    setInputValue('edit_os_add', rec.os_add);
    setInputValue('edit_os_va', rec.os_va);
    setInputValue('edit_pd', rec.pd);
    setInputValue('edit_bpjs_class', rec.bpjs_class || 'Non-BPJS');
    setInputValue('edit_bpjs_number', rec.bpjs_number || '');
    
    // Set Lens Type value and select state
    const currentLens = rec.lens_type || '';
    const select = document.getElementById('edit_lens_type_select');
    const customWrapper = document.getElementById('edit_custom_lens_wrapper');
    const customInput = document.getElementById('edit_lens_type_custom');
    const finalInput = document.getElementById('edit_lens_type');
    
    if (finalInput) finalInput.value = currentLens;

    if (select) {
        let foundOption = false;
        for (let i = 0; i < select.options.length; i++) {
            if (select.options[i].value === currentLens) {
                select.selectedIndex = i;
                foundOption = true;
                break;
            }
        }

        if (!foundOption) {
            if (currentLens === '' || currentLens === '-') {
                select.value = '';
                if (customWrapper) customWrapper.style.display = 'none';
                if (customInput) customInput.value = '';
            } else {
                select.value = 'CUSTOM';
                if (customWrapper) customWrapper.style.display = 'block';
                if (customInput) customInput.value = currentLens;
            }
        } else {
            if (customWrapper) customWrapper.style.display = 'none';
            if (customInput) customInput.value = '';
        }
    }

    setInputValue('edit_frame_code', rec.frame_code || '');
    setInputValue('edit_lens_price', rec.lens_price !== undefined && rec.lens_price !== null ? rec.lens_price : '');
    setInputValue('edit_frame_price', rec.frame_price !== undefined && rec.frame_price !== null ? rec.frame_price : '');
    setInputValue('edit_total_price', rec.total_price !== undefined && rec.total_price !== null ? rec.total_price : '');
    
    const diagStr = rec.diagnosis || '';
    setCheckboxChecked('edit_diag_miopia', diagStr.includes('Miopia'));
    setCheckboxChecked('edit_diag_hipermetropia', diagStr.includes('Hipermetropia'));
    setCheckboxChecked('edit_diag_astigmatisme', diagStr.includes('Astigmatisme'));
    setCheckboxChecked('edit_diag_presbiopi', diagStr.includes('Presbiopi'));
    setInputValue('edit_notes', rec.notes || '');

    const modal = document.getElementById('editRecordModal');
    if (modal) {
        modal.style.display = 'flex';
    }
}

function recalcEditModalTotal() {
    const lens = parseFloat(document.getElementById('edit_lens_price').value) || 0;
    const frame = parseFloat(document.getElementById('edit_frame_price').value) || 0;
    document.getElementById('edit_total_price').value = lens + frame;
}

function handleLensTypeChange(mode) {
    const isEdit = mode === 'edit';
    const select = document.getElementById(isEdit ? 'edit_lens_type_select' : 'lens_type_select');
    const customWrapper = document.getElementById(isEdit ? 'edit_custom_lens_wrapper' : 'custom_lens_wrapper');
    const customInput = document.getElementById(isEdit ? 'edit_lens_type_custom' : 'lens_type_custom');
    const finalInput = document.getElementById(isEdit ? 'edit_lens_type' : 'lens_type');

    if (select.value === 'CUSTOM') {
        customWrapper.style.display = 'block';
        customInput.focus();
        finalInput.value = customInput.value.trim();
    } else {
        customWrapper.style.display = 'none';
        finalInput.value = select.value;
    }
}

function updateFinalLensType(mode) {
    const isEdit = mode === 'edit';
    const select = document.getElementById(isEdit ? 'edit_lens_type_select' : 'lens_type_select');
    const customInput = document.getElementById(isEdit ? 'edit_lens_type_custom' : 'lens_type_custom');
    const finalInput = document.getElementById(isEdit ? 'edit_lens_type' : 'lens_type');

    if (select.value === 'CUSTOM') {
        finalInput.value = customInput.value.trim();
    }
}

function closeEditRecordModal() {
    document.getElementById('editRecordModal').style.display = 'none';
}

function formatRupiahDash(amount) {
    const num = Math.round(Number(amount) || 0);
    return num.toLocaleString('id-ID') + ',-';
}

function getIndonesianDateStr(dateStr) {
    if (!dateStr) return '';
    try {
        const parts = dateStr.split('-');
        if (parts.length === 3) {
            const y = parts[0];
            const m = parseInt(parts[1], 10);
            const d = parseInt(parts[2], 10);
            const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
            return (d < 10 ? '0' + d : d) + ' ' + (months[m - 1] || '') + ' ' + y;
        }
    } catch(e) {}
    return dateStr;
}

function getBpjsSubsidy(bpjsClass) {
    if (!bpjsClass) return 0;
    const str = String(bpjsClass).toLowerCase();
    if (str.includes('kelas 1') || str === '1') return 330000;
    if (str.includes('kelas 2') || str === '2') return 220000;
    if (str.includes('kelas 3') || str === '3') return 165000;
    return 0;
}

let currentPrintRecord = null;

function printPrescription(rec) {
    currentPrintRecord = rec;

    document.getElementById('rxNoExam').textContent = rec.record_number || 'REC-000';
    document.getElementById('rxPatientName').textContent = rec.patient_name || '-';
    document.getElementById('rxMrNumber').textContent = rec.mr_number || '-';
    document.getElementById('rxExamDate').textContent = rec.exam_date || '-';
    document.getElementById('rxExaminer').textContent = rec.examiner_name || '-';

    document.getElementById('rxOdSph').textContent = (parseFloat(rec.od_sph) >= 0 ? '+' : '') + parseFloat(rec.od_sph || 0).toFixed(2);
    document.getElementById('rxOdCyl').textContent = (parseFloat(rec.od_cyl) >= 0 ? '+' : '') + parseFloat(rec.od_cyl || 0).toFixed(2);
    document.getElementById('rxOdAxis').textContent = (rec.od_axis || 0) + '°';
    document.getElementById('rxOdAdd').textContent = (parseFloat(rec.od_add) >= 0 ? '+' : '') + parseFloat(rec.od_add || 0).toFixed(2);
    document.getElementById('rxOdVa').textContent = rec.od_va || '6/6';

    document.getElementById('rxOsSph').textContent = (parseFloat(rec.os_sph) >= 0 ? '+' : '') + parseFloat(rec.os_sph || 0).toFixed(2);
    document.getElementById('rxOsCyl').textContent = (parseFloat(rec.os_cyl) >= 0 ? '+' : '') + parseFloat(rec.os_cyl || 0).toFixed(2);
    document.getElementById('rxOsAxis').textContent = (rec.os_axis || 0) + '°';
    document.getElementById('rxOsAdd').textContent = (parseFloat(rec.os_add) >= 0 ? '+' : '') + parseFloat(rec.os_add || 0).toFixed(2);
    document.getElementById('rxOsVa').textContent = rec.os_va || '6/6';

    document.getElementById('rxPd').textContent = (rec.pd !== null && rec.pd !== undefined && rec.pd !== '' ? rec.pd : '62') + ' mm';
    document.getElementById('rxLensType').textContent = rec.lens_type || '-';
    document.getElementById('rxFrameCode').textContent = rec.frame_code || '-';
    document.getElementById('rxDiagnosis').textContent = rec.diagnosis || '-';
    document.getElementById('rxNotes').textContent = rec.notes || '-';

    // Signatures
    document.getElementById('rxSignPatient').textContent = rec.patient_name || '-';
    const dateFormatted = getIndonesianDateStr(rec.exam_date) || getIndonesianDateStr(new Date().toISOString().slice(0, 10));
    const defaultSignDatePlace = 'Sragen, ' + dateFormatted;
    document.getElementById('rxSignDatePlace').textContent = defaultSignDatePlace;
    document.getElementById('cfgSignDatePlace').value = defaultSignDatePlace;

    const defaultStaff = 'Atik DH';
    document.getElementById('rxSignStaff').textContent = defaultStaff;
    document.getElementById('cfgSignStaff').value = defaultStaff;

    // Pricing calculation
    let lensPrice = parseFloat(rec.lens_price || 0);
    let framePrice = parseFloat(rec.frame_price || 0);
    let totalPrice = parseFloat(rec.total_price || 0);

    // If lens_price and frame_price aren't set separately yet, distribute or default sensibly
    if (lensPrice <= 0 && framePrice <= 0 && totalPrice > 0) {
        lensPrice = Math.round((totalPrice * 2 / 3) / 1000) * 1000;
        framePrice = totalPrice - lensPrice;
    } else if (totalPrice <= 0) {
        totalPrice = lensPrice + framePrice;
    }

    let bpjsSubsidy = getBpjsSubsidy(rec.bpjs_class || rec.patient_bpjs_class);

    document.getElementById('cfgLensPrice').value = lensPrice;
    document.getElementById('cfgFramePrice').value = framePrice;
    document.getElementById('cfgBpjsCover').value = bpjsSubsidy;

    recalcPrintBilling();

    const printModal = document.getElementById('printPrescriptionModal');
    printModal.style.display = 'flex';
    printModal.scrollTop = 0;
}

function recalcPrintBilling() {
    const lens = parseFloat(document.getElementById('cfgLensPrice').value) || 0;
    const frame = parseFloat(document.getElementById('cfgFramePrice').value) || 0;
    const total = lens + frame;
    const bpjs = parseFloat(document.getElementById('cfgBpjsCover').value) || 0;
    const net = Math.max(0, total - bpjs);

    document.getElementById('rxLensPrice').textContent = formatRupiahDash(lens);
    document.getElementById('rxFramePrice').textContent = formatRupiahDash(frame);
    document.getElementById('rxTotalPrice').textContent = formatRupiahDash(total);
    document.getElementById('rxBpjsCover').textContent = formatRupiahDash(bpjs);
    document.getElementById('rxNetPayable').textContent = formatRupiahDash(net);
}

function triggerPrintPrescription() {
    document.body.classList.add('is-printing-prescription');
    window.print();
}

window.addEventListener('beforeprint', () => {
    const modal = document.getElementById('printPrescriptionModal');
    if (modal && modal.style.display === 'flex') {
        document.body.classList.add('is-printing-prescription');
    }
});

window.addEventListener('afterprint', () => {
    document.body.classList.remove('is-printing-prescription');
});

function closePrintModal() {
    document.body.classList.remove('is-printing-prescription');
    document.getElementById('printPrescriptionModal').style.display = 'none';
}

function openPatientHistoryModal(mrNumber, patientName) {
    document.getElementById('historyModalPatientSub').innerHTML = `Riwayat pemeriksaan mata untuk <strong>${patientName}</strong> (${mrNumber})`;
    const container = document.getElementById('patientHistoryContent');
    container.innerHTML = '';

    const patientRecords = allRecordsData.filter(r => r.mr_number === mrNumber);

    if (patientRecords.length === 0) {
        container.innerHTML = '<div style="text-align: center; padding: 2rem; color: var(--text-muted);">Belum ada riwayat pemeriksaan lain untuk pasien ini.</div>';
    } else {
        patientRecords.forEach((r, idx) => {
            const card = document.createElement('div');
            card.style.cssText = 'background: rgba(15, 23, 42, 0.02); border: 1px solid var(--color-border); border-radius: 12px; padding: 1rem; margin-bottom: 1rem;';

            const isBpjs = r.bpjs_class && r.bpjs_class !== 'Non-BPJS';
            const bpjsBadgeHtml = isBpjs
                ? `<span style="font-size: 0.78rem; background: rgba(16, 185, 129, 0.12); color: #047857; border: 1px solid #a7f3d0; padding: 0.2rem 0.65rem; border-radius: 50px; font-weight: 700; display: inline-flex; align-items: center; gap: 0.3rem;">
                    <ion-icon name="card-outline"></ion-icon> BPJS ${escapeHtml(r.bpjs_class)} ${r.bpjs_number ? '(' + escapeHtml(r.bpjs_number) + ')' : ''}
                   </span>`
                : `<span style="font-size: 0.78rem; background: rgba(100, 116, 139, 0.1); color: #475569; border: 1px solid #cbd5e1; padding: 0.2rem 0.65rem; border-radius: 50px; font-weight: 700; display: inline-flex; align-items: center; gap: 0.3rem;">
                    <ion-icon name="person-outline"></ion-icon> Non-BPJS (Mandiri)
                   </span>`;
            
            card.innerHTML = `
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem; flex-wrap: wrap; gap: 0.5rem;">
                    <div style="font-weight: 700; font-size: 0.95rem; color: var(--color-primary); display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap;">
                        <span><ion-icon name="calendar-outline"></ion-icon> Periksa #${patientRecords.length - idx} &bull; ${r.exam_date}</span>
                        ${bpjsBadgeHtml}
                    </div>
                    <span style="font-size: 0.8rem; background: rgba(99, 102, 241, 0.1); color: var(--color-primary); padding: 0.2rem 0.6rem; border-radius: 50px; font-weight: 600;">
                        ${r.record_number}
                    </span>
                </div>
                <div style="overflow-x: auto; margin-bottom: 0.75rem;">
                    <table style="width: 100%; border-collapse: collapse; text-align: center; font-size: 0.85rem; border: 1px solid var(--color-border);">
                        <thead style="background: rgba(15, 23, 42, 0.04); font-weight: 700;">
                            <tr>
                                <th style="padding: 0.4rem; border: 1px solid var(--color-border); text-align: left;">MATA</th>
                                <th style="padding: 0.4rem; border: 1px solid var(--color-border);">SPH</th>
                                <th style="padding: 0.4rem; border: 1px solid var(--color-border);">CYL</th>
                                <th style="padding: 0.4rem; border: 1px solid var(--color-border);">AXIS</th>
                                <th style="padding: 0.4rem; border: 1px solid var(--color-border);">ADD</th>
                                <th style="padding: 0.4rem; border: 1px solid var(--color-border);">VA</th>
                                <th style="padding: 0.4rem; border: 1px solid var(--color-border);">PD</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td style="padding: 0.4rem; border: 1px solid var(--color-border); font-weight: 700; color: var(--color-primary); text-align: left;">OD (Kanan)</td>
                                <td style="padding: 0.4rem; border: 1px solid var(--color-border);">${(r.od_sph >= 0 ? '+' : '') + parseFloat(r.od_sph).toFixed(2)}</td>
                                <td style="padding: 0.4rem; border: 1px solid var(--color-border);">${(r.od_cyl >= 0 ? '+' : '') + parseFloat(r.od_cyl).toFixed(2)}</td>
                                <td style="padding: 0.4rem; border: 1px solid var(--color-border);">${r.od_axis}°</td>
                                <td style="padding: 0.4rem; border: 1px solid var(--color-border);">${(r.od_add >= 0 ? '+' : '') + parseFloat(r.od_add).toFixed(2)}</td>
                                <td style="padding: 0.4rem; border: 1px solid var(--color-border);">${escapeHtml(r.od_va)}</td>
                                <td rowspan="2" style="padding: 0.4rem; border: 1px solid var(--color-border); vertical-align: middle; font-weight: 700; background: rgba(99, 102, 241, 0.05);">${r.pd} mm</td>
                            </tr>
                            <tr>
                                <td style="padding: 0.4rem; border: 1px solid var(--color-border); font-weight: 700; color: #ec4899; text-align: left;">OS (Kiri)</td>
                                <td style="padding: 0.4rem; border: 1px solid var(--color-border);">${(r.os_sph >= 0 ? '+' : '') + parseFloat(r.os_sph).toFixed(2)}</td>
                                <td style="padding: 0.4rem; border: 1px solid var(--color-border);">${(r.os_cyl >= 0 ? '+' : '') + parseFloat(r.os_cyl).toFixed(2)}</td>
                                <td style="padding: 0.4rem; border: 1px solid var(--color-border);">${r.os_axis}°</td>
                                <td style="padding: 0.4rem; border: 1px solid var(--color-border);">${(r.os_add >= 0 ? '+' : '') + parseFloat(r.os_add).toFixed(2)}</td>
                                <td style="padding: 0.4rem; border: 1px solid var(--color-border);">${escapeHtml(r.os_va)}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div style="font-size: 0.82rem; color: var(--color-dark); display: flex; flex-wrap: wrap; gap: 1rem; background: #ffffff; padding: 0.6rem 0.86rem; border-radius: 8px; border: 1px dashed var(--color-border);">
                    <div><strong>Jalur Pembayaran:</strong> <span style="font-weight: 700; color: ${isBpjs ? '#047857' : '#475569'};">${isBpjs ? 'BPJS ' + escapeHtml(r.bpjs_class) : 'Non-BPJS (Mandiri)'}</span></div>
                    <div><strong>Lensa:</strong> ${escapeHtml(r.lens_type || '-')}</div>
                    <div><strong>Frame:</strong> ${escapeHtml(r.frame_code || '-')}</div>
                    <div><strong>Pemeriksa:</strong> ${escapeHtml(r.examiner_name || '-')}</div>
                    ${r.diagnosis ? `<div><strong>Diagnosa:</strong> ${escapeHtml(r.diagnosis)}</div>` : ''}
                    ${r.notes ? `<div><strong>Catatan:</strong> ${escapeHtml(r.notes)}</div>` : ''}
                </div>
            `;
            container.appendChild(card);
        });
    }

    document.getElementById('patientHistoryModal').style.display = 'flex';
}

function closePatientHistoryModal() {
    document.getElementById('patientHistoryModal').style.display = 'none';
}
</script>
