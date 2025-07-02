<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}

require_once 'config.php';

if (!isset($_GET['id'])) {
    echo "Sale ID missing.";
    exit();
}

$id = intval($_GET['id']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['save'])) {
        $item_name = $_POST['item_name'];
        $quantity = intval($_POST['quantity']);
        $price = floatval($_POST['price']);
        $sale_date = $_POST['sale_date'];

        $stmt = $conn->prepare("UPDATE sales SET item_name=?, quantity=?, price=?, sale_date=? WHERE id=?");
        $stmt->bind_param("sidss", $item_name, $quantity, $price, $sale_date, $id);
        $stmt->execute();

        header("Location: sales_report.php?success=updated");
        exit();
    }

    if (isset($_POST['cancel'])) {
        header("Location: sales_report.php");
        exit();
    }
}

$result = $conn->query("SELECT * FROM sales WHERE id = $id");
if ($result->num_rows !== 1) {
    echo "Sale not found.";
    exit();
}
$sale = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Edit Sale</title>
    <link rel="stylesheet" href="styleboard.css">
</head>
<body>
<?php include 'includes/header.php'; ?>

<div class="dashboard-content">
    <h2>Edit Sale</h2>
    <form method="POST">
        <label>Item Name:</label><br>
        <input type="text" name="item_name" value="<?= htmlspecialchars($sale['item_name']) ?>" required><br><br>

        <label>Quantity:</label><br>
        <input type="number" name="quantity" value="<?= $sale['quantity'] ?>" required><br><br>

        <label>Price:</label><br>
        <input type="number" step="0.01" name="price" value="<?= $sale['price'] ?>" required><br><br>

        <label>Sale Date:</label><br>
        <input type="date" name="sale_date" value="<?= $sale['sale_date'] ?>" required><br><br>

        <button type="submit" name="save">💾 Save</button>
        <button type="submit" name="cancel" style="margin-left: 10px;">✖ Cancel</button>
    </form>
</div>
</body>
</html>
