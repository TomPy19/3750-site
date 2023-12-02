<?php
  $host = "185.28.21.204";
  $user = "u694427992_admin";
  $password = "Dickandballs69";
  $db = "u694427992_collectapp";
  $conn = mysqli_connect($host, $user, $password, $db);
  if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
  }
?>