<?php
// Database credentials
define("DB_HOST", "localhost");  // Change if your database is hosted remotely
define("DB_NAME", "SmallProject10"); // Your database name
define("DB_USER", "root"); // Your database username
define("DB_PASS", "+Ac2iBTTust="); // Your database password

// Set DSN (Data Source Name)
define("DB_DSN", "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=utf8mb4");

try {
    // Create a PDO instance
    $pdo = new PDO(DB_DSN, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false
    ]);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
?>
