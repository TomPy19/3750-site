<!-- /**
 * This script handles incoming requests and routes them to the appropriate page.
 * It expects a query string in the format of "?uri=<uri>&id=<id>".
 * The "uri" parameter specifies the page to display, and the "id" parameter is optional and specifies a specific item to display on that page.
 */ -->
 
<?php
// Parse the query string to get the requested URI and ID (if any)
$uriArr = explode('/', $_SERVER['REQUEST_URI']);
$uri = $uriArr[2];
if (count($uriArr) > 4) {
  $id = $uriArr[4];
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
}