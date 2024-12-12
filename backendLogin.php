<?php
session_start(); // Start the session

require_once 'db_config.php'; // Ensure $pdo is defined

ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture user input from the form using $_POST
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    // Check if username and password are empty
    if (empty($username) || empty($password)) {
        $_SESSION['error_message'] = "Username and Password are required.";
        header("Location: loginPage.php");
        exit();
    }

    // Prepare the SQL query to fetch the user record
    $stmt = $pdo->prepare("SELECT * FROM user WHERE user_name = :username LIMIT 1");
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();

    // Check if the user exists
    if ($user = $stmt->fetch(PDO::FETCH_ASSOC)) {
        // Verify the password (hashed in the database)
        if (password_verify($password, $user['password'])) {
            // Password matches, start the session for the user
            $_SESSION['user_id'] = $user['user_id']; // Use the user's ID or another unique identifier
            $_SESSION['user_name'] = $user['user_name']; // Store the username for display
            $_SESSION['error_message'] = "anayayre huhuhu pota"; // Clear any previous error messages

            // Debug: Check if session variables are set correctly
            error_log("User logged in: " . $user['user_name']);
            error_log("Session user_id: " . $_SESSION['user_id']);
            error_log("Session user_name: " . $_SESSION['user_name']);

            // Redirect to the landing page or dashboard
            header("Location: landingPage.php"); 
            exit();
        } else {
            // Password does not match
            $_SESSION['error_message'] = "Incorrect password.";
            header("Location: loginPage.php"); // Redirect back to login
            exit();
        }
    } else {
        // User not found
        $_SESSION['error_message'] = "Username does not exist.";
        header("Location: loginPage.php"); // Redirect back to login
        exit();
    }
}
?>
