<?php
/**
 * dashboard/admin.php
 * Main Admin Control Panel controller and view orchestrator.
 */

// 1. Authorization: Only allow authenticated admins
$role = $_SESSION['role'] ?? $_SESSION['user_role'] ?? 'user';
if (!isset($_SESSION['user_id']) || $role !== 'admin') {
    header("Location: ../index.php");
    exit();
}

// 2. Process any submitted admin actions (POST requests)
require_once __DIR__ . '/admin_actions.php';
$action_feedback = handleAdminPostActions($pdo);
$message         = $action_feedback['message'];
$error           = $action_feedback['error'];

// 3. Fetch data for the dashboard views
$available_assets = getAvailableAssets();
$products         = getAllProducts($pdo);
$all_users        = getUsers($pdo);
$all_orders       = getOrderHistory($pdo);

// 4. Render Header
include '../global/header.php';
?>

<!-- Dedicated Dashboard Stylesheet -->
<link rel="stylesheet" href="<?php echo $base_path; ?>css/dashboard.css">

<main class="dashboard-container">
    <h2 class="dashboard-title">Admin Control Panel</h2>

    <!-- Feedback Alerts -->
    <?php if (!empty($message)): ?>
        <div class="alert-success"><i class="fa-solid fa-circle-check"></i> <?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <?php if (!empty($error)): ?>
        <div class="alert-error"><i class="fa-solid fa-circle-exclamation"></i> <?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>

    <!-- 1. Create New Product Card -->
    <?php include __DIR__ . '/components/create_product_card.php'; ?>

    <!-- 2. Inventory & Stock Management -->
    <?php include __DIR__ . '/components/inventory_card.php'; ?>

    <!-- 3. User Management -->
    <?php include __DIR__ . '/components/users_card.php'; ?>

    <!-- 4. Platform Orders -->
    <?php include __DIR__ . '/components/orders_card.php'; ?>
</main>

<?php include '../global/footer.php'; ?>