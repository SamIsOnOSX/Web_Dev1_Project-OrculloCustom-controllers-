<?php
// register.php
session_start();
require 'database/db.php'; //lowkey just forgot that T-T

$errors = [];
$email = ''; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $email = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Enter a valid email address.";
    }

    if (empty($password)) {
        $errors[] = "Password is required.";
    } elseif (strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters.";
    }

    if (empty($errors)) {
        $email_safe = htmlspecialchars($email);
        
        // Check if email already exists
        $check_sql = "SELECT id FROM users WHERE email = :email";
        $check_stmt = $pdo->prepare($check_sql);
        $check_stmt->bindValue(':email', $email_safe);
        $check_stmt->execute();
        
        if ($check_stmt->rowCount() > 0) {
            $errors[] = "This email is already registered.";
        } else {
            // Encrypt password and insert new user
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $sql = "INSERT INTO users (email, password) VALUES (:email, :password)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':email', $email_safe);
            $stmt->bindValue(':password', $hashed_password);
            
            if ($stmt->execute()) {
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
    <title>Register - Orcullo Custom Controller</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/login.css">
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
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
            </div>
            
            <div class="input-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" class="login-btn">REGISTER</button>
        </form>
        
        <a href="login.php" class="register-link">Already have an account? Login here.</a>
    </div>

</body>
</html>