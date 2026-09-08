<!-- All Platform Orders Card -->
<div class="dashboard-card table-responsive">
    <h3><i class="fa-solid fa-receipt card-icon"></i>All Platform Orders</h3>
    <?php if (empty($all_orders)): ?>
        <p class="empty-text">No orders have been placed yet.</p>
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
                                <select name="status" class="table-select">
                                    <option value="Pending" <?php echo $order['status'] === 'Pending' ? 'selected' : ''; ?>>Pending</option>
                                    <option value="Processing" <?php echo $order['status'] === 'Processing' ? 'selected' : ''; ?>>Processing</option>
                                    <option value="Shipped" <?php echo $order['status'] === 'Shipped' ? 'selected' : ''; ?>>Shipped</option>
                                    <option value="Delivered" <?php echo $order['status'] === 'Delivered' ? 'selected' : ''; ?>>Delivered</option>
                                </select>
                            </td>
                            <td><button type="submit" name="update_order" class="btn-update-order">Update</button></td>
                        </form>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
