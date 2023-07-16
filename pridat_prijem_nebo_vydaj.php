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
        <?php
        connectToExpensesTable($connect, $userData);
        ?>
        <div class="container">
            <form class="default-form" id="default-form" method="post" action="">
                <h2>Přidat příjmy nebo výdaje</h2>
                <div class="default-field">
                    <table class="table" id="default-table">
                        <div class="form-group row">
                            <label for="nazev" class="col-sm-4 col-form-label">Název</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" id="nazev" name="nazev" required="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="datum" class="col-sm-4 col-form-label">Datum uhrazení</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="date" id="datum" name="datum" required="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="prijemvydaj" class="col-sm-4 col-form-label">Příjem nebo výdaj</label>
                            <div class="col-sm-8">
                                <select name="prijemvydaj" class="form-control">
                                    <option value="">--Vybrat--</option>
                                    <option value="Příjem">Příjem</option>
                                    <option value="Výdaj">Výdaj</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="castka" class="col-sm-4 col-form-label">Částka</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="number" min="0" name="castka" required="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="dan" class="col-sm-4 col-form-label">Daňová položka</label>
                            <div class="col-sm-8">
                                <select name="dan" class="form-control">
                                    <option value="">--Vybrat--</option>
                                    <option value="Ano">Ano</option>
                                    <option value="Ne">Ne</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="doklad" class="col-sm-4 col-form-label">Číslo dokladu</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" name="doklad" required="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="uhrada" class="col-sm-4 col-form-label">Druh úhrady</label>
                            <div class="col-sm-8">
                                <select name="uhrada" class="form-control">
                                    <option value="">--Vybrat--</option>
                                    <option value="Z účtu">Z účtu</option>
                                    <option value="Hotovost">Hotovost</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="popis" class="col-sm-4 col-form-label">Popis</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" name="popis">
                            </div>
                        </div>
                    </table>
                </div>
                <div class="text-center">
                    <input class="btn btn-success" type="submit" name="ulozit" value="Uložit">
                    <a href="domu.php" class="btn btn-danger">Zrušit</a>
                </div>
            </form>
        </div>

        <!-- Add the Bootstrap JS file -->
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    </body>
</html>