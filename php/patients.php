<?php
include 'config.php'; 
$message = '';

// --- Handle Add ---
if (isset($_POST['add'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $sql = "INSERT INTO patients (name, gender, contact, address) VALUES ('$name', '$gender', '$contact', '$address')";
    $message = mysqli_query($conn, $sql) ? "<div class='message success'>Patient added successfully!</div>" : "<div class='message error'>Error adding patient: " . mysqli_error($conn) . "</div>";
}

// --- Handle Soft Delete ---
if (isset($_GET['delete'])) {
    $id = intval($_GET['delete']);
    $sql = "UPDATE patients SET is_active = 0 WHERE id = $id";
    $message = mysqli_query($conn, $sql) ? "<div class='message success'>Patient deleted successfully.</div>" : "<div class='message error'>Error deleting patient: " . mysqli_error($conn) . "</div>";
}

// --- Handle Edit ---
if (isset($_POST['update'])) {
    $id = intval($_POST['id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $gender = mysqli_real_escape_string($conn, $_POST['gender']);
    $contact = mysqli_real_escape_string($conn, $_POST['contact']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $sql = "UPDATE patients SET name='$name', gender='$gender', contact='$contact', address='$address' WHERE id=$id";
    mysqli_query($conn, $sql);
    header("Location: patients.php");
    exit;
}

// --- Fetch All Active ---
$patients_result = mysqli_query($conn, "SELECT * FROM patients WHERE is_active = 1 ORDER BY name ASC");

// --- Fetch One for Edit ---
$edit_data = null;
if (isset($_GET['edit'])) {
    $id = intval($_GET['edit']);
    $result = mysqli_query($conn, "SELECT * FROM patients WHERE id = $id");
    $edit_data = mysqli_fetch_assoc($result);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Patients | PharmaLink</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<header>
    <div class="container">
        <div class="header-content">
            <div class="brand">
                <a href="index.php" class="logo">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" /></svg>
                </a>
                <h1>PharmaLink</h1>
            </div>
            <nav>
                <a href="index.php">Home</a>
                <a class="active" href="patients.php">Patients</a>
                <a href="doctors.php">Doctors</a>
                <a href="medicines.php">Medicines</a>
                <a href="prescriptions.php">Prescriptions</a>
                <a href="reports.php">Reports</a>
            </nav>
        </div>
    </div>
</header>

<main class="container">
    <div class="page-header">
        <h2><?php echo $edit_data ? 'Edit Patient' : 'Add New Patient'; ?></h2>
    </div>
    <?php if (!empty($message)) echo $message; ?>

    <!-- Add/Edit Form -->
    <div class="card form-container">
        <form method="POST" action="patients.php">
            <input type="hidden" name="id" value="<?php echo $edit_data['id'] ?? ''; ?>">
            <div class="form-grid">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" value="<?php echo $edit_data['name'] ?? ''; ?>" required>
                </div>
                <div class="form-group">
                    <label>Gender</label>
                    <select name="gender" required>
                        <option value="" disabled selected>Select gender</option>
                        <option value="Male" <?php if (($edit_data['gender'] ?? '') == 'Male') echo 'selected'; ?>>Male</option>
                        <option value="Female" <?php if (($edit_data['gender'] ?? '') == 'Female') echo 'selected'; ?>>Female</option>
                        <option value="Other" <?php if (($edit_data['gender'] ?? '') == 'Other') echo 'selected'; ?>>Other</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Contact Number</label>
                    <input type="text" name="contact" value="<?php echo $edit_data['contact'] ?? ''; ?>" required>
                </div>
                <div class="form-group">
                    <label>Address</label>
                    <input type="text" name="address" value="<?php echo $edit_data['address'] ?? ''; ?>" required>
                </div>
            </div>
            <div class="form-actions">
                <button type="submit" name="<?php echo $edit_data ? 'update' : 'add'; ?>" class="btn btn-primary">
                    <?php echo $edit_data ? 'Update Patient' : 'Save Patient'; ?>
                </button>
                <?php if ($edit_data) { ?>
                    <a href="patients.php" class="btn btn-secondary">Cancel</a>
                <?php } ?>
            </div>
        </form>
    </div>

    <!-- Patient List -->
    <div class="card">
        <h3>Patient List</h3>
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Gender</th>
                        <th>Contact</th>
                        <th>Address</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (mysqli_num_rows($patients_result) > 0) {
                        while ($row = mysqli_fetch_assoc($patients_result)) {
                            echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['name']}</td>
                                <td>{$row['gender']}</td>
                                <td>{$row['contact']}</td>
                                <td>{$row['address']}</td>
                                <td>
                                    <a href='patients.php?edit={$row['id']}' class='btn btn-sm btn-edit'>Edit</a>
                                    <a href='patients.php?delete={$row['id']}' class='btn btn-sm btn-delete' onclick='return confirm(\"Are you sure you want to delete this patient? This cannot be undone.\")'>Delete</a>
                                </td>
                            </tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>No active patients found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</main>

<footer>
    © 2025 Integrated Healthcare & Pharmacy. All Rights Reserved.
</footer>

<?php mysqli_close($conn); ?>
</body>
</html>
