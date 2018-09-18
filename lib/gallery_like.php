<?php
session_start();

require ($_SESSION['root'] . "/lib/lib.php");

try {
    if (isset($_POST['like_button'])){
        if (isset($_POST['like_img']) && !empty($_POST['like_img'])) {

            if (isset($_SESSION['uid'])){
                sanitize($_POST);

                $con = con($DB_DSN, $DB_USER, $DB_PASSWORD);
                $img_id = $_POST['like_img'];

                if (!has_liked($con, $img_id)){
                    add_like($con, $img_id);
                }

                echo("<script> alert('Image liked!');
                location.href='../index.php';</script>");
            }
        }
    }
}catch(PDOException $e) {
    echo "Like Image Error: " . $e->getMessage();
}
$con = NULL;


