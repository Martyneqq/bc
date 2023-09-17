<?php

if (!$connect = mysqli_connect("md377.wedos.net", "a328711_evidenc", "VURwVvue", "d328711_evidenc")) {
    die("Login error");
}
mysqli_set_charset($connect, "utf8");