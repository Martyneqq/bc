<?php
$server = "md377.wedos.net";
$username = "a328711_evidenc";
$password = "VURwVvue";
$database = "d328711_evidenc";

if (!$connect = mysqli_connect($server, $username, $password, $database)) {
    die("Login error");
}
mysqli_set_charset($connect, "utf8");