<?php

session_start();
include 'databaseConnection.php';

if (isset($_POST['delete']) || isset($_POST['delete0'])) {
    $id = $_POST['idd'];
    $delete = "DELETE FROM incomeexpense WHERE id='$id'";
    $query = mysqli_query($connect, $delete);
    if (isset($_POST['delete'])) {
        header("Location: evidence_prijmy_a_vydaje.php");
    } else if (isset($_POST['delete0'])) {
        header("Location: majetek_drobny.php");
    }
    die();
}