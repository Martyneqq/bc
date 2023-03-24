<?php
session_start();
include 'databaseConnection.php';

if (isset($_POST['delete'])) {
        $id = $_POST['idd'];
        $delete = "DELETE FROM incomeexpense WHERE id='$id'";
        $query = mysqli_query($connect, $delete);
        
        if ($query) {
            echo "Položka ", $id, " úspěšně smazána!";
        }
        header("Location: evidence_prijmy_a_vydaje.php");
        die();
    }