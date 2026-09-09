<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require '../database/db.php'; 
require '../database/users.php';
require 'validation.php';

$errors = [];
$username = '';
$email = ''; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $result = validateRegisterPayload($_POST);
    $errors = $result['errors'];
    $username = $result['data']['username'] ?? '';
    $email = $result['data']['email'] ?? '';

    if (empty($errors)) {
        // Check if email or username already exists
        $existing_user = getUserByEmailOrUsername($pdo, $email);
        
        if ($existing_user) {
            $errors[] = "This email or username is already registered.";
        } else {
            $hashed_password = password_hash($result['data']['password'], PASSWORD_DEFAULT);
            
            if (createUser($pdo, $username, $email, $hashed_password)) {
                header("Location: login.php");
                exit();
            } else {
                $errors[] = "Registration failed. Please try again.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - Orcullo Custom Controllers</title>
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

        <h2>Create Account</h2>
        <p class="auth-subtitle">Join the Orcullo community to customize and track orders.</p>

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

        <form method="POST" action="register.php" class="auth-form">
            <div class="input-group">
                <label for="username">
                    <i class="fa-solid fa-user input-icon"></i> Username
                </label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" placeholder="Choose a username" required autocomplete="username">
            </div>
            
            <div class="input-group">
                <label for="email">
                    <i class="fa-solid fa-envelope input-icon"></i> Email Address
                </label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" placeholder="yourname@domain.com" required autocomplete="email">
            </div>
            
            <div class="input-group password-group">
                <label for="password">
                    <i class="fa-solid fa-lock input-icon"></i> Password
                </label>
                <div class="password-input-wrapper">
                    <input type="password" id="password" name="password" placeholder="At least 6 characters" required autocomplete="new-password">
                    <button type="button" id="togglePassword" class="toggle-password-btn" aria-label="Toggle password visibility">
                        <i class="fa-regular fa-eye" id="togglePasswordIcon"></i>
                    </button>
                </div>
            </div>

            <button type="submit" class="login-btn">
                <span>Create Account</span> <i class="fa-solid fa-arrow-right"></i>
            </button>
        </form>

        <div class="auth-card-footer">
            <p>Already have an account? <a href="login.php" class="register-link">Sign in here</a></p>
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