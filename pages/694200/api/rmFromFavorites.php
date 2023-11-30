<?php
  include_once 'db.php';

  $userID = $_POST['userID'];
  $mangaID = $_POST['mangaID'];

  $sql = "DELETE FROM Favorites WHERE userID = '$userID' AND mangaID = '$mangaID'";

  if ($conn->query($sql) === TRUE) {
    echo json_encode(array('message' => "Removed from favorites"));
  } else {
    echo json_encode(array('message' => "Error: " . $conn->error));
  }
?>