<?php
$user_id = $_SESSION['user_id'];
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 1. Update Profile (Username & Email)
    if (isset($_POST['update_profile'])) {
        $new_username = trim($_POST['username'] ?? '');
        $new_email = trim($_POST['email'] ?? '');
        
        if ($new_username === '' || $new_email === '') {
            $error = "Username and email cannot be blank.";
        } else {
            // Check if email already belongs to another user
            $stmt = $pdo->prepare("SELECT id FROM users WHERE (email = :email OR username = :username) AND id != :id");
            $stmt->execute(['email' => $new_email, 'username' => $new_username, 'id' => $user_id]);
            if ($stmt->fetch()) {
                $error = "That username or email is already in use by another account.";
            } else {
                $stmt = $pdo->prepare("UPDATE users SET username = :username, email = :email WHERE id = :id");
                if ($stmt->execute(['username' => $new_username, 'email' => $new_email, 'id' => $user_id])) {
                    $message = "Profile successfully updated.";
                    $_SESSION['username'] = $new_username; 
                } else {
                    $error = "Could not update profile. Please try again.";
                }
            }
        }
    }
    // 2. Change Password
    elseif (isset($_POST['change_password'])) {
        $current_pwd = $_POST['current_password'] ?? '';
        $new_pwd     = $_POST['new_password'] ?? '';
        $confirm_pwd = $_POST['confirm_password'] ?? '';

        if (empty($current_pwd) || empty($new_pwd) || empty($confirm_pwd)) {
            $error = "All password fields are required.";
        } elseif (strlen($new_pwd) < 6) {
            $error = "New password must be at least 6 characters long.";
        } elseif ($new_pwd !== $confirm_pwd) {
            $error = "New password and confirmation do not match.";
        } else {
            // Verify current password
            $stmt = $pdo->prepare("SELECT password FROM users WHERE id = :id");
            $stmt->execute(['id' => $user_id]);
            $userData = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($userData && password_verify($current_pwd, $userData['password'])) {
                $hashed = password_hash($new_pwd, PASSWORD_DEFAULT);
                if (updateUserPassword($pdo, $user_id, $hashed)) {
                    $message = "Your password has been successfully changed.";
                } else {
                    $error = "Failed to change password. Please try again.";
                }
            } else {
                $error = "Incorrect current password.";
            }
        }
    }
}

$user = getUsers($pdo, $user_id);
$orders = getOrderHistory($pdo, $user_id);

include '../global/header.php';
?>

<link rel="stylesheet" href="<?php echo $base_path; ?>css/dashboard.css">

<div class="dashboard-page-wrapper">
    <main class="dashboard-container">
        <div class="dashboard-header">
            <span class="user-role-badge"><i class="fa-solid fa-user"></i> Customer Dashboard</span>
            <h1>Welcome, <span class="username-highlight"><?php echo htmlspecialchars($user['username']); ?></span></h1>
            <p>Manage your account credentials and track your custom controller orders.</p>
        </div>

        <?php if (!empty($message)): ?>
            <div class="alert-success">
                <i class="fa-solid fa-circle-check"></i>
                <span><?php echo htmlspecialchars($message); ?></span>
            </div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="alert-error">
                <i class="fa-solid fa-circle-exclamation"></i>
                <span><?php echo htmlspecialchars($error); ?></span>
            </div>
        <?php endif; ?>

        <!-- Account Info Card -->
        <div class="dashboard-card">
            <div class="card-header-bar">
                <h3><i class="fa-solid fa-user-pen card-icon"></i> Account Information</h3>
            </div>
            <form method="POST" action="index.php" class="dashboard-form">
                <div class="form-row">
                    <div class="form-group flex-1">
                        <label for="user_username"><i class="fa-solid fa-user label-icon"></i> Username</label>
                        <input type="text" id="user_username" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
                    </div>
                    
                    <div class="form-group flex-1">
                        <label for="user_email"><i class="fa-solid fa-envelope label-icon"></i> Email Address</label>
                        <input type="email" id="user_email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                    </div>
                </div>
                
                <button type="submit" name="update_profile" class="btn-primary">
                    <i class="fa-solid fa-floppy-disk"></i> Save Changes
                </button>
            </form>
        </div>

        <!-- Change Password Card -->
        <div class="dashboard-card">
            <div class="card-header-bar">
                <h3><i class="fa-solid fa-key card-icon"></i> Change Password</h3>
            </div>
            <form method="POST" action="index.php" class="dashboard-form">
                <div class="form-row">
                    <div class="form-group flex-1">
                        <label for="current_password"><i class="fa-solid fa-lock label-icon"></i> Current Password</label>
                        <div class="pwd-input-wrap">
                            <input type="password" id="current_password" name="current_password" placeholder="Enter current password" required autocomplete="current-password">
                            <button type="button" class="pwd-toggle-btn" onclick="togglePassword('current_password', this)" aria-label="Toggle password visibility">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="form-group flex-1">
                        <label for="new_password"><i class="fa-solid fa-shield-halved label-icon"></i> New Password</label>
                        <div class="pwd-input-wrap">
                            <input type="password" id="new_password" name="new_password" placeholder="Minimum 6 characters" required autocomplete="new-password">
                            <button type="button" class="pwd-toggle-btn" onclick="togglePassword('new_password', this)" aria-label="Toggle password visibility">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </div>
                    <div class="form-group flex-1">
                        <label for="confirm_password"><i class="fa-solid fa-check-double label-icon"></i> Confirm New Password</label>
                        <div class="pwd-input-wrap">
                            <input type="password" id="confirm_password" name="confirm_password" placeholder="Repeat new password" required autocomplete="new-password">
                            <button type="button" class="pwd-toggle-btn" onclick="togglePassword('confirm_password', this)" aria-label="Toggle password visibility">
                                <i class="fa-solid fa-eye"></i>
                            </button>
                        </div>
                    </div>
                </div>
                
                <button type="submit" name="change_password" class="btn-primary">
                    <i class="fa-solid fa-lock"></i> Update Password
                </button>
            </form>
        </div>

        <div class="dashboard-card">
            <div class="card-header-bar">
                <h3><i class="fa-solid fa-clock-rotate-left card-icon"></i> Order History</h3>
            </div>
            <?php if (empty($orders)): ?>
                <div class="empty-state-box">
                    <i class="fa-solid fa-box-open empty-icon"></i>
                    <p class="empty-text">You haven't placed any orders yet.</p>
                    <a href="<?php echo $base_path; ?>shop/shop.php" class="btn btn-outline">Explore Controllers</a>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="order-table">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Date</th>
                                <th>Total Amount</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($orders as $order): ?>
                                <?php 
                                    $status = strtolower($order['status'] ?? 'pending');
                                    $status_class = 'status-pending';
                                    if ($status === 'delivered' || $status === 'paid') $status_class = 'status-paid';
                                    elseif ($status === 'shipped') $status_class = 'status-shipped';
                                    elseif ($status === 'processing') $status_class = 'status-processing';
                                    elseif ($status === 'cancelled') $status_class = 'status-cancelled';
                                ?>
                                <tr>
                                    <td><span class="order-id-tag">#<?php echo htmlspecialchars($order['id']); ?></span></td>
                                    <td><?php echo date('M j, Y', strtotime($order['created_at'])); ?></td>
                                    <td class="price-cell">$<?php echo number_format($order['total_amount'], 2); ?></td>
                                    <td><span class="status-badge <?php echo $status_class; ?>"><?php echo htmlspecialchars($order['status']); ?></span></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </main>
</div>

<script>
function togglePassword(inputId, btn) {
    var input = document.getElementById(inputId);
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

<?php include '../global/footer.php'; ?>