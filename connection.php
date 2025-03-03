<?php
$host = 'localhost';
$db = 'resume';
$username = 'root';  // Adjust accordingly
$password = '';  // Adjust accordingly

try {
    // Establish the database connection
    $pdo = new PDO("mysql:host=$host;dbname=$db", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
