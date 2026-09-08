<!-- Inventory & Stock Management Card -->
<div class="dashboard-card table-responsive">
    <h3><i class="fa-solid fa-boxes-stacked card-icon"></i>Inventory & Stock Management</h3>
    <?php if (empty($products)): ?>
        <p class="empty-text">No products found in the catalog.</p>
    <?php else: ?>
        <table class="order-table">
            <thead>
                <tr>
                    <th class="col-image">Image</th>
                    <th>Product Name</th>
                    <th>Price</th>
                    <th>Current Stock</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($products as $product): ?>
                    <tr>
                        <td>
                            <?php if (!empty($product['image_path'])): ?>
                                <img src="<?php echo $base_path . htmlspecialchars($product['image_path']); ?>" alt="<?php echo htmlspecialchars($product['name']); ?>" class="product-thumb-img">
                            <?php else: ?>
                                <div class="product-thumb-placeholder">
                                    <i class="fa-solid fa-image"></i>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong><?php echo htmlspecialchars($product['name']); ?></strong>
                            <?php if (!empty($product['description'])): ?>
                                <div class="product-desc-preview" title="<?php echo htmlspecialchars($product['description']); ?>">
                                    <?php echo htmlspecialchars($product['description']); ?>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td>$<?php echo number_format($product['price'], 2); ?></td>
                        <td>
                            <form method="POST" action="index.php" class="stock-form">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <input type="number" name="new_stock" value="<?php echo $product['stock']; ?>" min="0" class="input-stock">
                                <button type="submit" name="update_stock" class="btn-update-stock">Update</button>
                            </form>
                        </td>
                        <td class="text-right">
                            <form method="POST" action="index.php" class="inline-form" onsubmit="return confirm('Are you sure you want to delete \'<?php echo htmlspecialchars(addslashes($product['name'])); ?>\'?');">
                                <input type="hidden" name="product_id" value="<?php echo $product['id']; ?>">
                                <button type="submit" name="delete_product" class="btn-delete">
                                    <i class="fa-solid fa-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>
