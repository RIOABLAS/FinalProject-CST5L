<?php

session_start();

require_once 'config/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$username = $_SESSION['username'];

try {
    $stmt = $conn->prepare("SELECT * FROM tasks WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$user_id]);
    $tasks = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $total_tasks = count($tasks);
    $completed_tasks = count(array_filter($tasks, fn($t) => $t['is_complete']));
    $pending_tasks = $total_tasks - $completed_tasks;

} catch (PDOException $e) {
    die("Error fetching tasks: " . $e->getMessage());
}

$success_message = isset($_SESSION['success']) ? $_SESSION['success'] : null;
if ($success_message) {
    unset($_SESSION['success']);
}

$error_message = isset($_SESSION['error']) ? $_SESSION['error'] : null;
if ($error_message) {
    unset($_SESSION['error']);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - My Tasks</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/styles.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="#">
                <i class="fas fa-tasks me-2"></i>My Tasks
            </a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <span class="navbar-text text-white me-3">
                            Welcome, <strong><?php echo htmlspecialchars($username); ?></strong>
                        </span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link btn btn-outline-light" href="auth/logout.php">
                            <i class="fas fa-sign-out-alt me-1"></i>Logout
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container-fluid py-4">
        <?php if ($success_message): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i><?php echo htmlspecialchars($success_message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if ($error_message): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i><?php echo htmlspecialchars($error_message); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-lg-3 mb-4">
                <div class="card shadow-sm mb-4 border-0">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-chart-bar me-2"></i>Task Statistics</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <small class="text-muted">Total Tasks</small>
                            <h3 class="text-primary fw-bold"><?php echo $total_tasks; ?></h3>
                        </div>

                        <div class="mb-3">
                            <small class="text-muted">Completed</small>
                            <h3 class="text-success fw-bold"><?php echo $completed_tasks; ?></h3>
                        </div>

                        <div class="mb-0">
                            <small class="text-muted">Pending</small>
                            <h3 class="text-warning fw-bold"><?php echo $pending_tasks; ?></h3>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-success text-white">
                        <h5 class="mb-0"><i class="fas fa-plus me-2"></i>Add New Task</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="tasks/add.php">
                            <div class="mb-3">
                                <label for="task_title" class="form-label fw-bold">Task Title</label>
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    id="task_title" 
                                    name="title" 
                                    required
                                    placeholder="Enter task title"
                                >
                            </div>

                            <div class="mb-3">
                                <label for="task_description" class="form-label fw-bold">Description</label>
                                <textarea 
                                    class="form-control" 
                                    id="task_description" 
                                    name="description" 
                                    rows="3"
                                    placeholder="Enter task description (optional)"
                                ></textarea>
                            </div>

                            <button type="submit" class="btn btn-success w-100 fw-bold">
                                <i class="fas fa-plus me-2"></i>Add Task
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="card shadow-sm border-0">
                    <div class="card-header bg-primary text-white">
                        <h5 class="mb-0"><i class="fas fa-list me-2"></i>My Tasks</h5>
                    </div>
                    <div class="card-body">
                        <?php if (empty($tasks)): ?>
                            <div class="text-center py-5">
                                <i class="fas fa-inbox" style="font-size: 3rem; color: #ccc;"></i>
                                <p class="text-muted mt-3">No tasks yet. Create one to get started!</p>
                            </div>

                        <?php else: ?>
                            <div class="list-group">
                                <?php foreach ($tasks as $task): ?>
                                    <div class="list-group-item border-0 border-bottom py-3">
                                        <div class="row align-items-center">
                                            <div class="col-md-6">
                                                <h6 class="mb-2 <?php echo $task['is_complete'] ? 'text-decoration-line-through text-muted' : 'fw-bold'; ?>">
                                                    <?php echo htmlspecialchars($task['title']); ?>
                                                </h6>
                                                <?php if (!empty($task['description'])): ?>
                                                    <p class="text-muted small mb-0">
                                                        <?php echo htmlspecialchars($task['description']); ?>
                                                    </p>
                                                <?php endif; ?>
                                                <small class="text-muted">
                                                    Created: <?php echo date('M d, Y', strtotime($task['created_at'])); ?>
                                                </small>
                                            </div>

                                            <div class="col-md-2 text-center">
                                                <?php if ($task['is_complete']): ?>
                                                    <span class="badge bg-success"><i class="fas fa-check me-1"></i>Completed</span>
                                                <?php else: ?>
                                                    <span class="badge bg-warning"><i class="fas fa-clock me-1"></i>Pending</span>
                                                <?php endif; ?>
                                            </div>

                                            <div class="col-md-4 text-end">
                                                <a 
                                                    href="tasks/complete.php?id=<?php echo $task['id']; ?>" 
                                                    class="btn btn-sm <?php echo $task['is_complete'] ? 'btn-warning' : 'btn-outline-success'; ?>" 
                                                    title="<?php echo $task['is_complete'] ? 'Mark as Pending' : 'Mark as Complete'; ?>"
                                                >
                                                    <i class="fas <?php echo $task['is_complete'] ? 'fa-undo' : 'fa-check'; ?>"></i>
                                                </a>

                                                <a 
                                                    href="tasks/edit.php?id=<?php echo $task['id']; ?>" 
                                                    class="btn btn-sm btn-outline-primary" 
                                                    title="Edit task"
                                                >
                                                    <i class="fas fa-edit"></i>
                                                </a>

                                                <a 
                                                    href="tasks/delete.php?id=<?php echo $task['id']; ?>" 
                                                    class="btn btn-sm btn-outline-danger" 
                                                    title="Delete task"
                                                    onclick="return confirm('Are you sure you want to delete this task?');"
                                                >
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
