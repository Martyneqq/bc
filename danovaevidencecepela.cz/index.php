<?php
session_start();
include 'databaseConnection.php';
include 'class/Authenticator.php';
include 'class/Head.php';
include 'class/Header.php';
include 'class/TaxRecordsPage.php';
include 'class/Alert.php';
include 'class/RecordsIncomeExpense.php';
include 'class/RecordsDemandDebt.php';
include 'class/RecordsAssets.php';
include 'class/AppLogic.php';
include 'class/DatabaseHelper.php';

$year = isset($_POST['dateSelect']) ? $_POST['dateSelect'] : date("Y");
$page = new TaxRecordsPage($connect, $year, "Daňová evidence", "Daňová evidence");

$page->RenderHTML($year);
?>
