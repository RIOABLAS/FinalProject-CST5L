<?php

session_start();

require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $username = trim($_POST['username'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    $errors = [];

    if (empty($username)) {
        $errors[] = "Username is required";
    } elseif (strlen($username) < 3) {
        $errors[] = "Username must be at least 3 characters";
    }

    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Valid email is required";
    }

    if (empty($password) || strlen($password) < 6) {
        $errors[] = "Password must be at least 6 characters";
    }

    if ($password !== $confirm_password) {
        $errors[] = "Passwords do not match";
    }

    if (empty($errors)) {
        try {
            $hashed_password = password_hash($password, PASSWORD_BCRYPT);

            $stmt = $conn->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$username, $email, $hashed_password]);

            $_SESSION['success'] = "Registration successful! Please log in.";
            header("Location: ../index.php");
            exit;

        } catch (PDOException $e) {
            if (strpos($e->getMessage(), 'username') !== false) {
                $errors[] = "Username already exists";
            } elseif (strpos($e->getMessage(), 'email') !== false) {
                $errors[] = "Email already registered";
            } else {
                $errors[] = "Registration failed. Please try again.";
            }
        }
    }

    $_SESSION['errors'] = $errors;
    header("Location: ../index.php");
    exit;

} else {
    header("Location: ../index.php");
    exit;
}
?>
