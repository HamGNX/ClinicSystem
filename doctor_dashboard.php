<?php
// Start the session
session_start();

// Include database connection
include 'connect.php';

// Check if the doctor is logged in
if (!isset($_SESSION['doctor_id'])) {
    header("Location: index.php");
    exit();
}

$doctor_id = $_SESSION['doctor_id'];

// Query to fetch doctor name and specialty
$query = "
    SELECT 
        CONCAT(d.FName, ' ', d.LName) AS doctor_name,
        s.Specialty_Name AS specialty
    FROM 
        Doctor d
    JOIN 
        Specialty s ON d.Specialty_ID = s.Specialty_ID
    WHERE 
        d.Doctor_ID = ?
";

$stmt = $mysqli->prepare($query);
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $doctor_name = $row['doctor_name'];
    $specialty = $row['specialty'];
} else {
    $doctor_name = "Unknown";
    $specialty = "Specialty Unknown";
}

// Query to fetch all appointments
$query_appointments = "
    SELECT 
        a.Appointment_ID,
        a.Appointment_Date, 
        CONCAT(p.FName, ' ', p.LName) AS patient_name 
    FROM 
        Appointment a
    JOIN 
        Patient p ON a.Patient_ID = p.Patient_ID
    WHERE 
        a.Doctor_ID = ?
    ORDER BY 
        a.Appointment_Date
";

$stmt_appointments = $mysqli->prepare($query_appointments);
$stmt_appointments->bind_param("i", $doctor_id);
$stmt_appointments->execute();
$appointments = $stmt_appointments->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="doctor_dashboard.css">
    <title>Doctor Dashboard</title>
</head>
<body>
    <div class="dashboard-container">
        <div class="sidebar">
            <a href="doctor_dashboard.php" class="sidebar-button">Appointments</a>
            <a href="doctor_medical_record.php" class="sidebar-button">Medical Record</a>
            <a href="index.php" class="sidebar-button">Logout</a>
        </div>
        <div class="content">
            <h1>Welcome, <?php echo htmlspecialchars($doctor_name); ?>!</h1>
            <h2>Specialty: <?php echo htmlspecialchars($specialty); ?></h2>
            <h3>All Appointments</h3>
            <?php if ($appointments->num_rows > 0): ?>
                <?php while ($appointment = $appointments->fetch_assoc()): ?>
                    <div class="appointment-card">
                        <p>Date: <?php echo date("Y-m-d H:i", strtotime($appointment['Appointment_Date'])); ?></p>
                        <p>Patient: <?php echo htmlspecialchars($appointment['patient_name']); ?></p>
                        <form method="POST" action="drop_appointment.php">
                            <input type="hidden" name="appointment_id" value="<?php echo $appointment['Appointment_ID']; ?>">
                            <button type="submit" class="drop-button">Drop</button>
                        </form>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No appointments found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>