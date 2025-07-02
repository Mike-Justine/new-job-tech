

<?php
session_start();
if (!isset($_SESSION['email'])) {
    header("Location:index.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>admin page</title>
     <link rel="stylesheet" href="styleboard.css">
</head>

<header>
    <h1>JobTech Solutions - Admin Dashboard</h1>
</header>

<div class="sidebar">
    <a href="#">Dashboard</a>
    <a href="manage_employees.php">Employees</a>
    <a href="inventory.php">Inventory</a>
    <a href="sales_report.php">Sales</a>
    <a href="manage_task.php">Manage Tasks</a>
    <a href="logout.php">Logout</a>
</div>


<div class="dashboard-content">
    <h1>Welcome, Admin!</h1>
    <div class="card-container">
        <div class="card">
            <h2>Employees</h2>
            <p>View and manage employee records</p>
            <a href="manage_employees.php">Manage</a>
        </div>
        
        <div class="card">
            <h2>Inventory</h2>
            <p>Check current inventory stock</p>
            <a href="inventory.php">View Inventory</a>
        </div>
        <div class="card">
            <h2>Sales Reports</h2>
            <p>View sales and analytics</p>
            <a href="sales_report.php">View Reports</a>
        </div>

         <div class="card">
    <h3>Manage Tasks</h3>
    <p>Allocate tasks to users and manage workload.</p>
    <a href="manage_task.php" class="btn">Assign Now</a>
</div>

      <div class="card">
            <h2>Logout</h2>
            <p>End admin session</p>
            <a href="logout.php">Logout</a>
        </div>
    </div>
</div>

    
</body>
</html>