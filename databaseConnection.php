<?php

if (!$connect = mysqli_connect("localhost", "root", "", "evidence")) {
    die("Login error");
}