<?php
require ($_SESSION['root'] . "/config/database.php");
require ($_SESSION['root'] . "/lib/classes/User.php");

//-------PATHS-------

define("SERVER_IMG_FILEPATH",$_SESSION['root'] . "/images/server_images/");
define("STUDIO_IMG_FILEPATH",$_SESSION['root'] . "/images/studio_images/");
define("GALLERY_IMG_FILEPATH",$_SESSION['root'] . "/images/gallery_images/");

define("SERVER_IMG_URLPATH", "../images/server_images/");
define("STUDIO_IMG_URLPATH", "../images/studio_images/");
define("GALLERY_IMG_URLPATH", "../images/gallery_images/");

define("DEFAULT_IMG", "https://place.cat/bw/500/375");


//-------DATABASE-------

//CONNECT

function con($DB_DSN, $DB_USER, $DB_PASSWORD){
    try {
        $con = new PDO ($DB_DSN,$DB_USER, $DB_PASSWORD);
        $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $con->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        return $con;
    } catch (PDOException $e) {
        echo "Database Connection Error: " . var_dump($e->getMessage());
    }
}

//CREATE

function newUser($con, &$user)
{
    try {
        $sql = $con->prepare("INSERT INTO users (
          username, first_name, last_name, email, password, token)
          VALUES (?, ?, ?, ?, ?, ?)");
        $sql->execute([$user->username, $user->first_name, $user->last_name,
            $user->email, $user->password, $user->token]);
    }catch(PDOException $e) {
        $e->getMessage();
    }
}

//DELETE

function delUser($con, $key, $val)
{
    try {
        if ($key == "id")
            $sql = $con->prepare("DELETE FROM users WHERE id = ?");
        else if ($key == "username")
            $sql = $con->prepare("DELETE FROM users WHERE username = ?");
        else if ($key == "email")
            $sql = $con->prepare("DELETE FROM users WHERE email = ?");
        else
            exit("Invalid key");

        $sql->execute([$val]);
    }catch(PDOException $e) {
        $e->getMessage();
    }
}

//DATABASE GETTERS

function getUser($con, $key, $val)
{
    try {
        if ($key == "id")
            $sql = $con->prepare("SELECT * FROM users WHERE id = ?");
        else if ($key == "username")
            $sql = $con->prepare("SELECT * FROM users WHERE username = ?");
        else if ($key == "email")
            $sql = $con->prepare("SELECT * FROM users WHERE email = ?");
        else
            exit("Invalid key");

        $sql->execute([$val]);
        $result = $sql->fetch(PDO::FETCH_OBJ);
        return ($result);
    }catch(PDOException $e) {
        $e->getMessage();
    }
}

function getUsers($con)
{
    try {
        $sql = $con->query("SELECT * FROM users");
        $result = $sql->fetchALL(PDO::FETCH_OBJ);
        return ($result);
    }catch(PDOException $e) {
        $e->getMessage();
    }
}

//DATABASE SETTERS

function setUser($con, $id, $key, $val)
{
    try {
        if ($key == "username")
            $sql = $con->prepare("UPDATE users SET username = :val WHERE id = :id");
        else if ($key == "first_name")
            $sql = $con->prepare("UPDATE users SET first_name = :val WHERE id = :id");
        else if ($key == "last_name")
            $sql = $con->prepare("UPDATE users SET last_name = :val WHERE id = :id");
        else if ($key == "email")
            $sql = $con->prepare("UPDATE users SET email = :val WHERE id = :id");
        else if ($key == "password")
            $sql = $con->prepare("UPDATE users SET password = :val WHERE id = :id");
        else if ($key == "token")
            $sql = $con->prepare("UPDATE users SET token = :val WHERE id = :id");
        else if ($key == "verified")
            $sql = $con->prepare("UPDATE users SET verified = :val WHERE id = :id");
        else if ($key == "notify")
            $sql = $con->prepare("UPDATE users SET notify = :val WHERE id = :id");
        else
            exit("Invalid key");

        $sql->execute(['id' => $id, 'val' => $val]);
    }catch(PDOException $e) {
        $e->getMessage();
    }
}


//CHECK

function pass_check($pass1, $pass2){
    return ($pass1 === $pass2);
}

function username_exists($con, $username){
    if (getUser($con, 'username', $username))
        return (1);
    return (0);
}

function email_exists($con, $email){
    if (getUser($con, 'email', $email))
        return (1);
    return (0);
}

function sanitize(array &$input){
    try{
        foreach ($input as $key){
            $key = htmlspecialchars($key, ENT_QUOTES);
            $key = filter_var($key, FILTER_SANITIZE_STRING);
        }
        return true;
    }catch (PDOException $e){
        echo "<script>alert('Db sanitize(&input) Error: " . var_dump($e->getMessage()) . "');</script>";
        return NULL;
    }
}

//REG CHECK

function reg_isinput(&$user){
    return ($user->username && $user->first_name && $user->last_name &&
        $user->email && $user->password && $user->password2);
}

function reg_check($con, $user){
    if (email_exists($con, $user->email))
        return (-1);
    else if (username_exists($con, $user->username))
        return (-2);
    else if (!reg_isinput($user))
        return (-3);
    else if (!pass_check($user->password, $user->password2))
        return (-4);
    else
        return (1);
}

//LOGIN CHECK

function login_check($con, $login_id, $password){
    pass_encrypt($password);
    if (!($user = getUser($con, 'username', $login_id)))
        if (!($user = getUser($con, 'email', $login_id)))
            return (-1);
    if (!pass_check($user->password, $password))
        return (-2);
    if ($user->verified != 1)
        return (-3);
    else
        return ($user->id);
}

//PRINT

function putUser($user)
{
    print("($user->id) $user->username, $user->email, $user->first_name, 
    $user->last_name, $user->password, $user->token");
}


//TOKEN
function token_gen(){
    $token = substr(md5(uniqid(rand(), true)), 16, 16);
    return $token;
}

//PASS ENCRYPT

function pass_encrypt(&$password){
    $password = (serialize(hash('whirlpool', $password)));
}

//EMAIL

//Email Confirmation Token
function email_resetPass($user){

    $subject = "Camagru Password Reset";
    $msg = "Hey, " . $user->first_name . "\n\n"
        . "Please click the link below to reset your password for Camagru\n\n"
        . "http://" . $_SESSION['login_url']
        . "?email=" . $user->email
        . "&token=" . $user->token;

    //$headers[] = 'MIME-Version: 1.0';
    //$headers[] = 'Content-type: text/html; charset=iso-8859-1';

    if (mail($user->email, $subject, $msg, $headers)) {
        echo("<script> alert('A password reset link has been sent to $user->email');</script>");
    } else {
        echo("<script> alert('Password reset email failed to send!');</script>");
    }
}


//IMAGE CHECKS

function img_check($img){
    if (img_checkSize($img)) {
        if (img_checkExt($img))
            return 1;
        else
            return -1;
    }
    return -2;
}

function img_checkSize($img){
    if (isset($img))
        if ($img['size'] < 5e+6)
            return 1;

    return 0;
}

function img_checkExt($img){
    if (isset($img)){
        $ext_array = array("jpeg", "jpg", "png");
        $ext = (end(explode('.',$_FILES['img']['name'])));

        if (in_array($ext, $ext_array))
            return 1;
    }
    return 0;
}


//IMAGE

function getLastImage($con, $uid){
    try {
        $sql = $con->prepare("SELECT * FROM images WHERE user_id = ? ORDER BY id DESC LIMIT 1");

        $sql->execute([$uid]);
        $img = $sql->fetch(PDO::FETCH_OBJ);
        return ($img);
    }catch(PDOException $e) {
        $e->getMessage();
    }
}

function img_upload($con, $uid, $ext){
    try{
        $sql = $con->prepare("INSERT INTO images (
          user_id, ext)
          VALUES (?, ?)");
        $sql->execute([$uid, $ext]);
    }catch (PDOException $e) {
        echo "Image Upload  Error: " . var_dump($e->getMessage());
    }
}

//STUDIO

function get_sel_stickers(){
    if (isset($_SESSION['sticker_selected'])){
        if (isset($_SESSION['stickers']) && !empty($_SESSION['stickers'])){
            echo "<h2>Selected Stickers</h2>";
            foreach ($_SESSION['stickers'] as $s){
                if (isset($s)){
                    echo "<img class='sticker' src='" . SERVER_IMG_URLPATH . $s ."'>";
                }
            }
        }
    }
}


function get_cam(){
    if (!isset($_SESSION['sticker_selected']) || !isset($_SESSION['stickers']) || empty($_SESSION['stickers'])){
        echo "style=\"display: none;\"";
    }
}

function get_studio_img(){
    if (!isset($_SESSION['studio_img'])){
        $_SESSION['studio_img'] = DEFAULT_IMG;
    }
    echo $_SESSION['studio_img'];
}

function overlay($img_name){
    $img_explode = explode('.',$img_name);
    $img_ext = end($img_explode);

    if ($img_ext == "png"){
        $dest = imagecreatefrompng(STUDIO_IMG_URLPATH . $img_name);
    }else{
        $dest = imagecreatefromjpeg(STUDIO_IMG_URLPATH . $img_name);
    }

    if (isset($_SESSION['stickers']) && !empty(end($_SESSION['stickers']))){
        foreach ($_SESSION['stickers'] as $s){
            $src = imagecreatefrompng(SERVER_IMG_URLPATH . $s);
            imagecopy($dest, $src, 0, 0, 0, 0, 150, 150);
            array_shift($_SESSION['stickers']);
        }
    }

    if ($img_ext == "png"){
        imagepng($dest, (STUDIO_IMG_URLPATH . $img_name));
    }else{
        imagejpeg($dest, (STUDIO_IMG_URLPATH . $img_name));
    }
}

function base64_to_png($base64_string, $file_dest) {
    $fd = fopen( $file_dest, 'wb' );

    // $data[ 0 ] == "data:image/png;base64"
    // $data[ 1 ] == "image data"
    $data = explode( ',', $base64_string );

    if (count($data) > 1){
        fwrite( $fd, base64_decode( $data[ 1 ] ) );
    }else{
        fclose( $fd );
        return NULL;
    }

    fclose( $fd );
    return $file_dest;
}

function get_user_recent_posts($con, $uid){
    try {
        $sql = $con->prepare("SELECT * FROM images WHERE user_id = ? ORDER BY id DESC LIMIT 5");

        $sql->execute([$uid]);

        if ($res = $sql->fetchAll()) {
            if (count($res) == 1){
                $img_name = $res[0]->id . "." . $res[0]->ext;
                echo "<img class='thumbnails' src='". GALLERY_IMG_URLPATH . $img_name ."'>";
            }
            else if (count($res) > 1){
                foreach ($res as $img) {
                    $img_name = $img->id . "." . $img->ext;
                    echo "<img class='thumbnails' src='". GALLERY_IMG_URLPATH . $img_name ."'>";
                }
            }
        }
    }catch(PDOException $e) {
        $e->getMessage();
    }
}

function get_gallery_images_anon($con, $offset){
    try {
        if ($offset == 0){
            $sql = $con->prepare("SELECT * FROM images ORDER BY id DESC LIMIT 8");
            $sql->execute();
        }else{
            $sql = $con->prepare("SELECT * FROM images ORDER BY id DESC LIMIT 8 OFFSET :offset");
            $sql->bindParam(':offset', $offset, PDO::PARAM_INT);
            $sql->execute();
        }

        if ($res = $sql->fetchAll()) {
            if (count($res) < 8){
                $_SESSION['end'] = true;
            }else{
                unset($_SESSION['end']);
            }
            if (count($res) == 1){
                $img_name = $res[0]->id . "." . $res[0]->ext;
                echo "<img class='gallery_img' src='". "images/gallery_images/" . $img_name ."'>";
            }
            else if (count($res) > 1){
                foreach ($res as $img) {
                    $img_name = $img->id . "." . $img->ext;
                    echo "<img class='gallery_img' src='". "images/gallery_images/" . $img_name ."'>";
                }
            }
        }
    }catch(PDOException $e) {
        $e->getMessage();
    }
}

function get_like_button($con, $img_id){
    try {
        if (!has_liked($con, $img_id)){
            echo "<form action=\"lib/gallery_like.php\" method=\"post\" class='gallery_form'>";
            echo "<input id='like_img' name='like_img' value='" . $img_id . "' style='display:none'>";
            echo "<input id='like_button' name='like_button' type='submit' value='Like' class='gallery_button'>";
            echo "</form>";
        }else{
            echo "<br>You liked this image!<br>";
        }
    }catch(PDOException $e) {
        $e->getMessage();
    }
}

function get_comment_button($img_id){
    try {
        echo "<form action=\"lib/gallery_comment.php\" method=\"post\" class='gallery_form'>";
        echo "<input id='comment_img' name='comment_img' value='" . $img_id . "' style='display:none'>";
        echo "<input id='comment_button' name='comment_button' type='submit' value='Comment' class='gallery_button' style='width: 80px'>";
        echo "</form>";
    }catch(PDOException $e) {
        $e->getMessage();
    }
}

function get_del_button($img_id){
    try {
        echo "<form action=\"lib/gallery_del_img.php\" method=\"post\" class='gallery_form'>";
        echo "<input id='del_img' name='del_img' value='" . $img_id . "' style='display:none'>";
        echo "<input id='del_button' name='del_button' type='submit' value='Delete' class='gallery_button'>";
        echo "</form>";
    }catch(PDOException $e) {
        $e->getMessage();
    }
}

function get_gallery_images($con, $uid, $offset){
    try {
        if ($offset == 0){
            $sql = $con->prepare("SELECT * FROM images ORDER BY id DESC LIMIT 8 ");
            $sql->execute();
        }else{
            $sql = $con->prepare("SELECT * FROM images ORDER BY id DESC  LIMIT 8 OFFSET :offset ");
            $sql->bindParam(':offset', $offset, PDO::PARAM_INT);
            $sql->execute();
        }

        if ($res = $sql->fetchAll()) {
            if (count($res) < 8){
                $_SESSION['end'] = true;
            }else{
                unset($_SESSION['end']);
            }
            if (count($res) == 1){
                $img_name = $res[0]->id . "." . $res[0]->ext;
                echo "<img class='gallery_img' src='". "images/gallery_images/" . $img_name ."'>";
                if (isset($_SESSION['uid'])){
                    if ($res[0]->user_id != $_SESSION['uid']){
                        get_like_button($con, $res[0]->id);
                        get_comment_button($res[0]->id);
                    }else{
                        get_del_button($res[0]->id);
                    }
                }
            }
            else if (count($res) > 1){
                foreach ($res as $img) {
                    $img_name = $img->id . "." . $img->ext;
                    echo "<img class='gallery_img' src='". "images/gallery_images/" . $img_name ."'>";

                    if (isset($_SESSION['uid'])){
                        if ($img->user_id != $_SESSION['uid']){
                            get_like_button($con, $img->id);
                            get_comment_button($img->id);
                        }else{
                            get_del_button($img->id);
                        }
                    }
                }
            }
        }
    }catch(PDOException $e) {
        $e->getMessage();
    }
}

function get_img_creator($con,$img_id){
    try{
        $sql = $con->prepare("SELECT user_id FROM images WHERE id = :img_id");
        $sql->bindParam(':img_id', $img_id, PDO::PARAM_INT);
        $creator_id = $sql->execute();

        $user = getUser($con, 'id', $creator_id);
        return $user;
    }catch(PDOException $e) {
        $e->getMessage();
    }
}

function email_comment($to_user,$comment,$from_user){
    sanitize($comment);

    $subject = "Camagru Comment Notification";
    $msg = htmlspecialchars($comment, ENT_QUOTES, 'UTF-8') . "\n\n"
        . "from: " . $from_user->username;

    $headers[] = 'MIME-Version: 1.0';
    $headers[] = 'Content-type: text/html; charset=iso-8859-1';

    if (mail($to_user->email, $subject, $msg, $headers)) {
        echo("<script> alert('Comment sent to ".htmlentities($to_user->username)."');</script>");
    } else {
        echo("<script> alert('Comment failed to send!');</script>");
    }
}

function add_like($con, $img_id){
    try{
        $sql = $con->prepare("INSERT INTO likes (
          img_id, liked_by)
          VALUES (:img_id, :liked_by)");

        $sql->bindParam(':img_id', $img_id, PDO::PARAM_INT);
        $sql->bindParam(':liked_by', $_SESSION['uid'], PDO::PARAM_INT);

        $sql->execute();

    }catch(PDOException $e) {
        $e->getMessage();
    }
}

function has_liked($con, $img_id){
    try{
        $sql = $con->prepare("SELECT * FROM likes WHERE img_id = :img_id");
        $sql->bindParam(':img_id', $img_id, PDO::PARAM_INT);
        $sql->execute();

        if ($ret = $sql->fetchAll()){
            if (count($ret) == 1){

                if ($_SESSION['uid'] == $ret[0]->liked_by){
                    return true;
                }else{
                    return false;
                }
            }else if (count($ret) > 1){
                foreach ($ret as $like){
                    if ($_SESSION['uid'] == $like->liked_by){
                        return true;
                    }
                }
                return false;
            }
        }
    }catch(PDOException $e) {
        $e->getMessage();
    }
}