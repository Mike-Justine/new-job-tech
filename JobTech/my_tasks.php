<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

$email = $_SESSION['email'];
$success = $_GET['success'] ?? '';

// Handle task verification
if (isset($_GET['verify'])) {
    $task_id = intval($_GET['verify']);

    // Update task as verified
    $stmt = $conn->prepare("UPDATE tasks SET is_completed = 1, verified_by_user = 1, verified_at = NOW() WHERE id = ? AND assigned_to = ?");
    $stmt->bind_param("is", $task_id, $email);
    $stmt->execute();

    // Fetch task title for message
    $task = $conn->query("SELECT title FROM tasks WHERE id = $task_id")->fetch_assoc();
    $task_title = $task['title'] ?? 'Task';

    // Prepare notification
    $title = "Task Verified";
    $message = "$email has verified the task: $task_title";

    // Insert notification for admin
    $notify = $conn->prepare("INSERT INTO notifications (title, message, recipient_role) VALUES (?, ?, 'admin')");
    $notify->bind_param("ss", $title, $message);
    $notify->execute();

    header("Location: my_tasks.php?success=verified");
    exit();
}




// Fetch tasks for this user
$result = $conn->query("SELECT * FROM tasks WHERE assigned_to = '$email' ORDER BY assigned_date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Tasks</title>
    <link rel="stylesheet" href="styleboard.css">
    <style>
        .verified {
            background-color: #d4edda;
            color: #155724;
        }
        .success-message {
            background-color: #cce5ff;
            padding: 10px;
            border-left: 5px solid #004085;
            margin-bottom: 20px;
            border-radius: 5px;
        }
    </style>
</head>
<body>

<?php include 'includes/header.php'; ?>
  <a href="user_page.php" style="display: inline-block; margin: 10px 0;
 padding: 10px 20px; background:rgb(165, 31, 31); color: white; text-decoration: none; border-radius: 5px;">
    ← Back to Dashboard
    </a>

<div class="dashboard-content">
    <h2>My Assigned Tasks</h2>

    <?php if ($success === 'verified'): ?>
        <div class="success-message">✅ Task Completed successfully!</div>
    <?php endif; ?>

    <button onclick="window.print()" style="margin-bottom: 15px; padding: 8px 12px;">🖨️ Print Tasks</button>

    <table border="1" cellpadding="10" cellspacing="0" style="width: 100%;">
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Task Description</th>
            <th>Status</th>
            <th>Assigned Date</th>
            <th>Action</th>
        </tr>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr class="<?= $row['verified_by_user'] ? 'verified' : '' ?>">
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td><?= htmlspecialchars($row['task_description']) ?></td>
                    <td>
                        <?php
                        if ($row['verified_by_user']) {
                            echo "✅ Verified";
                        } elseif ($row['is_completed']) {
                            echo "✔️ Completed";
                        } else {
                            echo "⏳ Pending";
                        }
                        ?>
                    </td>
                    <td><?= $row['assigned_date'] ?></td>
                    <td>
                        <?php if (!$row['verified_by_user']): ?>
                            <a href="my_tasks.php?verify=<?= $row['id'] ?>" onclick="return confirm('Mark this task as verified?')">Verify</a>
                        <?php else: ?>
                            <span>✔️ Verified</span>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="6" style="text-align:center;">You have no tasks assigned.</td></tr>
        <?php endif; ?>
    </table>
</div>

</body>
</html>
