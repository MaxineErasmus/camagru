<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Camagru | Register</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/display.css">
</head>
<body>
<header class="box">
    <h1>Camagru</h1>
</header>
<div class="menu">
    <a class="menu_buttonC" href="../index.php">Gallery</a>
    <a class="menu_buttonD" href="login_form.php">Login</a>
</div>
<br>
<main class="form">
    <form action="../lib/register.php" method="POST">
        <input name="username" type="text" placeholder="username" size="30"><br>
        <input name="first_name" type="text" placeholder="first name" size="30"><br>
        <input name="last_name" type="text" placeholder="last name" size="30"><br>
        <input name="email" type="email" placeholder="email" size="30"><br>
        <input name="password" type="password" placeholder="password"
               pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
               title="Min length: 8, must contain numbers (0-9), uppercase letters (A-Z) and lowercase letters (a-z)"><br>
        <input name="password2" type="password" placeholder="repeat password" title="Passwords must match"><br>
        <input name="register" type="submit" value="register" ><br>
    </form>
</main>
<footer>
    <hr>
    rerasmus | 2018
</footer>
</body>
</html>