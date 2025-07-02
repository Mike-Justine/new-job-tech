
<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: index.php");
    exit();
}

require_once 'config.php';

// Validate ID
if (!isset($_GET['id'])) {
    echo "Item ID missing.";
    exit();
}

$id = intval($_GET['id']);
$success = false;

// Handle Save (update)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $item_name = $_POST['item_name'];
    $quality = $_POST['quality'];
    $quantity = $_POST['quantity'];
    $price = $_POST['price'];

    $stmt = $conn->prepare("UPDATE inventory SET item_name=?, quality=?, quantity=?, price=? WHERE id=?");
    $stmt->bind_param("ssidi", $item_name, $quality, $quantity, $price, $id);
    $stmt->execute();

    $success = true;
}

// Fetch existing data
$result = $conn->query("SELECT * FROM inventory WHERE id = $id");
if ($result->num_rows !== 1) {
    echo "Item not found.";
    exit();
}
$item = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Inventory Item</title>
    <link rel="stylesheet" href="styleboard.css">
    <style>
        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 15px;
            border: 1px solid #c3e6cb;
        }
        .loading-spinner {
            display: none;
            width: 16px;
            height: 16px;
            border: 3px solid #fff;
            border-top: 3px solid #007bff;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin-left: 8px;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        button[disabled] {
            background-color: #888;
        }
    </style>
</head>
<body>

<?php include 'includes/header.php'; ?>

<div class="dashboard-content">
    <h2>Edit Inventory Item</h2>

    <?php if ($success): ?>
        <div class="success-message">✅ Item updated successfully!</div>
        <script>
            setTimeout(() => {
                window.location.href = 'inventory.php';
            }, 2000); // Redirect after 2 seconds
        </script>
    <?php endif; ?>

    <form method="POST" id="editForm">
        <label>Item Name:</label><br>
        <input type="text" name="item_name" value="<?= htmlspecialchars($item['item_name']) ?>" required><br><br>

        <label>Quality:</label><br>
        <input type="text" name="quality" value="<?= $item['quality'] ?>" required><br><br>

        <label>Quantity:</label><br>
        <input type="number" name="quantity" value="<?= $item['quantity'] ?>" required><br><br>

        <label>Price (KES):</label><br>
        <input type="number" step="0.01" name="price" value="<?= $item['price'] ?>" required><br><br>

        <button type="submit" id="saveBtn">💾 Save
            <span class="loading-spinner" id="spinner"></span>
        </button>
        <a href="inventory.php" style="margin-left: 15px;">Cancel</a>
    </form>
</div>

<script>
document.getElementById('editForm').addEventListener('submit', function () {
    const saveBtn = document.getElementById('saveBtn');
    const spinner = document.getElementById('spinner');

    saveBtn.disabled = true;
    spinner.style.display = 'inline-block';
});
</script>

</body>
</html>
