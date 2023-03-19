<?php
session_start();
include 'databaseConnection.php';

if (isset($_POST['delete'])) {
        $idf = $_POST['idd'];
        $delete = "DELETE FROM assets WHERE idf='$idf'";
        $query = mysqli_query($connect, $delete);
        
        if ($query) {
            echo "Položka ", $idf, " úspěšně smazána!";
        }
        header("Location: evidence_prijmy_a_vydaje.php");
        die();
    }