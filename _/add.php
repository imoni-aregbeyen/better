<?php
require 'conn.php';
$names = $values = [];
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $tbl = isset($_POST['tbl']) ? test_input($_POST['tbl']) : '';
  foreach ($_POST as $name => $value) {
    if (strpos($tbl, $name) === 0) {
      $sql = "SELECT $name FROM $tbl WHERE $name LIKE '$value'";
      $result = $conn->query($sql);
      if ($result->num_rows > 0) {
        $_SESSION['alerts'][] = "$value has already been added";
        header("Location: {$_SERVER['HTTP_REFERER']}");
        exit;
      }
    }
    if (!in_array($name, $keywords)) {
      $names[] = $name;
      $value = $conn->real_escape_string($value);
      $values[] = "'$value'";
    }
  }
  if (count($dis) > 0) {
    foreach ($dis as $ds) {
      $dis_str[] = "$ds='".test_input($_POST[$ds])."'";
    }
    $dis_str = implode(' AND ', $dis_str);
    $sql = "SELECT * FROM $tbl WHERE $dis_str";
    $result = $conn->query($sql);
    if ($result->num_rows > 0) {
      $_SESSION['alerts'][] = "data already added";
      header("Location: {$_SERVER['HTTP_REFERER']}");
      exit;
    }
  }
}
$sql = "INSERT INTO $tbl (" . implode(', ', $names) . ") VALUES (" . implode(', ', $values) . ")";
if ($conn->query($sql) === TRUE) {
  if ($msg !== '')
    $_SESSION['alerts'][] = $msg;
} else {
  $_SESSION['alerts'][] = "Error: " . $sql . "<br>" . $conn->error;
}
$conn->close();
if ($pg === '') {
  header('location: ' . $_SERVER['HTTP_REFERER']);
} else {
  header("Location: ../?page=$pg");
}
exit;