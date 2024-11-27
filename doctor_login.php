<?php
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $doctor_id = trim($_POST['doctor_id']);
    $password = trim($_POST['password']);

    // Query to check doctor credentials
    $query = "SELECT * FROM Doctor WHERE Doctor_ID = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("i", $doctor_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['Password'])) {
            // Start session and redirect to doctor dashboard
            session_start();
            $_SESSION['doctor_id'] = $row['Doctor_ID'];
            header("Location: doctor_dashboard.php");
            exit();
        } else {
            $error_message = "Invalid password.";
        }
    } else {
        $error_message = "No account found with this Doctor ID.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="doctor_login.css">
    <title>Doctor Login</title>
    <link rel="icon" type="image/jpg" href="logo.jpg">
</head>
<body>
    <div class="login-page">
        <div class="login-container">
            <h1>Doctor Login</h1>
            <?php if (!empty($error_message)) : ?>
                <p class="error-message"><?php echo $error_message; ?></p>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="input-group">
                    <label for="doctor_id">Doctor ID</label>
                    <input type="number" id="doctor_id" name="doctor_id" placeholder="Enter your Doctor ID" required>
                </div>
                <div class="input-group">
                    <label for="password">Password</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                        <span class="toggle-password" onclick="togglePassword()">Show</span>
                    </div>
                </div>
                <button type="submit" class="button">Log in</button>
            </form>
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordField = document.getElementById('password');
            const toggleText = document.querySelector('.toggle-password');
            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleText.textContent = 'Hide';
            } else {
                passwordField.type = 'password';
                toggleText.textContent = 'Show';
            }
        }
    </script>
</body>
</html>