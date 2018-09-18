<?php
session_start();

require ($_SESSION['root'] . "/lib/lib.php");

$img_name = $_SESSION['uid'] . ".png";

if (isset($_POST['base_img_string']) && !empty($_POST['base_img_string'])){

    //get img from user
    if (base64_to_png($_POST['base_img_string'],STUDIO_IMG_URLPATH . $img_name)){
        //add stickers
        overlay($img_name);

        $_SESSION['studio_img'] = STUDIO_IMG_URLPATH . $img_name;
    }

    unset($_POST['base_img_string']);
    echo("<script> alert('Stickers Added to Image!');
                    location.href='../dir/studio.php';</script>");
}else if (isset($_FILES['base_img']) && !empty($_FILES['base_img'])){

    if (!($image_check = getimagesize($_FILES['base_img']['tmp_name'])
        || !($ret = img_checkExt($_FILES['base_img'])))) {
        $_SESSION['studio_img'] = DEFAULT_IMG;

        echo("<script> alert('Error : Incorrect file type!');
            location.href='../dir/studio.php';</script>");
    }else{
        $img_explode = explode('.',$_FILES['base_img']['name']);

        if (!($img_ext = strtolower(end($img_explode)))){
            $_SESSION['studio_img'] = DEFAULT_IMG;

            echo("<script> alert('Error : Select image to upload');
            location.href='../dir/studio.php';</script>");
        }else{
            $img_name = $_SESSION['uid'] . "." . $img_ext;

            move_uploaded_file($_FILES['base_img']['tmp_name'], STUDIO_IMG_FILEPATH . $img_name);

            if (isset($_SESSION['stickers']) && !empty($_SESSION['stickers'])){
                overlay($img_name);
                echo("<script> alert('Stickers Added to Image!');</script>");
            }

            $_SESSION['studio_img'] = STUDIO_IMG_URLPATH . $img_name;

            unset($_POST['base_img']);
            echo("<script> location.href='../dir/studio.php';</script>");
        }
    }
}else{
    echo("<script> alert('No upload selected');
                    location.href='../dir/studio.php';</script>");
}

?>