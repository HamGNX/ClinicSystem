
<?php
$servername = "localhost:8889";  // Change if necessary
$username = "root";  // Change if necessary
$password = "root";      // Change if necessary
$dbname = "Clinic";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
