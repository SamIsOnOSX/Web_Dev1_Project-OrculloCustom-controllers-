<?php
require __DIR__ . '/db.php';

try {
    $pdo->exec("ALTER TABLE orders
        ADD COLUMN IF NOT EXISTS payment_method ENUM('cod','online') NOT NULL DEFAULT 'cod' AFTER total_amount,
        ADD COLUMN IF NOT EXISTS payment_proof VARCHAR(500) NULL DEFAULT NULL AFTER payment_method
    ");
    echo "<h2 style='font-family:monospace;color:green;'>Migration successful! Columns added to orders table.</h2>";
    echo "<p style='font-family:monospace;'>You can now delete this file.</p>";
} catch (PDOException $e) {
    echo "<h2 style='font-family:monospace;color:red;'>Migration failed: " . htmlspecialchars($e->getMessage()) . "</h2>";
}
?>
