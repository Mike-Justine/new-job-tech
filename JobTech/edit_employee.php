<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}

// Check and sanitize employee ID
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: manage_employees.php");
    exit();
}

$id = intval($_GET['id']);

// Fetch employee info
$result = $conn->query("SELECT * FROM employees WHERE id = $id");

if (!$result || $result->num_rows === 0) {
    echo "Employee not found.";
    exit();
}

$employee = $result->fetch_assoc();

// Handle update
if (isset($_POST['update'])) {
    $name = $conn->real_escape_string($_POST['name']);
    $email = $conn->real_escape_string($_POST['email']);
    $phone = $conn->real_escape_string($_POST['phone']);
    $role = $conn->real_escape_string($_POST['role']);

    $update = $conn->query("UPDATE employees 
                            SET name = '$name', email = '$email', phone = '$phone', role = '$role' 
                            WHERE id = $id");

    if ($update) {
        header("Location: manage_employees.php");
        exit();
    } else {
        echo "Failed to update employee.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Employee</title>
    <link rel="stylesheet" href="styleboard.css">
</head>
<body>

<?php include 'includes/header.php'; ?>
<?php include 'includes/sidebar.php'; ?>

<div class="dashboard-content">
    <h2>Edit Employee: <?= htmlspecialchars($employee['name']) ?></h2>

    <form method="POST">
        <label>Full Name</label><br>
        <input type="text" name="name" value="<?= htmlspecialchars($employee['name']) ?>" required><br><br>

        <label>Email</label><br>
        <input type="email" name="email" value="<?= htmlspecialchars($employee['email']) ?>" required><br><br>

        <label>Phone</label><br>
        <input type="text" name="phone" value="<?= htmlspecialchars($employee['phone']) ?>" required><br><br>

        <label>Role</label><br>
        <input type="text" name="role" value="<?= htmlspecialchars($employee['role']) ?>" required><br><br>

        <button type="submit" name="update">Update Employee</button>
        <a href="manage_employees.php">Cancel</a>
    </form>
</div>

</body>
</html>
