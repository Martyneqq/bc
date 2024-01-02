<?php
session_start();

include 'databaseConnection.php';
include 'class/Head.php';
include 'class/Header.php';
include 'class/RecordsIncomeExpense.php';
include 'class/Alert.php';
include 'class/Authenticator.php';
include 'class/AppLogic.php';
include 'class/DatabaseHelper.php';

//$year = isset($_POST['dateSelect']) ? $_POST['dateSelect'] : date("Y");
//$userData = check($connect);
//$userID = $userData['id'];
$rie = new RecordsIncomeExpense($connect, "Příjmy a výdaje", "Daňová evidence");

$rie->RenderHTML();
?>
