<?php
  $conn = new mysqli('185.28.21.204', 'u694427992_tgperso', 'Smartypants878!', 'u694427992_tgperso');

  // Check database connection
  if ($conn->connect_error) {
      die("Connection failed: " . $conn->connect_error);
  }

  $first = $_POST['first'];
  $last = $_POST['last'];
  $email = ($_POST['email']);

  // echo "<pre>";
  // var_dump($_POST);
  // echo "</pre>";

  $sql = "INSERT INTO Person (last_name, first_name, email) VALUES ('$last', '$first', '$email');";
  $result = $conn->query($sql);

  $rows = array();
  $sql = "SELECT * FROM Person";
  $result = $conn->query($sql);
  while ($row = $result->fetch_assoc()) {
    $rows[] = $row;
  }
  echo json_encode($rows[count($rows) - 1]);
  