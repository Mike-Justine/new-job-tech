<?php
session_start();
require_once 'config.php';

if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

$email = $_SESSION['email'];
$success = $_GET['success'] ?? '';

// Handle verify
if (isset($_GET['verify'])) {
    $id = intval($_GET['verify']);
    $conn->query("UPDATE sales SET verified_by_user = 1, verified_at = NOW() WHERE id = $id");
    header("Location: user_sales_report.php?success=verified");
    exit();
}

// Fetch all sales
$result = $conn->query("SELECT * FROM sales ORDER BY sale_date DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Sales Report</title>
    <link rel="stylesheet" href="styleboard.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
</head>
<body>

<?php include 'includes/header.php'; ?>
    <a href="user_page.php" style="display: inline-block; margin: 10px 0;
 padding: 10px 20px; background:rgb(165, 31, 31); color: white; text-decoration: none; border-radius: 5px;">
    ← Back to Dashboard
</a>

<div class="dashboard-content">
    <h2>Sales Report (Read-Only)</h2>

    <?php if ($success === 'verified'): ?>
        <div class="success-message">✅ Sale verified successfully!</div>
    <?php endif; ?>

    <table border="1" cellpadding="10" cellspacing="0" style="width: 100%;">
        <tr>
            <th>ID</th><th>Item</th><th>Quantity</th><th>Price</th><th>Total</th><th>Date</th><th>Status</th><th>Action</th>
        </tr>

        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= $row['id'] ?></td>
                <td><?= htmlspecialchars($row['item_name']) ?></td>
                <td><?= $row['quantity'] ?></td>
                <td>KES <?= number_format($row['price'], 2) ?></td>
                <td>KES <?= number_format($row['price'] * $row['quantity'], 2) ?></td>
                <td><?= $row['sale_date'] ?></td>
                <td><?= $row['verified_by_user'] ? "✅ Verified" : "❌ Not Verified" ?></td>
                <td>
                    <?php if (!$row['verified_by_user']): ?>
                        <a href="?verify=<?= $row['id'] ?>" onclick="return confirm('Confirm you’ve reviewed this sale?')">Verify</a>
                    <?php else: ?>
                        <span style="color: gray;">Verified</span>
                    <?php endif; ?>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <div style="margin-top: 20px;">
        <button onclick="window.print()">🖨️ Print Sales</button>
        <button onclick="downloadPDF()" style="margin-left: 10px;">⬇️ Download as PDF</button>
    </div>
</div>

<script>
function downloadPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();

    doc.text("Sales Report", 14, 16);
    const headers = [["ID", "Item", "Qty", "Price", "Total", "Date", "Verified"]];
    const data = [];

    document.querySelectorAll("table tr").forEach((row, i) => {
        if (i === 0) return;
        const cells = row.querySelectorAll("td");
        if (cells.length >= 7) {
            data.push([
                cells[0].innerText,
                cells[1].innerText,
                cells[2].innerText,
                cells[3].innerText,
                cells[4].innerText,
                cells[5].innerText,
                cells[6].innerText
            ]);
        }
    });

    doc.autoTable({ head: headers, body: data, startY: 20 });
    doc.save("user_sales_report.pdf");
}
</script>

</body>
</html>
