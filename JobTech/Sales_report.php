<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}

require_once 'config.php';

// Success message
$success = $_GET['success'] ?? '';

// Handle deletion
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $conn->query("DELETE FROM sales WHERE id=$id");
    header("Location: sales_report.php?success=deleted");
    exit();
}

// Handle add
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $item_name = $_POST['item_name'];
    $quantity = intval($_POST['quantity']);
    $price = floatval($_POST['price']);
    $sale_date = $_POST['sale_date'];

    $stmt = $conn->prepare("INSERT INTO sales (item_name, quantity, price, sale_date) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sids", $item_name, $quantity, $price, $sale_date);
    $stmt->execute();

    header("Location: sales_report.php?success=added");
    exit();
}

// Filter by date
$filter = "";
if (!empty($_GET['from']) && !empty($_GET['to'])) {
    $from = $_GET['from'];
    $to = $_GET['to'];
    $filter = "WHERE sale_date BETWEEN '$from' AND '$to'";
}

$sales = $conn->query("SELECT * FROM sales $filter ORDER BY sale_date DESC");

$total_amount = 0;
$total_quantity = 0;
$all_sales = [];
if ($sales && $sales->num_rows > 0) {
    while ($s = $sales->fetch_assoc()) {
        $total_amount += $s['price'] * $s['quantity'];
        $total_quantity += $s['quantity'];
        $all_sales[] = $s;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sales Report</title>
    <link rel="stylesheet" href="styleboard.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
</head>
<body>

<?php include 'includes/header.php'; ?>
<a href="admin_page.php" style="display: inline-block; margin: 10px 0;
 padding: 10px 20px; background:rgb(165, 31, 31); color: white; text-decoration: none; border-radius: 5px;">
    ← Back to Dashboard
</a>

<div class="dashboard-content">
    <h2>Sales Report</h2>

    <?php if ($success === 'added'): ?>
        <div class="success-message">✅ Sale added successfully.</div>
    <?php elseif ($success === 'deleted'): ?>
        <div class="success-message">🗑️ Sale deleted successfully.</div>
    <?php elseif ($success === 'updated'): ?>
        <div class="success-message">✏️ Sale updated successfully.</div>
    <?php endif; ?>

    <!-- Filter -->
    <form method="GET" style="margin-bottom: 20px;">
        <label>From: <input type="date" name="from" value="<?= $_GET['from'] ?? '' ?>"></label>
        <label>To: <input type="date" name="to" value="<?= $_GET['to'] ?? '' ?>"></label>
        <button type="submit">Filter</button>
        <a href="sales_report.php">Reset</a>
    </form>

    <!-- Add Sale -->
    <form method="POST" style="margin-bottom: 30px;">
        <h3>Add New Sale</h3>
        <input type="text" name="item_name" placeholder="Item Name" required>
        <input type="number" name="quantity" placeholder="Quantity" required>
        <input type="number" step="0.01" name="price" placeholder="Price" required>
        <input type="date" name="sale_date" required>
        <button type="submit" name="add">Add Sale</button>
    </form>

    <!-- Sales Table -->
    <table border="1" cellpadding="10" cellspacing="0" style="width: 100%;">
        <tr>
            <th>ID</th><th>Item</th><th>Quantity</th><th>Price (KES)</th><th>Total (KES)</th><th>Date</th><th>Actions</th>
        </tr>

        <?php if (!empty($all_sales)): ?>
            <?php foreach ($all_sales as $sale): ?>
            <tr>
                <td><?= $sale['id'] ?></td>
                <td><?= htmlspecialchars($sale['item_name']) ?></td>
                <td><?= $sale['quantity'] ?></td>
                <td><?= number_format($sale['price'], 2) ?></td>
                <td><?= number_format($sale['price'] * $sale['quantity'], 2) ?></td>
                <td><?= $sale['sale_date'] ?></td>
                <td>
                    <a href="edit_sale.php?id=<?= $sale['id'] ?>">Edit</a> |
                    <a href="sales_report.php?delete=<?= $sale['id'] ?>" onclick="return confirm('Delete this sale?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="7" style="text-align:center;">No sales records found.</td></tr>
        <?php endif; ?>
    </table>

    <br>
    <h3>Total Quantity Sold: <?= $total_quantity ?></h3>
    <h3>Total Sales Amount: KES <?= number_format($total_amount, 2) ?></h3>

    <div style="margin-top: 20px;">
        <button onclick="window.print()">🖨️ Print Sales</button>
        <button onclick="downloadPDF()">⬇️ Download as PDF</button>
    </div>
</div>

<script>
function downloadPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    doc.text("Sales Report", 14, 16);
    const headers = [["ID", "Item", "Quantity", "Price", "Total", "Date"]];
    const data = [];

    document.querySelectorAll("table tr").forEach((row, i) => {
        if (i === 0) return;
        const cells = row.querySelectorAll("td");
        if (cells.length > 5) {
            data.push([
                cells[0].innerText,
                cells[1].innerText,
                cells[2].innerText,
                cells[3].innerText,
                cells[4].innerText,
                cells[5].innerText
            ]);
        }
    });

    doc.autoTable({ head: headers, body: data, startY: 20 });
    doc.save("sales_report.pdf");
}
</script>

</body>
</html>
