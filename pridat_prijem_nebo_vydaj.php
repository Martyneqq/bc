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
                            <td><input class="form-control" type="text" name="nazevf" required=""></td>
                            <td><input class="form-control" type="date" name="datumf" required=""></td>
                            <td>
                                <select name="prijemvydajf" class="form-control">
                                    <option value="">--Vybrat--</option>
                                    <option value="Příjem">Příjem</option>
                                    <option value="Výdaj">Výdaj</option>
                                </select>
                                <!-- <td><input class="form-control" type="text" name="prijemvydaj[]" required=""></td> -->
                            </td>
                            <td><input class="form-control" type="text" name="castkaf" required=""></td>
                            <td>
                                <select name="danf" class="form-control">
                                    <option value="">--Vybrat--</option>
                                    <option value="Ano">Ano</option>
                                    <option value="Ne">Ne</option>
                                </select>
                                <!-- <td><input class="form-control" type="text" name="dan[]" required=""></td> -->
                            </td>
                            <td><input class="form-control" type="text" name="dokladf" required=""></td>
                            <td>
                                <select name="uhradaf" class="form-control">
                                    <option value="">--Vybrat--</option>
                                    <option value="Z účtu">Z účtu</option>
                                    <option value="Hotovost">Hotovost</option>
                                </select>
                                <!-- <td><input class="form-control" type="text" name="uhrada[]" required=""></td> -->
                            </td>
                            <td><input class="form-control" type="text" name="popisf"></td>
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