<?php
session_start();
include('connect.php');

// Fetch patient ID from session
$patient_id = $_SESSION['patient_id'];

// Automatically create payments for past appointments
$create_payments_query = "
    INSERT INTO Payment (Appointment_ID, Amount, Payment_Status)
    SELECT a.Appointment_ID, 100.00 AS Amount, 'Completed' AS Payment_Status
    FROM Appointment a
    LEFT JOIN Payment p ON a.Appointment_ID = p.Appointment_ID
    WHERE a.Patient_ID = ? 
    AND a.Appointment_Date < NOW() 
    AND p.Payment_ID IS NULL
";

$stmt_create_payments = $mysqli->prepare($create_payments_query);
$stmt_create_payments->bind_param("i", $patient_id);
$stmt_create_payments->execute();

// Fetch payment details linked to the patient's appointments
$query_payments = "
    SELECT 
        p.Payment_ID,
        a.Appointment_Date,
        p.Amount,
        p.Payment_Status
    FROM 
        Payment p
    JOIN 
        Appointment a ON p.Appointment_ID = a.Appointment_ID
    WHERE 
        a.Patient_ID = ?
    ORDER BY 
        a.Appointment_Date
";

$stmt = $mysqli->prepare($query_payments);
$stmt->bind_param("i", $patient_id);
$stmt->execute();
$result_payments = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Payment List</title>
    <link rel="stylesheet" href="patient_payment.css">
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
            <h1>Payment List</h1>
            <?php if ($result_payments->num_rows > 0): ?>
                <?php while ($payment = $result_payments->fetch_assoc()): ?>
                    <div class="payment-card">
                        <p><b>Appointment Date:</b> <?php echo date('d M Y, H:i', strtotime($payment['Appointment_Date'])); ?></p>
                        <p><b>Amount:</b> <?php echo number_format($payment['Amount'], 2); ?> THB</p>
                        <p><b>Status:</b> <?php echo htmlspecialchars($payment['Payment_Status']); ?></p>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No payments found.</p>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>