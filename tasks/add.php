<?php

session_start();

require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if (empty($title)) {
        $_SESSION['error'] = "Task title is required";
        header("Location: ../dashboard.php");
        exit;
    }

    if (strlen($title) > 255) {
        $_SESSION['error'] = "Task title is too long";
        header("Location: ../dashboard.php");
        exit;
    }

    try {
        $stmt = $conn->prepare(
            "INSERT INTO tasks (user_id, title, description) VALUES (?, ?, ?)"
        );
        
        $stmt->execute([$user_id, $title, $description]);

        $_SESSION['success'] = "Task added successfully!";
        header("Location: ../dashboard.php");
        exit;

    } catch (PDOException $e) {
        $_SESSION['error'] = "Failed to add task. Please try again.";
        header("Location: ../dashboard.php");
        exit;
    }

} else {
    header("Location: ../dashboard.php");
    exit;
}
?>
