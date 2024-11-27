<?php
session_start();
include('connect.php');

// Fetch patient ID from session
$patient_id = $_SESSION['patient_id'];

// Fetch available doctors and specialties
$query_doctors = "
    SELECT 
        d.Doctor_ID, 
        CONCAT(d.FName, ' ', d.LName) AS doctor_name, 
        s.Specialty_Name 
    FROM 
        Doctor d
    JOIN 
        Specialty s ON d.Specialty_ID = s.Specialty_ID
";
$result_doctors = $mysqli->query($query_doctors);

// Handle form submission to create an appointment
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['doctor_id'], $_POST['appointment_date'], $_POST['appointment_time'])) {
    $doctor_id = $_POST['doctor_id'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    $appointment_datetime = $appointment_date . ' ' . $appointment_time;

    // Check if the selected time is already booked
    $check_query = "
        SELECT * 
        FROM Appointment 
        WHERE Doctor_ID = ? AND Appointment_Date = ?
    ";
    $check_stmt = $mysqli->prepare($check_query);
    $check_stmt->bind_param("is", $doctor_id, $appointment_datetime);
    $check_stmt->execute();
    $existing_appointment = $check_stmt->get_result()->fetch_assoc();

    if ($existing_appointment) {
        $error_message = "The selected time slot is already booked. Please choose a different time.";
    } else {
        // Insert new appointment
        $insert_query = "
            INSERT INTO Appointment (Patient_ID, Doctor_ID, Appointment_Date, is_Confirmed) 
            VALUES (?, ?, ?, 0)
        ";
        $insert_stmt = $mysqli->prepare($insert_query);
        $insert_stmt->bind_param("iis", $patient_id, $doctor_id, $appointment_datetime);
        $insert_stmt->execute();
        $success_message = "Appointment successfully created!";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Appointment</title>
    <link rel="stylesheet" href="patient_appointments.css">
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
            <h1>Create Appointment</h1>
            
            <?php if (isset($error_message)): ?>
                <p class="error-message"><?php echo $error_message; ?></p>
            <?php endif; ?>

            <?php if (isset($success_message)): ?>
                <p class="success-message"><?php echo $success_message; ?></p>
            <?php endif; ?>

            <form method="POST" class="appointment-form">
                <label for="doctor_id">Choose Doctor:</label>
                <select name="doctor_id" id="doctor_id" required>
                    <option value="" disabled selected>Select a doctor</option>
                    <?php while ($doctor = $result_doctors->fetch_assoc()): ?>
                        <option value="<?php echo $doctor['Doctor_ID']; ?>">
                            <?php echo htmlspecialchars($doctor['doctor_name'] . ' - ' . $doctor['Specialty_Name']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>

                <label for="appointment_date">Choose Date:</label>
                <input type="date" id="appointment_date" name="appointment_date" min="<?php echo date('Y-m-d'); ?>" required>

                <label for="appointment_time">Choose Time:</label>
                <input type="time" id="appointment_time" name="appointment_time" required>

                <button type="submit">Create Appointment</button>
            </form>
        </div>
    </div>
</body>
</html>