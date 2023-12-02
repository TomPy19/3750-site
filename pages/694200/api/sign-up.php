<?php
  // ini_set('display_errors', 1);
  // ini_set('display_startup_errors', 1);
  // error_reporting(E_ALL);
  require_once 'db.php';
  if ($conn) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $query = "SELECT * FROM Users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);
    $user = mysqli_fetch_assoc($result);
    if ($user) {
      echo json_encode(array('message' => 'Error: User already exists.'));
      return;
    }

    $query = "INSERT INTO Users (name, email, password) VALUES ('$name', '$email', '$password')";
    if (mysqli_query($conn, $query)) {
      echo json_encode(array('message' => 'User created successfully.'));
    } else {
      echo json_encode(array('message' => 'Error: Could not create user.'));
    }
  } else {
    echo json_encode(array('message' => 'Error: Could not connect to database.'));
  }
?>
