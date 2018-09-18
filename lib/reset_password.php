<?php

require ($_SESSION['root'] . "/lib/lib.php");

$user = new User;

try {
    if (isset($_GET['email']) && !empty($_GET['email']) && isset($_GET['token']) && !empty($_GET['token'])) {
        $email = $_GET['email'];
        $token = $_GET['token'];

        $con = con($DB_DSN, $DB_USER, $DB_PASSWORD);

        $user = getUser($con, "email", $email);

        $_SESSION['uid'] = $user->id;
        $_SESSION['username'] = $user->username;
        $_SESSION['first_name'] = $user->first_name;
        $_SESSION['last_name'] = $user->last_name;
        $_SESSION['email'] = $user->email;

        echo("<script> alert('Enter your new password');
                location.href='../dir/profile.php';</script>");
    }
}catch(PDOException $e) {
    echo "Verification Error: " . $e->getMessage();
}

$con = NULL;

