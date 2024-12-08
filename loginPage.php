<?php
session_start(); // Start the session

require_once 'db_config.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if (empty($username) || empty($password)) {
        $_SESSION['error_message'] = "Username and password are required.";
        header("Location: loginPage.php");
        exit();
    }

    // Query the database for the user
    $stmt = $pdo->prepare("SELECT * FROM user WHERE user_name = :username LIMIT 1");
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user) {
        // Check if the password is correct
        if (password_verify($password, $user['password'])) {
            // Set session variables after successful login
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['user_name'];

            // Log session data for debugging
            error_log("Session set for user_id: " . $_SESSION['user_id']);
            error_log("Session set for user_name: " . $_SESSION['user_name']);

            // Redirect to the landing page
            header("Location: landingPage.php");
            exit();
        } else {
            $_SESSION['error_message'] = "Incorrect password.";
            header("Location: loginPage.php");
            exit();
        }
    } else {
        $_SESSION['error_message'] = "Username not found.";
        header("Location: loginPage.php");
        exit();
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quick Recall - Login</title>
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
    .login-container { background-color: #f9f9f9; padding: 20px; }
    .card { border-radius: 12px; }
    .card h3 { font-weight: 600; color: #333; }
    .btn-primary-login {
        color: #fefefe; background-color: #086942; border: none; 
        transition: background-color 0.3s ease, transform 0.3s ease;
    }
    .btn-primary-login:hover {
        color: #fefefe; background-color: #065533; transform: scale(1.02);
    }
    a { color: #086942; text-decoration: none; }
    a:hover { color: #065533; text-decoration: none; }
</style>

<body>

    <div class="login-container d-flex align-items-center justify-content-center vh-100">
        <div class="card shadow-lg border-0 p-4" style="max-width: 400px; width: 100%;">
            <div class="text-center mb-4">
                <h3 class="mt-3">Login</h3>
            </div>

            <form id="loginForm" method="POST" action="backendLogin.php" onsubmit="return validateForm()">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Enter your password" required>
                </div>
                <button type="submit" class="btn btn-primary-login w-100">Login</button>
            </form>

            <div class="text-center mt-3">
                <p class="small">Don't have an account? <a href="./signupPage.php">Sign Up</a></p>
            </div>
        </div>
    </div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>

<script>
function validateForm() {
    const username = document.getElementById("username").value;
    const password = document.getElementById("password").value;

    if (username === "" || password === "") {
        alert("Please fill in both fields.");
        return false; // Prevent form submission
    }

    return true; // Allow form submission
}
</script>

</body>
</html>
 