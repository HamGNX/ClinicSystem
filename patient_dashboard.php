<?php
session_start();
include('connect.php');

// Fetch patient ID from session
$patient_id = $_SESSION['patient_id'];

// Fetch patient details
$query_patient = "SELECT FName, LName, DOB, Phone FROM Patient WHERE Patient_ID = ?";
$stmt_patient = $mysqli->prepare($query_patient);
$stmt_patient->bind_param("i", $patient_id);
$stmt_patient->execute();
$result_patient = $stmt_patient->get_result();
$patient = $result_patient->fetch_assoc();

// Handle potential NULL or invalid DOB
if (!empty($patient['DOB'])) {
    try {
        $birth_date = new DateTime($patient['DOB']);
        $current_date = new DateTime();
        $age = $birth_date->diff($current_date)->y;
    } catch (Exception $e) {
        $age = "Unknown"; // Fallback value if DOB is invalid
    }
} else {
    $age = "Unknown"; // Fallback value if DOB is NULL
}

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_profile'])) {
    $updated_phone = $_POST['phone'];
    $updated_dob = $_POST['dob'];

    $update_query = "UPDATE Patient SET Phone = ?, DOB = ? WHERE Patient_ID = ?";
    $update_stmt = $mysqli->prepare($update_query);
    $update_stmt->bind_param("ssi", $updated_phone, $updated_dob, $patient_id);
    $update_stmt->execute();

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Fetch all appointments for the patient
$query_appointments = "
    SELECT 
        a.Appointment_Date, 
        d.FName AS doctor_first_name, 
        d.LName AS doctor_last_name, 
        s.Specialty_Name
    FROM 
        Appointment a
    JOIN 
        Doctor d ON a.Doctor_ID = d.Doctor_ID
    JOIN 
        Specialty s ON d.Specialty_ID = s.Specialty_ID
    WHERE 
        a.Patient_ID = ?
    ORDER BY 
        a.Appointment_Date
";

$stmt_appointments = $mysqli->prepare($query_appointments);
$stmt_appointments->bind_param("i", $patient_id);
$stmt_appointments->execute();
$result_appointments = $stmt_appointments->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Dashboard</title>
    <link rel="stylesheet" href="patient_dashboard.css">
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <a href="patient_dashboard.php" class="sidebar-button">Profile</a>
            <a href="patient_appointments.php" class="sidebar-button">Appointment</a>
            <a href="patient_payment.php" class="sidebar-button">Payment</a>
            <a href="patient_medical_records.php" class="sidebar-button">Medical Record</a>
            <a href="index.php" class="sidebar-button">Logout</a>
        </div>

        <!-- Content -->
        <div class="content">
    <!-- Profile Section -->
    <div class="profile-section">
        <h1>Welcome!!!</h1>
        <h1>⠀</h1>
        <p><b>Full Name:</b> <?php echo htmlspecialchars($patient['FName'] . ' ' . $patient['LName']); ?></p>
        <p><b>Age:</b> <?php echo $age; ?></p>
        <form method="POST">
            <label for="dob"><b>Date of Birth:</b></label>
            <input type="date" id="dob" name="dob" value="<?php echo !empty($patient['DOB']) ? htmlspecialchars($patient['DOB']) : ''; ?>" required>
            <br><br>
            <label for="phone"><b>Phone Number:</b></label>
            <input type="text" id="phone" name="phone" value="<?php echo htmlspecialchars($patient['Phone']); ?>" required>
            <br><br>
            <button type="submit" name="update_profile">Update</button>
        </form>
    </div>

    <!-- Appointments Section -->
    <div class="appointment-container">
        <h2>All Appointments</h2>
        <?php if ($result_appointments->num_rows > 0): ?>
            <?php while ($appointment = $result_appointments->fetch_assoc()): ?>
                <div class="appointment-card">
                    <p><b>Date:</b> <?php echo date('d M Y, H:i', strtotime($appointment['Appointment_Date'])); ?></p>
                    <p><b>Doctor:</b> <?php echo htmlspecialchars($appointment['doctor_first_name'] . ' ' . $appointment['doctor_last_name']); ?></p>
                    <p><b>Specialty:</b> <?php echo htmlspecialchars($appointment['Specialty_Name']); ?></p>
                    <form method="POST" action="patient_drop_appointment.php">
                        <input type="hidden" name="appointment_date" value="<?php echo htmlspecialchars($appointment['Appointment_Date']); ?>">
                        <button type="submit">Drop</button>
                    </form>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>No appointments found.</p>
        <?php endif; ?>
    </div>
</div>
    </div>
</body>
</html>