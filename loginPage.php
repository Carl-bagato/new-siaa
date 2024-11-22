<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quick Recall - Login</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <!-- <link rel="stylesheet" type="text/css" href="loginPage.css"> -->
</head>

<style>
    body {
    font-family: 'Inter', sans-serif;
    background-color: #f9f9f9;
    margin: 0;
    color: #262626;
}

/* Login Container */
.login-container {
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
.btn-primary-login {
    color: #fefefe;
    background-color: #086942;
    border: none;
    transition: background-color 0.3s ease, transform 0.3s ease;
}

.btn-primary-login:hover {
    color: #fefefe;
    background-color: #065533;
    transform: scale(1.02);
}

/* Links */
a {
    color: #086942;
    text-decoration: none;
}

a:hover {
    color: #065533;
    text-decoration: none;
}

</style>

<body>

<!--login-->
<div class="login-container d-flex align-items-center justify-content-center vh-100">
    <div class="card shadow-lg border-0 p-4" style="max-width: 400px; width: 100%;">
        <div class="text-center mb-4">
            <h3 class="mt-3">Login</h3>
        </div>
        <form>
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="username" class="form-control" id="username" placeholder="Enter your username" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="btn btn-primary-login w-100">Login</button>
        </form>
        <div class="text-center mt-3">
            <p class="small">Don't have an account? <a href="#signup">Sign Up</a></p>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>
