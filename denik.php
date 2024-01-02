<?php
session_start();

include 'databaseConnection.php';
include 'class/DatabaseHelper.php';
include 'class/Header.php';
include 'class/Head.php';
include 'class/Authenticator.php';
include 'class/Alert.php';
include 'class/AppLogic.php';
require_once 'class/RecordsJournalIncomeExpense.php';

$rj = new RecordsJournalIncomeExpense($connect, "Deník příjmů a výdajů", "Daňová evidence");

$rj->RenderHTML();