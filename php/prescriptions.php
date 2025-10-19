<?php
include 'config.php';
$message = '';

// --- Handle Form Submission ---
if (isset($_POST['add'])) {
    $patient_id = mysqli_real_escape_string($conn, $_POST['patient_id']);
    $doctor_id = mysqli_real_escape_string($conn, $_POST['doctor_id']);
    $dosages = $_POST['dosage'];
    $taken_from_inventory = isset($_POST['from_inventory']) ? true : false;

    if (isset($_POST['medicine_name'])) {
        foreach ($_POST['medicine_name'] as $index => $medicine_name) {
            $medicine_name = trim(mysqli_real_escape_string($conn, $medicine_name));
            $dosage = mysqli_real_escape_string($conn, $dosages[$index]);

            if (empty($medicine_name)) continue;

            $check_med = mysqli_query($conn, "SELECT id, quantity FROM medicines WHERE name = '$medicine_name' AND is_active = 1");
            if (mysqli_num_rows($check_med) > 0) {
                $med_data = mysqli_fetch_assoc($check_med);
                $medicine_id = $med_data['id'];

                if ($taken_from_inventory) {
                    $new_quantity = max(0, $med_data['quantity'] - 1);
                    mysqli_query($conn, "UPDATE medicines SET quantity = $new_quantity WHERE id = $medicine_id");
                }
            } else {
                mysqli_query($conn, "INSERT INTO medicines (name, quantity, price, expiry_date) VALUES ('$medicine_name', 0, 0, NOW())");
                $medicine_id = mysqli_insert_id($conn);
            }

            mysqli_query($conn, "INSERT INTO prescriptions (patient_id, doctor_id, medicine_id, dosage) VALUES ($patient_id, $doctor_id, $medicine_id, '$dosage')");
        }
    }
    $message = "<div class='message success'>Prescription(s) added successfully!</div>";
}

// --- Fetch active data for dropdowns ---
$patients_result = mysqli_query($conn, "SELECT id, name FROM patients WHERE is_active = 1 ORDER BY name ASC");
$doctors_result = mysqli_query($conn, "SELECT id, name FROM doctors WHERE is_active = 1 ORDER BY name ASC");
$medicines_result = mysqli_query($conn, "SELECT name, quantity, expiry_date FROM medicines WHERE is_active = 1 AND quantity > 0 AND expiry_date > NOW() ORDER BY name ASC");

// --- Fetch all prescriptions for historical view ---
$prescriptions_sql = "SELECT pre.id, 
                             pat.name as patient_name, pat.is_active as patient_is_active,
                             doc.name as doctor_name, doc.is_active as doctor_is_active,
                             med.name as medicine_name, med.is_active as medicine_is_active,
                             pre.dosage, pre.prescription_date
                      FROM prescriptions pre
                      LEFT JOIN patients pat ON pre.patient_id = pat.id
                      LEFT JOIN doctors doc ON pre.doctor_id = doc.id
                      LEFT JOIN medicines med ON pre.medicine_id = med.id
                      ORDER BY pre.prescription_date DESC";
$prescriptions_result = mysqli_query($conn, $prescriptions_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Prescriptions | PharmaLink</title>
    <link rel="stylesheet" href="styles.css">
    <script>
        function addMedicineField() {
            const container = document.getElementById('medicine-container');
            const field = document.createElement('div');
            field.classList.add('form-grid', 'medicine-group');
            field.style.cssText = 'grid-template-columns: 1fr 1fr auto; align-items: end;';
            field.innerHTML = `
                <div class="form-group">
                    <label>Medicine</label>
                    <input type="text" name="medicine_name[]" placeholder="Enter or select medicine" list="medicinesList" required>
                </div>
                <div class="form-group">
                    <label>Dosage</label>
                    <input type="text" name="dosage[]" placeholder="e.g., 1 tablet twice a day" required>
                </div>
                <div class="form-group">
                    <button type="button" class="btn btn-danger" onclick="this.closest('.medicine-group').remove()">Remove</button>
                </div>
            `;
            container.appendChild(field);
        }
    </script>
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
                <a href="medicines.php">Medicines</a>
                <a class="active" href="prescriptions.php">Prescriptions</a>
                <a href="reports.php">Reports</a>
            </nav>
        </div>
    </div>
</header>

<main class="container">
    <div class="page-header">
        <h2>Manage Prescriptions</h2>
        <p>Create new prescriptions by linking patients, doctors, and medicines.</p>
    </div>

    <?php if (!empty($message)) echo $message; ?>

    <div class="content-layout">
        <div class="card form-container">
            <h3>Add New Prescription</h3>
            <form method="POST" action="prescriptions.php">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="patient_id">Patient</label>
                        <select id="patient_id" name="patient_id" required>
                            <option value="" disabled selected>Select a patient</option>
                            <?php mysqli_data_seek($patients_result, 0); while($p = mysqli_fetch_assoc($patients_result)) echo "<option value='{$p['id']}'>{$p['name']}</option>"; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="doctor_id">Doctor</label>
                        <select id="doctor_id" name="doctor_id" required>
                            <option value="" disabled selected>Select a doctor</option>
                            <?php mysqli_data_seek($doctors_result, 0); while($d = mysqli_fetch_assoc($doctors_result)) echo "<option value='{$d['id']}'>{$d['name']}</option>"; ?>
                        </select>
                    </div>
                </div>

                <div id="medicine-container">
                    <div class="form-grid medicine-group" style="grid-template-columns: 1fr 1fr auto; align-items: end;">
                        <div class="form-group">
                            <label>Medicine</label>
                            <input type="text" name="medicine_name[]" placeholder="medicine" list="medicinesList" required>
                        </div>
                        <div class="form-group">
                            <label>Dosage</label>
                            <input type="text" name="dosage[]" placeholder="dosage" required>
                        </div>
                        <div class="form-group">
                            <button type="button" class="btn btn-danger" onclick="this.closest('.medicine-group').remove()">Remove</button>
                        </div>
                    </div>
                </div>

                <datalist id="medicinesList">
                    <?php 
                    mysqli_data_seek($medicines_result, 0); 
                    while($m = mysqli_fetch_assoc($medicines_result)) {
                        $exp_date = date('d-M-Y', strtotime($m['expiry_date']));
                        echo "<option value='{$m['name']}'>Stock: {$m['quantity']} | Expires: {$exp_date}</option>"; 
                    }
                    ?>
                </datalist>

                <div class="form-group">
                    <button type="button" class="btn btn-secondary" onclick="addMedicineField()">+ Add Another Medicine</button>
                </div>

                <div class="form-group form-check">
                    <input type="checkbox" class="form-check-input" id="from_inventory" name="from_inventory">
                    <label class="form-check-label" for="from_inventory">Taken from Inventory (Reduce stock)</label>
                </div>

                <div class="form-actions">
                    <button type="submit" name="add" class="btn btn-primary">Save Prescription</button>
                </div>
            </form>
        </div>

        <div class="card">
            <h3>Prescription History</h3>
            <div class="table-container">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>ID</th><th>Patient</th><th>Doctor</th><th>Medicine</th><th>Dosage</th><th>Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        if (mysqli_num_rows($prescriptions_result) > 0) {
                            while ($row = mysqli_fetch_assoc($prescriptions_result)) {
                                $patient_name = $row['patient_name'] . ($row['patient_is_active'] ? '' : ' <span class="inactive-tag">(Inactive)</span>');
                                $doctor_name = $row['doctor_name'] . ($row['doctor_is_active'] ? '' : ' <span class="inactive-tag">(Inactive)</span>');
                                $medicine_name = $row['medicine_name'] . ($row['medicine_is_active'] ? '' : ' <span class="inactive-tag">(Inactive)</span>');

                                echo "<tr>
                                        <td>{$row['id']}</td>
                                        <td>{$patient_name}</td>
                                        <td>{$doctor_name}</td>
                                        <td>{$medicine_name}</td>
                                        <td>{$row['dosage']}</td>
                                        <td>" . date('d M, Y', strtotime($row['prescription_date'])) . "</td>
                                      </tr>";
                            }
                        } else {
                            echo "<tr><td colspan='6'>No prescriptions found.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</main>

<footer>© 2025 Integrated Healthcare & Pharmacy. All Rights Reserved.</footer>

<?php mysqli_close($conn); ?>
</body>
</html