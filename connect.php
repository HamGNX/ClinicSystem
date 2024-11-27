
<?php
$mysqli = new mysqli('localhost:8889', 'root', 'root', 'Clinic'); // Update credentials if necessary

if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
}
?>