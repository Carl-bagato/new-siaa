<?php

return [
    'class' => 'yii\db\Connection', // Specifies that we are using Yii2's built-in DB connection class
    'dsn' => 'mysql:host=localhost;dbname=siaadatabase;charset=utf8mb4', // DSN string for MySQL connection
    'username' => 'root', // Database username
    'password' => '1802', // Database password
    'charset' => 'utf8mb4', // Charset to use for the connection
    'emulatePrepare' => true, // Enable prepare emulation for better performance
    'enableSchemaCache' => true, // Enable schema cache for better performance in production
    'schemaCacheDuration' => 3600, // Cache the schema for one hour
    'schemaCache' => 'cache', // Use the cache component for schema caching
];

$connection = Yii::$app->db;
if ($connection->isActive) {
    echo "Database connection is successful!";
} else {
    echo "Database connection failed.";
}

?>