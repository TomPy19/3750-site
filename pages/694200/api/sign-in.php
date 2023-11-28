<?php
  require_once 'db.php';

  $email = $_POST['email'];
  $password = $_POST['password'];

  $query = "SELECT * FROM Users WHERE email = '$email'";
  $result = mysqli_query($conn, $query);
  $user = mysqli_fetch_assoc($result);

  if (password_verify($password, $user['password'])) {
    echo json_encode(array('message' => $user));
  } else {
    echo json_encode(array('message' => 'Error: Incorrect email or password.'));
  }
?>