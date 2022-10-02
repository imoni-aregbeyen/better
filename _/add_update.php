<?php
require 'conn.php';
$names = $values = $assigns = [];
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
      $values[] = "'$value'";
      $assigns[] = "$name='{$value}'";
    }
  }
}
if ($id === 0) {
  $sql = "INSERT INTO $tbl (" . implode(', ', $names) . ") VALUES (" . implode(', ', $values) . ")";
} else {
  echo $sql = "UPDATE $tbl SET WHERE " . implode(', ', $assigns) . " id=$id"; die;
}

if ($conn->query($sql) === TRUE) {
  $_SESSION['alerts'][] = "Record updated successfully";
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