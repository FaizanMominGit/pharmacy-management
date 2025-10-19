<?php
include 'config.php';
$message = '';

// --- Handle Add ---
if (isset($_POST['add'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $specialization = mysqli_real_escape_string($conn, $_POST['specialization']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $sql = "INSERT INTO doctors (name, specialization, phone) VALUES ('$name', '$specialization', '$phone')";
    $message = mysqli_query($conn, $sql) ? "<div class='message success'>Doctor added successfully!</div>" : "<div class='message error'>Error: " . mysqli_error($conn) . "</div>";
}

// --- Handle Soft Delete ---
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $sql = "UPDATE doctors SET is_active = 0 WHERE id = $id";
    $message = mysqli_query($conn, $sql) ? "<div class='message success'>Doctor deleted successfully.</div>" : "<div class='message error'>Error: " . mysqli_error($conn) . "</div>";
}

// --- Handle Update ---
if (isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $specialization = mysqli_real_escape_string($conn, $_POST['specialization']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $sql = "UPDATE doctors SET name='$name', specialization='$specialization', phone='$phone' WHERE id=$id";
    mysqli_query($conn, $sql);
    header("Location: doctors.php");
    exit;
}

// --- Fetch All Active Doctors ---
$doctors_result = mysqli_query($conn, "SELECT * FROM doctors WHERE is_active = 1 ORDER BY name ASC");

// --- Fetch One for Edit ---
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $result = mysqli_query($conn, "SELECT * FROM doctors WHERE id = $id");
    $edit_data = mysqli_fetch_assoc($result);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Doctors | PharmaLink</title>
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
                <a class="active" href="doctors.php">Doctors</a>
                <a href="medicines.php">Medicines</a>
                <a href="prescriptions.php">Prescriptions</a>
                <a href="reports.php">Reports</a>
            </nav>
        </div>
    </div>
</header>

<main class="container">
    <div class="page-header">
        <h2><?php echo $edit_data ? 'Edit Doctor' : 'Add New Doctor'; ?></h2>
    </div>
    <?php if (!empty($message)) echo $message; ?>

    <!-- Add/Edit Form -->
    <div class="card form-container">
        <form method="POST" action="doctors.php">
            <input type="hidden" name="id" value="<?php echo $edit_data['id'] ?? ''; ?>">
            <div class="form-grid">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" value="<?php echo $edit_data['name'] ?? ''; ?>" required>
                </div>
                <div class="form-group">
                    <label>Specialization</label>
                    <input type="text" name="specialization" value="<?php echo $edit_data['specialization'] ?? ''; ?>" required>
                </div>
                <div class="form-group">
                    <label>Phone</label>
                    <input type="text" name="phone" value="<?php echo $edit_data['phone'] ?? ''; ?>" required>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" name="<?php echo $edit_data ? 'update' : 'add'; ?>" class="btn btn-primary">
                    <?php echo $edit_data ? 'Update Doctor' : 'Save Doctor'; ?>
                </button>
                <?php if ($edit_data) { ?>
                    <a href="doctors.php" class="btn btn-secondary">Cancel</a>
                <?php } ?>
            </div>
        </form>
    </div>

    <!-- Doctor List -->
    <div class="card">
        <h3>Doctor List</h3>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Specialization</th>
                        <th>Phone</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (mysqli_num_rows($doctors_result) > 0) {
                        while ($row = mysqli_fetch_assoc($doctors_result)) {
                            echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['name']}</td>
                                <td>{$row['specialization']}</td>
                                <td>{$row['phone']}</td>
                                <td>
                                    <a href='doctors.php?edit={$row['id']}' class='btn btn-sm btn-edit'>Edit</a>
                                    <a href='doctors.php?delete={$row['id']}' class='btn btn-sm btn-delete' onclick='return confirm(\"Are you sure you want to delete this doctor?\")'>Delete</a>
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='5'>No active doctors found.</td></tr>";
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
