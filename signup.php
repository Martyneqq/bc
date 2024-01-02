<?php
session_start();
include 'databaseConnection.php';
include 'class/Head.php';
include 'class/Header.php';
include 'class/Authenticator.php';
include 'class/DatabaseHelper.php';
include 'class/Alert.php';
include 'class/AppLogic.php';

$auth = new Authenticator($connect, "Daňová evidence");

$auth->RenderHTML();