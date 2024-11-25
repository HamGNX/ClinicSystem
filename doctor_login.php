
<?php
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $doctor_id = $_POST['doctor_id'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM Doctor WHERE Doctor_ID = '$doctor_id'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['Password'])) {
            session_start();
            $_SESSION['doctor_id'] = $row['Doctor_ID'];
            header("Location: doctor_dashboard.php");
        } else {
            echo "Invalid password.";
        }
    } else {
        echo "No doctor found.";
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="design.css">
</head>
<body class="DoctorLogin">
    <form method="POST" action="">
        <label>Doctor ID</label>
        <input type="text" name="doctor_id" required>
        <label>Password</label>
        <input type="password" name="password" required>
        <button type="submit">Log in</button>
    </form>
</body>
</html>
