<?php
  
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../style.css">
  <title>Sign In</title>
  <style>
    body {
      display: grid;
      align-items: start;
      justify-items: stretch;
    }
    #sign-in {
      display: flex;
      justify-content: center;
      align-items: center;
      flex-direction: column;
      width: fit-content;
      border: 1px solid bisque;
      margin: 0 auto;
      border-radius: .5rem;
      position: relative;
      top: -2rem;
    }
    #sign-in label {
      margin-top: .5rem;
      padding: .25rem;
    }
    #sign-in input {
      margin: .5rem;
      border-radius: .5rem;
      padding: .25rem;
      width: 16rem;
    }
    #sign-in input:focus {
      outline: none;
    }
    #sign-in button {
      margin: .5rem;
      border-radius: .5rem;
      padding: .25rem;
      background-color: #191F38;
      color: bisque;
      border: 1px solid bisque;
      transition: .1s all ease-in-out;
    }
    #sign-in button:hover {
      background-color: bisque;
      color: #191F38;
      transition: .1s all ease-in-out;
      cursor: pointer;
    }
    #sign-in h1 {
      margin: 0;
      margin-top: 1rem;
      padding: 0;
    }
  </style>
</head>
<body>
  <form id="sign-in">
    <h1>Sign In</h1>
    <label for="email">Email</label>
    <input type="text" name="email" id="email">
    <label for="password">Password</label>
    <input type="password" name="password" id="password">
    <button type="submit">Sign in</button>
    <p id="placeholder">‎</p>
  </form>
</body>
<script>
  $(document).ready(function() {
    $('#sign-in').submit(function(e) {
      e.preventDefault();
      $.post({
        url: '/694200/api/sign-in',
        data: $(this).serialize(),
        success: function(data) {
          data = JSON.parse(data).message;
          // console.log(data.substring(0,5));
          // first = data.name.split(' ')[0];
          // alert(`Hello ${first}!`)
          if (typeof data == "string") {
            if (data.substring(0,5) == 'Error') {
              alert(data);
            }
          } else {
            console.log(data);
            $('button').text('Success!')
            $('#placeholder').text('Redirecting...')
            $.cookie('login', true, {path: '/'})
            $.cookie('user', data.userID, {path: '/'})
            nameArr = data.name.split(' ')
            $.cookie('f_name', nameArr[0], {path: '/'})
            $.cookie('l_name', nameArr[1], {path: '/'})
            // console.log(data.name);
            // console.log(window.history)
            setTimeout(() => {
              window.location.href = '/694200/';
            }, 1500);
          }  
        }
      });
    });
  });
</script>
</html>