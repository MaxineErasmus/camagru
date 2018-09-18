<?php
session_start();

require ($_SESSION['root'] . "/lib/lib.php");

try {
    if ($_POST['register'] == "register") {
        $new_user = new user();

        sanitize($_POST);

        $new_user->username = $_POST['username'];
        $new_user->first_name = $_POST['first_name'];
        $new_user->last_name = $_POST['last_name'];
        $new_user->email = $_POST['email'];
        $new_user->password = $_POST['password'];
        $new_user->password2 = $_POST['password2'];

        $con = con($DB_DSN, $DB_USER, $DB_PASSWORD);

        $check = reg_check($con, $new_user);
        if ($check > 0){

            //Gen New Token
            $new_user->token = token_gen();

            //Encrypt Password & Clear Password2
            pass_encrypt($new_user->password);
            $new_user->password2 = NULL;

            //Database (New User)
            $sql = $con->prepare("INSERT INTO users (
          username, first_name, last_name, email, password, token)
          VALUES (?, ?, ?, ?, ?, ?)");
            $sql->execute([$new_user->username, $new_user->first_name, $new_user->last_name,
                $new_user->email, $new_user->password, $new_user->token]);

            //Email Confirmation Token
            $subject = "Camagru Activation Token";
            $msg = "Hey, " . $new_user->first_name . "\n\n"
                . "Please click the link below to verify your email address for Camagru\n\n"
                . "http://" . $_SESSION['url']
                . "?email=" . $new_user->email
                . "&token=" . $new_user->token;

            if (mail($new_user->email, $subject, $msg)) {
                echo("<script> alert('An activation link has been sent to $new_user->email');
                    location.href='../index.php';</script>");
            } else {
                echo("<script> alert('Confirmation email failed to send!');
                    location.href='../dir/register_form.php';</script>");
            }
        }else{
            if($check === -1)
                echo("<script> alert('Email already exists');</script>");
            else if($check === -2)
                echo("<script> alert('Username already exists');</script>");
            else if($check === -3)
                echo("<script> alert('Please fill in all fields');</script>");
            else
                echo("<script> alert('Passwords do not match');</script>");
            echo("<script>location.href='../dir/register_form.php';</script>");
        }
    }
}catch(PDOException $e){
    echo "Registration Error: " . $e->getMessage();
}

$con = NULL;
