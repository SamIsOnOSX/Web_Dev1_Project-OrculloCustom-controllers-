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
                    <th>Customer</th>
                    <th>Date</th>
                    <th>Total Amount</th>
                    <th>Payment</th>
                    <th>Proof</th>
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
                            <td><?php echo htmlspecialchars($order['username'] ?? 'Unknown'); ?></td>
                            <td><?php echo date('M j, Y', strtotime($order['created_at'])); ?></td>
                            <td>$<?php echo number_format($order['total_amount'], 2); ?></td>

                            <!-- Payment Method Badge -->
                            <td>
                                <?php
                                $method = strtolower($order['payment_method'] ?? 'cod');
                                if ($method === 'online'):
                                ?>
                                    <span class="payment-badge payment-badge--online">
                                        <i class="fa-solid fa-qrcode"></i> Online
                                    </span>
                                <?php else: ?>
                                    <span class="payment-badge payment-badge--cod">
                                        <i class="fa-solid fa-money-bill-wave"></i> COD
                                    </span>
                                <?php endif; ?>
                            </td>

                            <!-- Proof of Payment -->
                            <td class="proof-cell">
                                <?php
                                $proof = $order['payment_proof'] ?? '';
                                if (!empty($proof)):
                                    $proof_url = '../' . htmlspecialchars($proof);
                                    $ext       = strtolower(pathinfo($proof, PATHINFO_EXTENSION));
                                    if ($ext === 'pdf'):
                                ?>
                                    <a href="<?php echo $proof_url; ?>" target="_blank" class="proof-link proof-link--pdf">
                                        <i class="fa-solid fa-file-pdf"></i> View PDF
                                    </a>
                                <?php else: ?>
                                    <a href="<?php echo $proof_url; ?>" target="_blank" class="proof-link proof-link--img">
                                        <img src="<?php echo $proof_url; ?>" alt="Payment proof for order #<?php echo $order['id']; ?>" class="proof-thumbnail">
                                        <span>View</span>
                                    </a>
                                <?php endif; ?>
                                <?php else: ?>
                                    <span class="proof-none">&mdash;</span>
                                <?php endif; ?>
                            </td>

                            <td>
                                <select name="status" class="table-select">
                                    <option value="pending"   <?php echo strtolower($order['status']) === 'pending'   ? 'selected' : ''; ?>>Pending</option>
                                    <option value="paid"      <?php echo strtolower($order['status']) === 'paid'      ? 'selected' : ''; ?>>Paid</option>
                                    <option value="shipped"   <?php echo strtolower($order['status']) === 'shipped'   ? 'selected' : ''; ?>>Shipped</option>
                                    <option value="delivered" <?php echo strtolower($order['status']) === 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                    <option value="cancelled" <?php echo strtolower($order['status']) === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
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
