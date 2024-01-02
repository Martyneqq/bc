<?php
session_start();

include 'class/Authenticator.php';
include 'class/Head.php';
include 'class/Header.php';
include 'class/Alert.php';
include 'class/AppLogic.php';
include 'class/DatabaseConnect.php';
include 'class/DatabaseHelper.php';

$auth = new Authenticator($connect, "Daňová evidence");

$auth->RenderHTML();