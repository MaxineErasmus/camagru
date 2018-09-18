<?php

session_start();

try{
    if ($_POST['set_stickers'] == 'confirm'){
        unset($_SESSION['stickers']);
        unset($_SESSION['studio_img']);
        unset($_SESSION['sticker_selected']);

        if ((isset($_POST['sticker1']) && $_POST['sticker1'] == 'checked')
            || (isset($_POST['sticker2']) && $_POST['sticker2'] == 'checked')
            || (isset($_POST['sticker3']) && $_POST['sticker3'] == 'checked')
            || (isset($_POST['sticker4']) && $_POST['sticker4'] == 'checked')
            || (isset($_POST['sticker5']) && $_POST['sticker5'] == 'checked')
            || (isset($_POST['sticker6']) && $_POST['sticker6'] == 'checked')){
            $_SESSION['sticker_selected'] = true;
            $_SESSION['stickers'] = array();
        }

        if (isset($_POST['sticker1']) && $_POST['sticker1'] == 'checked'){
            array_push($_SESSION['stickers'], "1.png");
        }
        if (isset($_POST['sticker2']) && $_POST['sticker2'] == 'checked'){
            array_push($_SESSION['stickers'], "2.png");
        }
        if (isset($_POST['sticker3']) && $_POST['sticker3'] == 'checked'){
            array_push($_SESSION['stickers'], "3.png");
        }
        if (isset($_POST['sticker4']) && $_POST['sticker4'] == 'checked'){
            array_push($_SESSION['stickers'], "4.png");
        }
        if (isset($_POST['sticker5']) && $_POST['sticker5'] == 'checked'){
            array_push($_SESSION['stickers'], "5.png");
        }
        if (isset($_POST['sticker6']) && $_POST['sticker6'] == 'checked'){
            array_push($_SESSION['stickers'], "6.png");
        }

        echo("<script> alert('Stickers Selected!');
                location.href='../dir/studio.php';</script>");
    }else if ($_POST['rem_stickers'] == 'reset'){
        unset($_SESSION['stickers']);
        unset($_SESSION['studio_img']);
        unset($_SESSION['sticker_selected']);
        echo("<script> alert('Stickers Reset!');
                location.href='../dir/studio.php';</script>");
    }
}catch (PDOException $e){
    echo "Sticker Selection Error: " . $e->getMessage();
}


?>