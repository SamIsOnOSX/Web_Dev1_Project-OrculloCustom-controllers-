<?php
$role = $_SESSION['role'] ?? $_SESSION['user_role'] ?? 'user';

if (!isset($_SESSION['user_id']) || $role !== 'admin') {
    header("Location: ../index.php");
    exit();
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_user'])) {
    $target_id = (int)$_POST['user_id'];
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $role_val = $_POST['role'];
    
    $stmt = $pdo->prepare("UPDATE users SET username = :username, email = :email, role = :role WHERE id = :id");
    if ($stmt->execute(['username' => $username, 'email' => $email, 'role' => $role_val, 'id' => $target_id])) {
        $message = "User #$target_id successfully updated.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_order'])) {
    $order_id = (int)$_POST['order_id'];
    $status = $_POST['status'];
    
    $stmt = $pdo->prepare("UPDATE orders SET status = :status WHERE id = :id");
    if ($stmt->execute(['status' => $status, 'id' => $order_id])) {
        $message = "Order #$order_id status changed to '$status'.";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_stock'])) {
    $product_id = (int)$_POST['product_id'];
    $new_stock = (int)$_POST['new_stock'];
    updateProductStock($pdo, $product_id, $new_stock);
    $message = "Product stock successfully updated!";
}

$all_users = getUsers($pdo); 
$all_orders = getOrderHistory($pdo); 
$products = getAllProducts($pdo); 

include '../global/header.php';
?>

<link rel="stylesheet" href="<?php echo $base_path; ?>css/dashboard.css">

<main class="dashboard-container">
    <h2 class="dashboard-title">Admin Control Panel</h2>

    <?php if (!empty($message)): ?>
        <div class="alert-success"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <div class="dashboard-card" style="overflow-x: auto;">
        <h3>Inventory & Stock Management</h3>
        <table class="order-table">
            <thead>
                <tr>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Current Stock</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <form method="POST" action="index.php">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <td><?php echo htmlspecialchars($product['name']); ?></td>
                            <td>$<?php echo number_format($product['price'], 2); ?></td>
                            <td>
                                <input type="number" name="new_stock" value="<?php echo $product['stock']; ?>" min="0" style="width: 80px; padding: 6px; background: #2d2d2d; color: white; border: 1px solid #444; border-radius: 4px;">
                            </td>
                            <td>
                                <button type="submit" name="update_stock" style="padding: 6px 12px; background: #f59e0b; color: white; border: none; border-radius: 4px; cursor: pointer;">Update Stock</button>
                            </td>
                        </form>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="dashboard-card" style="overflow-x: auto;">
        <h3>Manage Users</h3>
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
                            <td><input type="text" name="username" value="<?php echo htmlspecialchars($u['username']); ?>" style="padding: 6px; width: 120px; background: #2d2d2d; color: white; border: 1px solid #444; border-radius: 4px;"></td>
                            <td><input type="email" name="email" value="<?php echo htmlspecialchars($u['email']); ?>" style="padding: 6px; width: 180px; background: #2d2d2d; color: white; border: 1px solid #444; border-radius: 4px;"></td>
                            <td>
                                <select name="role" style="padding: 6px; background: #2d2d2d; color: white; border: 1px solid #444; border-radius: 4px;">
                                    <option value="user" <?php echo $u['role'] === 'user' ? 'selected' : ''; ?>>User</option>
                                    <option value="admin" <?php echo $u['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                                </select>
                            </td>
                            <td><button type="submit" name="update_user" style="padding: 6px 12px; background: #3b82f6; color: white; border: none; border-radius: 4px; cursor: pointer;">Save</button></td>
                        </form>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <div class="dashboard-card" style="overflow-x: auto;">
        <h3>All Platform Orders</h3>
        <?php if (empty($all_orders)): ?>
            <p style="color: #a3a3a3;">No orders have been placed yet.</p>
        <?php else: ?>
            <table class="order-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>User ID</th>
                        <th>Date</th>
                        <th>Total Amount</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($all_orders as $order): ?>
                        <tr>
                            <form method="POST" action="index.php">
                                <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                <td>#<?php echo htmlspecialchars($order['id']); ?></td>
                                <td>#<?php echo htmlspecialchars($order['user_id']); ?></td>
                                <td><?php echo date('M j, Y', strtotime($order['created_at'])); ?></td>
                                <td>$<?php echo number_format($order['total_amount'], 2); ?></td>
                                <td>
                                    <select name="status" style="padding: 6px; background: #2d2d2d; color: white; border: 1px solid #444; border-radius: 4px;">
                                        <option value="Pending" <?php echo $order['status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="Processing" <?php echo $order['status'] === 'Processing' ? 'selected' : ''; ?>>Processing</option>
                                        <option value="Shipped" <?php echo $order['status'] === 'Shipped' ? 'selected' : ''; ?>>Shipped</option>
                                        <option value="Delivered" <?php echo $order['status'] === 'Delivered' ? 'selected' : ''; ?>>Delivered</option>
                                    </select>
                                </td>
                                <td><button type="submit" name="update_order" style="padding: 6px 12px; background: #10b981; color: white; border: none; border-radius: 4px; cursor: pointer;">Update</button></td>
                            </form>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</main>

<?php include '../global/footer.php'; ?>