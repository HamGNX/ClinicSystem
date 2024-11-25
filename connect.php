
<?php
$mysqli = new mysqli('localhost:8889','root','root','Clinic');
    if($mysqli->connect_errno){
        echo $mysqli->connect_errno.": ".$mysqli->connect_error;
    }
?>