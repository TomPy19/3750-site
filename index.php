<?php
  $uri = str_replace('/3750-site', '', $_SERVER['REQUEST_URI']);
  if (explode('/', $uri)[2] != 'api') {
    ?>
    <head>
      <title>3750 Site</title>
      <link rel="stylesheet" href="./includes/header.css">
    </head>
    <script>
      // console.log('<? //$uri ?>');
    </script>
    <?php
    include_once './includes/header.html';
  }
  if ($uri == '/') {
    require ('./pages/home.php');
  } else if (explode('/', $uri)[1] == '694200') {
    require ('./pages/694200/index.php');
  } else {
    $dir = explode('/', $uri)[1];
    // echo $dir;

    $pages = scandir('./pages/' . $dir . '/');
    $found = false;
    for ($i = 0; $i < count($pages); $i++) {
      if ($pages[$i] == 'index.php' or $pages[$i] == 'index.html') {
        $uri = $uri . '/' . $pages[$i];
        $found = true;
        break;
      }
    }

    if (!$found) {
      if ($matches = glob('./pages/' . $dir . '/*.html')) {
        // var_dump(substr($matches[0], 7));
        $uri = substr($matches[0], 7);
      } else if ($matches = glob('./pages/' . $dir . '/*.php')) {
        // var_dump(substr($matches[0], 7));
        $uri = substr($matches[0], 7);
      } else {
        $uri = '/pages/404.html';
      }
    }

    require ('./pages' . $uri);
  }
?>