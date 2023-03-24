<?php
session_start();
include 'inc/head.php';
include 'functions.php';
include 'databaseConnection.php';
$userData = check($connect);
include 'inc/header.php';
?>
<!DOCTYPE html>
<html>
    <head>

    </head>
    <header>
        
    </header>
    <body>
        <div style="margin: 1%">
            <form class="default-form" id ="default-form" method="post" action="">
                <hr>
                Přidat příjmy nebo výdaje
                <hr>
                <div class="default-field">
                    <table id="default-table">
                        <tr>
                            <th>Název</th>
                            <th>Datum uhrazení</th>
                            <th>Příjem nebo výdaj</th>
                            <th>Částka</th>
                            <th>Daňová položka</th>
                            <th>Číslo dokladu</th>
                            <th>Druh úhrady</th>
                            <th>Popis</th>
                            <th></th>
                        </tr>
                        <?php
                        connectToExpensesTable($connect, $userData);
                        ?>
                        <tr>
                            <td><input class="form-control" type="text" name="nazev" required=""></td>
                            <td><input class="form-control" type="date" name="datum" required=""></td>
                            <td>
                                <select name="prijemvydaj" class="form-control">
                                    <option value="">--Vybrat--</option>
                                    <option value="Příjem">Příjem</option>
                                    <option value="Výdaj">Výdaj</option>
                                </select>
                                <!-- <td><input class="form-control" type="text" name="prijemvydaj[]" required=""></td> -->
                            </td>
                            <td><input class="form-control" type="text" name="castka" required=""></td>
                            <td>
                                <select name="dan" class="form-control">
                                    <option value="">--Vybrat--</option>
                                    <option value="Ano">Ano</option>
                                    <option value="Ne">Ne</option>
                                </select>
                                <!-- <td><input class="form-control" type="text" name="dan[]" required=""></td> -->
                            </td>
                            <td><input class="form-control" type="text" name="doklad" required=""></td>
                            <td>
                                <select name="uhrada" class="form-control">
                                    <option value="">--Vybrat--</option>
                                    <option value="Z účtu">Z účtu</option>
                                    <option value="Hotovost">Hotovost</option>
                                </select>
                                <!-- <td><input class="form-control" type="text" name="uhrada[]" required=""></td> -->
                            </td>
                            <td><input class="form-control" type="text" name="popis"></td>
                        </tr>
                    </table>
                    <center>
                        <input class="btn btn-success" type="submit" name="ulozit" value="Uložit">
                    </center>
                </div>
            </form>
        </div>
    </body>
</html>