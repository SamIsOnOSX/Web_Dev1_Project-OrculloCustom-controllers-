<!-- Manage Users Card -->
<div class="dashboard-card table-responsive">
    <h3><i class="fa-solid fa-users card-icon"></i>Manage Users</h3>
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
                    <form method="POST" action="index.php">
                        <input type="hidden" name="user_id" value="<?php echo $u['id']; ?>">
                        <td>#<?php echo $u['id']; ?></td>
                        <td><input type="text" name="username" value="<?php echo htmlspecialchars($u['username']); ?>" class="input-user-name"></td>
                        <td><input type="email" name="email" value="<?php echo htmlspecialchars($u['email']); ?>" class="input-user-email"></td>
                        <td>
                            <select name="role" class="table-select">
                                <option value="user" <?php echo $u['role'] === 'user' ? 'selected' : ''; ?>>User</option>
                                <option value="admin" <?php echo $u['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                            </select>
                        </td>
                        <td><button type="submit" name="update_user" class="btn-save-user">Save</button></td>
                    </form>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
