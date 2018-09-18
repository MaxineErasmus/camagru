<?php
session_start();

$_SESSION['url'] = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
$_SESSION['root'] = __DIR__;
$_SESSION['app'] = basename(dirname(__FILE__));

require ($_SESSION['root'] . "/lib/lib.php");
require ($_SESSION['root'] . "/lib/verify.php");
require ($_SESSION['root'] . "/lib/paginate.php");

function get_prev_button(){
    if ($_SESSION['page'] == 0){
        echo "style='display:none'";
    }
}

function get_next_button(){
    if (isset($_SESSION['end']) && $_SESSION['end'] == true){
        echo "style='display:none'";
    }
}

if (!isset($_SESSION['page'])){
    $_SESSION['page'] = 0;
}

?>
<html>
<head>
    <title>Camagru | Gallery</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/display.css">
</head>
<body>
<header class="box">
    <h1>Gallery</h1>
</header>
<?php

   if (isset($_SESSION['uid']))
        require ($_SESSION['root'] . "/lib/gallery.php");
    else
        require ($_SESSION['root'] . "/lib/gallery_anon.php");

?>
<br>
<nav class="page_nav">
    <a class="page_nav_prev" href="index.php?nav=prev" <?php get_prev_button();?>>prev</a>
    <a class="page_nav_next" href="index.php?nav=next" <?php get_next_button();?>>next</a>
</nav>
<p style="text-align: center">
<?php echo "Page: " . ($_SESSION['page'] + 1) ;?>
</p>
<footer>
    <hr>
    rerasmus | 2018
</footer>
</body>
</html>