<div class="menu">
    <a class="menu_buttonC" href="dir/studio.php">Studio</a>
    <a class="menu_buttonD" href="dir/profile.php">Profile</a>
    <a class="menu_buttonF" href="lib/logout.php">Logout</a>
</div>
<main class="gallery">
        <?php
        $con = con($DB_DSN, $DB_USER, $DB_PASSWORD);
        $limit = 8;
        $offset = $_SESSION['page'] * $limit;

        get_gallery_images($con, $_SESSION['uid'], $offset);

        unset($con);
        ?>
</main>