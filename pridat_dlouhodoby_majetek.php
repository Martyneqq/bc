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
                Přidat dlouhodobý majetek
                <hr>
                <div class="default-field">
                    <table id="default-table">
                        <tr>
                            <th>Číslo položky</th>
                            <th>Název</th>
                            <th>Pořizovací cena</th>
                            <th>Datum zařazení</th>
                            <th>Datum vyřazení</th>
                            <th>Odpisová skupina</th>
                            <th>Způsob odpisu</th>
                            <th>Popis</th>
                        </tr>
                        <?php
                        connectToAssetsTable($connect, $userData);
                        ?>
                        <tr>
                            <td><input class="form-control" type="text" name="cislopolozky" required=""></td>
                            <td><input class="form-control" type="text" name="nazev" required=""></td>
                            <td><input class="form-control" type="text" name="castka" required=""></td>
                            <td><input class="form-control" type="date" name="datum" required=""></td>
                            <td><input class="form-control" type="date" name="datumvyrazeni"></td>
                            <td>
                                <select name="odpis" class="form-control" required="">
                                    <option value="">--Vybrat--</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                </select>
                                <!-- <td><input class="form-control" type="text" name="prijemvydaj[]" required=""></td> -->
                            </td>
                            <td>
                                <select name="zpusob" class="form-control" required="">
                                    <option value="">--Vybrat--</option>
                                    <option value="Rovnoměrný">Rovnoměrný</option>
                                    <option value="Zrychlený">Zrychlený</option>
                                </select>
                                <!-- <td><input class="form-control" type="text" name="dan[]" required=""></td> -->
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