

<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>About Us | JobTech</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: #f4f6f8;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #1e293b;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .container {
            max-width: 1000px;
            margin: 40px auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.1);
        }

        h2 {
            color: #0f172a;
            margin-top: 30px;
        }

        p {
            line-height: 1.7;
            color: #334155;
        }

        .team {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            margin-top: 30px;
        }

        .member {
            background: #f1f5f9;
            padding: 20px;
            border-radius: 10px;
            flex: 1 1 200px;
            text-align: center;
        }

        .member h4 {
            margin-bottom: 5px;
        }

        .btn-back {
            display: block;
            margin-top: 30px;
            text-align: center;
        }

        .btn-back a {
            text-decoration: none;
            color: white;
            background: #0ea5e9;
            padding: 10px 20px;
            border-radius: 5px;
        }

        .btn-back a:hover {
            background:rgb(28, 155, 17);
        }
    </style>
</head>
<body>

<header>
    <h1>About JobTech</h1>
    <p>Your Partner in Efficient Workforce and Task Management</p>
</header>

<div class="container">
    <h2>Our Mission</h2>
    <p>
        At <strong>JobTech</strong>, our mission is to streamline task assignments, 
        workforce management, and productivity tracking for organizations of all sizes.
        We aim to empower administrators and employees with the tools they need to collaborate
        efficiently and transparently.
    </p>

    <h2>Our Vision</h2>
    <p>
        We envision a future where job coordination is seamless and intuitive — 
        eliminating communication breakdowns and creating a culture of accountability and performance.
        JobTech is committed to leveraging technology to bridge the gap between employees and management.
    </p>

    <h2>Core Values</h2>
    <p>
        ✅ Transparency and Trust <br>
        ✅ Innovation in Simplicity <br>
        ✅ User Empowerment <br>
        ✅ Data-Driven Insights <br>
        ✅ Team Collaboration
    </p>

    <h2>What We Offer</h2>
    <p>
        🔹 Task assignment and monitoring for admins<br>
        🔹 Task verification and feedback system for employees<br>
        🔹 Live updates, notifications, and printable reports<br>
        🔹 A secure and user-friendly platform
    </p>

    <h2>Meet Our Team</h2>
    <div class="team">
        <div class="member">
            <h4>Mike Justine[+254 769 653 455]</h4>
            <p>System Developer & Project Manager</p>
        </div>
        <div class="member">
            <h4>Dee Jackal</h4>
            <p>Technician</p>
        </div>
        <div class="member">
            <h4>Lee waru</h4>
            <p>Backend Engineer</p>
        </div>
        <div class="member">
            <h4>Steve Kinyozi</h4>
            <p>Quality Assurance Analyst</p>
        </div>
    </div>

    <div class="btn-back">
        <a href="index.php">← Back to Dashboard</a>
    </div>
</div>

</body>
</html>
