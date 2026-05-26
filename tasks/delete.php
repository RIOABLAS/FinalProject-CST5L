<?php

session_start();

require_once '../config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: ../index.php");
    exit;
}

$user_id = $_SESSION['user_id'];

$task_id = isset($_GET['id']) ? intval($_GET['id']) : null;

if (!$task_id) {
    $_SESSION['error'] = "Invalid task ID";
    header("Location: ../dashboard.php");
    exit;
}

try {
    $stmt = $conn->prepare("DELETE FROM tasks WHERE id = ? AND user_id = ?");
    $result = $stmt->execute([$task_id, $user_id]);

    if ($stmt->rowCount() > 0) {
        $_SESSION['success'] = "Task deleted successfully!";
    } else {
        $_SESSION['error'] = "Task not found";
    }

} catch (PDOException $e) {
    $_SESSION['error'] = "Failed to delete task. Please try again.";
}

header("Location: ../dashboard.php");
exit;
?>
