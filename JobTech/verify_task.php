<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['email'])) {
    header("Location: login_register.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $task_id = intval($_POST['task_id']);
    $email = $_SESSION['email'];
    $now = date('Y-m-d H:i:s');

    $stmt = $conn->prepare("UPDATE tasks SET verified_by_user = 1, verified_at = ?
     WHERE id = ? AND assigned_to = ?");
    $stmt->bind_param("sis", $now, $task_id, $email);
    $stmt->execute();

    header("Location: user_page.php?success=verified");
    exit();
}
?>
