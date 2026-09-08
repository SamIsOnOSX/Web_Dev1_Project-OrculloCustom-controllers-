<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require '../database/db.php'; 
require '../database/users.php';
require 'validation.php';

$errors = [];
$identifier = ''; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = validateLoginPayload($_POST);
    $errors = $result['errors'];
    $identifier = $result['data']['identifier'];

    if (empty($errors)) {
        $user = getUserByEmailOrUsername($pdo, $identifier);

        if ($user && password_verify($result['data']['password'], $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['user_role'] = $user['role'] ?? 'customer';
            
            header("Location: ../index.php");
            exit();
        } else {
            $errors[] = "Invalid username/email or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Orcullo Custom Controllers</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/login.css">
</head>
<body class="login-body">
    <div class="login-card">
        <h2>Welcome Back</h2>
        <?php if (!empty($errors)): ?>
            <div class="error-box">
                <?php foreach ($errors as $e): ?>
                    <p>⚠ <?php echo $e; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <div class="input-group">
                <label for="identifier">Username or Email Address</label>
                <input type="text" id="identifier" name="identifier" value="<?php echo htmlspecialchars($identifier); ?>" required>
            </div>
            
            <div class="input-group" style="position: relative;">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required style="width: 100%; padding-right: 40px;">
                <!-- Simple toggle button for show/hide password -->
                <button type="button" id="togglePassword" style="position: absolute; right: 10px; top: 32px; background: none; border: none; color: #aaa; cursor: pointer; font-size: 0.85rem;">Show</button>
            </div>

            <button type="submit" class="login-btn">LOGIN</button>
        </form>
        <a href="register.php" class="register-link">Don't have an account? Register here.</a>
    </div>

    <!-- Quick script to control password visibility -->
    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', function () {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.textContent = type === 'password' ? 'Show' : 'Hide';
        });
    </script>
</body>
</html>