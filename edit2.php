<?php
session_start();

include 'databaseConnection.php';
include 'class/DatabaseHelper.php';
include 'class/RecordsDemandDebt.php';
include 'class/Head.php';
include 'class/Header.php';
include 'class/Alert.php';
include 'class/Authenticator.php';
include 'class/AppLogic.php';

$edit = new RecordsDemandDebt($connect, "Pohledávky a závazky", "Daňová evidence");

$edit->RenderHTML();
