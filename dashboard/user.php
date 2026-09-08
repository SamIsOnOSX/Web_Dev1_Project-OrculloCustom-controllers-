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

<main class="dashboard-container">
    <h2 class="dashboard-title">Welcome, <?php echo htmlspecialchars($user['username']); ?>!</h2>

    <?php if (!empty($message)): ?>
        <div class="alert-success"><?php echo $message; ?></div>
    <?php endif; ?>

    <div class="dashboard-card">
        <h3>Account Information</h3>
        <form method="POST" action="index.php">
            <div class="form-group">
                <label>Username</label>
                <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>
            
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
            </div>
            
            <button type="submit" name="update_profile" class="btn-primary">Save Changes</button>
        </form>
    </div>

    <div class="dashboard-card">
        <h3>Order History</h3>
        <?php if (empty($orders)): ?>
            <p style="color: #a3a3a3;">You haven't placed any orders yet.</p>
        <?php else: ?>
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
                        <tr>
                            <td>#<?php echo htmlspecialchars($order['id']); ?></td>
                            <td><?php echo date('M j, Y', strtotime($order['created_at'])); ?></td>
                            <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                            <td><span class="status-badge"><?php echo htmlspecialchars($order['status']); ?></span></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</main>

<?php include '../global/footer.php'; ?>