<?php
session_start();

include 'databaseConnection.php';
include 'class/DatabaseHelper.php';
include 'class/RecordsIncomeExpense.php';
include 'class/Head.php';
include 'class/Header.php';
include 'class/Alert.php';
include 'class/Authenticator.php';
include 'class/AppLogic.php';

$edit = new RecordsIncomeExpense($connect, "Příjmy a výdaje", "Daňová evidence");

$edit->RenderHTML();