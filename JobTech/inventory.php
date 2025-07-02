

<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}

require_once 'config.php';

// Handle deletion
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $conn->query("DELETE FROM inventory WHERE id = $id");
    header("Location: inventory.php");
    exit();
}

// Handle addition
if (isset($_POST['add'])) {
    $item_name = $_POST['item_name'];
    $quality = $_POST['quality'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];

    $conn->query("INSERT INTO inventory (item_name, quality, quantity, price)
                  VALUES ('$item_name', '$quality', '$quantity', '$price')");
    header("Location: inventory.php");
    exit();
}

// Fetch inventory
$result = $conn->query("SELECT * FROM inventory");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Inventory Management</title>
    <link rel="stylesheet" href="styleboard.css">
</head>
<body>

<?php include 'includes/header.php'; ?>

<a href="admin_page.php" style="display: inline-block; margin: 10px 0;
   padding: 10px 20px; background: rgb(165, 31, 31); color: white; text-decoration:
   none; border-radius: 5px;">
    ← Back to Dashboard
</a>

<div class="dashboard-content">
    <h2>Inventory Management</h2>

    <form method="POST" style="margin-bottom: 30px;">
        <h3>Add New Item</h3>
        <input type="text" name="item_name" placeholder="Item Name" required>
        <input type="text" name="quality" placeholder="Quality" required>
        <input type="number" name="quantity" placeholder="Quantity" required>
        <input type="number" step="0.01" name="price" placeholder="Price" required>
        <button type="submit" name="add">Add Item</button>
    </form>

    <table border="1" cellpadding="10" cellspacing="0" style="width: 100%;">
        <tr>
            <th>ID</th> <th>Item Name</th> <th>Quality</th> <th>Quantity</th> <th>Price</th> <th>Actions</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= htmlspecialchars($row['item_name']) ?></td>
            <td><?= $row['quality'] ?></td>
            <td><?= $row['quantity'] ?></td>
            <td>KES <?= number_format($row['price'], 2) ?></td>
            <td>
                <a href="edit_inventory.php?id=<?= $row['id'] ?>">Edit</a> |
                <a href="inventory.php?delete=<?= $row['id'] ?>" onclick="return confirm('Delete this item?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <!-- One Clean Print and Download PDF Action -->
    <div style="margin-top: 20px;">
        <button onclick="window.print()" style="padding: 10px 15px; background-color: #28a745; 
        color: white; border: none; border-radius: 4px;">
            🖨️ Print Inventory
        </button>
        <button onclick="downloadPDF()" style="padding: 10px 15px; background-color: #007bff;
        color: white; border: none; border-radius: 4px; margin-left: 10px;">
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

    doc.text("Inventory List", 14, 16);
    const headers = [["ID", "Item Name", "Quality", "Quantity", "Price"]];

    const data = [];
    document.querySelectorAll("table tr").forEach((row, i) => {
        if (i === 0) return; // skip table header
        const cells = row.querySelectorAll("td");
        if (cells.length > 0) {
            data.push([
                cells[0].innerText,
                cells[1].innerText,
                cells[2].innerText,
                cells[3].innerText,
                cells[4].innerText.replace("KES ", "")
            ]);
        }
    });

    doc.autoTable({
        head: headers,
        body: data,
        startY: 20
    });

    doc.save("inventory.pdf");
}
</script>

</body>
</html>
