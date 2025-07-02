<?php
session_start();

// Simulate a logged-in user (remove this in real use)
$name = $_SESSION['name'] ?? null;
$email = $_SESSION['email'] ?? null;
$role = $_SESSION['role'] ?? null;

$is_logged_in = isset($email);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Welcome to JobTech Solutions</title>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
  <style>
    body {
      margin: 0;
      font-family: 'Poppins', sans-serif;
      background-color: #f0f2f5;
    }

    header {
      background-color: #2d3748;
      padding: 5px;
      color: white;
      text-align: center;
    }

    .container {
      max-width: 1100px;
      margin: 40px auto;
      padding: 5px;
      text-align: center;
    }

    .welcome-message h1 {
      font-size: 2.2rem;
      margin-bottom: 10px;
    }

    .welcome-message p {
      font-size: 1.1rem;
      color: #555;
      margin-bottom: 40px;
    }

    .grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
      gap: 20px;
    }

    .card {
      background-color: #fff;
      padding: 25px;
      border-radius: 10px;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
      transition: transform 0.3s;
    }

    .card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }

    .card h3 {
      font-size: 1.3rem;
      margin-bottom: 10px;
      color: #2d3748;
    }

    .card p {
      font-size: 0.95rem;
      color: #666;
      margin-bottom: 15px;
    }

    .card a {
      display: inline-block;
      padding: 8px 16px;
      background-color: #4a90e2;
      color: white;
      border-radius: 5px;
      text-decoration: none;
    }

    .card a:hover {
      background-color:rgb(6, 65, 19);
    }

    footer {
      margin-top: 60px;
      padding: 5px;
      text-align: center;
      color: #aaa;
      font-size: 1.2rem;
    }
  </style>
</head>
<body>

  <header>
    <h2>JobTech Portal</h2>
    <p> Login or register to start using the platform </p>
  </header>

  <div class="container">
    <div class="welcome-message">
     
      <h1>
        <?= $is_logged_in 
          ? "Welcome, " . htmlspecialchars($name ?? $role) 
          : "Welcome to JobTech Solutions" ?>
      </h1>
       <marquee behaviour="scroll" direction="left" scrollamount="10">
      <p>
        <?= $is_logged_in 
          ? "Glad to have you back. What would you like to do today?" 
          : "Your central hub for work, tasks, and progress | JobTech, the future for Technology!." ?>
      </p>
      </marquee>
    </div>

    <div class="grid">
      <?php if ($is_logged_in): ?>
        <div class="card">
          <h3>📝 My Tasks</h3>
          <p>Check and update your assigned tasks.</p>
          <a href="my_tasks.php">View Tasks</a>
        </div>

        <?php if ($role === 'admin'): ?>
        <div class="card">
          <h3>⚙️ Admin Panel</h3>
          <a href="admin_dashboard.php">Admin Dashboard</a>
        </div>
        <?php endif; ?>

      <?php else: ?>
        <div class="card">
          <h3>🔐 Login</h3>
          <p>Already have an account? Sign in here.</p>
      <a href="izndex.php?form=login">Login</a>
     

        </div>

        <div class="card">
          <h3>📝 Register</h3>
          <p>Create user account to get started.</p>
         
         <a href="izndex.php?form=register">Register</a>

        </div>
      <?php endif; ?>

      <div class="card">
        <h3>ℹ️ About Us</h3>
        <p>Learn more about the JobTech system.</p>
        <a href="about_us.php">About Project</a>
      </div>
    </div>
  </div>

  <footer>
    &copy; <?= date('Y') ?> JobTech Solutions. All rights reserved. Dev. Mike [+254 769 653 455]
  </footer>

</body>
</html>
