<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}

require_once 'config.php';

// Handle employee deletion
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM employees WHERE id=$id");
    header("Location: manage_employees.php");
    exit();
}

// Handle employee addition (Save)
if (isset($_POST['add'])) {
    $name  = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $role  = $_POST['role'];

    $conn->query("INSERT INTO employees (name, email, phone, role)
                  VALUES ('$name', '$email', '$phone', '$role')");
    header("Location: manage_employees.php");
    exit();
}

// Fetch employees
$result = $conn->query("SELECT * FROM employees");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Employees</title>
    <link rel="stylesheet" href="styleboard.css">
</head>
<body>

<?php include 'includes/header.php'; ?>

<a href="admin_page.php" style="display: inline-block; margin: 10px 0;
 padding: 10px 20px; background:rgb(165, 31, 31); color: white; text-decoration: none; border-radius: 5px;">
    ← Back to Dashboard
</a>

<div class="dashboard-content">
    <h2>Employee Management</h2>

    <form method="POST" style="margin-bottom: 30px;">
        <h3>Add New Employee</h3>
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="phone" placeholder="Phone" required>
        <input type="text" name="role" placeholder="Role" required>
        <button type="submit" name="add">💾 Save Employee</button>
    </form>

    <table border="1" cellpadding="10" cellspacing="0" style="width: 100%;">
        <tr>
            <th>ID</th><th>Name</th><th>Email</th><th>Phone</th><th>Role</th><th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['phone']) ?></td>
            <td><?= htmlspecialchars($row['role']) ?></td>
            <td>
                <a href="edit_employee.php?id=<?= $row['id'] ?>">Edit</a> |
                <a href="manage_employees.php?delete=<?= $row['id'] ?>" onclick="return confirm('Are you sure?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <!-- Print & Download PDF Actions -->
    <div style="margin-top: 20px;">
        <button onclick="window.print()" style="padding: 10px 15px; background-color: #28a745; color: white; border: none; border-radius: 4px;">
            🖨️ Print Employees
        </button>

        <button onclick="downloadPDF()" style="padding: 10px 15px; background-color: #007bff; color: white; border: none; border-radius: 4px; margin-left: 10px;">
            ⬇️ Download as PDF
        </button>
    </div>
</div>

<!-- jsPDF and autoTable scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>

<script>
function downloadPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    doc.text("Employee List", 14, 16);
    const headers = [["ID", "Name", "Email", "Phone", "Role"]];

    const data = [];
    document.querySelectorAll("table tr").forEach((row, i) => {
        if (i === 0) return; // skip header
        const cells = row.querySelectorAll("td");
        if (cells.length > 0) {
            data.push([
                cells[0].innerText,
                cells[1].innerText,
                cells[2].innerText,
                cells[3].innerText,
                cells[4].innerText
            ]);
        }
    });

    doc.autoTable({
        head: headers,
        body: data,
        startY: 20
    });

    doc.save("employees.pdf");
}
</script>

</body>
</html>
