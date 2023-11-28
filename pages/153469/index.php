<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <link rel="stylesheet" href="../style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <title>CREATE DB</title>
  <style>
    form {
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    table {
      border-collapse: collapse;
    }
    th, td {
      border: 1px solid bisque;
    }
    .body-wrapper {
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    #search-form {
      display: flex;
      flex-direction: column;
      align-items: center;
    }
    #search-input {
      display: inline-block;
      border: none;
      padding: .5rem;
      margin-top: .25rem;
      border-radius: .5rem;
    }
    #search-input:focus {
      outline: bisque solid 2px;
    }
    #search-submit {
      display: inline-block;
      position: relative;
      right: -5rem;
      top: -1.5rem;
      border: none;
      margin-bottom: -1.5rem;
      background-color: transparent;
      cursor: pointer;
      color: #191F38;
    }
    #search-submit:hover {
      color: #779;
    }
    #reset-btn {
      margin-top: .5rem;
      padding: .25rem;
      border-radius: .5rem;
      border: none;
      cursor: pointer;
    }
  </style>
</head>
<body>
  <div class="body-wrapper">
    <h1>CREATE DB</h1>
    <form id="add-person">
      <label for="first-input">First Name</label>
      <input type="text" name="first" id="first-input">
      <br>
      <label for="last-input">Last Name</label>
      <input type="text" name="last" id="last-input">
      <br>
      <label for="email-input">Email Address</label>
      <input type="email" name="email" id="email-input">
      <br>
      <button type="submit">Add</button>
      <br><br>
    </form>
    <form id="search-form">
      <label for="search-input">Search</label>
      <input type="text" name="search" id="search-input">
      <button type="submit" id="search-submit"><i class="fas fa-search"></i></button>
    </form>
    <br>
    <table id="names-table">
      <thead>
        <tr>
          <th>Last Name</th>
          <th>First Name</th>
          <th>email</th>
        </tr>
      </thead>
      <tbody id="names-table-body">
        <?php

          $conn = new mysqli('185.28.21.204', 'u694427992_tgperso', 'Smartypants878!', 'u694427992_tgperso');

          // Check database connection
          if ($conn->connect_error) {
              die("Connection failed: " . $conn->connect_error);
          }

          $sql = "SELECT * FROM Person";
          $result = $conn->query($sql);
          while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['last_name'] . "</td>";
            echo "<td>" . $row['first_name'] . "</td>";
            echo "<td>" . $row["email"] . "</td>";
            echo "</tr>";
          };
        ?>
      </tbody>
    </table>
    <button id="reset-btn">Reset</button>
  </div>
</body>
</html>
<script>
$(document).ready(function(){
  $('#add-person').submit(function(e){
    e.preventDefault();
    var data = $(this).serialize();
    $.post({
      url: 'create.php',
      data: data,
      success: function(data){
        data = JSON.parse(data);
        // console.log(data);
        // console.log(data['last_name']);
        // console.log(data['first_name']);
        // console.log(data['email']);
        $('#names-table-body').append(`
          <tr>
            <td>${data['last_name']}</td>
            <td>${data['first_name']}</td>
            <td>${data['email']}</td>
          </tr>
        `)
      }
    })
  })

  $("#search-form").submit(function(e) {
    e.preventDefault();
    var data = {
      search: $('#search-input').val().toLowerCase()
    };
    $.post({
      url: 'search.php',
      data: data,
      success: function(data){
        data = JSON.parse(data);
        console.log(data);
        // console.log(data['last_name']);
        // console.log(data['first_name']);
        // console.log(data['email']);
        $('#names-table-body').empty();
        for (let i = 0; i < data.length; i++) {
          $('#names-table-body').append(`
            <tr>
              <td>${data[i]['last_name']}</td>
              <td>${data[i]['first_name']}</td>
              <td>${data[i]['email']}</td>
            </tr>
          `)
        }
      }
    })
  })

  $('#reset-btn').click(function(){
    $('#names-table-body').empty();
    $.post({
      url: 'search.php',
      data: {search: ''},
      success: function(data){
        data = JSON.parse(data);
        console.log(data);
        for (let i = 0; i < data.length; i++) {
          $('#names-table-body').append(`
            <tr>
              <td>${data[i]['last_name']}</td>
              <td>${data[i]['first_name']}</td>
              <td>${data[i]['email']}</td>
            </tr>
          `)
        }
      }
    })
  })
})
</script>