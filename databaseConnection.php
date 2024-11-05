<?php
$server = "127.0.0.1";
$username = "root";
$password = "";
$database = "index";

if (!$connect = mysqli_connect($server, $username, $password, $database)) {
    die("Login error");
}
mysqli_set_charset($connect, "utf8");