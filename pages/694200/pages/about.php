<!-- /**
 * This file contains the HTML and JavaScript code for the "About" page of the website.
 * It includes links to external CSS and JavaScript files, as well as the Anilist API.
 * The page displays information about the website and how to use it, and includes a "Back" button
 * that allows the user to navigate to the previous page.
 */ -->

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../style.css">
  <script src="https://cdn.jsdelivr.net/npm/handlebars@latest/dist/handlebars.js"></script>
  <script src="https://kit.fontawesome.com/cd8cff598a.js" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Collect App</title>
  <style>
    a {
      text-decoration: underline;
    }
    #back-btn {
      float: left;
      font-size: 1.5rem;
      position: relative;
      top: 0;
      right: 0;
      padding: 1rem;
      margin: .5rem;
      margin-bottom: -4rem;
      margin-right: -5rem;
      border-radius: 5px;
      border: none;
      background-color: bisque;
      cursor: pointer;
      width: fit-content;
    }
  </style>
</head>
<body>
  <div class="body-content">
    <button id="back-btn">Back</button>
    <h1 class="title">About</h1>
    <p>This is a website that uses the <a href="https://anilist.gitbook.io/">Anilist API</a> to display information about manga.</p>
    <p>To use the search engine, either select a sortby option, or search for your favorite manga!</p>
    <p>Click on the manga title to see more information about it.</p>
  </div>
</body>
<script>
  // JavaScript code to handle the back button.
  $('#back-btn').click(function() {
    window.history.back();
  });
</script>
</html>