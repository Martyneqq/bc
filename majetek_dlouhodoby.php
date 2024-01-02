<?php
session_start();

include 'databaseConnection.php';
include 'class/Head.php';
include 'class/Header.php';
include 'class/RecordsAssets.php';
include 'class/Alert.php';
include 'class/Authenticator.php';
include 'class/AppLogic.php';
include 'class/DatabaseHelper.php';

$ra = new RecordsAssets($connect, "Dlouhodobý majetek", "Daňová evidence");

/*if ($userID) {
    executeDepreciation($connect, $userID);
}*/

$ra->RenderHTML();