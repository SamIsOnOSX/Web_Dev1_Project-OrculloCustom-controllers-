<?php
$user_id = $_SESSION['user_id'];
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $new_username = trim($_POST['username']);
    $new_email = trim($_POST['email']);
    
    $stmt = $pdo->prepare("UPDATE users SET username = :username, email = :email WHERE id = :id");
    if ($stmt->execute(['username' => $new_username, 'email' => $new_email, 'id' => $user_id])) {
        $message = "Profile successfully updated.";
        $_SESSION['username'] = $new_username; 
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
                                    if ($status === 'delivered') $status_class = 'status-delivered';
                                    elseif ($status === 'shipped') $status_class = 'status-shipped';
                                    elseif ($status === 'processing') $status_class = 'status-processing';
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

<?php include '../global/footer.php'; ?>