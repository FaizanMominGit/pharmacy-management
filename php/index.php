<?php
// 1. Database Connection
include(__DIR__ . '/config.php');

// 2. Fetch Data - Get counts for active records
$patient_count = 0;
$doctor_count = 0;
$medicine_count = 0;

// Query to get the total number of active patients
$sql_patients = "SELECT COUNT(id) as total_patients FROM patients WHERE is_active = 1";
$result_patients = mysqli_query($conn, $sql_patients);
if ($result_patients && mysqli_num_rows($result_patients) > 0) {
    $row = mysqli_fetch_assoc($result_patients);
    $patient_count = $row['total_patients'];
}

// Query to get the total number of active doctors
$sql_doctors = "SELECT COUNT(id) as total_doctors FROM doctors WHERE is_active = 1";
$result_doctors = mysqli_query($conn, $sql_doctors);
if ($result_doctors && mysqli_num_rows($result_doctors) > 0) {
    $row = mysqli_fetch_assoc($result_doctors);
    $doctor_count = $row['total_doctors'];
}

// Query to get the total number of active medicines
$sql_medicines = "SELECT COUNT(id) as total_medicines FROM medicines WHERE is_active = 1";
$result_medicines = mysqli_query($conn, $sql_medicines);
if ($result_medicines && mysqli_num_rows($result_medicines) > 0) {
    $row = mysqli_fetch_assoc($result_medicines);
    $medicine_count = $row['total_medicines'];
}

// Close the connection
mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | Integrated Healthcare</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <header>
        <div class="container">
            <div class="header-content">
                <div class="brand">
                    <a href="index.php" class="logo">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z" />
                        </svg>
                    </a>
                    <h1>PharmaLink</h1>
                </div>
                <nav>
                    <a class="active" href="index.php">Home</a>
                    <a href="patients.php">Patients</a>
                    <a href="doctors.php">Doctors</a>
                    <a href="medicines.php">Medicines</a>
                    <a href="prescriptions.php">Prescriptions</a>
                    <a href="reports.php">Reports</a>
                    <span class="nav-spacer"></span>
                </nav>
            </div>
        </div>
    </header>

    <main class="container">
        <section class="hero">
            <h2 class="title">All-in-One Healthcare & Pharmacy Management</h2>
            <p class="subtitle">Streamline your clinic's operations with a simple, fast, and modern management system. Handle patients, doctors, and inventory with ease.</p>
            <div class="cta-buttons">
                <a class="btn btn-primary" href="patients.php">Add New Patient</a>
                <a class="btn btn-ghost" href="reports.php">View Reports</a>
            </div>
        </section>

        <!-- The features section now shows the live counts from the database -->
        <div class="features">
            <div class="feature">
                <div class="icon icon-patient">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg>
                </div>
                <div>
                    <h4>Active Patients</h4>
                    <p class="stat-number"><?php echo $patient_count; ?></p>
                </div>
            </div>
            <div class="feature">
                <div class="icon icon-inventory">
                     <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </div>
                <div>
                    <h4>Active Doctors</h4>
                    <p class="stat-number"><?php echo $doctor_count; ?></p>
                </div>
            </div>
            <div class="feature">
                <div class="icon icon-reports">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14c0 1.1.9 2 2 2h7"/><path d="M14 15v6"/><path d="M17 18h-6"/><path d="M12 4h.01"/><path d="M16 4h.01"/><path d="M8 4h.01"/></svg>
                </div>
                <div>
                    <h4>Active Medicines</h4>
                    <p class="stat-number"><?php echo $medicine_count; ?></p>
                </div>
            </div>
        </div>

        <div class="content-grid">
            <div class="card">
                <div class="card-header">
                    <div class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/></svg></div>
                    <h3>Patients</h3>
                </div>
                <p>Add new patient records or view the complete list of all registered patients.</p>
                <div class="actions">
                    <a class="btn btn-secondary" href="patients.php">Add Patient</a>
                    <a class="btn btn-ghost" href="patients.php">View All</a>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <div class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg></div>
                    <h3>Doctors</h3>
                </div>
                <p>Maintain an up-to-date directory of all doctors and their specializations.</p>
                <div class="actions">
                    <a class="btn btn-secondary" href="doctors.php">Add Doctor</a>
                    <a class="btn btn-ghost" href="doctors.php">View All</a>
                </div>
            </div>
            <div class="card">
                <div class="card-header">
                    <div class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10V6a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14c0 1.1.9 2 2 2h7"/><path d="M14 15v6"/><path d="M17 18h-6"/><path d="M12 4h.01"/><path d="M16 4h.01"/><path d="M8 4h.01"/></svg></div>
                    <h3>Medicines</h3>
                </div>
                <p>Manage your pharmacy's inventory, including stock, pricing, and expiry.</p>
                <div class="actions">
                    <a class="btn btn-secondary" href="medicines.php">Add Medicine</a>
                    <a class="btn btn-ghost" href="medicines.php">View All</a>
                </div>
            </div>
        </div>
    </main>

    <footer>
        © 2025 Integrated Healthcare & Pharmacy. All Rights Reserved.
    </footer>
</body>
</html>
