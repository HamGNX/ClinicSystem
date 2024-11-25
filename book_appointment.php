
<?php
include 'db_connect.php';
session_start();

if (!isset($_SESSION['patient_id'])) {
    header("Location: patient_login.php");
    exit();
}

$patient_id = $_SESSION['patient_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $doctor_id = $_POST['doctor_id'];
    $appointment_date = $_POST['appointment_date'];

    $sql = "INSERT INTO Appointment (Patient_ID, Doctor_ID, Appointment_Date) VALUES ('$patient_id', '$doctor_id', '$appointment_date')";
    if ($conn->query($sql) === TRUE) {
        echo "Appointment booked successfully!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="design.css">
</head>
<body class="BookAppointment">
    <form method="POST" action="">
        <label>Doctor ID</label>
        <input type="text" name="doctor_id" required>
        <label>Appointment Date</label>
        <input type="datetime-local" name="appointment_date" required>
        <button type="submit">Book Appointment</button>
    </form>
</body>
</html>
