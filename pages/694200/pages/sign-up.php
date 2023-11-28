<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="../style.css">
  <title>Sign In</title>
  <style>
    body {
      display: grid;
      align-items: start;
      justify-items: stretch;
    }
    #sign-up {
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
      width: fit-content;
      border: 1px solid bisque;
      margin: 0 auto;
      border-radius: .5rem;
    }
    #sign-up label {
      margin-top: .5rem;
      padding: .25rem;
    }
    #sign-up input {
      margin: .5rem;
      border-radius: .5rem;
      padding: .25rem;
      width: 16rem;
    }
    #sign-up input:focus {
      outline: none;
    }
    #sign-up button {
      margin: .5rem;
      border-radius: .5rem;
      padding: .25rem;
      background-color: #191F38;
      color: bisque;
      border: 1px solid bisque;
      transition: .1s all ease-in-out;
    }
    #sign-up button:hover {
      background-color: bisque;
      color: #191F38;
      transition: .1s all ease-in-out;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <form id="sign-up">
    <label for="name">Full Name</label>
    <input type="text" name="name" id="name">
    <label for="email">Email</label>
    <input type="text" name="email" id="email">
    <label for="password">Password</label>
    <input type="password" name="password" id="password">
    <button type="submit">Sign up</button>
  </form>
</body>
<script>
  $(document).ready(function() {
    $('#sign-up').on('submit', function(e) {
      e.preventDefault();
      $.post({
        url: '/694200/api/sign-up',
        data: $(this).serialize(),
        success: function(data) {
          data = JSON.parse(data)
          if (data.message == 'User created successfully.') {
            window.location.href = '/694200/pages/sign-in';
          } else {
            alert(data.message);
          }
        }
      });
    });
  });
</script>
</html>