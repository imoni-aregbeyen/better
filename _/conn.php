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

$keywords = ['tbl', 'pg'];
$alerts = isset($_SESSION['alerts']) ? $_SESSION['alerts'] : [];
$alerts_count = count($alerts);
$pg = isset($_POST['pg']) ? $_POST['pg'] : '';
$id = isset($_POST['id']) ? (int)test_input($_POST['id']) : 0;

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}