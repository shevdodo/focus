<?php
/**
 * Master Data Management View (Lensa & Frame) - OPTIK FOCUS
 */
?>

<div class="animate-fade-in" style="max-width: 1200px; margin: 0 auto; padding-bottom: 3rem;">

    <!-- TOP KPI STATS SUMMARY -->
    <div class="dashboard-grid mb-4">
        <!-- Lens Stat Card -->
        <div class="stat-card balance-card">
            <div class="stat-icon-wrapper" style="background: rgba(99, 102, 241, 0.12); color: var(--color-primary);">
                <ion-icon name="glasses-outline"></ion-icon>
            </div>
            <div class="stat-details">
                <span class="stat-label">MASTER LENSA</span>
                <h3 class="stat-value"><?= number_format($lensStats['total_items'] ?? 0) ?> <span style="font-size: 1rem; font-weight: 500; color: var(--text-muted);">Varian</span></h3>
                <div class="stat-subtext">
                    <span><?= $lensStats['total_brands'] ?? 0 ?> Brand &bull; Total Stok: <strong><?= number_format($lensStats['total_stock'] ?? 0) ?> pcs</strong></span>
                </div>
            </div>
        </div>

        <!-- Frame Stat Card -->
        <div class="stat-card income-card">
            <div class="stat-icon-wrapper" style="background: rgba(16, 185, 129, 0.12); color: #10b981;">
                <ion-icon name="color-filter-outline"></ion-icon>
            </div>
            <div class="stat-details">
                <span class="stat-label">MASTER FRAME</span>
                <h3 class="stat-value"><?= number_format($frameStats['total_items'] ?? 0) ?> <span style="font-size: 1rem; font-weight: 500; color: var(--text-muted);">Model</span></h3>
                <div class="stat-subtext">
                    <span><?= $frameStats['total_brands'] ?? 0 ?> Brand &bull; Total Stok: <strong><?= number_format($frameStats['total_stock'] ?? 0) ?> pcs</strong></span>
                </div>
            </div>
        </div>

        <!-- Low Stock Warning Card -->
        <div class="stat-card expense-card">
            <div class="stat-icon-wrapper" style="background: rgba(245, 158, 11, 0.12); color: #f59e0b;">
                <ion-icon name="alert-circle-outline"></ion-icon>
            </div>
            <div class="stat-details">
                <span class="stat-label">STATUS STOK MINIM</span>
                <?php $totalLow = ($lensStats['low_stock'] ?? 0) + ($frameStats['low_stock'] ?? 0); ?>
                <h3 class="stat-value" style="color: <?= $totalLow > 0 ? '#f59e0b' : 'var(--color-dark)' ?>;"><?= $totalLow ?> <span style="font-size: 1rem; font-weight: 500; color: var(--text-muted);">Item</span></h3>
                <div class="stat-subtext">
                    <span>Lensa: <?= $lensStats['low_stock'] ?? 0 ?> &bull; Frame: <?= $frameStats['low_stock'] ?? 0 ?> item perlu restok</span>
                </div>
            </div>
        </div>
    </div>

    <!-- MAIN TAB CONTAINER & ACTION HEADER -->
    <div class="card-widget mb-4" style="border-top: 4px solid var(--color-primary);">
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem;">
            
            <!-- Tab Switchers -->
            <div style="display: flex; background: rgba(15, 23, 42, 0.05); border-radius: 12px; padding: 4px; gap: 4px;">
                <button type="button" id="btnTabLensa" onclick="switchMasterTab('lensa')" 
                        style="padding: 0.6rem 1.4rem; border-radius: 8px; font-weight: 700; font-size: 0.95rem; border: none; cursor: pointer; transition: all 0.2s ease; display: flex; align-items: center; gap: 0.5rem; <?= $activeTab === 'lensa' ? 'background: #ffffff; color: var(--color-primary); box-shadow: var(--shadow-sm);' : 'background: transparent; color: var(--text-muted);' ?>">
                    <ion-icon name="glasses-outline" style="font-size: 1.2rem;"></ion-icon>
                    <span>Katalog Lensa</span>
                    <span style="background: rgba(99, 102, 241, 0.12); color: var(--color-primary); font-size: 0.75rem; padding: 0.15rem 0.55rem; border-radius: 50px; font-weight: 800;"><?= count($lenses) ?></span>
                </button>
                
                <button type="button" id="btnTabFrame" onclick="switchMasterTab('frame')" 
                        style="padding: 0.6rem 1.4rem; border-radius: 8px; font-weight: 700; font-size: 0.95rem; border: none; cursor: pointer; transition: all 0.2s ease; display: flex; align-items: center; gap: 0.5rem; <?= $activeTab === 'frame' ? 'background: #ffffff; color: var(--color-primary); box-shadow: var(--shadow-sm);' : 'background: transparent; color: var(--text-muted);' ?>">
                    <ion-icon name="color-filter-outline" style="font-size: 1.2rem;"></ion-icon>
                    <span>Katalog Frame / Bingkai</span>
                    <span style="background: rgba(16, 185, 129, 0.12); color: #10b981; font-size: 0.75rem; padding: 0.15rem 0.55rem; border-radius: 50px; font-weight: 800;"><?= count($frames) ?></span>
                </button>
            </div>

            <!-- Action Buttons -->
            <div>
                <button type="button" id="btnCreateLensa" onclick="openAddLensModal()" class="btn btn-primary px-4 py-2.5" style="display: <?= $activeTab === 'lensa' ? 'inline-flex' : 'none' ?>; align-items: center; gap: 0.5rem; border-radius: 10px; font-weight: 700;">
                    <ion-icon name="add-circle-outline" style="font-size: 1.25rem;"></ion-icon>
                    <span>Tambah Lensa Baru</span>
                </button>
                
                <button type="button" id="btnCreateFrame" onclick="openAddFrameModal()" class="btn btn-success px-4 py-2.5" style="display: <?= $activeTab === 'frame' ? 'inline-flex' : 'none' ?>; align-items: center; gap: 0.5rem; border-radius: 10px; font-weight: 700; background: #10b981; border-color: #10b981; color: #ffffff;">
                    <ion-icon name="add-circle-outline" style="font-size: 1.25rem;"></ion-icon>
                    <span>Tambah Frame Baru</span>
                </button>
            </div>
        </div>

        <!-- =================================================================== -->
        <!-- TAB CONTENT 1: KATALOG LENSA -->
        <!-- =================================================================== -->
        <div id="tabContentLensa" style="display: <?= $activeTab === 'lensa' ? 'block' : 'none' ?>;">
            
            <!-- Filter Bar Lensa -->
            <form method="GET" action="<?= baseUrl('master') ?>" style="margin-bottom: 1.25rem;">
                <input type="hidden" name="tab" value="lensa">
                <div class="row-layout" style="align-items: center; gap: 0.75rem;">
                    <div style="flex: 1; min-width: 220px;">
                        <input type="text" name="search_lens" class="form-control" 
                               placeholder="🔎 Cari nama lensa, kode, atau coating..." 
                               value="<?= htmlspecialchars($searchLens) ?>">
                    </div>
                    <div style="width: 180px;">
                        <select name="brand_lens" class="form-control" onchange="this.form.submit()">
                            <option value="">-- Semua Brand --</option>
                            <?php foreach ($lensBrands as $b): ?>
                                <option value="<?= htmlspecialchars($b) ?>" <?= $brandLens === $b ? 'selected' : '' ?>><?= htmlspecialchars($b) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div style="width: 180px;">
                        <select name="category_lens" class="form-control" onchange="this.form.submit()">
                            <option value="">-- Semua Kategori --</option>
                            <?php foreach ($lensCategories as $c): ?>
                                <option value="<?= htmlspecialchars($c) ?>" <?= $categoryLens === $c ? 'selected' : '' ?>><?= htmlspecialchars($c) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-primary py-2.5 px-3" style="border-radius: 8px;">Cari</button>
                        <?php if (!empty($searchLens) || !empty($brandLens) || !empty($categoryLens)): ?>
                            <a href="<?= baseUrl('master?tab=lensa') ?>" class="btn btn-secondary py-2.5 px-3" style="border-radius: 8px; font-weight: 600;">Reset</a>
                        <?php endif; ?>
                    </div>
                </div>
            </form>

            <!-- Table Master Lensa -->
            <div class="table-responsive" style="border-radius: 12px; border: 1px solid var(--color-border); overflow: hidden;">
                <table class="table" style="margin: 0;">
                    <thead style="background: rgba(15, 23, 42, 0.04);">
                        <tr>
                            <th style="width: 110px;">KODE</th>
                            <th>NAMA LENSA</th>
                            <th>BRAND</th>
                            <th>KATEGORI</th>
                            <th class="text-center">INDEX</th>
                            <th>COATING / FITUR</th>
                            <th class="text-right">HARGA ACUAN</th>
                            <th class="text-center">STOK</th>
                            <th class="text-center" style="width: 110px;">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($lenses)): ?>
                            <tr>
                                <td colspan="9" class="text-center" style="padding: 2.5rem; color: var(--text-muted);">
                                    <ion-icon name="search-outline" style="font-size: 2rem; color: #94a3b8; display: block; margin: 0 auto 0.5rem;"></ion-icon>
                                    Tidak ada data lensa yang ditemukan.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($lenses as $l): ?>
                                <tr class="transaction-row">
                                    <td>
                                        <span style="font-family: monospace; font-size: 0.85rem; font-weight: 700; background: rgba(99, 102, 241, 0.08); color: var(--color-primary); padding: 0.2rem 0.5rem; border-radius: 6px;">
                                            <?= htmlspecialchars($l['code']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <strong style="color: var(--color-dark); font-size: 0.95rem; display: block;">
                                            <?= htmlspecialchars($l['name']) ?>
                                        </strong>
                                    </td>
                                    <td>
                                        <span style="font-weight: 600; color: #475569;"><?= htmlspecialchars($l['brand']) ?></span>
                                    </td>
                                    <td>
                                        <span class="badge-category" style="background: rgba(99, 102, 241, 0.1); color: var(--color-primary);">
                                            <?= htmlspecialchars($l['category']) ?>
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <span style="font-weight: 700; font-size: 0.85rem; background: rgba(15, 23, 42, 0.06); padding: 0.15rem 0.45rem; border-radius: 4px;">
                                            <?= htmlspecialchars($l['index_refraction'] ?? '1.56') ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span style="font-size: 0.85rem; color: var(--text-muted);"><?= htmlspecialchars($l['coating'] ?? '-') ?></span>
                                    </td>
                                    <td class="text-right" style="font-weight: 700; color: var(--color-dark);">
                                        Rp <?= number_format($l['price'] ?? 0, 0, ',', '.') ?>
                                    </td>
                                    <td class="text-center">
                                        <?php 
                                        $stk = (int)($l['stock'] ?? 0);
                                        $bg = $stk > 10 ? 'rgba(16, 185, 129, 0.12)' : ($stk > 0 ? 'rgba(245, 158, 11, 0.12)' : 'rgba(239, 68, 68, 0.12)');
                                        $fg = $stk > 10 ? '#047857' : ($stk > 0 ? '#b45309' : '#dc2626');
                                        ?>
                                        <span style="display: inline-block; font-weight: 800; font-size: 0.82rem; background: <?= $bg ?>; color: <?= $fg ?>; padding: 0.2rem 0.65rem; border-radius: 50px;">
                                            <?= $stk ?> pcs
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div style="display: flex; justify-content: center; gap: 0.4rem;">
                                            <button type="button" class="btn btn-sm btn-secondary" onclick='openEditLensModal(<?= json_encode($l) ?>)' style="padding: 0.35rem 0.65rem; border-radius: 6px;" title="Edit Data Lensa">
                                                <ion-icon name="create-outline" style="font-size: 1.05rem;"></ion-icon>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger" onclick="confirmDeleteLens(<?= $l['id'] ?>, '<?= htmlspecialchars($l['name'], ENT_QUOTES) ?>')" style="padding: 0.35rem 0.65rem; border-radius: 6px; background: #fee2e2; color: #ef4444; border: none;" title="Hapus Lensa">
                                                <ion-icon name="trash-outline" style="font-size: 1.05rem;"></ion-icon>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- =================================================================== -->
        <!-- TAB CONTENT 2: KATALOG FRAME / BINGKAI -->
        <!-- =================================================================== -->
        <div id="tabContentFrame" style="display: <?= $activeTab === 'frame' ? 'block' : 'none' ?>;">
            
            <!-- Filter Bar Frame -->
            <form method="GET" action="<?= baseUrl('master') ?>" style="margin-bottom: 1.25rem;">
                <input type="hidden" name="tab" value="frame">
                <div class="row-layout" style="align-items: center; gap: 0.75rem;">
                    <div style="flex: 1; min-width: 220px;">
                        <input type="text" name="search_frame" class="form-control" 
                               placeholder="🔎 Cari kode frame, merk, warna, atau material..." 
                               value="<?= htmlspecialchars($searchFrame) ?>">
                    </div>
                    <div style="width: 180px;">
                        <select name="brand_frame" class="form-control" onchange="this.form.submit()">
                            <option value="">-- Semua Brand --</option>
                            <?php foreach ($frameBrands as $b): ?>
                                <option value="<?= htmlspecialchars($b) ?>" <?= $brandFrame === $b ? 'selected' : '' ?>><?= htmlspecialchars($b) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div style="width: 180px;">
                        <select name="type_frame" class="form-control" onchange="this.form.submit()">
                            <option value="">-- Semua Tipe Rim --</option>
                            <?php foreach ($frameTypes as $t): ?>
                                <option value="<?= htmlspecialchars($t) ?>" <?= $typeFrame === $t ? 'selected' : '' ?>><?= htmlspecialchars($t) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="btn btn-success py-2.5 px-3" style="border-radius: 8px; background: #10b981; border-color: #10b981; color: #fff;">Cari</button>
                        <?php if (!empty($searchFrame) || !empty($brandFrame) || !empty($typeFrame)): ?>
                            <a href="<?= baseUrl('master?tab=frame') ?>" class="btn btn-secondary py-2.5 px-3" style="border-radius: 8px; font-weight: 600;">Reset</a>
                        <?php endif; ?>
                    </div>
                </div>
            </form>

            <!-- Table Master Frame -->
            <div class="table-responsive" style="border-radius: 12px; border: 1px solid var(--color-border); overflow: hidden;">
                <table class="table" style="margin: 0;">
                    <thead style="background: rgba(15, 23, 42, 0.04);">
                        <tr>
                            <th style="width: 130px;">KODE FRAME</th>
                            <th>NAMA & MODEL FRAME</th>
                            <th>BRAND</th>
                            <th>TIPE RIM</th>
                            <th>MATERIAL</th>
                            <th>WARNA</th>
                            <th class="text-right">HARGA BINGKAI</th>
                            <th class="text-center">STOK</th>
                            <th class="text-center" style="width: 110px;">AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($frames)): ?>
                            <tr>
                                <td colspan="9" class="text-center" style="padding: 2.5rem; color: var(--text-muted);">
                                    <ion-icon name="color-filter-outline" style="font-size: 2rem; color: #94a3b8; display: block; margin: 0 auto 0.5rem;"></ion-icon>
                                    Tidak ada data frame yang ditemukan.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($frames as $f): ?>
                                <tr class="transaction-row">
                                    <td>
                                        <span style="font-family: monospace; font-size: 0.85rem; font-weight: 700; background: rgba(16, 185, 129, 0.1); color: #047857; padding: 0.2rem 0.5rem; border-radius: 6px;">
                                            <?= htmlspecialchars($f['code']) ?>
                                        </span>
                                    </td>
                                    <td>
                                        <strong style="color: var(--color-dark); font-size: 0.95rem; display: block;">
                                            <?= htmlspecialchars($f['name']) ?>
                                        </strong>
                                    </td>
                                    <td>
                                        <span style="font-weight: 700; color: #334155;"><?= htmlspecialchars($f['brand']) ?></span>
                                    </td>
                                    <td>
                                        <span class="badge-category" style="background: rgba(15, 23, 42, 0.06); color: #334155;">
                                            <?= htmlspecialchars($f['type'] ?? 'Full Rim') ?>
                                        </span>
                                    </td>
                                    <td>
                                        <span style="font-size: 0.85rem; font-weight: 600; color: #475569;"><?= htmlspecialchars($f['material'] ?? '-') ?></span>
                                    </td>
                                    <td>
                                        <span style="font-size: 0.85rem; color: var(--text-muted);"><?= htmlspecialchars($f['color'] ?? '-') ?></span>
                                    </td>
                                    <td class="text-right" style="font-weight: 700; color: var(--color-dark);">
                                        Rp <?= number_format($f['price'] ?? 0, 0, ',', '.') ?>
                                    </td>
                                    <td class="text-center">
                                        <?php 
                                        $stk = (int)($f['stock'] ?? 0);
                                        $bg = $stk > 5 ? 'rgba(16, 185, 129, 0.12)' : ($stk > 0 ? 'rgba(245, 158, 11, 0.12)' : 'rgba(239, 68, 68, 0.12)');
                                        $fg = $stk > 5 ? '#047857' : ($stk > 0 ? '#b45309' : '#dc2626');
                                        ?>
                                        <span style="display: inline-block; font-weight: 800; font-size: 0.82rem; background: <?= $bg ?>; color: <?= $fg ?>; padding: 0.2rem 0.65rem; border-radius: 50px;">
                                            <?= $stk ?> pcs
                                        </span>
                                    </td>
                                    <td class="text-center">
                                        <div style="display: flex; justify-content: center; gap: 0.4rem;">
                                            <button type="button" class="btn btn-sm btn-secondary" onclick='openEditFrameModal(<?= json_encode($f) ?>)' style="padding: 0.35rem 0.65rem; border-radius: 6px;" title="Edit Data Frame">
                                                <ion-icon name="create-outline" style="font-size: 1.05rem;"></ion-icon>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-danger" onclick="confirmDeleteFrame(<?= $f['id'] ?>, '<?= htmlspecialchars($f['name'], ENT_QUOTES) ?>')" style="padding: 0.35rem 0.65rem; border-radius: 6px; background: #fee2e2; color: #ef4444; border: none;" title="Hapus Frame">
                                                <ion-icon name="trash-outline" style="font-size: 1.05rem;"></ion-icon>
                                            </button>
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
</div>

<!-- ======================================================================= -->
<!-- MODAL: TAMBAH / EDIT LENSA -->
<!-- ======================================================================= -->
<div id="modalLens" style="display: none; position: fixed; inset: 0; z-index: 9999; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); align-items: center; justify-content: center; padding: 1rem;">
    <div style="background: #ffffff; border-radius: 16px; width: 100%; max-width: 580px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2); overflow: hidden; animation: fadeIn 0.2s ease;">
        <div style="padding: 1.25rem 1.5rem; background: var(--color-primary); color: #ffffff; display: flex; align-items: center; justify-content: space-between;">
            <h3 id="modalLensTitle" style="margin: 0; font-size: 1.15rem; font-weight: 700; color: #ffffff;">Tambah Lensa Baru</h3>
            <button type="button" onclick="closeLensModal()" style="background: none; border: none; color: #ffffff; font-size: 1.5rem; cursor: pointer;">&times;</button>
        </div>
        
        <form id="formLens" method="POST" action="<?= baseUrl('master/lensa/create') ?>">
            <input type="hidden" name="id" id="lens_id" value="">
            
            <div style="padding: 1.5rem; max-height: 75vh; overflow-y: auto;">
                <div class="row-layout mb-3">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="lens_code" style="font-weight: 600;">Kode Lensa <span class="text-muted">(Opsional / Auto)</span></label>
                            <input type="text" name="code" id="lens_code" class="form-control" placeholder="Contoh: LNS-ORI-156">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="lens_name" style="font-weight: 600;">Nama Varian Lensa <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="lens_name" class="form-control" placeholder="Contoh: SV Ori 1.56 UV 420" required>
                        </div>
                    </div>
                </div>

                <div class="row-layout mb-3">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="lens_brand" style="font-weight: 600;">Brand / Merk Lensa <span class="text-danger">*</span></label>
                            <input type="text" name="brand" id="lens_brand" class="form-control" placeholder="Contoh: Oriental, Leinz, Essilor" required list="brand_lens_suggestions">
                            <datalist id="brand_lens_suggestions">
                                <option value="Oriental">
                                <option value="Leinz">
                                <option value="Ecosoft">
                                <option value="Essilor">
                                <option value="Hoya">
                                <option value="Zeiss">
                            </datalist>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="lens_category" style="font-weight: 600;">Kategori Lensa <span class="text-danger">*</span></label>
                            <select name="category" id="lens_category" class="form-control" required>
                                <option value="Single Vision">Single Vision</option>
                                <option value="Bifokal">Bifokal (Kriptok/Flattop)</option>
                                <option value="Progressive">Progressive</option>
                                <option value="Office Lens">Office / Workplace Lens</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row-layout mb-3">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="lens_index" style="font-weight: 600;">Index Refraksi</label>
                            <select name="index_refraction" id="lens_index" class="form-control">
                                <option value="1.50">1.50 (Standard Organic)</option>
                                <option value="1.56" selected>1.56 (Mid Index)</option>
                                <option value="1.61">1.61 (Hi Index Thin)</option>
                                <option value="1.67">1.67 (Ultra Thin)</option>
                                <option value="1.74">1.74 (Super Thin)</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="lens_coating" style="font-weight: 600;">Fitur / Coating</label>
                            <input type="text" name="coating" id="lens_coating" class="form-control" placeholder="Contoh: Bluecut, UV420, Photochromic">
                        </div>
                    </div>
                </div>

                <div class="row-layout">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="lens_price" style="font-weight: 600;">Harga Acuan (Rp)</label>
                            <input type="number" name="price" id="lens_price" class="form-control" placeholder="0" min="0" step="5000">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="lens_stock" style="font-weight: 600;">Jumlah Stok Tersedia</label>
                            <input type="number" name="stock" id="lens_stock" class="form-control" placeholder="0" min="0" value="10">
                        </div>
                    </div>
                </div>
            </div>

            <div style="padding: 1rem 1.5rem; background: var(--color-light); border-top: 1px solid var(--color-border); display: flex; justify-content: flex-end; gap: 0.75rem;">
                <button type="button" onclick="closeLensModal()" class="btn btn-secondary px-4">Batal</button>
                <button type="submit" class="btn btn-primary px-4 font-weight-bold">Simpan Data Lensa</button>
            </div>
        </form>
    </div>
</div>

<!-- ======================================================================= -->
<!-- MODAL: TAMBAH / EDIT FRAME -->
<!-- ======================================================================= -->
<div id="modalFrame" style="display: none; position: fixed; inset: 0; z-index: 9999; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); align-items: center; justify-content: center; padding: 1rem;">
    <div style="background: #ffffff; border-radius: 16px; width: 100%; max-width: 580px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.2); overflow: hidden; animation: fadeIn 0.2s ease;">
        <div style="padding: 1.25rem 1.5rem; background: #10b981; color: #ffffff; display: flex; align-items: center; justify-content: space-between;">
            <h3 id="modalFrameTitle" style="margin: 0; font-size: 1.15rem; font-weight: 700; color: #ffffff;">Tambah Frame Baru</h3>
            <button type="button" onclick="closeFrameModal()" style="background: none; border: none; color: #ffffff; font-size: 1.5rem; cursor: pointer;">&times;</button>
        </div>
        
        <form id="formFrame" method="POST" action="<?= baseUrl('master/frame/create') ?>">
            <input type="hidden" name="id" id="frame_id" value="">
            
            <div style="padding: 1.5rem; max-height: 75vh; overflow-y: auto;">
                <div class="row-layout mb-3">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="frame_code" style="font-weight: 600;">Kode Frame / Model <span class="text-danger">*</span></label>
                            <input type="text" name="code" id="frame_code_input" class="form-control" placeholder="Contoh: RB-5228" required>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="frame_name" style="font-weight: 600;">Nama Lengkap Frame <span class="text-danger">*</span></label>
                            <input type="text" name="name" id="frame_name_input" class="form-control" placeholder="Contoh: Ray-Ban Wayfarer Classic" required>
                        </div>
                    </div>
                </div>

                <div class="row-layout mb-3">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="frame_brand" style="font-weight: 600;">Brand / Merk Frame <span class="text-danger">*</span></label>
                            <input type="text" name="brand" id="frame_brand_input" class="form-control" placeholder="Contoh: Ray-Ban, Oakley, Molsion" required list="brand_frame_suggestions">
                            <datalist id="brand_frame_suggestions">
                                <option value="Ray-Ban">
                                <option value="Oakley">
                                <option value="Silhouette">
                                <option value="Molsion">
                                <option value="Gentle Monster">
                                <option value="Trendy Frame">
                            </datalist>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="frame_type" style="font-weight: 600;">Tipe Bingkai (Rim)</label>
                            <select name="type" id="frame_type_input" class="form-control">
                                <option value="Full Rim">Full Rim (Penuh)</option>
                                <option value="Half Rim">Half Rim / Supra (Gantung)</option>
                                <option value="Rimless">Rimless / Frame Bor (Tanpa Bingkai)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="row-layout mb-3">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="frame_material" style="font-weight: 600;">Material Bahan</label>
                            <input type="text" name="material" id="frame_material_input" class="form-control" placeholder="Contoh: Acetate, Titanium, TR90, Metal">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="frame_color" style="font-weight: 600;">Warna Frame</label>
                            <input type="text" name="color" id="frame_color_input" class="form-control" placeholder="Contoh: Matte Black, Rose Gold, Tortoise">
                        </div>
                    </div>
                </div>

                <div class="row-layout">
                    <div class="col-6">
                        <div class="form-group">
                            <label for="frame_price" style="font-weight: 600;">Harga Bingkai (Rp)</label>
                            <input type="number" name="price" id="frame_price_input" class="form-control" placeholder="0" min="0" step="10000">
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label for="frame_stock" style="font-weight: 600;">Jumlah Stok Tersedia</label>
                            <input type="number" name="stock" id="frame_stock_input" class="form-control" placeholder="0" min="0" value="5">
                        </div>
                    </div>
                </div>
            </div>

            <div style="padding: 1rem 1.5rem; background: var(--color-light); border-top: 1px solid var(--color-border); display: flex; justify-content: flex-end; gap: 0.75rem;">
                <button type="button" onclick="closeFrameModal()" class="btn btn-secondary px-4">Batal</button>
                <button type="submit" class="btn btn-success px-4 font-weight-bold" style="background: #10b981; border-color: #10b981; color: #fff;">Simpan Data Frame</button>
            </div>
        </form>
    </div>
</div>

<!-- ======================================================================= -->
<!-- DELETE CONFIRMATION FORM -->
<!-- ======================================================================= -->
<form id="formDeleteMaster" method="POST" action="">
    <input type="hidden" name="id" id="delete_master_id" value="">
</form>

<script>
function switchMasterTab(tabName) {
    const tabLensa = document.getElementById('tabContentLensa');
    const tabFrame = document.getElementById('tabContentFrame');
    const btnLensa = document.getElementById('btnTabLensa');
    const btnFrame = document.getElementById('btnTabFrame');
    const btnCreateLensa = document.getElementById('btnCreateLensa');
    const btnCreateFrame = document.getElementById('btnCreateFrame');

    if (tabName === 'frame') {
        tabLensa.style.display = 'none';
        tabFrame.style.display = 'block';
        btnLensa.style.background = 'transparent';
        btnLensa.style.color = 'var(--text-muted)';
        btnLensa.style.boxShadow = 'none';

        btnFrame.style.background = '#ffffff';
        btnFrame.style.color = 'var(--color-primary)';
        btnFrame.style.boxShadow = 'var(--shadow-sm)';

        btnCreateLensa.style.display = 'none';
        btnCreateFrame.style.display = 'inline-flex';
    } else {
        tabFrame.style.display = 'none';
        tabLensa.style.display = 'block';
        btnFrame.style.background = 'transparent';
        btnFrame.style.color = 'var(--text-muted)';
        btnFrame.style.boxShadow = 'none';

        btnLensa.style.background = '#ffffff';
        btnLensa.style.color = 'var(--color-primary)';
        btnLensa.style.boxShadow = 'var(--shadow-sm)';

        btnCreateFrame.style.display = 'none';
        btnCreateLensa.style.display = 'inline-flex';
    }
}

// Lens Modal Functions
function openAddLensModal() {
    document.getElementById('modalLensTitle').textContent = 'Tambah Lensa Baru';
    document.getElementById('formLens').action = "<?= baseUrl('master/lensa/create') ?>";
    document.getElementById('lens_id').value = '';
    document.getElementById('lens_code').value = '';
    document.getElementById('lens_name').value = '';
    document.getElementById('lens_brand').value = 'Oriental';
    document.getElementById('lens_category').value = 'Single Vision';
    document.getElementById('lens_index').value = '1.56';
    document.getElementById('lens_coating').value = '';
    document.getElementById('lens_price').value = '';
    document.getElementById('lens_stock').value = '10';
    document.getElementById('modalLens').style.display = 'flex';
}

function openEditLensModal(lens) {
    document.getElementById('modalLensTitle').textContent = 'Edit Data Lensa';
    document.getElementById('formLens').action = "<?= baseUrl('master/lensa/edit') ?>";
    document.getElementById('lens_id').value = lens.id;
    document.getElementById('lens_code').value = lens.code || '';
    document.getElementById('lens_name').value = lens.name || '';
    document.getElementById('lens_brand').value = lens.brand || '';
    document.getElementById('lens_category').value = lens.category || 'Single Vision';
    document.getElementById('lens_index').value = lens.index_refraction || '1.56';
    document.getElementById('lens_coating').value = lens.coating || '';
    document.getElementById('lens_price').value = lens.price || '';
    document.getElementById('lens_stock').value = lens.stock || 0;
    document.getElementById('modalLens').style.display = 'flex';
}

function closeLensModal() {
    document.getElementById('modalLens').style.display = 'none';
}

function confirmDeleteLens(id, name) {
    if (confirm(`Apakah Anda yakin ingin menghapus data lensa "${name}"?`)) {
        const form = document.getElementById('formDeleteMaster');
        form.action = "<?= baseUrl('master/lensa/delete') ?>";
        document.getElementById('delete_master_id').value = id;
        form.submit();
    }
}

// Frame Modal Functions
function openAddFrameModal() {
    document.getElementById('modalFrameTitle').textContent = 'Tambah Frame Baru';
    document.getElementById('formFrame').action = "<?= baseUrl('master/frame/create') ?>";
    document.getElementById('frame_id').value = '';
    document.getElementById('frame_code_input').value = '';
    document.getElementById('frame_name_input').value = '';
    document.getElementById('frame_brand_input').value = 'Ray-Ban';
    document.getElementById('frame_type_input').value = 'Full Rim';
    document.getElementById('frame_material_input').value = 'Acetate';
    document.getElementById('frame_color_input').value = 'Black';
    document.getElementById('frame_price_input').value = '';
    document.getElementById('frame_stock_input').value = '5';
    document.getElementById('modalFrame').style.display = 'flex';
}

function openEditFrameModal(frame) {
    document.getElementById('modalFrameTitle').textContent = 'Edit Data Frame';
    document.getElementById('formFrame').action = "<?= baseUrl('master/frame/edit') ?>";
    document.getElementById('frame_id').value = frame.id;
    document.getElementById('frame_code_input').value = frame.code || '';
    document.getElementById('frame_name_input').value = frame.name || '';
    document.getElementById('frame_brand_input').value = frame.brand || '';
    document.getElementById('frame_type_input').value = frame.type || 'Full Rim';
    document.getElementById('frame_material_input').value = frame.material || '';
    document.getElementById('frame_color_input').value = frame.color || '';
    document.getElementById('frame_price_input').value = frame.price || '';
    document.getElementById('frame_stock_input').value = frame.stock || 0;
    document.getElementById('modalFrame').style.display = 'flex';
}

function closeFrameModal() {
    document.getElementById('modalFrame').style.display = 'none';
}

function confirmDeleteFrame(id, name) {
    if (confirm(`Apakah Anda yakin ingin menghapus data frame "${name}"?`)) {
        const form = document.getElementById('formDeleteMaster');
        form.action = "<?= baseUrl('master/frame/delete') ?>";
        document.getElementById('delete_master_id').value = id;
        form.submit();
    }
}
</script>
