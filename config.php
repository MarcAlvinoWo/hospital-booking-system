<?php

define('DB_SERVER', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'demo');

$link = mysqli_connect(DB_SERVER, DB_USERNAME, DB_PASSWORD);
if ($link === false) {
    die('ERROR: Could not connect to MySQL server. ' . mysqli_connect_error());
}

if (!mysqli_select_db($link, DB_NAME)) {
    $createDbSql = "CREATE DATABASE IF NOT EXISTS " . DB_NAME . " CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci";
    if (!mysqli_query($link, $createDbSql)) {
        die('ERROR: Could not create database. ' . mysqli_error($link));
    }
    if (!mysqli_select_db($link, DB_NAME)) {
        die('ERROR: Could not select database. ' . mysqli_error($link));
    }
}
?>
