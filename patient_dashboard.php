<?php
include 'db_connect.php';
session_start();

if (!isset($_SESSION['patient_id'])) {
    header("Location: patient_login.php");
    exit();
}

$patient_id = $_SESSION['patient_id'];

$sql = "SELECT * FROM Appointment WHERE Patient_ID = '$patient_id'";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="design.css">
</head>
<body class="PatientProfile">
    <h1>Welcome to your dashboard!</h1>
    <h2>Your Upcoming Appointments:</h2>
    <ul>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <li>
                <?= $row['Appointment_Date'] ?> with Doctor ID: <?= $row['Doctor_ID'] ?>
            </li>
        <?php } ?>
    </ul>
</body>
</html>
