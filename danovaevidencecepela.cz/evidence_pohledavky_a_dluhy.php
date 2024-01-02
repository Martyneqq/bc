<?php
session_start();

include 'databaseConnection.php';
include 'class/Head.php';
include 'class/Header.php';
include 'class/RecordsDemandDebt.php';
include 'class/Alert.php';
include 'class/Authenticator.php';
include 'class/AppLogic.php';
include 'class/DatabaseHelper.php';

$rdd = new RecordsDemandDebt($connect, "Pohledávky a závazky", "Daňová evidence");

$rdd->RenderHTML();
?>
