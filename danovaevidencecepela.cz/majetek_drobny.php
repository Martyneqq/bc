<?php

session_start();

include 'databaseConnection.php';
include 'class/Head.php';
include 'class/Header.php';
include 'class/Alert.php';
include 'class/RecordsMinorAssets.php';
include 'class/RecordsIncomeExpense.php';
include 'class/Authenticator.php';
include 'class/AppLogic.php';
include 'class/DatabaseHelper.php';

$rma = new RecordsMinorAssets($connect, "Drobný majetek", "Daňová evidence");

$rma->RenderHTML();
?>
