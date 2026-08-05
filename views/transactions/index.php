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
                        <option value="Single Vision Antiradiasi" <?= ($filters['lens_type'] ?? '') === 'Single Vision Antiradiasi' ? 'selected' : '' ?>>Single Vision Antiradiasi</option>
                        <option value="Photocromic Bluecut" <?= ($filters['lens_type'] ?? '') === 'Photocromic Bluecut' ? 'selected' : '' ?>>Photocromic Bluecut</option>
                        <option value="Progressive Office" <?= ($filters['lens_type'] ?? '') === 'Progressive Office' ? 'selected' : '' ?>>Progressive Office</option>
                        <option value="Bifokal Kryptok" <?= ($filters['lens_type'] ?? '') === 'Bifokal Kryptok' ? 'selected' : '' ?>>Bifokal Kryptok</option>
                        <option value="Bluecut Hi-Index 1.67" <?= ($filters['lens_type'] ?? '') === 'Bluecut Hi-Index 1.67' ? 'selected' : '' ?>>Bluecut Hi-Index 1.67</option>
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
                                            <?php if (!empty($rec['patient_bpjs_class']) && $rec['patient_bpjs_class'] !== 'Non-BPJS'): ?>
                                                <span class="bpjs-badge">
                                                    <ion-icon name="card-outline"></ion-icon>
                                                    BPJS <?= htmlspecialchars($rec['patient_bpjs_class']) ?> <?= !empty($rec['patient_bpjs_number']) ? '(' . htmlspecialchars($rec['patient_bpjs_number']) . ')' : '' ?>
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
                                    <button class="btn btn-history-action" 
                                            onclick="openPatientHistoryModal('<?= $rec['mr_number'] ?>', '<?= htmlspecialchars(addslashes($rec['patient_name'])) ?>')" 
                                            title="Lihat Histori Periksa Pasien Ini">
                                        <ion-icon name="time-outline" class="mr-1"></ion-icon>
                                        Riwayat Pasien
                                    </button>

                                    <!-- Cetak Resep Button -->
                                    <button class="btn btn-print-action" 
                                            onclick="printPrescription(<?= htmlspecialchars(json_encode($rec)) ?>)" 
                                            title="Cetak Resep Kacamata">
                                        <ion-icon name="print-outline" class="mr-1"></ion-icon>
                                        Cetak Resep
                                    </button>

                                    <!-- Edit Button -->
                                    <button class="btn-action-icon btn-action-edit" 
                                            onclick="openEditRecordModal(<?= htmlspecialchars(json_encode($rec)) ?>)" 
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
                    <label for="edit_lens_type">Jenis Lensa</label>
                    <input type="text" name="lens_type" id="edit_lens_type" class="form-control" required>
                </div>
                <div class="form-group" style="flex: 1;">
                    <label for="edit_frame_code">Kode Frame</label>
                    <input type="text" name="frame_code" id="edit_frame_code" class="form-control">
                </div>
            </div>

            <div class="form-group mb-3">
                <label for="edit_total_price">Total Biaya (Rupiah)</label>
                <input type="number" name="total_price" id="edit_total_price" class="form-control">
            </div>

            <div style="display: flex; gap: 0.75rem; justify-content: flex-end; border-top: 1px solid var(--color-border); padding-top: 1.25rem;">
                <button type="button" class="btn btn-secondary" onclick="closeEditRecordModal()">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- MODAL PRINT RESEP KACAMATA (OPTIK FOCUS PRESCRIPTION CARD) -->
<div id="printPrescriptionModal" class="modal-overlay" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(15, 23, 42, 0.7); backdrop-filter: blur(8px); z-index: 1100; align-items: center; justify-content: center; padding: 1.5rem;">
    <div style="background: #ffffff; color: #0f172a; width: 100%; max-width: 540px; border-radius: 16px; padding: 2rem; box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);" id="printableCard">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #6366f1; padding-bottom: 1rem; margin-bottom: 1.25rem;">
            <div>
                <h2 style="color: #6366f1; font-size: 1.5rem; font-weight: 800; margin: 0; display: flex; align-items: center; gap: 0.5rem;">
                    <ion-icon name="glasses-outline"></ion-icon> OPTIK FOCUS
                </h2>
                <p style="font-size: 0.8rem; color: #64748b; margin: 0.2rem 0 0 0;">Klinik & Layanan Rekam Medis Kacamata Profesional</p>
            </div>
            <div style="text-align: right;">
                <span style="font-size: 0.75rem; font-weight: 700; background: #e0e7ff; color: #4f46e5; padding: 0.25rem 0.5rem; border-radius: 4px;" id="rxNoExam">EXAM-000</span>
            </div>
        </div>

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; background: #f8fafc; padding: 0.85rem; border-radius: 8px; font-size: 0.85rem; margin-bottom: 1.25rem;">
            <div><strong>Nama Pasien:</strong> <span id="rxPatientName">-</span></div>
            <div><strong>No. RM:</strong> <span id="rxMrNumber">-</span></div>
            <div><strong>Tanggal:</strong> <span id="rxExamDate">-</span></div>
            <div><strong>Pemeriksa:</strong> <span id="rxExaminer">-</span></div>
        </div>

        <h4 style="font-size: 0.9rem; font-weight: 700; color: #334155; margin-bottom: 0.5rem;">UKURAN REFRAKSI MATA</h4>
        <table style="width: 100%; border-collapse: collapse; text-align: center; font-size: 0.85rem; margin-bottom: 1.25rem; border: 1px solid #cbd5e1;">
            <thead>
                <tr style="background: #f1f5f9; font-weight: 700;">
                    <th style="padding: 0.5rem; border: 1px solid #cbd5e1; text-align: left;">MATA</th>
                    <th style="padding: 0.5rem; border: 1px solid #cbd5e1;">SPH</th>
                    <th style="padding: 0.5rem; border: 1px solid #cbd5e1;">CYL</th>
                    <th style="padding: 0.5rem; border: 1px solid #cbd5e1;">AXIS</th>
                    <th style="padding: 0.5rem; border: 1px solid #cbd5e1;">ADD</th>
                    <th style="padding: 0.5rem; border: 1px solid #cbd5e1;">VA</th>
                    <th style="padding: 0.5rem; border: 1px solid #cbd5e1;">PD</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td style="padding: 0.5rem; border: 1px solid #cbd5e1; font-weight: 700; color: #4f46e5; text-align: left;">OD (Kanan)</td>
                    <td style="padding: 0.5rem; border: 1px solid #cbd5e1;" id="rxOdSph">0.00</td>
                    <td style="padding: 0.5rem; border: 1px solid #cbd5e1;" id="rxOdCyl">0.00</td>
                    <td style="padding: 0.5rem; border: 1px solid #cbd5e1;" id="rxOdAxis">0°</td>
                    <td style="padding: 0.5rem; border: 1px solid #cbd5e1;" id="rxOdAdd">0.00</td>
                    <td style="padding: 0.5rem; border: 1px solid #cbd5e1;" id="rxOdVa">6/6</td>
                    <td rowspan="2" style="padding: 0.5rem; border: 1px solid #cbd5e1; vertical-align: middle; font-weight: 700; background: #faf5ff; color: #7e22ce;" id="rxPd">62 mm</td>
                </tr>
                <tr>
                    <td style="padding: 0.5rem; border: 1px solid #cbd5e1; font-weight: 700; color: #db2777; text-align: left;">OS (Kiri)</td>
                    <td style="padding: 0.5rem; border: 1px solid #cbd5e1;" id="rxOsSph">0.00</td>
                    <td style="padding: 0.5rem; border: 1px solid #cbd5e1;" id="rxOsCyl">0.00</td>
                    <td style="padding: 0.5rem; border: 1px solid #cbd5e1;" id="rxOsAxis">0°</td>
                    <td style="padding: 0.5rem; border: 1px solid #cbd5e1;" id="rxOsAdd">0.00</td>
                    <td style="padding: 0.5rem; border: 1px solid #cbd5e1;" id="rxOsVa">6/6</td>
                </tr>
            </tbody>
        </table>

        <div style="font-size: 0.85rem; color: #334155; margin-bottom: 0.85rem; display: flex; justify-content: space-between;">
            <div><strong>Jenis Lensa:</strong> <span id="rxLensType">-</span></div>
            <div><strong>Kode Frame:</strong> <span id="rxFrameCode">-</span></div>
        </div>

        <div style="font-size: 0.82rem; color: #334155; margin-bottom: 1.25rem; background: #f8fafc; padding: 0.75rem; border-radius: 8px; border-left: 3px solid #6366f1;">
            <div style="margin-bottom: 0.35rem;"><strong>Diagnosa Refraksi:</strong> <span id="rxDiagnosis" style="font-weight: 600;">-</span></div>
            <div><strong>Anamnesa / Catatan:</strong> <span id="rxNotes">-</span></div>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: flex-end; margin-top: 1.5rem;">
            <div style="font-size: 0.75rem; color: #94a3b8;">
                *Kartu resep kacamata sah dari Optik Focus
            </div>
            <div style="text-align: center;">
                <div style="font-size: 0.8rem; color: #64748b; margin-bottom: 2.5rem;">Pemeriksa / Optometris</div>
                <div style="font-weight: 700; border-top: 1px solid #0f172a; padding-top: 0.2rem; min-width: 140px;" id="rxSignExaminer">dr. Optometris</div>
            </div>
        </div>

        <div class="no-print" style="display: flex; gap: 0.75rem; justify-content: flex-end; margin-top: 1.5rem; border-top: 1px solid #e2e8f0; padding-top: 1rem;">
            <button type="button" class="btn btn-secondary" onclick="closePrintModal()">Tutup</button>
            <button type="button" class="btn btn-primary" onclick="window.print()">
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

function openEditRecordModal(rec) {
    document.getElementById('editRecordId').value = rec.id;
    document.getElementById('edit_exam_date').value = rec.exam_date;
    document.getElementById('edit_examiner_name').value = rec.examiner_name;
    document.getElementById('edit_od_sph').value = rec.od_sph;
    document.getElementById('edit_od_cyl').value = rec.od_cyl;
    document.getElementById('edit_od_axis').value = rec.od_axis;
    document.getElementById('edit_od_add').value = rec.od_add;
    document.getElementById('edit_od_va').value = rec.od_va;
    document.getElementById('edit_os_sph').value = rec.os_sph;
    document.getElementById('edit_os_cyl').value = rec.os_cyl;
    document.getElementById('edit_os_axis').value = rec.os_axis;
    document.getElementById('edit_os_add').value = rec.os_add;
    document.getElementById('edit_os_va').value = rec.os_va;
    document.getElementById('edit_pd').value = rec.pd;
    document.getElementById('edit_lens_type').value = rec.lens_type;
    document.getElementById('edit_frame_code').value = rec.frame_code || '';
    document.getElementById('edit_total_price').value = rec.total_price !== undefined && rec.total_price !== null ? rec.total_price : '';
    const diagStr = rec.diagnosis || '';
    document.getElementById('edit_diag_miopia').checked = diagStr.includes('Miopia');
    document.getElementById('edit_diag_hipermetropia').checked = diagStr.includes('Hipermetropia');
    document.getElementById('edit_diag_astigmatisme').checked = diagStr.includes('Astigmatisme');
    document.getElementById('edit_diag_presbiopi').checked = diagStr.includes('Presbiopi');
    document.getElementById('edit_notes').value = rec.notes || '';

    document.getElementById('editRecordModal').style.display = 'flex';
}

function closeEditRecordModal() {
    document.getElementById('editRecordModal').style.display = 'none';
}

function printPrescription(rec) {
    document.getElementById('rxNoExam').textContent = rec.record_number;
    document.getElementById('rxPatientName').textContent = rec.patient_name;
    document.getElementById('rxMrNumber').textContent = rec.mr_number;
    document.getElementById('rxExamDate').textContent = rec.exam_date;
    document.getElementById('rxExaminer').textContent = rec.examiner_name;

    document.getElementById('rxOdSph').textContent = (rec.od_sph >= 0 ? '+' : '') + parseFloat(rec.od_sph).toFixed(2);
    document.getElementById('rxOdCyl').textContent = (rec.od_cyl >= 0 ? '+' : '') + parseFloat(rec.od_cyl).toFixed(2);
    document.getElementById('rxOdAxis').textContent = rec.od_axis + '°';
    document.getElementById('rxOdAdd').textContent = (rec.od_add >= 0 ? '+' : '') + parseFloat(rec.od_add).toFixed(2);
    document.getElementById('rxOdVa').textContent = rec.od_va;

    document.getElementById('rxOsSph').textContent = (rec.os_sph >= 0 ? '+' : '') + parseFloat(rec.os_sph).toFixed(2);
    document.getElementById('rxOsCyl').textContent = (rec.os_cyl >= 0 ? '+' : '') + parseFloat(rec.os_cyl).toFixed(2);
    document.getElementById('rxOsAxis').textContent = rec.os_axis + '°';
    document.getElementById('rxOsAdd').textContent = (rec.os_add >= 0 ? '+' : '') + parseFloat(rec.os_add).toFixed(2);
    document.getElementById('rxOsVa').textContent = rec.os_va;

    document.getElementById('rxPd').textContent = rec.pd + ' mm';
    document.getElementById('rxLensType').textContent = rec.lens_type;
    document.getElementById('rxFrameCode').textContent = rec.frame_code || '-';
    document.getElementById('rxDiagnosis').textContent = rec.diagnosis || '-';
    document.getElementById('rxNotes').textContent = rec.notes || '-';
    document.getElementById('rxSignExaminer').textContent = rec.examiner_name;

    document.getElementById('printPrescriptionModal').style.display = 'flex';
}

function closePrintModal() {
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
            card.style.cssText = 'background: rgba(15, 23, 42, 0.02); border: 1px solid var(--color-border); border-radius: 12px; padding: 1rem;';
            
            card.innerHTML = `
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                    <div style="font-weight: 700; font-size: 0.95rem; color: var(--color-primary); display: flex; align-items: center; gap: 0.4rem;">
                        <ion-icon name="calendar-outline"></ion-icon> Periksa #${patientRecords.length - idx} &bull; ${r.exam_date}
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
                                <td style="padding: 0.4rem; border: 1px solid var(--color-border);">${r.od_va}</td>
                                <td rowspan="2" style="padding: 0.4rem; border: 1px solid var(--color-border); vertical-align: middle; font-weight: 700; background: rgba(99, 102, 241, 0.05);">${r.pd} mm</td>
                            </tr>
                            <tr>
                                <td style="padding: 0.4rem; border: 1px solid var(--color-border); font-weight: 700; color: #ec4899; text-align: left;">OS (Kiri)</td>
                                <td style="padding: 0.4rem; border: 1px solid var(--color-border);">${(r.os_sph >= 0 ? '+' : '') + parseFloat(r.os_sph).toFixed(2)}</td>
                                <td style="padding: 0.4rem; border: 1px solid var(--color-border);">${(r.os_cyl >= 0 ? '+' : '') + parseFloat(r.os_cyl).toFixed(2)}</td>
                                <td style="padding: 0.4rem; border: 1px solid var(--color-border);">${r.os_axis}°</td>
                                <td style="padding: 0.4rem; border: 1px solid var(--color-border);">${(r.os_add >= 0 ? '+' : '') + parseFloat(r.os_add).toFixed(2)}</td>
                                <td style="padding: 0.4rem; border: 1px solid var(--color-border);">${r.os_va}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div style="font-size: 0.82rem; color: var(--color-dark); display: flex; flex-wrap: wrap; gap: 1rem;">
                    <div><strong>Lensa:</strong> ${r.lens_type}</div>
                    <div><strong>Frame:</strong> ${r.frame_code || '-'}</div>
                    <div><strong>Pemeriksa:</strong> ${r.examiner_name}</div>
                    ${r.diagnosis ? `<div><strong>Diagnosa:</strong> ${r.diagnosis}</div>` : ''}
                    ${r.notes ? `<div><strong>Catatan:</strong> ${r.notes}</div>` : ''}
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
