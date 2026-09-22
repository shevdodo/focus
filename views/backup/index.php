<?php
/**
 * Backup & Restore Database Management View - OPTIK FOCUS
 */
?>

<div class="animate-fade-in" style="max-width: 1240px; margin: 0 auto; padding-bottom: 3rem;">

    <!-- TOP KPI STATS SUMMARY -->
    <div class="dashboard-grid mb-4">
        <!-- Database Size & File -->
        <div class="stat-card balance-card">
            <div class="stat-icon-wrapper" style="background: rgba(99, 102, 241, 0.12); color: var(--color-primary);">
                <ion-icon name="server-outline"></ion-icon>
            </div>
            <div class="stat-details">
                <span class="stat-label">UKURAN BASIS DATA</span>
                <h3 class="stat-value"><?= $dbFormattedSize ?></h3>
                <div class="stat-subtext">
                    <span>File: <code>optik_focus.db</code> &bull; SQLite v3</span>
                </div>
            </div>
        </div>

        <!-- Patients & Medical Records -->
        <div class="stat-card income-card">
            <div class="stat-icon-wrapper" style="background: rgba(16, 185, 129, 0.12); color: #10b981;">
                <ion-icon name="documents-outline"></ion-icon>
            </div>
            <div class="stat-details">
                <span class="stat-label">DATA PASIEN & RM</span>
                <h3 class="stat-value"><?= number_format($totalPatients) ?> <span style="font-size: 1rem; font-weight: 500; color: var(--text-muted);">Pasien</span></h3>
                <div class="stat-subtext">
                    <span><strong><?= number_format($totalRecords) ?></strong> Rekam Medis Tercatat</span>
                </div>
            </div>
        </div>

        <!-- Administrator Accounts -->
        <div class="stat-card expense-card" style="border-color: rgba(99, 102, 241, 0.2);">
            <div class="stat-icon-wrapper" style="background: rgba(99, 102, 241, 0.1); color: var(--color-primary);">
                <ion-icon name="shield-checkmark-outline"></ion-icon>
            </div>
            <div class="stat-details">
                <span class="stat-label">PENGGUNA ADMINISTRATOR</span>
                <h3 class="stat-value"><?= number_format($totalAdmins) ?> <span style="font-size: 1rem; font-weight: 500; color: var(--text-muted);">Akun</span></h3>
                <div class="stat-subtext">
                    <span><?= number_format($totalStaff) ?> Staf & Optometris terdaftar</span>
                </div>
            </div>
        </div>

        <!-- Server Snapshots Available -->
        <div class="stat-card" style="background-color: var(--color-white); border-color: var(--color-border);">
            <div class="stat-icon-wrapper" style="background: rgba(245, 158, 11, 0.12); color: #f59e0b;">
                <ion-icon name="archive-outline"></ion-icon>
            </div>
            <div class="stat-details">
                <span class="stat-label">SNAPSHOT CADANGAN</span>
                <h3 class="stat-value"><?= count($backupFiles) ?> <span style="font-size: 1rem; font-weight: 500; color: var(--text-muted);">File</span></h3>
                <div class="stat-subtext">
                    <span>Tersimpan di server internal</span>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN TWO-COLUMN SECTION: BACKUP & RESTORE -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(420px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
        
        <!-- CARD 1: BACKUP & DOWNLOAD DATABASE -->
        <div class="card-widget" style="display: flex; flex-direction: column; justify-content: space-between; border-top: 4px solid var(--color-primary); box-shadow: var(--shadow-md);">
            <div>
                <div style="display: flex; align-items: center; gap: 0.85rem; margin-bottom: 1.25rem;">
                    <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(99, 102, 241, 0.12); color: var(--color-primary); display: flex; align-items: center; justify-content: center; font-size: 1.6rem; flex-shrink: 0;">
                        <ion-icon name="cloud-download-outline"></ion-icon>
                    </div>
                    <div>
                        <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--color-dark); margin: 0;">Cadangkan & Unduh Database</h2>
                        <p class="text-muted text-sm" style="margin: 0.2rem 0 0 0;">Download file SQLite aktif untuk arsip mandiri atau migrasi server.</p>
                    </div>
                </div>

                <div style="background: var(--color-light); border: 1px solid var(--color-border); border-radius: 12px; padding: 1.2rem; margin-bottom: 1.5rem;">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                        <span class="text-sm font-semibold" style="color: var(--text-muted);">Database Aktif:</span>
                        <code style="background: #ffffff; padding: 0.2rem 0.5rem; border-radius: 6px; border: 1px solid var(--color-border); font-weight: 700; color: var(--color-primary);">database/optik_focus.db</code>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                        <span class="text-sm font-semibold" style="color: var(--text-muted);">Ukuran File:</span>
                        <strong style="color: var(--color-dark);"><?= $dbFormattedSize ?></strong>
                    </div>
                    <div style="display: flex; justify-content: space-between; align-items: center;">
                        <span class="text-sm font-semibold" style="color: var(--text-muted);">Terakhir Diperbarui:</span>
                        <span class="text-sm" style="color: var(--color-dark);"><?= $dbLastModified ?></span>
                    </div>
                </div>

                <p class="text-sm text-muted mb-4">
                    Menekan tombol unduh akan memicu checkpoint SQLite secara aman dan mengunduh berkas biner basis data lengkap (seluruh pasien, rekam medis, master lensa/frame, dan staf).
                </p>
            </div>

            <div>
                <!-- Download Live DB Button -->
                <a href="<?= baseUrl('backup/download') ?>" class="btn btn-primary btn-block" style="display: flex; align-items: center; justify-content: center; gap: 0.6rem; padding: 0.85rem 1.5rem; font-size: 1rem; font-weight: 700; border-radius: 10px; text-decoration: none; margin-bottom: 1rem; box-shadow: 0 4px 12px rgba(99, 102, 241, 0.25);">
                    <ion-icon name="download-outline" style="font-size: 1.3rem;"></ion-icon>
                    <span>Download File Database (.db)</span>
                </a>

                <!-- Create Server Snapshot Form -->
                <form action="<?= baseUrl('backup/create') ?>" method="POST" style="background: rgba(15, 23, 42, 0.02); border: 1px dashed var(--color-border); border-radius: 10px; padding: 0.9rem;">
                    <label class="text-xs font-semibold text-muted" style="display: block; margin-bottom: 0.35rem;">ATAU SIMPAN SNAPSHOT CADANGAN DI SERVER:</label>
                    <div style="display: flex; gap: 0.5rem;">
                        <input type="text" name="snapshot_note" class="form-control" placeholder="Catatan singkat (opsional)..." style="font-size: 0.85rem; padding: 0.45rem 0.75rem;">
                        <button type="submit" class="btn btn-secondary" style="white-space: nowrap; font-size: 0.85rem; padding: 0.45rem 1rem; display: flex; align-items: center; gap: 0.4rem;">
                            <ion-icon name="save-outline"></ion-icon>
                            <span>Simpan Snapshot</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- CARD 2: RESTORE DATABASE -->
        <div class="card-widget" style="display: flex; flex-direction: column; justify-content: space-between; border-top: 4px solid #10b981; box-shadow: var(--shadow-md);">
            <div>
                <div style="display: flex; align-items: center; gap: 0.85rem; margin-bottom: 1.25rem;">
                    <div style="width: 48px; height: 48px; border-radius: 12px; background: rgba(16, 185, 129, 0.12); color: #10b981; display: flex; align-items: center; justify-content: center; font-size: 1.6rem; flex-shrink: 0;">
                        <ion-icon name="cloud-upload-outline"></ion-icon>
                    </div>
                    <div>
                        <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--color-dark); margin: 0;">Pulihkan Database (Restore)</h2>
                        <p class="text-muted text-sm" style="margin: 0.2rem 0 0 0;">Unggah file cadangan (.db / .sqlite) untuk menggantikan data saat ini.</p>
                    </div>
                </div>

                <div class="alert alert-info" style="margin-bottom: 1.25rem; background: rgba(99, 102, 241, 0.08); border-left: 4px solid var(--color-primary); padding: 0.85rem 1rem; border-radius: 8px;">
                    <div style="display: flex; gap: 0.6rem; align-items: flex-start;">
                        <ion-icon name="shield-checkmark-outline" style="font-size: 1.3rem; color: var(--color-primary); flex-shrink: 0; margin-top: 0.1rem;"></ion-icon>
                        <p style="margin: 0; font-size: 0.85rem; line-height: 1.4; color: var(--text-main);">
                            <strong>Perlindungan Otomatis Aktif:</strong> Sebelum proses penimpaan file dilakukan, sistem akan secara otomatis membuat berkas backup pengaman darurat (<code>pre_restore_auto_backup_...db</code>).
                        </p>
                    </div>
                </div>

                <form id="restoreUploadForm" action="<?= baseUrl('backup/restore') ?>" method="POST" enctype="multipart/form-data">
                    <div class="form-group mb-3">
                        <label style="font-weight: 600; font-size: 0.9rem; color: var(--text-main); margin-bottom: 0.5rem; display: block;">
                            Pilih File Cadangan Database
                        </label>
                        
                        <!-- Drag & Drop Zone -->
                        <div id="dropZone" style="border: 2px dashed #cbd5e1; border-radius: 12px; padding: 1.5rem; text-align: center; background: #fafafa; cursor: pointer; transition: all 0.2s ease;">
                            <ion-icon name="document-attach-outline" style="font-size: 2.2rem; color: var(--text-muted); margin-bottom: 0.5rem;"></ion-icon>
                            <p id="fileUploadLabel" style="font-weight: 600; color: var(--color-dark); margin: 0 0 0.25rem 0; font-size: 0.95rem;">Klik untuk memilih file cadangan (.db / .sqlite)</p>
                            <span style="font-size: 0.75rem; color: var(--text-muted);">Mendukung SQLite 3 biner database file</span>
                            <input type="file" name="backup_file" id="backupFileInput" accept=".db,.sqlite,.sqlite3" style="display: none;" required>
                        </div>
                    </div>

                    <button type="button" id="btnTriggerRestore" class="btn btn-success btn-block" onclick="confirmRestoreUpload()" style="display: flex; align-items: center; justify-content: center; gap: 0.6rem; padding: 0.85rem 1.5rem; font-size: 1rem; font-weight: 700; border-radius: 10px; width: 100%; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.25);">
                        <ion-icon name="sync-outline" style="font-size: 1.3rem;"></ion-icon>
                        <span>Pulihkan Database Sekarang</span>
                    </button>
                </form>
            </div>
            
            <div style="margin-top: 1rem; text-align: center;">
                <span class="text-xs text-muted">Pastikan koneksi internet stabil dan file tidak rusak saat diunggah.</span>
            </div>
        </div>
    </div>

    <!-- DANGER ZONE: KOSONGKAN DATABASE -->
    <div class="card-widget mb-4" style="border: 1px solid rgba(239, 68, 68, 0.3); background: linear-gradient(180deg, #ffffff 0%, rgba(254, 242, 242, 0.4) 100%); border-top: 5px solid var(--color-danger); box-shadow: var(--shadow-sm); border-radius: 16px;">
        <div style="display: flex; justify-content: space-between; align-items: flex-start; flex-wrap: wrap; gap: 1.5rem;">
            
            <div style="flex: 1 1 500px;">
                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem;">
                    <span style="display: inline-flex; align-items: center; justify-content: center; width: 34px; height: 34px; border-radius: 8px; background: rgba(239, 68, 68, 0.12); color: var(--color-danger); font-size: 1.3rem;">
                        <ion-icon name="warning-outline"></ion-icon>
                    </span>
                    <h2 style="font-size: 1.3rem; font-weight: 800; color: var(--color-danger); margin: 0;">Zona Bahaya: Kosongkan Seluruh Basis Data</h2>
                </div>
                <p class="text-sm" style="color: #64748b; margin-bottom: 1rem; line-height: 1.6;">
                    Fitur ini digunakan untuk <strong>mereset total database</strong>, menghapus seluruh data pasien, riwayat rekam medis, dan pengguna non-admin, <strong>hingga hanya tersisa data akun pengguna Administrator</strong>.
                </p>

                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(240px, 1fr)); gap: 0.75rem; margin-bottom: 0.75rem;">
                    <div style="background: rgba(254, 226, 226, 0.5); border: 1px solid rgba(239, 68, 68, 0.2); border-radius: 8px; padding: 0.65rem 0.85rem; font-size: 0.82rem; color: #991b1b; display: flex; align-items: center; gap: 0.5rem;">
                        <ion-icon name="close-circle" style="color: var(--color-danger); font-size: 1.1rem; flex-shrink: 0;"></ion-icon>
                        <span><strong><?= number_format($totalRecords) ?> Rekam Medis</strong> dihapus permanen</span>
                    </div>
                    <div style="background: rgba(254, 226, 226, 0.5); border: 1px solid rgba(239, 68, 68, 0.2); border-radius: 8px; padding: 0.65rem 0.85rem; font-size: 0.82rem; color: #991b1b; display: flex; align-items: center; gap: 0.5rem;">
                        <ion-icon name="close-circle" style="color: var(--color-danger); font-size: 1.1rem; flex-shrink: 0;"></ion-icon>
                        <span><strong><?= number_format($totalPatients) ?> Data Pasien</strong> dihapus permanen</span>
                    </div>
                    <div style="background: rgba(254, 226, 226, 0.5); border: 1px solid rgba(239, 68, 68, 0.2); border-radius: 8px; padding: 0.65rem 0.85rem; font-size: 0.82rem; color: #991b1b; display: flex; align-items: center; gap: 0.5rem;">
                        <ion-icon name="close-circle" style="color: var(--color-danger); font-size: 1.1rem; flex-shrink: 0;"></ion-icon>
                        <span><strong><?= number_format($totalStaff) ?> Akun Staf / Optometris</strong> dihapus</span>
                    </div>
                    <div style="background: rgba(209, 250, 229, 0.6); border: 1px solid rgba(16, 185, 129, 0.3); border-radius: 8px; padding: 0.65rem 0.85rem; font-size: 0.82rem; color: #065f46; display: flex; align-items: center; gap: 0.5rem;">
                        <ion-icon name="checkmark-circle" style="color: #10b981; font-size: 1.1rem; flex-shrink: 0;"></ion-icon>
                        <span><strong><?= number_format($totalAdmins) ?> Akun Administrator</strong> tetap utuh</span>
                    </div>
                </div>

                <div style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.8rem; color: var(--text-muted);">
                    <ion-icon name="shield-checkmark" style="color: #10b981; font-size: 1rem;"></ion-icon>
                    <span>Sistem otomatis membuat backup darurat (<code>pre_reset_auto_backup_...db</code>) sebelum penghapusan data dimulai.</span>
                </div>
            </div>

            <div style="display: flex; align-items: center; justify-content: center; flex-shrink: 0; padding-top: 0.5rem;">
                <button type="button" class="btn btn-danger" onclick="openResetModal()" style="background: var(--color-danger); border-color: var(--color-danger); padding: 0.9rem 1.75rem; font-size: 1rem; font-weight: 700; border-radius: 12px; display: flex; align-items: center; gap: 0.6rem; box-shadow: 0 4px 14px rgba(239, 68, 68, 0.35);">
                    <ion-icon name="trash-outline" style="font-size: 1.3rem;"></ion-icon>
                    <span>Kosongkan Database...</span>
                </button>
            </div>
        </div>
    </div>

    <!-- SERVER SNAPSHOTS HISTORY TABLE -->
    <div class="card-widget animate-fade-in" style="margin-bottom: 2rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.25rem;">
            <div>
                <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--color-dark); margin: 0 0 0.25rem 0;">Daftar File Cadangan di Server</h2>
                <p class="text-muted text-sm" style="margin: 0;">Snapshot cadangan tersimpan di direktori privat <code>database/backups/</code>.</p>
            </div>
            <div>
                <form action="<?= baseUrl('backup/create') ?>" method="POST" style="display: inline-block;">
                    <button type="submit" class="btn btn-secondary" style="font-weight: 600; font-size: 0.9rem; display: flex; align-items: center; gap: 0.5rem;">
                        <ion-icon name="add-circle-outline" style="font-size: 1.1rem; color: var(--color-primary);"></ion-icon>
                        <span>Buat Cadangan Baru Sekarang</span>
                    </button>
                </form>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table" style="width: 100%;">
                <thead>
                    <tr>
                        <th>Nama Berkas Cadangan</th>
                        <th>Ukuran</th>
                        <th>Waktu Pembuatan</th>
                        <th>Tipe</th>
                        <th class="text-center" style="width: 220px;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($backupFiles)): ?>
                        <tr>
                            <td colspan="5" class="text-center text-muted py-5">
                                <div style="display: flex; flex-direction: column; align-items: center; gap: 0.5rem;">
                                    <ion-icon name="archive-outline" style="font-size: 2.5rem; color: #cbd5e1;"></ion-icon>
                                    <span style="font-weight: 600;">Belum ada berkas cadangan di server.</span>
                                    <span style="font-size: 0.85rem;">Klik tombol "Buat Cadangan Baru Sekarang" atau unduh cadangan ke komputer Anda.</span>
                                </div>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($backupFiles as $backup): ?>
                            <tr>
                                <td style="font-weight: 600; color: var(--color-dark);">
                                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                                        <ion-icon name="document-text-outline" style="font-size: 1.2rem; color: var(--color-primary);"></ion-icon>
                                        <code><?= htmlspecialchars($backup['name'], ENT_QUOTES, 'UTF-8') ?></code>
                                    </div>
                                </td>
                                <td>
                                    <span style="font-weight: 600; color: var(--text-main);"><?= $backup['size'] ?></span>
                                </td>
                                <td>
                                    <span class="text-muted text-sm"><?= $backup['date'] ?></span>
                                </td>
                                <td>
                                    <?php if ($backup['is_auto']): ?>
                                        <span class="stat-badge" style="background: rgba(245, 158, 11, 0.1); color: #b45309; border: 1px solid rgba(245, 158, 11, 0.25); font-size: 0.72rem; padding: 0.2rem 0.5rem;">
                                            <ion-icon name="shield-outline"></ion-icon>
                                            <span>Auto-Backup</span>
                                        </span>
                                    <?php else: ?>
                                        <span class="stat-badge badge-success" style="background: rgba(99, 102, 241, 0.08); color: var(--color-primary); border: 1px solid rgba(99, 102, 241, 0.2); font-size: 0.72rem; padding: 0.2rem 0.5rem;">
                                            <ion-icon name="bookmark-outline"></ion-icon>
                                            <span>Manual Snapshot</span>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td class="text-center">
                                    <div style="display: inline-flex; gap: 0.35rem; align-items: center;">
                                        <!-- Download Snapshot -->
                                        <a href="<?= baseUrl('backup/download-snapshot?file=' . urlencode($backup['name'])) ?>" class="btn-action-icon" title="Download berkas ini ke komputer" style="color: var(--color-primary); background: var(--color-primary-light);">
                                            <ion-icon name="download-outline"></ion-icon>
                                        </a>

                                        <!-- Restore from Snapshot Button -->
                                        <button type="button" class="btn-action-icon" title="Pulihkan database dari berkas ini" onclick="openRestoreSnapshotModal('<?= htmlspecialchars($backup['name'], ENT_QUOTES, 'UTF-8') ?>')" style="color: #10b981; background: var(--color-success-light);">
                                            <ion-icon name="refresh-outline"></ion-icon>
                                        </button>

                                        <!-- Delete Snapshot -->
                                        <form action="<?= baseUrl('backup/delete-snapshot') ?>" method="POST" style="display: inline;" onsubmit="return confirm('Apakah Anda yakin ingin menghapus berkas cadangan <?= htmlspecialchars($backup['name'], ENT_QUOTES, 'UTF-8') ?>?');">
                                            <input type="hidden" name="filename" value="<?= htmlspecialchars($backup['name'], ENT_QUOTES, 'UTF-8') ?>">
                                            <button type="submit" class="btn-action-icon text-danger" title="Hapus berkas cadangan ini" style="background: var(--color-danger-light);">
                                                <ion-icon name="trash-outline"></ion-icon>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL: KONFIRMASI KOSONGKAN DATABASE                                      -->
<!-- ========================================================================= -->
<div id="resetModal" class="modal-backdrop" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(4px); z-index: 9999; align-items: center; justify-content: center; padding: 1rem; overflow-y: auto;">
    <div class="modal-content animate-fade-in" style="background: #ffffff; width: 100%; max-width: 540px; max-height: 90vh; overflow-y: auto; border-radius: 18px; box-shadow: var(--shadow-lg); border: 1px solid rgba(239, 68, 68, 0.2); margin: auto;">
        
        <div style="background: #fee2e2; padding: 1.5rem; border-bottom: 1px solid #fca5a5; display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <div style="width: 40px; height: 40px; border-radius: 50%; background: #ef4444; color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                    <ion-icon name="warning-outline"></ion-icon>
                </div>
                <div>
                    <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800; color: #991b1b;">Konfirmasi Pengosongan Basis Data</h3>
                    <p style="margin: 0.15rem 0 0 0; font-size: 0.8rem; color: #b91c1c;">Tindakan pembersihan menyeluruh (Destructive Action)</p>
                </div>
            </div>
            <button type="button" onclick="closeResetModal()" style="background: transparent; border: none; font-size: 1.5rem; color: #991b1b; cursor: pointer; line-height: 1;">&times;</button>
        </div>

        <form action="<?= baseUrl('backup/reset') ?>" method="POST" style="padding: 1.5rem;" onsubmit="return validateResetForm()">
            
            <div class="alert alert-danger" style="margin-bottom: 1.25rem; font-size: 0.85rem; line-height: 1.45; background: #fff5f5; border: 1px solid #fed7d7; color: #9b2c2c; padding: 0.75rem 1rem; border-radius: 8px;">
                <strong>Perhatian:</strong> Seluruh riwayat rekam medis, pemeriksaan refraksi, dan data registrasi pasien akan <strong>dihapus total</strong>. Hanya data akun pengguna <strong>Administrator</strong> yang akan dipertahankan.
            </div>

            <!-- Optional wipe master data toggle -->
            <div class="form-group mb-3" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 8px; padding: 0.75rem 1rem;">
                <label style="display: flex; align-items: center; gap: 0.65rem; cursor: pointer; margin: 0; font-size: 0.9rem; font-weight: 600; color: var(--text-main);">
                    <input type="checkbox" name="wipe_master" value="1" style="width: 18px; height: 18px; cursor: pointer;">
                    <span>Kosongkan juga Master Data (Katalog Lensa & Frame Kacamata)</span>
                </label>
                <small class="text-muted" style="display: block; margin-top: 0.35rem; margin-left: 2rem; font-size: 0.78rem;">
                    Jika dicentang, seluruh data master lensa & frame juga akan dihapus. Jika tidak, data master tetap tersimpan.
                </small>
            </div>

            <!-- Confirmation Phrase Input -->
            <div class="form-group mb-3">
                <label for="confirmationTextInput" style="font-size: 0.9rem; font-weight: 700; color: var(--color-dark); margin-bottom: 0.4rem; display: block;">
                    Ketik kata <span style="color: var(--color-danger); letter-spacing: 1px;">KOSONGKAN</span> untuk mengonfirmasi:
                </label>
                <input type="text" id="confirmationTextInput" name="confirmation_text" class="form-control" placeholder="KOSONGKAN" autocomplete="off" required style="font-weight: 700; letter-spacing: 1px; text-transform: uppercase;">
                <small class="text-muted" style="font-size: 0.75rem; margin-top: 0.25rem; display: block;">Ketik tepat kata "KOSONGKAN" dengan huruf kapital.</small>
            </div>

            <!-- Admin Password Input -->
            <div class="form-group mb-4">
                <label for="adminPasswordInput" style="font-size: 0.9rem; font-weight: 700; color: var(--color-dark); margin-bottom: 0.4rem; display: block;">
                    Password Administrator Saat Ini:
                </label>
                <input type="password" id="adminPasswordInput" name="admin_password" class="form-control" placeholder="Masukkan kata sandi akun admin Anda..." autocomplete="current-password" required>
                <small class="text-muted" style="font-size: 0.75rem; margin-top: 0.25rem; display: block;">Verifikasi keamanan wajib untuk mencegah penghapusan tanpa izin.</small>
            </div>

            <div style="display: flex; gap: 0.75rem; justify-content: flex-end; border-top: 1px solid var(--color-border); padding-top: 1.25rem;">
                <button type="button" class="btn btn-secondary" onclick="closeResetModal()">Batal</button>
                <button type="submit" class="btn btn-danger" style="display: flex; align-items: center; gap: 0.5rem; font-weight: 700;">
                    <ion-icon name="trash-outline"></ion-icon>
                    <span>Eksekusi Kosongkan Database</span>
                </button>
            </div>
        </form>
    </div>
</div>

<!-- ========================================================================= -->
<!-- MODAL: KONFIRMASI RESTORE DARI SNAPSHOT SERVER                            -->
<!-- ========================================================================= -->
<div id="restoreSnapshotModal" class="modal-backdrop" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.7); backdrop-filter: blur(4px); z-index: 9999; align-items: center; justify-content: center; padding: 1rem; overflow-y: auto;">
    <div class="modal-content animate-fade-in" style="background: #ffffff; width: 100%; max-width: 500px; max-height: 90vh; overflow-y: auto; border-radius: 18px; box-shadow: var(--shadow-lg); margin: auto;">
        
        <div style="background: #dcfce7; padding: 1.5rem; border-bottom: 1px solid #86efac; display: flex; align-items: center; justify-content: space-between;">
            <div style="display: flex; align-items: center; gap: 0.75rem;">
                <div style="width: 40px; height: 40px; border-radius: 50%; background: #10b981; color: #ffffff; display: flex; align-items: center; justify-content: center; font-size: 1.3rem;">
                    <ion-icon name="refresh-outline"></ion-icon>
                </div>
                <div>
                    <h3 style="margin: 0; font-size: 1.15rem; font-weight: 800; color: #166534;">Pulihkan Basis Data</h3>
                    <p style="margin: 0.15rem 0 0 0; font-size: 0.8rem; color: #15803d;">Dari Snapshot Cadangan Server</p>
                </div>
            </div>
            <button type="button" onclick="closeRestoreSnapshotModal()" style="background: transparent; border: none; font-size: 1.5rem; color: #166534; cursor: pointer; line-height: 1;">&times;</button>
        </div>

        <form action="<?= baseUrl('backup/restore-snapshot') ?>" method="POST" style="padding: 1.5rem;">
            <input type="hidden" name="filename" id="snapshotFilenameInput">

            <p style="color: var(--text-main); font-size: 0.95rem; line-height: 1.5; margin-bottom: 1rem;">
                Apakah Anda yakin ingin memulihkan database dari snapshot:
                <br>
                <code id="snapshotFilenameDisplay" style="background: #f1f5f9; padding: 0.35rem 0.6rem; border-radius: 6px; font-weight: 700; color: var(--color-primary); display: inline-block; margin-top: 0.5rem;"></code>
            </p>

            <div class="alert alert-warning" style="margin-bottom: 1.5rem; font-size: 0.82rem; background: #fefce8; border: 1px solid #fef08a; color: #854d0e; padding: 0.75rem 1rem; border-radius: 8px;">
                <ion-icon name="information-circle-outline" style="font-size: 1.1rem; vertical-align: middle; margin-right: 0.3rem;"></ion-icon>
                Data saat ini akan digantikan dengan data pada snapshot tersebut. Cadangan otomatis tetap dibuat secara mandiri oleh sistem.
            </div>

            <div style="display: flex; gap: 0.75rem; justify-content: flex-end; border-top: 1px solid var(--color-border); padding-top: 1.25rem;">
                <button type="button" class="btn btn-secondary" onclick="closeRestoreSnapshotModal()">Batal</button>
                <button type="submit" class="btn btn-success" style="display: flex; align-items: center; gap: 0.5rem; font-weight: 700;">
                    <ion-icon name="checkmark-circle-outline"></ion-icon>
                    <span>Ya, Pulihkan Sekarang</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    // --- File Dropzone Logic ---
    const dropZone = document.getElementById('dropZone');
    const fileInput = document.getElementById('backupFileInput');
    const fileUploadLabel = document.getElementById('fileUploadLabel');
    const restoreForm = document.getElementById('restoreUploadForm');

    dropZone.addEventListener('click', () => fileInput.click());

    fileInput.addEventListener('change', function() {
        if (fileInput.files.length > 0) {
            const fileName = fileInput.files[0].name;
            const fileSize = (fileInput.files[0].size / 1024).toFixed(1) + ' KB';
            fileUploadLabel.innerHTML = `File terpilih: <strong>${fileName}</strong> (${fileSize})`;
            dropZone.style.borderColor = '#10b981';
            dropZone.style.background = '#f0fdf4';
        }
    });

    ['dragenter', 'dragover'].forEach(eventName => {
        dropZone.addEventListener(eventName, (e) => {
            e.preventDefault();
            dropZone.style.borderColor = 'var(--color-primary)';
            dropZone.style.background = '#eff6ff';
        }, false);
    });

    ['dragleave', 'drop'].forEach(eventName => {
        dropZone.addEventListener(eventName, (e) => {
            e.preventDefault();
            dropZone.style.borderColor = '#cbd5e1';
            dropZone.style.background = '#fafafa';
        }, false);
    });

    dropZone.addEventListener('drop', (e) => {
        const dt = e.dataTransfer;
        const files = dt.files;
        if (files.length > 0) {
            fileInput.files = files;
            const event = new Event('change');
            fileInput.dispatchEvent(event);
        }
    });

    function confirmRestoreUpload() {
        if (!fileInput.files || fileInput.files.length === 0) {
            alert('Silakan pilih file database cadangan (.db atau .sqlite) terlebih dahulu!');
            fileInput.click();
            return;
        }

        const fileName = fileInput.files[0].name;
        if (confirm(`Peringatan: Seluruh data saat ini akan digantikan oleh data dari file "${fileName}".\n\nSistem akan membuat cadangan otomatis sebelum penimpaan file.\n\nApakah Anda yakin ingin melanjutkan pemulihan?`)) {
            restoreForm.submit();
        }
    }

    // --- Reset Database Modal Logic ---
    const resetModal = document.getElementById('resetModal');
    const confirmationTextInput = document.getElementById('confirmationTextInput');
    const adminPasswordInput = document.getElementById('adminPasswordInput');

    function openResetModal() {
        confirmationTextInput.value = "";
        adminPasswordInput.value = "";
        resetModal.style.display = 'flex';
        setTimeout(() => confirmationTextInput.focus(), 150);
    }

    function closeResetModal() {
        resetModal.style.display = 'none';
    }

    function validateResetForm() {
        const text = confirmationTextInput.value.trim().toUpperCase();
        if (text !== 'KOSONGKAN') {
            alert('Harap ketik kata "KOSONGKAN" dengan tepat pada kotak konfirmasi.');
            confirmationTextInput.focus();
            return false;
        }

        if (!adminPasswordInput.value) {
            alert('Harap masukkan password Administrator Anda untuk memvalidasi otorisasi.');
            adminPasswordInput.focus();
            return false;
        }

        return confirm('PERINGATAN AKHIR: Apakah Anda BENAR-BENAR YAKIN ingin mengosongkan seluruh database? Tindakan ini tidak dapat dibatalkan!');
    }

    resetModal.addEventListener('click', function(e) {
        if (e.target === resetModal) {
            closeResetModal();
        }
    });

    // --- Restore from Snapshot Modal Logic ---
    const restoreSnapshotModal = document.getElementById('restoreSnapshotModal');
    const snapshotFilenameInput = document.getElementById('snapshotFilenameInput');
    const snapshotFilenameDisplay = document.getElementById('snapshotFilenameDisplay');

    function openRestoreSnapshotModal(filename) {
        snapshotFilenameInput.value = filename;
        snapshotFilenameDisplay.textContent = filename;
        restoreSnapshotModal.style.display = 'flex';
    }

    function closeRestoreSnapshotModal() {
        restoreSnapshotModal.style.display = 'none';
    }

    restoreSnapshotModal.addEventListener('click', function(e) {
        if (e.target === restoreSnapshotModal) {
            closeRestoreSnapshotModal();
        }
    });
</script>
