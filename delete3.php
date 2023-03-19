<?php
session_start();
include 'databaseConnection.php';

if (isset($_POST['delete3'])) {
        $idf = $_POST['idd3'];
        $delete = "DELETE FROM assets WHERE idf='$idf'";
        $query = mysqli_query($connect, $delete);
        
        if ($query) {
            echo "Položka ", $idf, " úspěšně smazána!";
        }
        header("Location: majetek_dlouhodoby.php");
        die();
    }