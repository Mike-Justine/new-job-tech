
<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location:index.php");
    exit();
}


require_once 'config.php';

$email = $_SESSION['email'];
$userQuery = $conn->query("SELECT * FROM users WHERE email='$email'");
$user = $userQuery->fetch_assoc();


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Dashboard | JobTech</title>
    <link rel="stylesheet" href="styleboard.css">
</head>
<body>

<header style="background-color:#34495e;color:
               white;
               padding:15px;
               text-align:center;">
    <h1>JobTech Solutions - User Dashboard</h1>
</header>

<div class="sidebar">
    <a href="#">Dashboard</a>
    <a href="my_tasks.php">My Tasks</a>
    <a href="inventory_view.php">Inventory Report</a>
    <a href="user_sales_report.php">Sales Report</a>
    <a href="logout.php">Logout</a>
</div>


<div class="dashboard-content">
    <h2>Welcome, <?= htmlspecialchars($user['name']) ?></h2>

    <div class="card-container">
        <div class="card">
            <h3>My Profile</h3>
            <p>Name: <?= htmlspecialchars($user['name']) ?></p>
            <p>Email: <?= htmlspecialchars($user['email']) ?></p>
            <p>Role: <?= htmlspecialchars($user['role']) ?></p>
        </div>

        <div class="card">
            <h3>Assigned Tasks</h3>
            <p><a href="my_tasks.php">View My Tasks</a></p>
        </div>

        <div class="card">
            <h3>Inventory (Read Only)</h3>
            <p><a href="inventory_view.php">Browse Inventory</a></p>
        </div>
         
        <div class="card">
            <h3>Sales_report</h3>
            <P><a href="user_sales_report.php">Check sales report here</a> </P>
        </div>

        <div class="card">
            <h3>Logout</h3>
            <p><a href="logout.php">Sign Out</a></p>
        </div>
    </div>
</div>

    
</body>
</html>