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

<div class="dashboard-page-wrapper">
    <main class="dashboard-container">
        <div class="dashboard-header">
            <span class="user-role-badge admin-badge"><i class="fa-solid fa-shield-halved"></i> Administrator Panel</span>
            <h1>Admin <span class="username-highlight">Control Center</span></h1>
            <p>Manage product catalog, stock inventory, user accounts, and platform orders.</p>
        </div>

        <!-- Feedback Alerts -->
        <?php if (!empty($message)): ?>
            <div class="alert-success"><i class="fa-solid fa-circle-check"></i> <span><?php echo htmlspecialchars($message); ?></span></div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="alert-error"><i class="fa-solid fa-circle-exclamation"></i> <span><?php echo htmlspecialchars($error); ?></span></div>
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
</div>

<?php include '../global/footer.php'; ?>