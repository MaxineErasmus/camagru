<?php
session_start();

require ($_SESSION['root'] . "/lib/lib.php");

try{
    $con = con($DB_DSN, $DB_USER, $DB_PASSWORD);

    $img_explode = explode('.',$_SESSION['studio_img']);
    $img_ext = end($img_explode);
    $img_name = $_SESSION['uid'] . "." . $img_ext;

    img_upload($con,$_SESSION['uid'],$img_ext);

    //rename img to db img id . ext
    $img = getLastImage($con,$_SESSION['uid']);
    $img_rename = $img->id . "." . $img->ext;

    rename(STUDIO_IMG_FILEPATH . $img_name, GALLERY_IMG_FILEPATH . $img_rename);

    unset($_SESSION['studio_img']);

    echo("<script> alert('Image Posted!');
        location.href='../dir/studio.php'; </script>");
}catch (PDOException $e){
    echo "<script>alert('" . "Upload Error: " . $e->getMessage() . "');</script>";
}

?>