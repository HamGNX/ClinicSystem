<?php
session_start();
include('connect.php');

// Fetch doctor ID from session
$doctor_id = $_SESSION['doctor_id'];

// Fetch appointments and associated medical records
$query_records = "
    SELECT 
        a.Appointment_ID,
        a.Appointment_Date,
        CONCAT(p.FName, ' ', p.LName) AS patient_name,
        IFNULL(m.Notes, '') AS notes
    FROM 
        Appointment a
    JOIN 
        Patient p ON a.Patient_ID = p.Patient_ID
    LEFT JOIN 
        Medical_Record m ON a.Appointment_ID = m.Appointment_ID
    WHERE 
        a.Doctor_ID = ?
    ORDER BY 
        a.Appointment_Date
";

$stmt = $mysqli->prepare($query_records);
$stmt->bind_param("i", $doctor_id);
$stmt->execute();
$result = $stmt->get_result();

// Handle form submission to save notes
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['appointment_id'], $_POST['notes'])) {
    $appointment_id = $_POST['appointment_id'];
    $notes = $_POST['notes'];

    // Check if the record already exists
    $check_query = "SELECT * FROM Medical_Record WHERE Appointment_ID = ?";
    $check_stmt = $mysqli->prepare($check_query);
    $check_stmt->bind_param("i", $appointment_id);
    $check_stmt->execute();
    $existing_record = $check_stmt->get_result()->fetch_assoc();

    if ($existing_record) {
        // Update existing record
        $update_query = "UPDATE Medical_Record SET Notes = ? WHERE Appointment_ID = ?";
        $update_stmt = $mysqli->prepare($update_query);
        $update_stmt->bind_param("si", $notes, $appointment_id);
        $update_stmt->execute();
    } else {
        // Insert new record
        $insert_query = "INSERT INTO Medical_Record (Appointment_ID, Notes) VALUES (?, ?)";
        $insert_stmt = $mysqli->prepare($insert_query);
        $insert_stmt->bind_param("is", $appointment_id, $notes);
        $insert_stmt->execute();
    }

    // Refresh the page to display updated records
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Medical Records</title>
    <link rel="stylesheet" href="doctor_medical_record.css">
    <link rel="icon" type="image/jpg" href="logo.jpg">
</head>
<body>
    <div class="dashboard-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <a href="doctor_dashboard.php" class="sidebar-button">Appointments</a>
            <a href="doctor_medical_record.php" class="sidebar-button">Medical Record</a>
            <a href="index.php" class="sidebar-button">Logout</a>
        </div>

        <!-- Content -->
        <div class="content">
            
            <h3>Medical Records</h3>

            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="appointment-card">
                    <p><b>Date:</b> <?php echo date('d/m/Y H:i', strtotime($row['Appointment_Date'])); ?></p>
                    <p><b>Patient:</b> <?php echo htmlspecialchars($row['patient_name']); ?></p>
                    <form method="POST" action="">
                        <textarea name="notes" style="width: 100%; height: 150px; padding: 10px; border: 1px solid #ccc; border-radius: 10px;"><?php echo htmlspecialchars($row['notes']); ?></textarea>
                        <input type="hidden" name="appointment_id" value="<?php echo $row['Appointment_ID']; ?>">
                        <button type="submit" class="drop-button">Save</button>
                    </form>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>