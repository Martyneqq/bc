<?php
session_start();
include 'databaseConnection.php';

if (isset($_POST['delete2'])) {
        $idp = $_POST['idd2'];
        $delete = "DELETE FROM demanddebt WHERE idp='$idp'";
        $query = mysqli_query($connect, $delete);
        
        if ($query) {
            echo "Položka ", $idp, " úspěšně smazána!";
        }
        header("Location: evidence_pohledavky_a_dluhy.php");
        die();
    }