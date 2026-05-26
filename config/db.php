<?php

// Database credentials
$db_host = "zephyr.proxy.rlwy.net";
$db_port = "48650";
$db_name = "todo_database";
$db_user = "root";
$db_password = "ofhUhlYxnWunfdjXkolZcqysMUYvbdTo";

try {
    $conn = new PDO(
        "mysql:host=$db_host;port=$db_port;dbname=$db_name;charset=utf8",
        $db_user,
        $db_password
    );
    
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    die("Database Connection Failed: " . $e->getMessage());
}
?>
