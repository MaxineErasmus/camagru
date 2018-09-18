<?php

require 'database.php';

try {
    echo "----------------SETUP---------------- <br>";

    //create PDO instance
    $con = new PDO($DB_HOST, $DB_USER, $DB_PASSWORD);
    $con->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
    $con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "1. SQL Connection Successful <br>";

    //PDO mysql CREATE DATABASE
    $sql = "CREATE DATABASE IF NOT EXISTS Camagru";
    $con->exec($sql);
    echo "2. Camagru Database Created <br>";


    //PDO mysql USE DATABASE
    $sql = "USE Camagru";
    $con->exec($sql);
    echo "3. Connected to Camagru <br>";


    //PDO mysql CREATE TABLE (Users)
    $sql = "CREATE TABLE IF NOT EXISTS Users (
        id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
        username VARCHAR (50),
        first_name VARCHAR (50),
        last_name VARCHAR (50),
        email VARCHAR(100),
        password VARCHAR (1000),
        token VARCHAR (1000),
        verified TINYINT(1) DEFAULT 0,
        notify TINYINT(1) DEFAULT 1
    )";
    $con->exec($sql);
    echo "4. Users Table Created <br>";


    //PDO mysql CREATE TABLE (Images)
    $sql = "CREATE TABLE IF NOT EXISTS Images (
        id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        user_id INT UNSIGNED,
        ext VARCHAR (50),
        FOREIGN KEY (user_id) REFERENCES Users(id),
        timestamp DATETIME DEFAULT CURRENT_TIMESTAMP
    )";
    $con->exec($sql);
    echo "5. Images Table Created <br>";


    //PDO mysql CREATE TABLE (Likes)
    $sql = "CREATE TABLE IF NOT EXISTS Likes (
        id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
        img_id INT UNSIGNED,
        liked_by INT UNSIGNED,
        FOREIGN KEY (img_id) REFERENCES Images(id),
        FOREIGN KEY (liked_by) REFERENCES Users(id),
        timestamp DATETIME DEFAULT CURRENT_TIMESTAMP
    )";
    $con->exec($sql);
    echo "6. Likes Table Created <br>";


    $con = NULL;

    echo "--------------FINISHED--------------- <br>";
    echo '<a href="../index.php" style="text-decoration: none">-----> Visit Camagru Website <-----</a>';
}
catch(PDOException $e) {
    echo "----------------ERROR---------------- <br>";
    echo $sql . "<br>" . $e->getMessage() . "<br>";
}

?>

