<?php

session_start();
include 'databaseConnection.php';

if (isset($_POST['delete3'])) {
    $id = $_POST['idd3'];
    $delete = "DELETE FROM assets WHERE id='$id'";
    $query = mysqli_query($connect, $delete);

    if ($query) {
        echo "Položka ", $id, " úspěšně smazána!";
    }
    if (isset($_POST['delete1'])) {
        header("Location: evidence_prijmy_a_vydaje.php");
    } else {
        header("Location: majetek_dlouhodoby.php");
    }
    die();
}