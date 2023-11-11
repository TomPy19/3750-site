/**
 * This script handles incoming requests and routes them to the appropriate page.
 * It expects a query string in the format of "?uri=<uri>&id=<id>".
 * The "uri" parameter specifies the page to display, and the "id" parameter is optional and specifies a specific item to display on that page.
 */
 
<?php
// Parse the query string to get the requested URI and ID (if any)
$uriString = explode('=', explode('&',$_SERVER['QUERY_STRING'])[1])[1];
$uris = explode('/', $uriString);
$uri = $uris[0];
if (count($uris) > 2) {
  $id = $uris[2];
}

// Include the header HTML
include '../includes/header.html';

// Route the request to the appropriate page
if ($uri === '') {
  require 'pages/home.php';
} else if ($uri === 'manga') {
  if (isset($id)) {
    require 'pages/manga.php';
  }
} else if ($uri === "about") {
  require 'pages/about.php';
}