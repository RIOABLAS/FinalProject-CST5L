<?php

session_start();

require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    $errors = [];

    if (empty($email)) {
        $errors[] = "Email is required";
    }

    if (empty($password)) {
        $errors[] = "Password is required";
    }

    if (empty($errors)) {
        try {
            $stmt = $conn->prepare("SELECT id, username, password FROM users WHERE email = ?");
            $stmt->execute([$email]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verify user exists and password is correct
            if ($user && password_verify($password, $user['password'])) {
                // Successful login
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                $_SESSION['email'] = $email;

                header("Location: ../dashboard.php");
                exit;

            } else {
                // Failed login
                $errors[] = "Invalid email or password";
            }

        } catch (PDOException $e) {
            $errors[] = "Login failed. Please try again.";
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
