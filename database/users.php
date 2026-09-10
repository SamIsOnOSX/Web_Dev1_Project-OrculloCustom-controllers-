<?php
function getUserByEmailOrUsername($pdo, $identifier) {
    $sql = "SELECT id, username, email, password, role FROM users WHERE email = :identifier OR username = :identifier";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':identifier', $identifier);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function createUser($pdo, $username, $email, $hashed_password) {
    $sql = "INSERT INTO users (username, email, password) VALUES (:username, :email, :password)";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':username', $username);
    $stmt->bindValue(':email', $email);
    $stmt->bindValue(':password', $hashed_password);
    return $stmt->execute();
}

function getAllUsers($pdo) {
    $sql = "SELECT id, username, email, role, created_at FROM users ORDER BY id DESC";
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function updateUserRole($pdo, $user_id, $role) {
    $sql = "UPDATE users SET role = :role WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':role', $role);
    $stmt->bindValue(':id', $user_id, PDO::PARAM_INT);
    return $stmt->execute();
}

function updateUserPassword($pdo, $user_id, $hashed_password) {
    $sql = "UPDATE users SET password = :password WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':password', $hashed_password);
    $stmt->bindValue(':id', $user_id, PDO::PARAM_INT);
    return $stmt->execute();
}
?>