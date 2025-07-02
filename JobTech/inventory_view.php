<?php 
session_start();
require_once 'config.php';

if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

$email = $_SESSION['email'];
$result = $conn->query("SELECT * FROM inventory ORDER BY item_name ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inventory View</title>
    <link rel="stylesheet" href="styleboard.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.28/jspdf.plugin.autotable.min.js"></script>
    <style>
        .verified-row { background-color: #e0ffe0; }
        .action-buttons { margin-top: 15px; }
    </style>
</head>
<body>

<?php include 'includes/header.php'; ?>
<a href="user_page.php" style="display: inline-block; margin: 10px 0;
 padding: 10px 20px; background:rgb(165, 31, 31); color: white; text-decoration: none; border-radius: 5px;">
    ← Back to Dashboard
</a>

<div class="dashboard-content">
    <h2>Inventory List</h2>

    <div class="action-buttons">
        <button onclick="window.print()">🖨️ Print Inventory</button>
        <button onclick="downloadPDF()">⬇️ Download as PDF</button>
    </div>

    <table border="1" cellpadding="10" cellspacing="0" style="width: 100%;" id="inventoryTable">
        <tr>
            <th>ID</th>
            <th>Item Name</th>
            <th>Quality</th>
            <th>Available Quantity</th>
            <th>Price (KES)</th>
            <th>Action</th>
        </tr>

        <?php if ($result->num_rows > 0): ?>
            <?php while ($row = $result->fetch_assoc()): 
                $verified = isset($_SESSION['verified_inventory'][$row['id']]);
            ?>
                <tr class="<?= $verified ? 'verified-row' : '' ?>">
                    <td><?= $row['id'] ?></td>
                    <td><?= htmlspecialchars($row['item_name']) ?></td>
                    <td><?= $row['quality'] ?></td>
                    <td><?= $row['quantity'] ?></td>
                    <td><?= number_format($row['price'], 2) ?></td>
                    <td>
                        <?php if (!$verified): ?>
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="verify_id" value="<?= $row['id'] ?>">
                                <button type="submit">Verify</button>
                            </form>
                        <?php else: ?>
                            ✅ Verified
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="5" style="text-align:center;">No inventory found.</td></tr>
        <?php endif; ?>
    </table>
</div>

<?php
// Handle verification per session (can be replaced with DB action)
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['verify_id'])) {
    $id = intval($_POST['verify_id']);
    $_SESSION['verified_inventory'][$id] = true;
     header("Location: inventory_view.php");
    exit();
}
?>

<script>
function downloadPDF() {
    const { jsPDF } = window.jspdf;
    const doc = new jsPDF();
    doc.text("Inventory List", 14, 16);

    const headers = [["ID", "Item Name", "Quality", "Quantity", "Price"]];
    const data = [];

    document.querySelectorAll("#inventoryTable tr").forEach((row, i) => {
        if (i === 0) return;
        const cells = row.querySelectorAll("td");
        if (cells.length >= 4) {
            data.push([
                cells[0].innerText,
                cells[1].innerText,
                cells[2].innerText,
                cells[3].innerText
                cells[4].innerText
            ]);
        }
    });

    doc.autoTable({
        head: headers,
        body: data,
        startY: 20
    });

    doc.save("inventory_list.pdf");
}
</script>

</body>
</html>
