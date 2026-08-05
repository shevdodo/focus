<div class="row-layout mb-4 animate-fade-in" style="align-items: stretch;">
    <!-- Summary Statistics -->
    <div class="col-8" style="flex: 2 1 500px; display: flex; flex-direction: column; gap: 1rem;">
        <div class="dashboard-grid" style="margin-bottom: 0;">
            <!-- Card Total Users -->
            <div class="stat-card balance-card">
                <div class="stat-icon-wrapper">
                    <ion-icon name="people-outline"></ion-icon>
                </div>
                <div class="stat-details">
                    <span class="stat-label">Total Pengguna</span>
                    <h2 class="stat-value"><?= count($users) ?></h2>
                </div>
            </div>

            <!-- Card Admin Users -->
            <?php 
            $adminCount = count(array_filter($users, function($u) { return $u['role'] === 'admin'; }));
            $staffCount = count($users) - $adminCount;
            ?>
            <div class="stat-card income-card">
                <div class="stat-icon-wrapper" style="background-color: rgba(99, 102, 241, 0.1); color: var(--color-primary);">
                    <ion-icon name="shield-checkmark-outline"></ion-icon>
                </div>
                <div class="stat-details">
                    <span class="stat-label">Administrator & Doctor</span>
                    <h2 class="stat-value"><?= $adminCount ?></h2>
                </div>
            </div>

            <!-- Card Staff Users -->
            <div class="stat-card expense-card" style="background-color: var(--color-white); border-color: var(--color-border);">
                <div class="stat-icon-wrapper" style="background-color: var(--color-light); color: var(--text-main);">
                    <ion-icon name="person-outline"></ion-icon>
                </div>
                <div class="stat-details">
                    <span class="stat-label">Optometris / Staf</span>
                    <h2 class="stat-value"><?= $staffCount ?></h2>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="monthly-breakdown-card animate-fade-in" style="margin-bottom: 2rem;">
    <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem;">
        <div>
            <h2 style="font-size: 1.25rem; font-weight: 700; color: var(--color-dark); margin-bottom: 0.25rem;">Daftar Pengguna Sistem</h2>
            <p class="text-muted text-sm">Kelola hak akses dan akun pengguna Klinik OPTIK FOCUS.</p>
        </div>
        <button class="btn btn-primary" onclick="openAddModal()">
            <ion-icon name="person-add-outline" class="mr-2"></ion-icon>
            <span>Tambah Pengguna</span>
        </button>
    </div>

    <!-- Responsive Table -->
    <div class="table-responsive">
        <table class="table" style="width: 100%;">
            <thead>
                <tr>
                    <th>Nama Lengkap</th>
                    <th>Username</th>
                    <th>Hak Akses (Role)</th>
                    <th class="text-center" style="width: 120px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($users)): ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted py-4">Belum ada pengguna terdaftar.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($users as $user): ?>
                        <tr>
                            <td style="font-weight: 600; color: var(--color-dark);">
                                <?= htmlspecialchars($user['name'], ENT_QUOTES, 'UTF-8') ?>
                                <?php if ((int)$user['id'] === (int)$_SESSION['user']['id']): ?>
                                    <span style="font-size: 0.7rem; background-color: var(--color-primary-light); color: var(--color-primary); padding: 0.15rem 0.4rem; border-radius: 4px; margin-left: 0.5rem; font-weight: 700;">Anda</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <code style="background-color: var(--color-light); padding: 0.2rem 0.4rem; border-radius: 6px; color: var(--text-main); font-weight: 600;">@<?= htmlspecialchars($user['username'], ENT_QUOTES, 'UTF-8') ?></code>
                            </td>
                            <td>
                                <?php if ($user['role'] === 'admin'): ?>
                                    <span class="stat-badge badge-success" style="background-color: rgba(99, 102, 241, 0.1); color: var(--color-primary); border: 1px solid rgba(99, 102, 241, 0.2); margin-top: 0; padding: 0.25rem 0.75rem;">
                                        <ion-icon name="shield-checkmark-outline"></ion-icon>
                                        <span>Administrator</span>
                                    </span>
                                <?php elseif ($user['role'] === 'optometris'): ?>
                                    <span class="stat-badge" style="background-color: rgba(16, 185, 129, 0.1); color: #10b981; border: 1px solid rgba(16, 185, 129, 0.2); margin-top: 0; padding: 0.25rem 0.75rem;">
                                        <ion-icon name="eye-outline"></ion-icon>
                                        <span>Optometris</span>
                                    </span>
                                <?php else: ?>
                                    <span class="stat-badge" style="background-color: var(--color-light); color: var(--text-main); border: 1px solid var(--color-border); margin-top: 0; padding: 0.25rem 0.75rem;">
                                        <ion-icon name="person-outline"></ion-icon>
                                        <span>Staf Optik</span>
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div style="display: inline-flex; gap: 0.5rem; align-items: center;">
                                    <!-- Edit Button -->
                                    <button class="btn-action-delete" style="color: var(--color-primary); background: none;" 
                                            onclick="openEditModal(<?= htmlspecialchars(json_encode($user)) ?>)" 
                                            title="Ubah Data">
                                        <ion-icon name="create-outline" style="font-size: 1.25rem;"></ion-icon>
                                    </button>
                                    
                                    <!-- Delete Button -->
                                    <?php if ((int)$user['id'] !== (int)$_SESSION['user']['id']): ?>
                                        <form method="POST" action="<?= baseUrl('users/delete') ?>" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?');" style="margin: 0; padding: 0;">
                                            <input type="hidden" name="id" value="<?= $user['id'] ?>">
                                            <button type="submit" class="btn-action-delete" title="Hapus Pengguna">
                                                <ion-icon name="trash-outline"></ion-icon>
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <button class="btn-action-delete" style="opacity: 0.3; cursor: not-allowed;" title="Anda tidak bisa menghapus akun Anda sendiri" disabled>
                                            <ion-icon name="trash-outline"></ion-icon>
                                        </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- ==========================================================================
   MODAL DIALOG - TAMBAH / UBAH PENGGUNA
   ========================================================================== -->
<div id="userModal" class="modal-overlay" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background-color: rgba(15, 23, 42, 0.6); backdrop-filter: blur(8px); -webkit-backdrop-filter: blur(8px); z-index: 1000; align-items: center; justify-content: center; padding: 1.5rem;">
    <div class="monthly-breakdown-card" style="width: 100%; max-width: 480px; margin-bottom: 0; border-radius: var(--radius-lg);">
        <div style="display: flex; justify-content: space-between; align-items: center; border-bottom: 1px solid var(--color-border); padding-bottom: 1rem; margin-bottom: 1.5rem;">
            <h3 id="modalTitle" style="font-size: 1.15rem; font-weight: 700; color: var(--color-dark);">Tambah Pengguna Baru</h3>
            <button onclick="closeUserModal()" style="background: none; border: none; font-size: 1.5rem; color: var(--text-muted); cursor: pointer;">
                <ion-icon name="close-outline"></ion-icon>
            </button>
        </div>

        <form id="userForm" method="POST" action="<?= baseUrl('users/create') ?>">
            <input type="hidden" name="id" id="userId" value="">
            
            <div class="form-group mb-3">
                <label for="userNameInput">Nama Lengkap</label>
                <input type="text" name="name" id="userNameInput" class="form-control" placeholder="Contoh: dr. Hendra Optometris" required>
            </div>

            <div class="form-group mb-3">
                <label for="userUsernameInput">Username</label>
                <div style="position: relative; display: flex; align-items: center;">
                    <span style="position: absolute; left: 1rem; font-weight: 700; color: var(--text-muted);">@</span>
                    <input type="text" name="username" id="userUsernameInput" class="form-control" style="padding-left: 2.25rem;" placeholder="hendra" required>
                </div>
            </div>

            <div class="form-group mb-3">
                <label id="passwordLabel" for="userPasswordInput">Kata Sandi</label>
                <input type="password" name="password" id="userPasswordInput" class="form-control" placeholder="••••••••" required>
                <p id="passwordHelp" class="text-muted text-sm" style="display: none; margin-top: 0.25rem; font-size: 0.75rem;">Biarkan kosong jika tidak ingin mengubah kata sandi.</p>
            </div>

            <div class="form-group mb-4">
                <label for="userRoleInput">Hak Akses (Role)</label>
                <select name="role" id="userRoleInput" class="form-control" style="background-color: var(--color-white); font-family: inherit; font-size: 0.95rem; cursor: pointer;">
                    <option value="optometris">Optometris / Pemeriksa</option>
                    <option value="staf">Staf Optik / Kasir</option>
                    <option value="admin">Administrator</option>
                </select>
            </div>

            <div style="display: flex; gap: 0.75rem; justify-content: flex-end; border-top: 1px solid var(--color-border); padding-top: 1.25rem; margin-top: 1.5rem;">
                <button type="button" class="btn btn-secondary" onclick="closeUserModal()">Batal</button>
                <button type="submit" class="btn btn-primary" id="btnSubmitModal">
                    <ion-icon name="checkmark-circle-outline" class="mr-2"></ion-icon>
                    <span>Simpan Pengguna</span>
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    const userModal = document.getElementById('userModal');
    const userForm = document.getElementById('userForm');
    const modalTitle = document.getElementById('modalTitle');
    const userIdInput = document.getElementById('userId');
    const userNameInput = document.getElementById('userNameInput');
    const userUsernameInput = document.getElementById('userUsernameInput');
    const userPasswordInput = document.getElementById('userPasswordInput');
    const passwordLabel = document.getElementById('passwordLabel');
    const passwordHelp = document.getElementById('passwordHelp');
    const userRoleInput = document.getElementById('userRoleInput');

    function openAddModal() {
        modalTitle.textContent = "Tambah Pengguna Baru";
        userForm.action = "<?= baseUrl('users/create') ?>";
        userIdInput.value = "";
        userNameInput.value = "";
        userUsernameInput.value = "";
        userPasswordInput.value = "";
        userPasswordInput.placeholder = "••••••••";
        userPasswordInput.required = true;
        passwordLabel.textContent = "Kata Sandi";
        passwordHelp.style.display = "none";
        userRoleInput.value = "optometris";
        userModal.style.display = "flex";
    }

    function openEditModal(userObj) {
        modalTitle.textContent = "Ubah Data Pengguna";
        userForm.action = "<?= baseUrl('users/edit') ?>";
        userIdInput.value = userObj.id;
        userNameInput.value = userObj.name;
        userUsernameInput.value = userObj.username;
        userPasswordInput.value = "";
        userPasswordInput.placeholder = "Isi hanya jika ingin diganti";
        userPasswordInput.required = false;
        passwordLabel.textContent = "Kata Sandi Baru";
        passwordHelp.style.display = "block";
        userRoleInput.value = userObj.role;
        userModal.style.display = "flex";
    }

    function closeUserModal() {
        userModal.style.display = "none";
    }

    userModal.addEventListener('click', function(e) {
        if (e.target === userModal) {
            closeUserModal();
        }
    });
</script>
