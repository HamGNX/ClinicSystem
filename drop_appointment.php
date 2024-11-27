
<?php
session_start();
include 'connect.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $appointment_id = $_POST['appointment_id'];

    $query = "DELETE FROM Appointment WHERE Appointment_ID = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $appointment_id);
    if ($stmt->execute()) {
        header("Location: doctor_dashboard.php");
    } else {
        echo "Error dropping appointment.";
    }
}
?>