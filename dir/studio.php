<?php

session_start();

if (!isset($_SESSION['uid'])){
    echo("<script> alert('Login or Register to Join');
        location.href='login_form.php';</script>");
}

require ($_SESSION['root'] . "/lib/lib.php");


if (!isset($_SESSION['studio_img'])){
    $_SESSION['studio_img'] = DEFAULT_IMG;
}

function get_post_button(){
    if ($_SESSION['studio_img'] == DEFAULT_IMG){
        echo "style='display:none'";
    }
}
?>

<html>
<head>
    <title>Camagru | Studio</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="../js/camera.js"></script>
    <link rel="stylesheet" href="../css/display.css">
</head>
<body>
<header class="box">
    <h1>Studio</h1>
</header>
<div class="menu">
    <a class="menu_buttonC" href="../index.php">Gallery</a>
    <a class="menu_buttonD" href="profile.php">Profile</a>
    <a class="menu_buttonF" href="../lib/logout.php">Logout</a>
</div>
<main class="studio">
    <br>
    <?php get_sel_stickers(); ?>
    <br>

    <h2>Stickers</h2>
    <p><i>select a sticker to use the webcam</i></p>
    <form class="studio_panel" style="height: 80px;" method="post" action="../lib/set_sticker.php">
        <img src="../images/server_images/1.png" class="sticker">
        <input name="sticker1" type="checkbox" class="sticker_but" value="checked">
        <img src="../images/server_images/2.png" class="sticker">
        <input name="sticker2" type="checkbox" class="sticker_but" value="checked">
        <img src="../images/server_images/3.png" class="sticker">
        <input name="sticker3" type="checkbox" class="sticker_but" value="checked">
        <img src="../images/server_images/4.png" class="sticker">
        <input name="sticker4" type="checkbox" class="sticker_but" value="checked">
        <img src="../images/server_images/5.png" class="sticker">
        <input name="sticker5" type="checkbox" class="sticker_but" value="checked">
        <img src="../images/server_images/6.png" class="sticker">
        <input name="sticker6" type="checkbox" class="sticker_but" value="checked">
        <br>
        <input name="set_stickers" type="submit" value="confirm" style="width: 100px">
        <input name="rem_stickers" type="submit" value="reset" style="width: 100px">
    </form>
    <br>

    <div <?php get_cam(); ?>>
        <h2>WebCam</h2>
        <video id="video" class="studio_panel" style="width: 10px; height: 10px"></video>
        <br>
        <button id="cam_on_but" onclick="camOn();">Cam On</button>
        <button id="cam_off_but" onclick="camOff();">Cam Off</button>
        <button id="cam_snap_but" onclick="draw();">Snap</button>
        <br><br>
        <canvas id="canvas" class="studio_panel" width="500" height="375" style="display:none;"></canvas>
    </div>

    <form id="studio_upload" name="studio_upload" method="post" action="../lib/upload_base_img.php" enctype="multipart/form-data">
        <input id="base_img_string" name="base_img_string" type="text" style="display: none">
        <input id="base_img" name="base_img" type="file" >
        <br>
        <input id="base_img_submit" name="base_img_submit" type="submit" value="upload">
    </form>

    <img class="studio_panel" src="<?php echo htmlspecialchars($_SESSION['studio_img'], ENT_QUOTES, 'UTF-8');?>">
    <form id="studio_post" name="studio_post" method="post" action="../lib/upload_studio_img.php">
        <input id="studio_post_button" name="studio_post_button" type="submit" value="post" <?php get_post_button();?>>
    </form>

    <?php
    $con = con($DB_DSN, $DB_USER, $DB_PASSWORD);

    get_user_recent_posts($con, $_SESSION['uid']);

    unset($con);
    ?>

</main>
<footer>
    <hr>
    rerasmus | 2018
</footer>
</body>
</html>
