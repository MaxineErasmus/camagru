<?php
session_start();

require ($_SESSION['root'] . "/lib/lib.php");

try {
    $user = new User();

    if (isset($_POST['forgot_password']) && $_POST['forgot_password'] == "forgot password") {
        if (empty($_POST['login_id']))
            echo("<script> alert('Enter your username or email address');</script>");
        else{
            $con = con($DB_DSN, $DB_USER, $DB_PASSWORD);

            sanitize($_POST);

            $login_id = $_POST['login_id'];

            if (!($user = getUser($con, 'username', $login_id)))
                if (!($user = getUser($con, 'email', $login_id)))
                    echo("<script> alert('Incorrect email or username');
                        location.href='../dir/login_form.php';</script>");

            $user->token = token_gen();
            setUser($con,$user->id,"token",$user->token);

            email_resetPass($user);
        }
        echo("<script>location.href='../dir/login_form.php';</script>");
    }
    if (isset($_POST['login']) && $_POST['login'] == "login") {
        $con = con($DB_DSN, $DB_USER, $DB_PASSWORD);

        sanitize($_POST);

        $check = login_check($con, $_POST['login_id'], $_POST['password']);
        if ($check > 0){
            //Retrieve User from Db
            $con = con($DB_DSN, $DB_USER, $DB_PASSWORD);
            $user = getUser($con, 'id', $check);

            //Create Session Variables
            $_SESSION['uid'] = $user->id;
            $_SESSION['username'] = $user->username;
            $_SESSION['first_name'] = $user->first_name;
            $_SESSION['last_name'] = $user->last_name;
            $_SESSION['email'] = $user->email;
            $_SESSION['notify'] = $user->notify;

            echo("<script> alert('Logged In!');
                location.href='../index.php';</script>");
        }else{
            if($check === -1)
                echo("<script> alert('Incorrect email or username');</script>");
            else if ($check === -2)
                echo("<script> alert('Incorrect password');</script>");
            else
                echo("<script> alert('Account is not yet verified, check your email for the activation token');</script>");
            echo("<script>location.href='../dir/login_form.php';</script>");
        }
    }
}catch(PDOException $e){
    echo "Login Error: " . $e->getMessage();
}

$con = NULL;
?>