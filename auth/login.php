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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Space+Grotesk:wght@500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/login.css">
</head>
<body class="login-body">
    <a href="../index.php" class="auth-back-link">
        <i class="fa-solid fa-arrow-left"></i> Back to Store
    </a>

    <div class="login-card">
        <div class="auth-logo">
            <img src="../Assets/LogoOnly.png" alt="Orcullo Logo" class="auth-logo-img">
            <img src="../Assets/TextOnly.png" alt="Orcullo Custom Controller" class="auth-text-logo">
        </div>

        <h2>Welcome Back</h2>
        <p class="auth-subtitle">Sign in to access your dashboard and saved builds.</p>

        <?php if (!empty($errors)): ?>
            <div class="error-box">
                <i class="fa-solid fa-circle-exclamation error-icon"></i>
                <div class="error-messages">
                    <?php foreach ($errors as $e): ?>
                        <p><?php echo htmlspecialchars($e); ?></p>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <form method="POST" action="login.php" class="auth-form">
            <div class="input-group">
                <label for="identifier">
                    <i class="fa-solid fa-user input-icon"></i> Username or Email
                </label>
                <input type="text" id="identifier" name="identifier" value="<?php echo htmlspecialchars($identifier); ?>" placeholder="e.g. player1 or player@domain.com" required autocomplete="username">
            </div>
            
            <div class="input-group password-group">
                <label for="password">
                    <i class="fa-solid fa-lock input-icon"></i> Password
                </label>
                <div class="password-input-wrapper">
                    <input type="password" id="password" name="password" placeholder="Enter your password" required autocomplete="current-password">
                    <button type="button" id="togglePassword" class="toggle-password-btn" aria-label="Toggle password visibility">
                        <i class="fa-regular fa-eye" id="togglePasswordIcon"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="login-btn">
                <span>Sign In</span> <i class="fa-solid fa-arrow-right"></i>
            </button>
        </form>

        <div class="auth-card-footer">
            <p>Don't have an account? <a href="register.php" class="register-link">Create one now</a></p>
        </div>
    </div>

    <!-- Password visibility toggle script -->
    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        const icon = document.querySelector('#togglePasswordIcon');

        if (togglePassword && password) {
            togglePassword.addEventListener('click', function () {
                const isPassword = password.getAttribute('type') === 'password';
                password.setAttribute('type', isPassword ? 'text' : 'password');
                if (icon) {
                    icon.classList.toggle('fa-eye', !isPassword);
                    icon.classList.toggle('fa-eye-slash', isPassword);
                }
            });
        }
    </script>
</body>
</html>