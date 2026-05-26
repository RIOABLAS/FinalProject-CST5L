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
    $stmt = $conn->prepare("SELECT is_complete FROM tasks WHERE id = ? AND user_id = ?");
    $stmt->execute([$task_id, $user_id]);
    $task = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$task) {
        $_SESSION['error'] = "Task not found";
        header("Location: ../dashboard.php");
        exit;
    }

    $new_status = !$task['is_complete'];

    $stmt = $conn->prepare("UPDATE tasks SET is_complete = ? WHERE id = ? AND user_id = ?");
    $stmt->execute([$new_status, $task_id, $user_id]);

    if ($new_status) {
        $_SESSION['success'] = "Task marked as complete!";
    } else {
        $_SESSION['success'] = "Task marked as pending!";
    }

} catch (PDOException $e) {
    $_SESSION['error'] = "Failed to update task status. Please try again.";
}

header("Location: ../dashboard.php");
exit;
?>
