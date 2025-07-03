<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$host = "sql301.infinityfree.com";       
$user = "if0_39355525";                  
$pass = "8F3OSbA6tM";     
$dbname = "if0_39355525_rainbow_market_db"; 

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
