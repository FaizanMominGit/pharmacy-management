<?php
include 'config.php';
$message = '';

// --- Handle Add ---
if (isset($_POST['add'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $quantity = intval($_POST['quantity']);
    $price = floatval($_POST['price']);
    $expiry_date = mysqli_real_escape_string($conn, $_POST['expiry_date']);
    $sql = "INSERT INTO medicines (name, quantity, price, expiry_date) VALUES ('$name', $quantity, $price, '$expiry_date')";
    $message = mysqli_query($conn, $sql) ? "<div class='message success'>Medicine added successfully!</div>" : "<div class='message error'>Error: " . mysqli_error($conn) . "</div>";
}

// --- Handle Soft Delete ---
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $sql = "UPDATE medicines SET is_active = 0 WHERE id = $id";
    $message = mysqli_query($conn, $sql) ? "<div class='message success'>Medicine deleted successfully.</div>" : "<div class='message error'>Error: " . mysqli_error($conn) . "</div>";
}

// --- Handle Update ---
if (isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $quantity = intval($_POST['quantity']);
    $price = floatval($_POST['price']);
    $expiry_date = mysqli_real_escape_string($conn, $_POST['expiry_date']);
    $sql = "UPDATE medicines SET name='$name', quantity=$quantity, price=$price, expiry_date='$expiry_date' WHERE id=$id";
    mysqli_query($conn, $sql);
    header("Location: medicines.php");
    exit;
}

// --- Fetch All Active Medicines ---
$medicines_result = mysqli_query($conn, "SELECT * FROM medicines WHERE is_active = 1 ORDER BY name ASC");

// --- Fetch One for Edit ---
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $result = mysqli_query($conn, "SELECT * FROM medicines WHERE id = $id");
    $edit_data = mysqli_fetch_assoc($result);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Medicines | PharmaLink</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
    <div class="container">
        <div class="header-content">
            <div class="brand">
                <a href="index.php" class="logo"><svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" /></svg></a>
                <h1>PharmaLink</h1>
            </div>
            <nav>
                <a href="index.php">Home</a>
                <a href="patients.php">Patients</a>
                <a href="doctors.php">Doctors</a>
                <a class="active" href="medicines.php">Medicines</a>
                <a href="prescriptions.php">Prescriptions</a>
                <a href="reports.php">Reports</a>
            </nav>
        </div>
    </div>
</header>

<main class="container">
    <div class="page-header">
        <h2><?php echo $edit_data ? 'Edit Medicine' : 'Add New Medicine'; ?></h2>
    </div>
    <?php if (!empty($message)) echo $message; ?>

    <!-- Add/Edit Form -->
    <div class="card form-container">
        <form method="POST" action="medicines.php">
            <input type="hidden" name="id" value="<?php echo $edit_data['id'] ?? ''; ?>">
            <div class="form-grid">
                <div class="form-group">
                    <label>Medicine Name</label>
                    <input type="text" name="name" value="<?php echo $edit_data['name'] ?? ''; ?>" required>
                </div>
                <div class="form-group">
                    <label>Quantity</label>
                    <input type="number" name="quantity" value="<?php echo $edit_data['quantity'] ?? ''; ?>" required>
                </div>
                <div class="form-group">
                    <label>Price</label>
                    <input type="number" step="0.01" name="price" value="<?php echo $edit_data['price'] ?? ''; ?>" required>
                </div>
                <div class="form-group">
                    <label>Expiry Date</label>
                    <input type="date" name="expiry_date" value="<?php echo $edit_data['expiry_date'] ?? ''; ?>" required>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" name="<?php echo $edit_data ? 'update' : 'add'; ?>" class="btn btn-primary">
                    <?php echo $edit_data ? 'Update Medicine' : 'Save Medicine'; ?>
                </button>
                <?php if ($edit_data) { ?>
                    <a href="medicines.php" class="btn btn-secondary">Cancel</a>
                <?php } ?>
            </div>
        </form>
    </div>

    <!-- Medicine List -->
    <div class="card">
        <h3>Medicine Stock List</h3>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Expiry Date</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (mysqli_num_rows($medicines_result) > 0) {
                        while ($row = mysqli_fetch_assoc($medicines_result)) {
                            echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['name']}</td>
                                <td>{$row['quantity']}</td>
                                <td>" . number_format($row['price'], 2) . "</td>
                                <td>{$row['expiry_date']}</td>
                                <td>
                                    <a href='medicines.php?edit={$row['id']}' class='btn btn-sm btn-edit'>Edit</a>
                                    <a href='medicines.php?delete={$row['id']}' class='btn btn-sm btn-delete' onclick='return confirm(\"Are you sure you want to delete this medicine?\")'>Delete</a>
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No active medicines found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<footer>© 2025 Integrated Healthcare & Pharmacy. All Rights Reserved.</footer>

<?php mysqli_close($conn); ?>
</body>
</html>
