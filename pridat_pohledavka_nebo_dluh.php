<?php
session_start();
include 'functions.php';
include 'databaseConnection.php';
$userData = check($connect);
?>
<!DOCTYPE html>
<html>
        <head>
    <?php
    include 'inc/head.php';
    ?>
    </head>
    <header>
    <?php
    include 'inc/header.php';
    ?>
    </header>
    <body>
        <?php
        connectToDemandDebtTable($connect, $userData);
        ?>
        <div class="container">
            <form class="default-form" id="default-form" method="post" action="">
                <h3>Přidat pohledávku nebo dluh</h3>
                <div class="default-field">
                    <table class="table" id="default-table">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Název</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" id="nazevp" name="nazevp" required="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Číslo dokladu</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" name="cislodokladp" required="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Firma</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" name="firmap" required="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Datum vystavení</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="date" name="datump" required="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Pohledávka/dluh</label>
                            <div class="col-sm-8">
                                <select name="pohledavkadluhp" class="form-control" required="">
                                    <option value="">--Vybrat--</option>
                                    <option value="Pohledávka">Pohledávka</option>
                                    <option value="Dluh">Dluh</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Hodnota</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="number" min="0"  name="hodnotap" required="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Daňový?</label>
                            <div class="col-sm-8">
                                <select name="danp" class="form-control" required="">
                                    <option value="">--Vybrat--</option>
                                    <option value="Ano">Ano</option>
                                    <option value="Ne">Ne</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Popis</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" name="popisp">
                            </div>
                        </div>
                    </table>
                </div>
                <div class="text-center">
                    <input class="btn btn-success" type="submit" name="ulozit" value="Uložit">
                    <input class="btn btn-primary" type="submit" name="ulozitadalsipohl" value="Uložit a další">
                    <a href="index.php" class="btn btn-danger">Zrušit</a>
                </div>
            </form>
        </div>
        <!-- Add the Bootstrap JS file -->
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

    </body>
</html>