<?php
session_start();

require ($_SESSION['root'] . "/lib/lib.php");

try {
    if (isset($_POST['del_button'])){
        if (isset($_POST['del_img']) && !empty($_POST['del_img'])) {
            if (isset($_SESSION['uid'])){
                sanitize($_POST);

                $del_img_id = $_POST['del_img'];

                $con = con($DB_DSN, $DB_USER, $DB_PASSWORD);

                $sql = $con->prepare("DELETE FROM images WHERE id = :id AND user_id = :uid");

                $sql->bindParam(':id', $del_img_id, PDO::PARAM_INT);
                $sql->bindParam(':uid', $_SESSION['uid'], PDO::PARAM_INT);

                $sql->execute();

                unlink(GALLERY_IMG_FILEPATH . $del_img_id . ".png");

                echo("<script> alert('Image deleted!');
                location.href='../index.php';</script>");
            }
        }
    }
}catch(PDOException $e) {
    echo "Delete Image Error: " . $e->getMessage();
}
$con = NULL;


