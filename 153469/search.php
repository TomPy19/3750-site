<?php
  $conn = new mysqli('185.28.21.204', 'u694427992_tgperso', 'Smartypants878!', 'u694427992_tgperso');

  // Check database connection
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }

  if ($_POST['search'] == '') {
    $sql = "SELECT * FROM Person";
    $result = $conn->query($sql);
    $rows = array();
    while ($row = $result->fetch_assoc()) {
      $rows[] = $row;
    }
    echo json_encode($rows);
    exit();
  }
  
  $sql = "SELECT * FROM Person WHERE last_name LIKE '$_POST[search]'";
  $result = $conn->query($sql);
  $rows = array();
  while ($row = $result->fetch_assoc()) {
    $rows[] = $row;
  }

  echo json_encode($rows);