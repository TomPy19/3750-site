<?php
  require_once 'db.php';

  $userID = $_POST['userID'];

  $sql = "SELECT * FROM Favorites WHERE userID = '$userID'";
  $rows = array();
  $result = mysqli_query($conn, $sql);
  while ($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
  }

  echo json_encode(array('message' => $rows));
?>