<?php
session_start();
include('connect.php');

// Fetch patient ID from session
$patient_id = $_SESSION['patient_id'];

// Fetch medical records
$query_records = "
    SELECT 
        a.Appointment_Date,
        CONCAT(d.FName, ' ', d.LName) AS doctor_name,
        m.Notes
    FROM 
        Medical_Record m
    JOIN 
        Appointment a ON m.Appointment_ID = a.Appointment_ID
    JOIN 
        Doctor d ON a.Doctor_ID = d.Doctor_ID
    WHERE 
        a.Patient_ID = ?
    ORDER BY 
        a.Appointment_Date
";

$stmt = $mysqli->prepare($query_records);
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result_records = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Records</title>
    <link rel="stylesheet" href="patient_medical_records.css">
    <link rel="icon" type="image/jpg" href="logo.jpg">
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
            <h1>Medical Records</h1>
            <?php if ($result_records->num_rows > 0): ?>
                <?php while ($record = $result_records->fetch_assoc()): ?>
                    <div class="record-card">
                        <p><b>Appointment Date:</b> <?php echo date('d M Y, H:i', strtotime($record['Appointment_Date'])); ?></p>
                        <p><b>Doctor:</b> <?php echo htmlspecialchars($record['doctor_name']); ?></p>
                        <p><b>Notes:</b> <?php echo nl2br(htmlspecialchars($record['Notes'])); ?></p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No medical records found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>