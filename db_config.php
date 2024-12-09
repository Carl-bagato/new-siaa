<?php
$servername = "localhost";
$username = "root";
$password = "1802";
$dbname = "siaadatabase";
$charset = 'utf8mb4';

// Set up the DSN (Data Source Name)
$dsn = "mysql:host=$servername;dbname=$dbname;charset=$charset";

// Function to check database connection
function checkDbConnection($dsn, $username, $password) {
    try {
        // Attempt to create the PDO instance and connect to the database
        $pdo = new PDO($dsn, $username, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        // Test if the connection is successful by querying a simple table or row
        $stmt = $pdo->query("SELECT 1");
        if ($stmt) {
            //echo "Database connection is successful!";
        }
        return $pdo;
    } catch (PDOException $e) {
        // Output detailed error message if connection fails
        echo "Connection failed: " . $e->getMessage();
        return null; // Return null if connection failed
    }
}

// Call the function to check the connection
$pdo = checkDbConnection($dsn, $username, $password);
?>
