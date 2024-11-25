
<?php
include 'db_connect.php';
session_start();

if (!isset($_SESSION['doctor_id'])) {
    header("Location: doctor_login.php");
    exit();
}

$doctor_id = $_SESSION['doctor_id'];

$sql = "SELECT * FROM Appointment WHERE Doctor_ID = '$doctor_id'";
$result = $conn->query($sql);

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="design.css">
</head>
<body class="DoctorDashboard">
    <h1>Welcome to your dashboard!</h1>
    <h2>Today's Appointments:</h2>
    <ul>
        <?php while ($row = $result->fetch_assoc()) { ?>
            <li>
                <?= $row['Appointment_Date'] ?> with Patient ID: <?= $row['Patient_ID'] ?>
            </li>
        <?php } ?>
    </ul>
</body>
</html>
