<?php
// Backend login logic
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query to check the patient's email
    $query = "SELECT * FROM Patient WHERE Email = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if (password_verify($password, $row['Password'])) {
            session_start();
            $_SESSION['patient_id'] = $row['Patient_ID'];
            header("Location: patient_dashboard.php");
            exit();
        } else {
            
            $error_message = "Invalid password.";
        }
    } else {
        $error_message = "No account found with this email.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="design.css">
    <title>Patient Login</title>
    <link rel="icon" type="image/jpg" href="logo.jpg">
</head>
<body>
    <div class="login-page">
        <div class="login-container">
            <h1>Sign in</h1>
            <?php if (!empty($error_message)) : ?>
                <p class="error-message"><?php echo $error_message; ?></p>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="input-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email" required>
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
            <a href="#" class="forgot-password">Forgot your password?</a>
            <div class="divider">
                <span>New User</span>
            </div>
            <a href="patient_register.php" class="create-account">Create an account</a>
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