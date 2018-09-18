<?php
session_start();

require ($_SESSION['root'] . "/lib/lib.php");

try {
    if ($_POST['upload_img'] == "upload"){
        if (isset($_FILES['img'])){
            if (($ret = img_check($_FILES['img'])) > 0){
                echo("<script> alert('Success');</script>");
                $con = con($DB_DSN, $DB_USER, $DB_PASSWORD);

                img_upload($con, $_SESSION['uid'], $_FILES['img']);
                echo("<script> alert('" . $studio_img_path . $_FILES['img']['name'] . "');</script>");

                move_uploaded_file($_FILES['img']['tmp_name'], $studio_img_path . $_FILES['img']['name']);

                $img = getLastImage($con, $_SESSION['uid']);

                rename($studio_img_path . $_FILES['img']['name'], $studio_img_path . $img->id . "." . $img->ext);
                $_SESSION['before_img'] = $studio_img_path . $img->id . "." . $img->ext;
            }else if ($ret === -1)
                echo("<script> alert('Incorrect File Extension');</script>");
            else if ($ret === -2)
                echo("<script> alert('Incorrect File Size');</script>");

        }
    }
    echo("<script> location.href='../dir/studio.php'; </script>");
}catch(PDOException $e) {
    echo "Upload Error: " . $e->getMessage();
}

?>

