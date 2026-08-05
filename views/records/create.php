<?php
/**
 * Standalone Input Rekam Medis Optik View - OPTIK FOCUS
 */
?>

<div class="animate-fade-in" style="max-width: 1000px; margin: 0 auto; padding-bottom: 3rem;">
    
    <!-- Top Action Bar -->
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: 1rem;">
        <div>
            <a href="<?= baseUrl('transactions') ?>" class="btn-link" style="display: inline-flex; align-items: center; gap: 0.4rem; color: var(--text-muted); font-size: 0.9rem; text-decoration: none; margin-bottom: 0.5rem;">
                <ion-icon name="arrow-back-outline"></ion-icon>
                <span>Kembali ke Daftar Rekam Medis</span>
            </a>
            <h2 style="font-size: 1.6rem; font-weight: 800; color: var(--color-dark); margin: 0; display: flex; align-items: center; gap: 0.6rem;">
                <ion-icon name="add-circle-outline" style="color: var(--color-primary);"></ion-icon>
                Form Input Rekam Medis Optik Baru
            </h2>
            <p class="text-muted text-sm" style="margin-top: 0.25rem;">
                Isi data pemeriksaan refraksi mata pasien, resep kacamata, dan fitting lensa Klinik OPTIK FOCUS.
            </p>
        </div>

        <div>
            <a href="<?= baseUrl('transactions') ?>" class="btn btn-secondary px-3 py-2">
                <ion-icon name="close-outline" class="mr-1"></ion-icon>
                Batal
            </a>
        </div>
    </div>

    <form method="POST" action="<?= baseUrl('records/create') ?>" id="formCreateRecord">
        
        <!-- SECTION 1: DATA PASIEN -->
        <div class="card-widget mb-4" style="border-top: 4px solid var(--color-primary);">
            <div class="card-widget-header">
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <div style="width: 36px; height: 36px; border-radius: 8px; background: rgba(99, 102, 241, 0.1); color: var(--color-primary); display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                        <ion-icon name="person-outline"></ion-icon>
                    </div>
                    <div>
                        <h3 style="margin: 0; font-size: 1.1rem;">1. Data Identitas Pasien</h3>
                        <p class="text-muted text-xs" style="margin: 0;">Isi data identitas dan jaminan BPJS pasien</p>
                    </div>
                </div>
            </div>

            <!-- Patient Form Inputs -->
            <div id="newPatientSection">
                <div class="row-layout">
                    <div class="col-6 mb-3">
                        <div class="form-group">
                            <label for="patient_name" style="font-weight: 600;">Nama Lengkap Pasien <span class="text-danger">*</span></label>
                            <input type="text" name="patient_name" id="patient_name" class="form-control" placeholder="Contoh: Budi Santoso" required>
                        </div>
                    </div>
                    <div class="col-6 mb-3">
                        <div class="form-group">
                            <label for="patient_phone" style="font-weight: 600;">No. Telepon / WhatsApp</label>
                            <input type="text" name="patient_phone" id="patient_phone" class="form-control" placeholder="Contoh: 081234567890">
                        </div>
                    </div>
                </div>

                <div class="row-layout">
                    <div class="col-4 mb-3">
                        <div class="form-group">
                            <label for="patient_gender" style="font-weight: 600;">Jenis Kelamin</label>
                            <select name="patient_gender" id="patient_gender" class="form-control">
                                <option value="L">Laki-laki (L)</option>
                                <option value="P">Perempuan (P)</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-4 mb-3">
                        <div class="form-group">
                            <label for="patient_dob" style="font-weight: 600;">Tanggal Lahir</label>
                            <input type="date" name="patient_dob" id="patient_dob" class="form-control">
                        </div>
                    </div>
                    <div class="col-4 mb-3">
                        <div class="form-group">
                            <label for="patient_address" style="font-weight: 600;">Alamat Pasien</label>
                            <input type="text" name="patient_address" id="patient_address" class="form-control" placeholder="Alamat domisili...">
                        </div>
                    </div>
                </div>

                <!-- BPJS Coverage Row -->
                <div class="row-layout" style="background: rgba(16, 185, 129, 0.05); border: 1px dashed rgba(16, 185, 129, 0.3); border-radius: 10px; padding: 0.85rem; margin-top: 0.5rem;">
                    <div class="col-6 mb-2">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="bpjs_class" style="font-weight: 700; color: #047857; display: flex; align-items: center; gap: 0.4rem;">
                                <ion-icon name="card-outline" style="font-size: 1.1rem;"></ion-icon>
                                <span>Pilihan Kelas BPJS Kesehatan</span>
                            </label>
                            <select name="bpjs_class" id="bpjs_class" class="form-control" style="font-weight: 600; border-color: #a7f3d0;">
                                <option value="Non-BPJS">Non-BPJS / Pasien Umum</option>
                                <option value="Kelas 1">BPJS Kesehatan Kelas 1 (Subsidi Rp 330.000)</option>
                                <option value="Kelas 2">BPJS Kesehatan Kelas 2 (Subsidi Rp 220.000)</option>
                                <option value="Kelas 3">BPJS Kesehatan Kelas 3 (Subsidi Rp 165.000)</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-6 mb-2">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="bpjs_number" style="font-weight: 700; color: #047857;">Nomor Kartu BPJS Kesehatan</label>
                            <input type="text" name="bpjs_number" id="bpjs_number" class="form-control" placeholder="Contoh: 0001827491023" style="border-color: #a7f3d0;">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 2: DETAIL PEMERIKSAAN -->
        <div class="card-widget mb-4" style="border-top: 4px solid #10b981;">
            <div class="card-widget-header">
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <div style="width: 36px; height: 36px; border-radius: 8px; background: rgba(16, 185, 129, 0.1); color: #10b981; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                        <ion-icon name="calendar-outline"></ion-icon>
                    </div>
                    <div>
                        <h3 style="margin: 0; font-size: 1.1rem;">2. Jadwal & Optometris Pemeriksa</h3>
                        <p class="text-muted text-xs" style="margin: 0;">Tanggal dan petugas pemeriksa refraksi</p>
                    </div>
                </div>
            </div>

            <div class="row-layout">
                <div class="col-6">
                    <div class="form-group">
                        <label for="exam_date" style="font-weight: 600;">Tanggal Pemeriksaan <span class="text-danger">*</span></label>
                        <input type="date" name="exam_date" id="exam_date" class="form-control" value="<?= date('Y-m-d') ?>" required>
                    </div>
                </div>
                <div class="col-6">
                    <div class="form-group">
                        <label for="examiner_name" style="font-weight: 600;">Nama Optometris / Pemeriksa <span class="text-danger">*</span></label>
                        <input type="text" name="examiner_name" id="examiner_name" class="form-control" value="<?= htmlspecialchars($_SESSION['user']['name'] ?? 'dr. Optometris') ?>" required>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 3: RESEP REFRAKSI MATA (OD & OS) -->
        <div class="card-widget mb-4" style="border-top: 4px solid #8b5cf6;">
            <div class="card-widget-header">
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <div style="width: 36px; height: 36px; border-radius: 8px; background: rgba(139, 92, 246, 0.1); color: #8b5cf6; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                        <ion-icon name="glasses-outline"></ion-icon>
                    </div>
                    <div>
                        <h3 style="margin: 0; font-size: 1.1rem;">3. Hasil Pemeriksaan Refraksi Mata</h3>
                        <p class="text-muted text-xs" style="margin: 0;">Ukuran Spheris (SPH), Cylinder (CYL), Axis, Addition (ADD), dan Visus (VA)</p>
                    </div>
                </div>
            </div>

            <div class="mb-2" style="display: flex; align-items: center; gap: 0.35rem; color: var(--color-primary); font-size: 0.78rem; font-weight: 600;">
                <ion-icon name="swap-horizontal-outline" style="font-size: 1.1rem;"></ion-icon>
                <span>Geser tabel ke samping pada layar HP untuk mengisi kolom lengkap</span>
            </div>

            <div class="refraction-table-container mb-3">
                <table class="table" style="text-align: center; margin: 0; font-size: 0.95rem;">
                    <thead style="background: rgba(15, 23, 42, 0.05); font-weight: 700;">
                        <tr>
                            <th style="text-align: left; padding: 0.75rem 1rem; width: 160px;">POSISI MATA</th>
                            <th>SPHERIS (SPH)</th>
                            <th>CYLINDER (CYL)</th>
                            <th>AXIS (DERAJAT)</th>
                            <th>ADDITION (ADD)</th>
                            <th>VISUS (VA)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- OD Row (Mata Kanan) -->
                        <tr style="border-bottom: 1px solid var(--color-border); background: rgba(99, 102, 241, 0.02);">
                            <td style="text-align: left; font-weight: 800; color: var(--color-primary); padding: 0.75rem 1rem;">
                                <div style="display: flex; align-items: center; gap: 0.4rem;">
                                    <span style="display: inline-block; width: 10px; height: 10px; border-radius: 50%; background: var(--color-primary);"></span>
                                    <span>OD (Mata Kanan)</span>
                                </div>
                            </td>
                            <td style="padding: 0.5rem;">
                                <input type="number" step="0.25" name="od_sph" placeholder="0.00" class="form-control text-center" style="font-weight: 700; color: var(--color-primary);" value="0.00">
                            </td>
                            <td style="padding: 0.5rem;">
                                <input type="number" step="0.25" name="od_cyl" placeholder="0.00" class="form-control text-center" value="0.00">
                            </td>
                            <td style="padding: 0.5rem;">
                                <input type="number" step="1" min="0" max="180" name="od_axis" placeholder="0" class="form-control text-center" value="0">
                            </td>
                            <td style="padding: 0.5rem;">
                                <input type="number" step="0.25" name="od_add" placeholder="0.00" class="form-control text-center" value="0.00">
                            </td>
                            <td style="padding: 0.5rem;">
                                <input type="text" name="od_va" placeholder="6/6" class="form-control text-center" value="6/6">
                            </td>
                        </tr>

                        <!-- OS Row (Mata Kiri) -->
                        <tr style="background: rgba(236, 72, 153, 0.02);">
                            <td style="text-align: left; font-weight: 800; color: #ec4899; padding: 0.75rem 1rem;">
                                <div style="display: flex; align-items: center; gap: 0.4rem;">
                                    <span style="display: inline-block; width: 10px; height: 10px; border-radius: 50%; background: #ec4899;"></span>
                                    <span>OS (Mata Kiri)</span>
                                </div>
                            </td>
                            <td style="padding: 0.5rem;">
                                <input type="number" step="0.25" name="os_sph" placeholder="0.00" class="form-control text-center" style="font-weight: 700; color: #ec4899;" value="0.00">
                            </td>
                            <td style="padding: 0.5rem;">
                                <input type="number" step="0.25" name="os_cyl" placeholder="0.00" class="form-control text-center" value="0.00">
                            </td>
                            <td style="padding: 0.5rem;">
                                <input type="number" step="1" min="0" max="180" name="os_axis" placeholder="0" class="form-control text-center" value="0">
                            </td>
                            <td style="padding: 0.5rem;">
                                <input type="number" step="0.25" name="os_add" placeholder="0.00" class="form-control text-center" value="0.00">
                            </td>
                            <td style="padding: 0.5rem;">
                                <input type="text" name="os_va" placeholder="6/6" class="form-control text-center" value="6/6">
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pupillary Distance (PD) -->
            <div style="background: rgba(99, 102, 241, 0.04); border: 1px solid rgba(99, 102, 241, 0.15); border-radius: 10px; padding: 0.85rem 1rem; display: flex; align-items: center; justify-content: space-between; margin-top: 1rem; margin-bottom: 1.25rem;">
                <div style="display: flex; align-items: center; gap: 0.6rem;">
                    <ion-icon name="resize-outline" style="font-size: 1.4rem; color: var(--color-primary);"></ion-icon>
                    <div>
                        <strong style="color: var(--color-dark); font-size: 0.95rem;">Pupillary Distance (PD)</strong>
                        <p class="text-muted text-xs" style="margin: 0;">Jarak antar titik pusat pupil mata (dalam milimeter)</p>
                    </div>
                </div>

                <div style="display: flex; align-items: center; gap: 0.4rem;">
                    <input type="number" step="0.5" min="40" max="80" name="pd" id="pd" class="form-control text-center" style="width: 100px; font-size: 1.05rem; font-weight: 700; color: var(--color-primary);" value="62">
                    <span style="font-weight: 700; color: var(--text-muted); font-size: 0.85rem;">mm</span>
                </div>
            </div>

            <!-- Diagnosa & Catatan Medis -->
            <div class="row-layout">
                <!-- Pilihan Diagnosa Multi-Choice -->
                <div class="col-6 mb-3">
                    <div class="form-group">
                        <label style="font-weight: 700; color: var(--color-dark); display: flex; align-items: center; gap: 0.4rem;">
                            <ion-icon name="medical-outline" style="color: var(--color-primary); font-size: 1.1rem;"></ion-icon>
                            <span>Diagnosa Refraksi (Dapat Pilih Banyak)</span>
                        </label>
                        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.6rem; background: rgba(15, 23, 42, 0.02); border: 1px solid var(--color-border); border-radius: 12px; padding: 0.85rem;">
                            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; padding: 0.5rem 0.75rem; background: #ffffff; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.88rem; font-weight: 600; transition: all 0.2s ease;">
                                <input type="checkbox" name="diagnosis[]" value="Miopia (-)" style="accent-color: var(--color-primary); width: 16px; height: 16px;">
                                <span>Miopia (-)</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; padding: 0.5rem 0.75rem; background: #ffffff; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.88rem; font-weight: 600; transition: all 0.2s ease;">
                                <input type="checkbox" name="diagnosis[]" value="Hipermetropia (+)" style="accent-color: var(--color-primary); width: 16px; height: 16px;">
                                <span>Hipermetropia (+)</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; padding: 0.5rem 0.75rem; background: #ffffff; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.88rem; font-weight: 600; transition: all 0.2s ease;">
                                <input type="checkbox" name="diagnosis[]" value="Astigmatisme (cyl)" style="accent-color: var(--color-primary); width: 16px; height: 16px;">
                                <span>Astigmatisme (cyl)</span>
                            </label>
                            <label style="display: flex; align-items: center; gap: 0.5rem; cursor: pointer; padding: 0.5rem 0.75rem; background: #ffffff; border: 1px solid var(--color-border); border-radius: 8px; font-size: 0.88rem; font-weight: 600; transition: all 0.2s ease;">
                                <input type="checkbox" name="diagnosis[]" value="Presbiopi (add)" style="accent-color: var(--color-primary); width: 16px; height: 16px;">
                                <span>Presbiopi (add)</span>
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Input Catatan Medis Manual -->
                <div class="col-6 mb-3">
                    <div class="form-group">
                        <label for="notes" style="font-weight: 700; color: var(--color-dark); display: flex; align-items: center; gap: 0.4rem;">
                            <ion-icon name="document-text-outline" style="color: var(--color-primary); font-size: 1.1rem;"></ion-icon>
                            <span>Catatan Medis Manual</span>
                        </label>
                        <textarea name="notes" id="notes" class="form-control" rows="3" placeholder="Masukkan keluhan fisik pasien (misal: mata sering pegal, asthenopia) & saran pemakaian..." style="border-radius: 12px; height: calc(100% - 28px); min-height: 95px;"></textarea>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECTION 4: LENSA, FRAME & TRANSAKSI -->
        <div class="card-widget mb-4" style="border-top: 4px solid #f59e0b;">
            <div class="card-widget-header">
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <div style="width: 36px; height: 36px; border-radius: 8px; background: rgba(245, 158, 11, 0.1); color: #f59e0b; display: flex; align-items: center; justify-content: center; font-size: 1.2rem;">
                        <ion-icon name="pricetag-outline"></ion-icon>
                    </div>
                    <div>
                        <h3 style="margin: 0; font-size: 1.1rem;">4. Spesifikasi Lensa, Bingkai & Biaya Transaksi</h3>
                        <p class="text-muted text-xs" style="margin: 0;">Pilihan jenis lensa, bingkai kacamata, dan total nominal biaya</p>
                    </div>
                </div>
            </div>

            <div class="row-layout">
                <div class="col-4 mb-3">
                    <div class="form-group">
                        <label for="lens_type" style="font-weight: 600;">Jenis Lensa Kacamata <span class="text-danger">*</span></label>
                        <select name="lens_type" id="lens_type" class="form-control" style="font-size: 0.95rem;" required>
                            <option value="Single Vision Antiradiasi">Single Vision Antiradiasi</option>
                            <option value="Photocromic Bluecut">Photocromic Bluecut</option>
                            <option value="Progressive Office">Progressive Office</option>
                            <option value="Bifokal Kryptok">Bifokal Kryptok</option>
                            <option value="Bluecut Hi-Index 1.67">Bluecut Hi-Index 1.67</option>
                            <option value="Drivewear Polarized">Drivewear Polarized</option>
                        </select>
                    </div>
                </div>

                <div class="col-4 mb-3">
                    <div class="form-group">
                        <label for="frame_code" style="font-weight: 600;">Kode / Merk Frame</label>
                        <input type="text" name="frame_code" id="frame_code" class="form-control" placeholder="Contoh: Ray-Ban RB5228 Matte Black">
                    </div>
                </div>

                <div class="col-4 mb-3">
                    <div class="form-group">
                        <label for="total_price" style="font-weight: 600;">Total Biaya (Rupiah)</label>
                        <div class="input-currency-wrapper">
                            <span class="currency-prefix">Rp</span>
                            <input type="number" name="total_price" id="total_price" class="form-control" placeholder="0" min="0">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- FORM SUBMIT BUTTONS -->
        <div style="display: flex; gap: 1rem; justify-content: flex-end; align-items: center; margin-top: 2rem;">
            <a href="<?= baseUrl('transactions') ?>" class="btn btn-secondary px-4 py-3" style="border-radius: 50px;">
                Batal
            </a>
            <button type="submit" class="btn btn-primary px-5 py-3 rounded-pill shadow-lg" style="font-size: 1.05rem; font-weight: 700;">
                <ion-icon name="save-outline" class="mr-2" style="font-size: 1.3rem;"></ion-icon>
                Simpan Rekam Medis
            </button>
        </div>

    </form>
</div>

<script>
function togglePatientForm(val) {
    const sec = document.getElementById('newPatientSection');
    const nameInput = document.getElementById('patient_name');
    if (val) {
        sec.style.display = 'none';
        nameInput.required = false;
    } else {
        sec.style.display = 'block';
        nameInput.required = true;
    }
}
</script>
