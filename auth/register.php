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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/login.css">
</head>
<body class="login-body">
    <div class="login-card">
        <h2>Create Account</h2>
        <?php if (!empty($errors)): ?>
            <div class="error-box">
                <?php foreach ($errors as $e): ?>
                    <p>⚠ <?php echo $e; ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="register.php">
            <div class="input-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>" required>
            </div>
            
            <div class="input-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
            </div>
            
            <div class="input-group" style="position: relative;">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required style="width: 100%; padding-right: 40px;">
                <button type="button" id="togglePassword" style="position: absolute; right: 10px; top: 32px; background: none; border: none; color: #aaa; cursor: pointer; font-size: 0.85rem;">Show</button>
            </div>

            <button type="submit" class="login-btn">REGISTER</button>
        </form>
        <a href="login.php" class="register-link">Already have an account? Login here.</a>
    </div>

    <!-- Password visibility toggle script -->
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