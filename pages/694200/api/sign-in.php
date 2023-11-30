<?php
  require_once 'db.php';

  $email = $_POST['email'];
  $password = $_POST['password'];
  $date = gmdate('Y-m-d H:i:s');

  $query = "SELECT * FROM Users WHERE email = '$email'";

  $result = $conn->query($query);
  $user = mysqli_fetch_assoc($result);
  if ($user) {
    $cnt = $user['login_cnt'] + 1;
    if (password_verify($password, $user['password'])) {
      $sql = "UPDATE Users SET last_login = '$date' WHERE email = '$email';
              UPDATE Users SET login_cnt = '$cnt' WHERE email = '$email';";
      $conn->multi_query($sql);
      echo json_encode(array('message' => $user));
    } else {
      echo json_encode(array('message' => 'Error: Incorrect email or password.'));
    }
  } else {
    echo json_encode(array('message' => 'Error: Cannot find user. Please try again.'));
  }
?>