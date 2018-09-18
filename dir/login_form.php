<?php
session_start();

$_SESSION['login_url'] = $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];

require ($_SESSION['root'] . "/lib/reset_password.php");

?>

<!DOCTYPE html>
<html>
<head>
    <title>Camagru | Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/display.css">
</head>
<body>
<header class="box">
    <h1>Camagru</h1>
</header>
<div class="menu">
    <a class="menu_buttonC" href="../index.php">Gallery</a>
    <a class="menu_buttonD" href="register_form.php">Register</a>
</div>
<br>
<main class="form">
    <form action="../lib/login.php" method="POST">
        <input name="login_id" type="text" placeholder="username / email" size="50"><br>
        <input name="password" type="password" placeholder="password"><br>
        <input name="login" type="submit" value="login"><br>
        <input name="forgot_password" type="submit" value="forgot password"><br>
    </form>
</main>
<footer>
    <hr>
    rerasmus | 2018
</footer>
</body>
</html>