<div class="menu">
    <a class="menu_buttonC" href="dir/login_form.php">Login</a>
    <a class="menu_buttonD" href="dir/register_form.php">Register</a>
</div>
<main class="gallery">
    <?php
    $con = con($DB_DSN, $DB_USER, $DB_PASSWORD);
    $limit = 8;
    $offset = $_SESSION['page'] * $limit;

    get_gallery_images_anon($con, $offset);

    unset($con);
    ?>
</main>
