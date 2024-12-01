<?php

session_start();

require_once 'db_config.php'; 

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_name = $_POST['username'];
    $user_password = $_POST['password'];

    // Query to check if the user exists in the database
    $sql = "SELECT user_id, password FROM user WHERE user_name = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $user_name);
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if the user exists
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Verify password (note: in a real app, you would hash passwords and use password_verify)
        if ($user_password === $row['password']) {
            // Password is correct, start a session and redirect
            $_SESSION['user_id'] = $row['user_id'];
            $_SESSION['user_name'] = $user_name;
            header("Location: landingPage.php");
            exit();
        } else {
            // Incorrect password
            echo "<script>alert('Incorrect password.');</script>";
        }
    } else {
        // User not found
        echo "<script>alert('User not found.');</script>";
    }

    // Close the prepared statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>