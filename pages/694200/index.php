<?php
/**
 * This script handles incoming requests and routes them to the appropriate page.
 * It expects a query string in the format of "?uri=<uri>&id=<id>".
 * The "uri" parameter specifies the page to display, and the "id" parameter is optional and specifies a specific item to display on that page.
 */

// Parse the query string to get the requested URI and ID (if any)
$uriArr = explode('/', $_SERVER['REQUEST_URI']);
$uri = $uriArr[2];
if (count($uriArr) > 4) {
  $id = $uriArr[4];
}

if ($uri != 'api') {?>
  <link rel="stylesheet" href="../style.css">
  <script src="https://cdn.jsdelivr.net/npm/handlebars@latest/dist/handlebars.js"></script>
  <script src="https://kit.fontawesome.com/cd8cff598a.js" crossorigin="anonymous"></script>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js" integrity="sha512-3j3VU6WC5rPQB4Ld1jnLV7Kd5xr+cq9avvhwqzbH/taCRNURoeEpoPBK9pDyeukwSxwRPJ8fDgvYXd6SkaZ2TA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
  <?php
}

// Route the request to the appropriate page
if ($uri === '') {
  require './pages/694200/pages/home.php';
} else if ($uri === 'manga') {
  // echo '<pre>';
  // print_r($_SERVER);
  // echo $id;
  // echo '</pre>';
  if (isset($id)) {
    require './pages/694200/pages/manga.php';
  }
} else if ($uri === "about") {
  require './pages/694200/pages/about.php';
} else if ($uri === 'api') {
  $endpoint = $uriArr[3];
  require './pages/694200/api/' . $endpoint . '.php';
} else if ($uri === 'sign-in') {
  require './pages/694200/pages/sign-in.php';
} else if ($uri === 'sign-up') {
  require './pages/694200/pages/sign-up.php';
} else if ($uri === 'favorites') {
  require './pages/694200/pages/favorites.php';
}