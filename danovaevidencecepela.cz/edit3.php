<?php
session_start();

include 'databaseConnection.php';
include 'class/RecordsAssets.php';
include 'class/Head.php';
include 'class/Header.php';
include 'class/Alert.php';
include 'class/Authenticator.php';
include 'class/AppLogic.php';
include 'class/DatabaseHelper.php';

$edit = new RecordsAssets($connect, "Dlouhodobý majetek", "Daňová evidence");

$edit->RenderHTML();
?>
