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
        connectToDemandDebtTable($connect, $userData);
        ?>
        <div class="container">
            <form class="default-form" id="default-form" method="post" action="">
                <h2>Přidat pohledávku nebo dluh</h2>
                <div class="default-field">
                    <table class="table" id="default-table">
                        <div class="form-group row">
                            <label for="nazevp" class="col-sm-4 col-form-label">Název</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" id="nazevp" name="nazevp" required="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="cislodokladp" class="col-sm-4 col-form-label">Číslo dokladu</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" name="cislodokladp" required="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="firmap" class="col-sm-4 col-form-label">Firma</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" name="firmap" required="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="datump" class="col-sm-4 col-form-label">Datum vystavení</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="date" name="datump" required="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="pohledavkadluhp" class="col-sm-4 col-form-label">Pohledávka/dluh</label>
                            <div class="col-sm-8">
                                <select name="pohledavkadluhp" class="form-control">
                                    <option value="">--Vybrat--</option>
                                    <option value="Pohledávka">Pohledávka</option>
                                    <option value="Dluh">Dluh</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="hodnotap" class="col-sm-4 col-form-label">Hodnota</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="number" min="0"  name="hodnotap" required="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="danp" class="col-sm-4 col-form-label">Daňový?</label>
                            <div class="col-sm-8">
                                <select name="danp" class="form-control">
                                    <option value="">--Vybrat--</option>
                                    <option value="Ano">Ano</option>
                                    <option value="Ne">Ne</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="popisp" class="col-sm-4 col-form-label">Popis</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" name="popisp">
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