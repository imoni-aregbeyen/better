<?php
session_start();
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "better";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$keywords = ['tbl'];
$alerts = isset($_SESSION['alerts']) ? $_SESSION['alerts'] : [];
$alerts_count = count($alerts);

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}