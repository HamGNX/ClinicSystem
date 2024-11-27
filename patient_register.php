<?php
include 'connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $fname = trim($_POST['fname']);
    $lname = trim($_POST['lname']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    // Check if email already exists
    $query = "SELECT * FROM Patient WHERE Email = ?";
    $stmt = $mysqli->prepare($query);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $error_message = "Email is already registered.";
    } else {
        // Insert new patient
        $query = "INSERT INTO Patient (FName, LName, Email, Password) VALUES (?, ?, ?, ?)";
        $stmt = $mysqli->prepare($query);
        $stmt->bind_param("ssss", $fname, $lname, $email, $password);

        if ($stmt->execute()) {
            header("Location: patient_login.php");
            exit();
        } else {
            $error_message = "Failed to create an account. Please try again.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="patient_register.css">
    <title>Patient Registration</title>
    <link rel="icon" type="image/jpg" href="logo.jpg">
</head>
<body>
    <div class="signup-page">
        <div class="signup-container">
            <h1>Create an account</h1>
            <p class="already-account">Already have an account? <a href="patient_login.php">Log in</a></p>

            <?php if (!empty($error_message)) : ?>
                <p class="error-message"><?php echo $error_message; ?></p>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="input-group">
                    <label for="fname">First Name</label>
                    <input type="text" id="fname" name="fname" placeholder="Enter your first name" required>
                </div>
                <div class="input-group">
                    <label for="lname">Last Name</label>
                    <input type="text" id="lname" name="lname" placeholder="Enter your last name" required>
                </div>
                <div class="input-group">
                    <label for="email">What's your email?</label>
                    <input type="email" id="email" name="email" placeholder="Enter your email address" required>
                </div>
                <div class="input-group">
                    <label for="password">Create a password</label>
                    <div class="password-wrapper">
                        <input type="password" id="password" name="password" placeholder="Enter your password" required>
                        <span class="toggle-password" onclick="togglePassword()">Hide</span>
                    </div>
                    <p class="password-hint">Use 8 or more characters with a mix of letters, numbers & symbols</p>
                </div>
                <button type="submit" class="button">Create an account</button>
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