<?php
session_start();

require ($_SESSION['root'] . "/lib/lib.php");

try {
    if (!isset($_POST['comment_img']) || empty($_POST['comment_img'])){
        echo("<script>location.href='../index.php';</script>");
    }
    if (isset($_POST['comment_submit'])){
        if (isset($_POST['comment']) && !empty($_POST['comment'])
            && isset($_POST['comment_img']) && !empty($_POST['comment_img'])) {
            if (isset($_SESSION['uid'])){
                sanitize($_POST);

                //get owner of image
                $con = con($DB_DSN, $DB_USER, $DB_PASSWORD);

                $to_user = get_img_creator($con,$_POST['comment_img']);
                $from_user = getUser($con, 'id', $_SESSION['uid']);
                $comment = $_POST['comment'];

                //email_comment($to_user, $comment, $from_user);

                echo("<script>location.href='../index.php';</script>");
            }
        }
    }
}catch(PDOException $e) {
    echo "Comment Image Error: " . $e->getMessage();
}
$con = NULL;

?>

<html>
<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="../css/display.css">
</head>
<body>
<div class="menu">
    <a class="menu_buttonA" href="../index.php">Gallery</a>
    <a class="menu_buttonC" href="../dir/studio.php">Studio</a>
    <a class="menu_buttonD" href="../dir/profile.php">Profile</a>
    <a class="menu_buttonF" href="logout.php">Logout</a>
</div>
<main class="studio">
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
        <img class="comment_img" src="<?php echo GALLERY_IMG_URLPATH . $_POST['comment_img'] . ".png"?>">
        <br>
        <input name="comment_img" type="text" style="display: none" value="<?php echo $_POST['comment_img'];?>">
        <textarea name="comment" style="height: 300px; width: 500px" placeholder="Enter comment here..."></textarea>
        <br>
        <input name="comment_submit" type="submit" value="Comment">
    </form>
</main>
<footer>
    <hr>
    rerasmus | 2018
</footer>
</body>
</html>