

<?php
// Initialize database connection settings
$host = "localhost:3306";
$username = "root";
$password = "";
$database = "demo";

// Create connection with error handling
try {
    $connection = mysqli_connect($host, $username, $password);
    if (!$connection) {
        throw new Exception("Connection failed: " . mysqli_connect_error());
    }
    
    // Select database
    $db = mysqli_select_db($connection, $database);
    if (!$db) {
        throw new Exception("Database selection failed: " . mysqli_error($connection));
    }

    // Set character set to prevent SQL injection
    mysqli_set_charset($connection, 'utf8mb4');
} catch (Exception $e) {
    die("Error: " . $e->getMessage());
}
?>
