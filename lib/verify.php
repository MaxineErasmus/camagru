<?php

try {
    if (isset($_GET['email']) && !empty($_GET['email']) && isset($_GET['token']) && !empty($_GET['token'])) {

        sanitize($_GET);

        $email = $_GET['email'];
        $token = $_GET['token'];

        $con = con($DB_DSN, $DB_USER, $DB_PASSWORD);

        $sql = $con->prepare("UPDATE users SET verified = 1 WHERE email = ? AND token = ?");
        $sql->execute([$email, $token]);

        echo("<script> alert('Verified!');
                location.href='dir/login_form.php';</script>");
    }
}catch(PDOException $e) {
    echo "Verification Error: " . $e->getMessage();
}
$con = NULL;


