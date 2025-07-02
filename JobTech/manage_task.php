<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}

$message = $error = "";

// CREATE a new task
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['assign'])) {
    $email = $_POST['assigned_to_email'] ?? '';
    $title = trim($_POST['title'] ?? '');
    $description = trim($_POST['task_description'] ?? '');
    $due_date = $_POST['due_date'] ?? '';
    $status = "Pending";
    $assigned_date = date('Y-m-d');

    if ($email && $title && $description && $due_date) {
        $stmt = $conn->prepare("INSERT INTO tasks (title, task_description, status, assigned_date, assigned_to) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $title, $description, $status, $due_date, $email);
        if ($stmt->execute()) {
            $message = "✅ Task assigned to <strong>$email</strong> successfully!";
        } else {
            $error = "❌ Failed to assign task: " . $stmt->error;
        }
    } else {
        $error = "❌ Please fill in all required fields.";
    }
}

// UPDATE a task
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $task_id = $_POST['task_id'];
    $title = $_POST['title'];
    $task_description = $_POST['description'];
    $status = $_POST['status'];
    $due_date = $_POST['due_date'];

    $stmt = $conn->prepare("UPDATE tasks SET title=?, task_description=?, status=?, assigned_date=? WHERE id=?");
    $stmt->bind_param("ssssi", $title, $task_description, $status, $due_date, $task_id);
    if ($stmt->execute()) {
        $message = "✅ Task updated successfully.";
    } else {
        $error = "❌ Failed to update task: " . $stmt->error;
    }
}

// DELETE a task
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $task_id = $_POST['task_id'];
    $stmt = $conn->prepare("DELETE FROM tasks WHERE id=?");
    $stmt->bind_param("i", $task_id);
    if ($stmt->execute()) {
        $message = "🗑️ Task deleted successfully.";
    } else {
        $error = "❌ Failed to delete task: " . $stmt->error;
    }
}

// Fetch user emails
$users = $conn->query("SELECT email, name FROM users ORDER BY name ASC");

// Fetch all tasks
$tasks = $conn->query("SELECT * FROM tasks ORDER BY assigned_date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Task Management</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f6f9; padding: 20px; }
        h2 { text-align: center; color: #333; }

        .container { max-width: 1100px; margin: auto; }

        .form-box {
            background: #fff; padding: 20px; border-radius: 10px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1); margin-bottom: 30px;
        }

        label { display: block; margin-top: 10px; font-weight: bold; }
        input[type="text"], input[type="date"], select, textarea {
            width: 100%; padding: 8px; margin-top: 5px; border-radius: 5px; border: 1px solid #ccc;
        }

        button {
            margin-top: 15px; padding: 8px 16px; border: none;
            border-radius: 5px; background: #007bff; color: white; cursor: pointer;
        }

        button:hover { background: #0056b3; }

        table {
            width: 100%; border-collapse: collapse; background: white;
        }

        th, td {
            padding: 10px; border: 1px solid #ccc; text-align: left;
        }

        .success { background: #d4edda; color: #155724; padding: 10px; margin-bottom: 15px; border-radius: 5px; }
        .error { background: #f8d7da; color: #721c24; padding: 10px; margin-bottom: 15px; border-radius: 5px; }

        .action-btn { display: flex; gap: 10px; }
        .action-btn form { display: inline; }
        .delete-btn { background: #dc3545; }
        .delete-btn:hover { background: #b52a38; }
    </style>
</head>
<body>
    </body>
   <?php include 'includes/header.php'; ?>
    <a href="admin_page.php" style="display: inline-block; margin: 10px 0;
 padding: 10px 20px; background:rgb(165, 31, 31); color: white; text-decoration: none; border-radius: 5px;">
    ← Back to Dashboard
</a>

<div class="container">
    <h2>Admin Task Management</h2>

    <?php if ($message): ?><div class="success"><?= $message ?></div><?php endif; ?>
    <?php if ($error): ?><div class="error"><?= $error ?></div><?php endif; ?>

    <div class="form-box">
        <h3>Assign New Task</h3>
        <form method="post" action="">
            <label>Assign To (Email):</label>
            <select name="assigned_to_email" required>
                <option value="">-- Select User --</option>
                <?php while ($user = $users->fetch_assoc()): ?>
                    <option value="<?= htmlspecialchars($user['email']) ?>">
                        <?= htmlspecialchars($user['name']) ?> (<?= htmlspecialchars($user['email']) ?>)
                    </option>
                <?php endwhile; ?>
            </select>

            <label>Task Title:</label>
            <input type="text" name="title" required>

            <label>Task Description:</label>
            <textarea name="task_description" rows="4" required></textarea>

            <label>Due Date:</label>
            <input type="date" name="due_date" required>

            <button type="submit" name="assign">Assign Task</button>
        </form>
    </div>

    <div class="form-box">
        <h3>All Tasks</h3>
        <table>
            <tr>
                <th>ID</th>
                <th>Assigned To</th>
                <th>Title</th>
                <th>task_description</th>
                <th>Status</th>
                <th>Due Date</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $tasks->fetch_assoc()): ?>
                <tr>
                    <form method="post">
                        <td><?= $row['id'] ?><input type="hidden" name="task_id" value="<?= $row['id'] ?>"></td>
                        <td><?= htmlspecialchars($row['assigned_to']) ?></td>
                        <td><input type="text" name="title" value="<?= htmlspecialchars($row['title']) ?>" required></td>
                        <td><textarea name="description" required><?= htmlspecialchars($row['task_description']) ?></textarea></td>
                        <td>
                            <select name="status">
                                <option <?= $row['status'] == 'Pending' ? 'selected' : '' ?>>Pending</option>
                                <option <?= $row['status'] == 'In Progress' ? 'selected' : '' ?>>In Progress</option>
                                <option <?= $row['status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
                            </select>
                        </td>
                        <td><input type="date" name="due_date" value="<?= $row['assigned_date'] ?>" required></td>
                        <td class="action-btn">
                            <button type="submit" name="update">Update</button>
                    </form>
                    <form method="post" onsubmit="return confirm('Are you sure you want to delete this task?');">
                        <input type="hidden" name="task_id" value="<?= $row['id'] ?>">
                        <button type="submit" name="delete" class="delete-btn">Delete</button>
                    </form>
                        </td>
                </tr>
            <?php endwhile; ?>
        </table>
    </div>
</div>

</body>
</html>
