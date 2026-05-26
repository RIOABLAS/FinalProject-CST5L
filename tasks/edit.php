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
    $stmt = $conn->prepare("SELECT * FROM tasks WHERE id = ? AND user_id = ?");
    $stmt->execute([$task_id, $user_id]);
    $task = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$task) {
        $_SESSION['error'] = "Task not found";
        header("Location: ../dashboard.php");
        exit;
    }

} catch (PDOException $e) {
    $_SESSION['error'] = "Error fetching task";
    header("Location: ../dashboard.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['description'] ?? '');

    if (empty($title)) {
        $error = "Task title is required";
    } elseif (strlen($title) > 255) {
        $error = "Task title is too long";
    } else {
        try {
            $stmt = $conn->prepare(
                "UPDATE tasks SET title = ?, description = ? WHERE id = ? AND user_id = ?"
            );
            
            $stmt->execute([$title, $description, $task_id, $user_id]);

            $_SESSION['success'] = "Task updated successfully!";
            header("Location: ../dashboard.php");
            exit;

        } catch (PDOException $e) {
            $error = "Failed to update task. Please try again.";
        }
    }
}

$error = isset($error) ? $error : null;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task - My Tasks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row">
            <div class="col-md-8 col-lg-6 mx-auto">
                <a href="../dashboard.php" class="btn btn-secondary mb-3">
                    <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                </a>

                <div class="card shadow-lg border-0">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0"><i class="fas fa-edit me-2"></i>Edit Task</h4>
                    </div>

                    <div class="card-body p-4">
                        <?php if ($error): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <i class="fas fa-exclamation-circle me-2"></i><?php echo htmlspecialchars($error); ?>
                                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                            </div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="mb-3">
                                <label for="task_title" class="form-label fw-bold">Task Title</label>
                                <input 
                                    type="text" 
                                    class="form-control form-control-lg" 
                                    id="task_title" 
                                    name="title" 
                                    value="<?php echo htmlspecialchars($task['title']); ?>"
                                    required
                                >
                            </div>

                            <div class="mb-4">
                                <label for="task_description" class="form-label fw-bold">Description</label>
                                <textarea 
                                    class="form-control" 
                                    id="task_description" 
                                    name="description" 
                                    rows="5"
                                ><?php echo htmlspecialchars($task['description'] ?? ''); ?></textarea>
                            </div>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-primary btn-lg fw-bold">
                                    <i class="fas fa-save me-2"></i>Save Changes
                                </button>
                                <a href="../dashboard.php" class="btn btn-secondary btn-lg fw-bold">
                                    Cancel
                                </a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
