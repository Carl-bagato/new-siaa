<?php
require_once 'db_config.php'; // Use your existing DB configuration

try {
    $sql = "SELECT user_id, password FROM user";
    $stmt = $pdo->query($sql);

    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $hashedPassword = password_hash($row['password'], PASSWORD_DEFAULT);
        $updateStmt = $pdo->prepare("UPDATE user SET password = :password WHERE user_id = :user_id");
        $updateStmt->execute(['password' => $hashedPassword, 'user_id' => $row['user_id']]);
    }

    echo "Passwords updated successfully.";
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
