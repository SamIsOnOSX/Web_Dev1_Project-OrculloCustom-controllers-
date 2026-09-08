<?php
// Ensure variables exist even if the file is loaded directly
$products = $products ?? [];
$users = $users ?? [];

if (!isset($pdo)) {
    // If someone tries to open index.php directly, redirect them to dashboard.php
    header("Location: dashboard.php");
    exit();
}
include '../global/header.php';
?>

<main style="max-width: 1000px; margin: 40px auto; padding: 20px; color: white; font-family: 'Inter', sans-serif;">
    <h2 style="margin-bottom: 10px;">Admin Dashboard</h2>
    <p style="color: #a3a3a3; margin-bottom: 30px;">Welcome back, <?php echo htmlspecialchars($_SESSION['username']); ?>! Manage your store inventory and user permissions below.</p>

    <?php if (!empty($message)): ?>
        <div style="background: #10b981; color: white; padding: 12px; border-radius: 6px; margin-bottom: 20px;">
            <?php echo htmlspecialchars($message); ?>
        </div>
    <?php endif; ?>

    <!-- Stock Management Section -->
    <section style="background: #1a1a1a; padding: 20px; border-radius: 8px; margin-bottom: 40px;">
        <h3 style="margin-top: 0; margin-bottom: 20px; border-bottom: 1px solid #333; padding-bottom: 10px;">Inventory & Stock Management</h3>
        <table style="width: 100%; border-collapse: collapse;">
            <tr style="border-bottom: 2px solid #333; text-align: left;">
                <th style="padding: 10px;">Product Name</th>
                <th style="padding: 10px;">Price</th>
                <th style="padding: 10px;">Current Stock</th>
                <th style="padding: 10px; text-align: center;">Action</th>
            </tr>
            <?php foreach ($products as $product): ?>
                <tr style="border-bottom: 1px solid #222;">
                    <td style="padding: 12px;"><?php echo htmlspecialchars($product['name']); ?></td>
                    <td style="padding: 12px;">$<?php echo number_format($product['price'], 2); ?></td>
                    <form method="POST" action="dashboard.php">
                        <td style="padding: 12px;">
                            <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                            <input type="number" name="new_stock" value="<?php echo $product['stock']; ?>" min="0" style="width: 80px; padding: 6px; background: #222; border: 1px solid #444; color: white; border-radius: 4px;">
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <button type="submit" name="update_stock" style="padding: 6px 14px; background: white; color: black; border: none; font-weight: bold; border-radius: 4px; cursor: pointer;">Update Stock</button>
                        </td>
                    </form>
                </tr>
            <?php endforeach; ?>
        </table>
    </section>

    <!-- User Account Management Section -->
    <section style="background: #1a1a1a; padding: 20px; border-radius: 8px;">
        <h3 style="margin-top: 0; margin-bottom: 20px; border-bottom: 1px solid #333; padding-bottom: 10px;">User Account Management</h3>
        <table style="width: 100%; border-collapse: collapse;">
            <tr style="border-bottom: 2px solid #333; text-align: left;">
                <th style="padding: 10px;">Username</th>
                <th style="padding: 10px;">Email</th>
                <th style="padding: 10px;">Role</th>
                <th style="padding: 10px; text-align: center;">Action</th>
            </tr>
            <?php foreach ($users as $u): ?>
                <tr style="border-bottom: 1px solid #222;">
                    <td style="padding: 12px;"><?php echo htmlspecialchars($u['username']); ?></td>
                    <td style="padding: 12px; color: #a3a3a3;"><?php echo htmlspecialchars($u['email']); ?></td>
                    <form method="POST" action="dashboard.php">
                        <td style="padding: 12px;">
                            <input type="hidden" name="user_id" value="<?php echo $u['id']; ?>">
                            <select name="role" style="padding: 6px; background: #222; border: 1px solid #444; color: white; border-radius: 4px;">
                                <option value="customer" <?php echo $u['role'] === 'customer' ? 'selected' : ''; ?>>Customer</option>
                                <option value="admin" <?php echo $u['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                            </select>
                        </td>
                        <td style="padding: 12px; text-align: center;">
                            <button type="submit" name="update_role" style="padding: 6px 14px; background: #3b82f6; color: white; border: none; font-weight: bold; border-radius: 4px; cursor: pointer;">Save Role</button>
                        </td>
                    </form>
                </tr>
            <?php endforeach; ?>
        </table>
    </section>
</main>

<?php include '../global/footer.php'; ?>