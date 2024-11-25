
<?php
include 'db_connect.php';
session_start();

if (!isset($_SESSION['patient_id'])) {
    header("Location: patient_login.php");
    exit();
}

$patient_id = $_SESSION['patient_id'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $appointment_id = $_POST['appointment_id'];
    $amount = $_POST['amount'];

    $sql = "INSERT INTO Payment (Appointment_ID, Payment_Date, Amount, Payment_Status) VALUES ('$appointment_id', NOW(), '$amount', 'Pending')";
    if ($conn->query($sql) === TRUE) {
        echo "Payment initiated successfully!";
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
<body class="PaymentPage">
    <form method="POST" action="">
        <label>Appointment ID</label>
        <input type="text" name="appointment_id" required>
        <label>Amount</label>
        <input type="number" name="amount" required>
        <button type="submit">Make Payment</button>
    </form>
</body>
</html>
