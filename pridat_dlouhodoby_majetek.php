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
        connectToAssetsTable($connect, $userData);
        ?>
        <script>
            window.onload = function () {
                var castkaInput = document.querySelector('input[name="castka"]');
                //var dokladInput = document.querySelector('input[name="doklad"]');
                //var danInput = document.querySelector('select[name="dan"]');
                var odpisInput = document.querySelector('select[name="odpis"]');
                var zpusobInput = document.querySelector('select[name="zpusob"]');
                var errorMessage = document.querySelector('#error-message');

                castkaInput.addEventListener('input', function () {
                    if (castkaInput.value < 80000) {
                        //dokladInput.disabled = true;
                        //danInput.disabled = true;
                        odpisInput.disabled = true;
                        zpusobInput.disabled = true;
                        errorMessage.style.display = 'block';
                    } else {
                        //dokladInput.disabled = false;
                        //danInput.disabled = false;
                        odpisInput.disabled = false;
                        zpusobInput.disabled = false;
                        errorMessage.style.display = 'none';
                    }
                });
            };
        </script>
        <div class="container">
            <form class="default-form" id="default-form" method="post" action="">
                <h3>Přidat dlouhodobý nebo drobný majetek</h3>
                <div class="default-field">
                    <table class="table" id="default-table">
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Číslo položky</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" name="doklad" required="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Název</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" name="nazev" required="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Pořizovací cena</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="number" min="0" name="castka" required="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Datum zařazení</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="date" name="datum" required="">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Daň</label>
                            <div class="col-sm-8">
                                <select name="dan" class="form-control" required="">
                                    <option value="">--Vybrat--</option>
                                    <option value="Ano">Ano</option>
                                    <option value="Ne">Ne</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Odpisová skupina</label>
                            <div class="col-sm-8">
                                <select name="odpis" class="form-control" required="">
                                    <option value="">--Vybrat--</option>
                                    <option value="1">1</option>
                                    <option value="2">2</option>
                                    <option value="3">3</option>
                                    <option value="4">4</option>
                                    <option value="5">5</option>
                                    <option value="6">6</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Způsob odpisu</label>
                            <div class="col-sm-8">
                                <select name="zpusob" class="form-control" required="">
                                    <option value="">--Vybrat--</option>
                                    <option value="Rovnoměrný">Rovnoměrný</option>
                                    <option value="Zrychlený">Zrychlený</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-sm-4 col-form-label">Popis</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" name="popis">
                            </div>
                        </div>
                    </table>
                </div>
                <div class="text-center">
                    <input class="btn btn-success" type="submit" name="ulozit" value="Uložit">
                    <input class="btn btn-primary" type="submit" name="ulozitadalsi" value="Uložit a další">
                    <a href="index.php" class="btn btn-danger">Zrušit</a>
                </div>
            </form>
        </div>

        <!-- Add the Bootstrap JS file -->
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    </body>
</html>