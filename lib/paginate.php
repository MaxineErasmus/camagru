<?php

try{
    if (isset($_GET['nav']) && !empty($_GET['nav'])){
        sanitize($_GET);

        if (!isset($_SESSION['page'])){
            $_SESSION['page'] = 0;
        }
        if ($_GET['nav'] == "next"){
            $_SESSION['page']++;
            echo("<script>location.href='" . "index.php" . "';</script>");
        }elseif ($_GET['nav'] == "prev"){
            if ($_SESSION['page'] !== 0){
                $_SESSION['page']--;
                echo("<script>location.href='" . "index.php" . "';</script>");
            }
        }
    }
}catch(PDOException $e) {
    echo "Pagination Error: " . $e->getMessage();
}

?>