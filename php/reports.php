<?php
include 'config.php';

// --- Fetch all counts for active records ---

// 1. Get total active patients
$result_patients = mysqli_query($conn, "SELECT COUNT(id) AS total FROM patients WHERE is_active = 1");
$patient_count = mysqli_fetch_assoc($result_patients)['total'] ?? 0;

// 2. Get total active doctors
$result_doctors = mysqli_query($conn, "SELECT COUNT(id) AS total FROM doctors WHERE is_active = 1");
$doctor_count = mysqli_fetch_assoc($result_doctors)['total'] ?? 0;

// 3. Get total active medicines
$result_medicines = mysqli_query($conn, "SELECT COUNT(id) AS total FROM medicines WHERE is_active = 1");
$medicine_count = mysqli_fetch_assoc($result_medicines)['total'] ?? 0;

// 4. Get total prescriptions
$result_prescriptions = mysqli_query($conn, "SELECT COUNT(id) AS total FROM prescriptions");
$prescription_count = mysqli_fetch_assoc($result_prescriptions)['total'] ?? 0;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reports | PharmaLink</title>
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
                    <a href="patients.php">Patients</a>
                    <a href="doctors.php">Doctors</a>
                    <a href="medicines.php">Medicines</a>
                    <a href="prescriptions.php">Prescriptions</a>
                    <a class="active" href="reports.php">Reports</a>
                </nav>
            </div>
        </div>
    </header>

    <main class="container">
        <div class="page-header">
            <h2>System Summary</h2>
            <p>A high-level overview of all the active data in the healthcare management system.</p>
        </div>

        <!-- Stats Grid -->
        <div class="content-grid">
            <div class="stat-card">
                <div class="icon icon-patient">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-4.67c.12-.241.242-.487.363-.732a9.04 9.04 0 012.824 4.043 9.04 9.04 0 01-1.33 4.28z" /></svg>
                </div>
                <div class="stat-info">
                    <h4>Active Patients</h4>
                    <p class="stat-number"><?php echo $patient_count; ?></p>
                </div>
            </div>

            <div class="stat-card">
                <div class="icon icon-doctor">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
                </div>
                <div class="stat-info">
                    <h4>Active Doctors</h4>
                    <p class="stat-number"><?php echo $doctor_count; ?></p>
                </div>
            </div>

            <div class="stat-card">
                <div class="icon icon-inventory">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M21 11.25v8.25a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5v-8.25M12 4.875A2.625 2.625 0 1014.625 7.5H9.375A2.625 2.625 0 1012 4.875z" /></svg>
                </div>
                <div class="stat-info">
                    <h4>Active Medicines</h4>
                    <p class="stat-number"><?php echo $medicine_count; ?></p>
                </div>
            </div>
            
            <div class="stat-card">
                <div class="icon icon-reports">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h.01M15 12h.01M7.834 16.596a.75.75 0 01.336-1.025l2.643-1.524a.75.75 0 01.668 0l2.643 1.524a.75.75 0 01.336 1.025m-5.962 0A8.25 8.25 0 0112 15a8.25 8.25 0 015.166 1.596m-5.962 0a8.25 8.25 0 00-5.166 1.596M15 9.75a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                </div>
                <div class="stat-info">
                    <h4>Total Prescriptions</h4>
                    <p class="stat-number"><?php echo $prescription_count; ?></p>
                </div>
            </div>
        </div>
    </main>

    <footer>
        © 2025 Integrated Healthcare & Pharmacy. All Rights Reserved.
    </footer>

    <?php
    mysqli_close($conn);
    ?>
</body>
</html>
