<?php
session_start();

require ($_SESSION['root'] . "/lib/lib.php");

$user = new user();

try {
    if ($_POST['profile'] == "update"){
        sanitize($_POST);

        if (empty($_POST['username']) && empty($_POST['first_name'])
            && empty($_POST['last_name']) && empty($_POST['email'])){
            echo("<script> alert('What do you want to update?');
            location.href='../dir/profile.php';</script>");
        }

        $con = con($DB_DSN, $DB_USER, $DB_PASSWORD);

        if (isset($_POST['username']) && !empty($_POST['username'])) {
            if (username_exists($con, $_POST['username'])){
                echo("<script> alert('Username already exists');
                    location.href='../dir/profile.php';</script>");
            }else{
                $user->username = $_POST['username'];
                setUser($con, $_SESSION['uid'], "username", $user->username);
                $_SESSION['username'] = $user->username;
            }
        }
        if (isset($_POST['first_name']) && !empty($_POST['first_name'])) {
            $user->first_name = $_POST['first_name'];
            setUser($con, $_SESSION['uid'], "first_name", $user->first_name);
            $_SESSION['first_name'] = $user->first_name;
        }
        if (isset($_POST['last_name']) && !empty($_POST['last_name'])) {
            $user->last_name = $_POST['last_name'];
            setUser($con, $_SESSION['uid'], "last_name", $user->last_name);
            $_SESSION['last_name'] = $user->last_name;
        }
        if (isset($_POST['email']) && !empty($_POST['email'])) {
            if (email_exists($con, $_POST['email'])){
                echo("<script> alert('Email already exists');
                    location.href='../dir/profile.php';</script>");
            }else{
                $user->email = $_POST['email'];
                setUser($con, $_SESSION['uid'], "email", $user->email);
                $_SESSION['email'] = $user->email;
            }
        }

        echo("<script> alert('Profile Updated!');
            location.href='../dir/profile.php';</script>");
    }
    if (isset($_POST['notify_on']) || isset($_POST['notify_off'])){
        $con = con($DB_DSN, $DB_USER, $DB_PASSWORD);

        if (isset($_POST['notify_on'])){
            setUser($con, $_SESSION['uid'], "notify", 1);
            $_SESSION['notify'] = 1;
        }
        else{
            setUser($con, $_SESSION['uid'], "notify", 0);
            $_SESSION['notify'] = 0;
        }
        echo("<script> alert('Email Notification Settings Updated!');
            location.href='../dir/profile.php';</script>");
    }
    if ($_POST['password'] == "change") {
        if (empty($_POST['new_password'])){
            echo("<script> alert('You have not entered a new password');
            location.href='../dir/profile.php';</script>");
        }

        $con = con($DB_DSN, $DB_USER, $DB_PASSWORD);

        $new_password = $_POST['new_password'];
        pass_encrypt($new_password);

        setUser($con, $_SESSION['uid'], "password", $new_password);

        echo("<script> alert('Password Updated!'); 
        location.href='../dir/profile.php';</script>");
    }
}catch(PDOException $e){
    echo "Profile Update Error: " . $e->getMessage();
}

$con = NULL;
