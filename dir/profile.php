<?php
session_start();

if (!isset($_SESSION['uid'])) {
    echo("<script> alert('Login or Register to Join');
        location.href='login_form.php';</script>");
}

?>
<html>
<head>
    <title>Camagru | Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/display.css">
</head>
<body>
<header class="box">
    <h1>Profile</h1>
</header>
<div class="menu">
    <a class="menu_buttonC" href="../index.php">Gallery</a>
    <a class="menu_buttonD" href="studio.php">Studio</a>
    <a class="menu_buttonF" href="../lib/logout.php">Logout</a>
</div>
<main class="form">
    <form action="../lib/update_profile.php" method="POST">
        <h2>User Info</h2>
        <?php
        echo ("
        
        <input name='username' type='text' placeholder=" . htmlspecialchars($_SESSION['username'], ENT_QUOTES, 'UTF-8') . " size='30'>
        <br>
        <input name='first_name' type='text' placeholder=" . htmlspecialchars($_SESSION['first_name'], ENT_QUOTES, 'UTF-8') . " size='30'>
        <br>
        <input name='last_name' type='text' placeholder=" . htmlspecialchars($_SESSION['last_name'], ENT_QUOTES, 'UTF-8') . " size='30'>
        <br>
        <input name='email' type='email' placeholder=" . htmlspecialchars($_SESSION['email'], ENT_QUOTES, 'UTF-8') . " size='100'>
        <br>
        
        ");
        ?>

        <input name='profile' type='submit' value='update' >
        <br><br>

        <h2>Email Notifications</h2>
        <?php

            if ($_SESSION['notify'] == 1)
                echo "<input name='notify_off' type='submit' value='Unsubscribe' >";
            else
                echo "<input name='notify_on' type='submit' value='Subscribe'>";

        ?>
        <br>
    </form>
    <form action="../lib/update_profile.php" method="POST">
        <h2>Change Password</h2>
        <input name="new_password" type="password" placeholder="new password"
               pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{8,}"
               title="Min length: 8, must contain numbers (0-9), uppercase letters (A-Z) and lowercase letters (a-z)">
        <br>
        <input name="password" type="submit" value="change" >
    </form>
</main>
<footer>
    <hr>
    rerasmus | 2018
</footer>
</body>
</html>