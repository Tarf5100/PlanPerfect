<?php
$host = "localhost";
$user = "root";         
$password = "root";        
$dbname = "PlanPerfect"; 

$conn = mysqli_connect($host, $user, $password, $dbname, 8889);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
