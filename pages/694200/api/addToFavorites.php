<?php
  include_once 'db.php';

  $userID = $_POST['userID'];
  $mangaID = $_POST['mangaID'];

  $sql = "INSERT INTO Favorites (userID, mangaID) VALUES ('$userID', '$mangaID')";

  if ($conn->query($sql) === TRUE) {
    echo json_encode(array('message' => "Added to favorites"));
  } else {
    echo json_encode(array('message' => "Error: " . $conn->error));
  }
?>