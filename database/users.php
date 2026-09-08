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
?>