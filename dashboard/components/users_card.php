<?php
if (!isset($all_users) || !is_array($all_users)) {
    $all_users = [];
}
?>

<!-- Manage Users Card -->
<div class="dashboard-card table-responsive">
    <div class="card-header-bar">
        <h3><i class="fa-solid fa-users card-icon"></i>Manage Users</h3>
    </div>
    <table class="order-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($all_users as $u): ?>
                <tr>
                    <td><span class="order-id-tag">#<?php echo $u['id']; ?></span></td>
                    <td><input type="text" name="username" value="<?php echo htmlspecialchars($u['username']); ?>" form="user-form-<?php echo $u['id']; ?>" class="input-user-name"></td>
                    <td><input type="email" name="email" value="<?php echo htmlspecialchars($u['email']); ?>" form="user-form-<?php echo $u['id']; ?>" class="input-user-email"></td>
                    <td>
                        <select name="role" form="user-form-<?php echo $u['id']; ?>" class="table-select">
                            <option value="customer" <?php echo $u['role'] === 'customer' ? 'selected' : ''; ?>>Customer</option>
                            <option value="admin" <?php echo $u['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                        </select>
                    </td>
                    <td>
                        <button type="submit" name="update_user" form="user-form-<?php echo $u['id']; ?>" class="btn-save-user">Save</button>
                    </td>
                </tr>
                <form id="user-form-<?php echo $u['id']; ?>" method="POST" action="index.php">
                    <input type="hidden" name="user_id" value="<?php echo $u['id']; ?>">
                </form>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<!-- Change User Password Card -->
<div class="dashboard-card">
    <div class="card-header-bar">
        <h3><i class="fa-solid fa-key card-icon"></i>Change User Passwords</h3>
    </div>
    <p class="admin-section-note"><i class="fa-solid fa-circle-info"></i> You can reset the password for any customer account. Admin passwords cannot be changed from here.</p>
    <div class="user-pwd-grid">
        <?php foreach ($all_users as $u): ?>
            <?php if ($u['role'] === 'admin'): ?>
                <!-- Admin row — locked, cannot change -->
                <div class="user-pwd-row user-pwd-row--locked">
                    <div class="user-pwd-identity">
                        <span class="user-pwd-name"><?php echo htmlspecialchars($u['username']); ?></span>
                        <span class="user-pwd-role-badge admin-pwd-badge"><i class="fa-solid fa-shield-halved"></i> Admin</span>
                    </div>
                    <div class="user-pwd-locked-msg">
                        <i class="fa-solid fa-lock"></i> Password protected
                    </div>
                </div>
            <?php else: ?>
                <!-- Customer row — can change password -->
                <div class="user-pwd-row">
                    <div class="user-pwd-identity">
                        <span class="user-pwd-name"><?php echo htmlspecialchars($u['username']); ?></span>
                        <span class="user-pwd-role-badge customer-pwd-badge"><i class="fa-solid fa-user"></i> Customer</span>
                    </div>
                    <form method="POST" action="index.php" class="user-pwd-form">
                        <input type="hidden" name="user_id" value="<?php echo $u['id']; ?>">
                        <div class="pwd-input-wrap">
                            <input type="password" name="new_password" placeholder="New password (min. 6 chars)" class="input-new-pwd" minlength="6" required autocomplete="new-password">
                            <button type="button" class="pwd-toggle-btn" onclick="togglePasswordSibling(this)" aria-label="Toggle password visibility">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                        <button type="submit" name="admin_change_password" class="btn-change-pwd">
                            <i class="fa-solid fa-floppy-disk"></i> Set Password
                        </button>
                    </form>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
</div>

<script>
function togglePasswordSibling(btn) {
    var wrap  = btn.closest('.pwd-input-wrap');
    var input = wrap.querySelector('input[type="password"], input[type="text"]');
    var icon  = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('fa-eye', 'fa-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('fa-eye-slash', 'fa-eye');
    }
}
</script>
