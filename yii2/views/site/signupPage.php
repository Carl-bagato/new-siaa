<?php
session_start();
require_once 'db_config.php'; // Ensure $pdo is defined

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Capture user input from the form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Hash the password before storing it
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    // Prepare the SQL query to insert the user record
    $stmt = $pdo->prepare("INSERT INTO user (user_name, password) VALUES (:username, :password)");
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);

    // Execute the query to insert the new user
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Account created successfully!";
        header("Location: loginPage.php");
        exit();
    } else {
        $_SESSION['error_message'] = "Error creating account.";
        header("Location: signupPage.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quick Recall - Sign Up</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
</head>

<style>
    body {
        font-family: 'Inter', sans-serif;
        background-color: #f9f9f9;
        margin: 0;
        color: #262626;
    }

    /* Sign Up Container */
    .signup-container {
        background-color: #f9f9f9;
        padding: 20px;
    }

    .card {
        border-radius: 12px;
    }

    .card h3 {
        font-weight: 600;
        color: #333;
    }

    /* Buttons */
    .btn-primary-signup {
        color: #fefefe;
        background-color: #73d4eb;
        border: none;
        transition: background-color 0.3s ease, transform 0.3s ease;
    }

    .btn-primary-signup:hover {
        color: #fefefe;
        background-color: #58b9d0;
        transform: scale(1.02);
    }

    /* Links */
    a {
        color: #73d4eb;
        text-decoration: none;
    }

    a:hover {
        color: #58b9d0;
        text-decoration: none;
    }
</style>

<body>

<!-- Sign Up -->
<div class="signup-container d-flex align-items-center justify-content-center vh-100">
    <div class="card shadow-lg border-0 p-4" style="max-width: 400px; width: 100%;">
        <div class="text-center mb-4">
            <h3 class="mt-3">Sign Up</h3>
        </div>
        <!-- Ensure the form uses POST method -->
        <form method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Create a password" required>
            </div>
            <div class="mb-3">
                <label for="confirmPassword" class="form-label">Confirm Password</label>
                <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Confirm your password" required>
            </div>
            <button type="submit" class="btn btn-primary-signup w-100">Sign Up</button>
        </form>
        <div class="text-center mt-3">
            <p class="small">Already have an account? <a href="./loginPage.php">Login</a></p>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
