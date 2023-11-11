<?php
$uriString = explode('=', explode('&',$_SERVER['QUERY_STRING'])[1])[1];
$uris = explode('/', $uriString);
$uri = $uris[0];
if (count($uris) > 2) {
  $id = $uris[2];
}

// echo "<pre>";
// var_dump($uri);
// echo "</pre>";

include '../includes/header.html';

if ($uri === '') {
  require 'pages/home.php';
} else if ($uri === 'manga') {
  if (isset($id)) {
    require 'pages/manga.php';
  }
}