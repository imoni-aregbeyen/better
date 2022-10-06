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

$keywords = ['tbl', 'pg', 'msg'];
$alerts = isset($_SESSION['alerts']) ? $_SESSION['alerts'] : [];
$alerts_count = count($alerts);
$pg = isset($_POST['pg']) ? $_POST['pg'] : '';
$id = isset($_POST['id']) ? (int)test_input($_POST['id']) : 0;
$msg = isset($_POST['msg']) ? test_input($_POST['msg']) : '';
$date = date('Y-m-d');

function test_input($data) {
  $data = trim($data);
  $data = stripslashes($data);
  $data = htmlspecialchars($data);
  return $data;
}

function get_data($tbl, $id = 0) {
  $data = [];
  $sql = "SELECT * FROM $tbl";
  if ( is_int( $id ) && $id !== 0 )
    $sql = "SELECT * FROM $tbl WHERE id=$id";
  $rs = $GLOBALS['conn']->query($sql);
  if ($rs->num_rows > 0) {
    while ($row = $rs->fetch_assoc()) {
      $data[] = $row;
    }
  }
  return $data;
}