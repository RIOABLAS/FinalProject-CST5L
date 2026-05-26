<?php

// Database credentials
$db_host = "localhost";
$db_name = "todo_database";
$db_user = "";
$db_password = "";

try {
    $conn = new PDO(
        "mysql:host=$db_host;dbname=$db_name;charset=utf8",
        $db_user,
        $db_password
    );
    
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch (PDOException $e) {
    die("Database Connection Failed: " . $e->getMessage());
}
?>
