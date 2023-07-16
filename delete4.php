<?php

session_start();
include 'databaseConnection.php';

if (isset($_POST['deleteD'])) {
    $id = $_POST['iddD'];
    $delete = "DELETE FROM minorassets WHERE id='$id'";
    $query = mysqli_query($connect, $delete);

    if ($query) {
        echo "Položka ", $id, " úspěšně smazána!";
    }

    header("Location: majetek_drobny.php");
    
    die();
}