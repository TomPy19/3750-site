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
      display: flex;
      flex-direction: column;
    }
    #sign-up {
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
      width: fit-content;
      border: 1px solid bisque;
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
    #sign-up h1 {
      margin: 0;
      margin-top: 1rem;
      padding: 0;
    }
    #back-btn {
      font-size: 1.5rem;
      padding: 1rem;
      border-radius: 5px;
      border: none;
      background-color: bisque;
      cursor: pointer;
      width: fit-content;
      margin: 1rem;
      margin-top: 0;
    }
    .content-wrapper {
      margin: 0 auto;
      margin-top: 10rem;
      display: flex;
      flex-direction: row;
      align-items: flex-start;
      width: 30%;
      justify-content: flex-start;
    }
  </style>
</head>
<body>
  <div class="content-wrapper">
    <button id="back-btn">Back</button>
    <form id="sign-up">
      <h1>Sign Up</h1>
      <label for="name">Full Name</label>
      <input type="text" name="name" id="name">
      <label for="email">Email</label>
      <input type="text" name="email" id="email">
      <label for="password">Password</label>
      <input type="password" name="password" id="password">
      <button type="submit">Sign up</button>
    </form>
  </div>
  
</body>
<script>
  $(document).ready(function() {
    $('#back-btn').click(function() {
      window.history.back();
    });
    $('#sign-up').on('submit', function(e) {
      e.preventDefault();
      $.post({
        url: '/694200/api/sign-up',
        data: $(this).serialize(),
        success: function(data) {
          data = JSON.parse(data)
          if (data.message == 'User created successfully.') {
            window.location.href = 'sign-in';
          } else {
            alert(data.message);
          }
        }
      });
    });
  });
</script>
</html>