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
                Přidat pohledávku nebo dluh
                <hr>
                <div class="default-field">
                    <table id="default-table">
                        <tr>
                            <th>Název</th>
                            <th>Číslo dokladu</th>
                            <th>Firma</th>
                            <th>Datum vystavení</th>
                            <th>Pohledávka/dluh</th>
                            <th>Hodnota</th>
                            <th>Daňový?</th>
                            <th>Popis</th>
                        </tr>
                        <?php
                        $connect = mysqli_connect("localhost", "root", "", "evidence");
                        connectToDemandDebtTable($connect, $userData);
                        ?>
                        <tr>
                            <td><input class="form-control" type="text" name="nazevp" required=""></td>
                            <td><input class="form-control" type="text" name="cislodokladp" required=""></td>
                            <td><input class="form-control" type="text" name="firmap" required=""></td>
                            <td><input class="form-control" type="date" name="datump" required=""></td>
                            <td>
                                <select name="pohledavkadluhp" class="form-control">
                                    <option value="">--Vybrat--</option>
                                    <option value="Pohledavka">Pohledávka</option>
                                    <option value="Dluh">Dluh</option>
                                </select>
                                <!-- <td><input class="form-control" type="text" name="dan[]" required=""></td> -->
                            </td>
                            <td><input class="form-control" type="text" name="hodnotap" required=""></td>
                            <td>
                                <select name="danp" class="form-control">
                                    <option value="">--Vybrat--</option>
                                    <option value="Ano">Ano</option>
                                    <option value="Ne">Ne</option>
                                </select>
                                <!-- <td><input class="form-control" type="text" name="uhrada[]" required=""></td> -->
                            </td>

                            <td><input class="form-control" type="text" name="popisp"></td>
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