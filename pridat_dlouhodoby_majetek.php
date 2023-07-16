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
        <script>
            window.onload = function () {
                var castkaInput = document.querySelector('input[name="castka"]');
                var cisloPolozkyInput = document.querySelector('input[name="cislopolozky"]');
                var danInput = document.querySelector('select[name="dan"]');
                var odpisInput = document.querySelector('select[name="odpis"]');
                var zpusobInput = document.querySelector('select[name="zpusob"]');
                var errorMessage = document.querySelector('#error-message');

                castkaInput.addEventListener('input', function () {
                    if (castkaInput.value < 80000) {
                        cisloPolozkyInput.disabled = true;
                        danInput.disabled = true;
                        odpisInput.disabled = true;
                        zpusobInput.disabled = true;
                        errorMessage.style.display = 'block';
                    } else {
                        cisloPolozkyInput.disabled = false;
                        danInput.disabled = false;
                        odpisInput.disabled = false;
                        zpusobInput.disabled = false;
                        errorMessage.style.display = 'none';
                    }
                });
            };
        </script>
    </head>
</head>
<header>

</header>
<body>
    <?php
    connectToAssetsTable($connect, $userData);
    ?>
    <div class="container">
        <form class="default-form" id="default-form" method="post" action="">
            <h2>Přidat dlouhodobý nebo drobný majetek</h2>
            <div class="default-field">
                <table class="table" id="default-table">
                    <div class="form-group row">
                        <label for="cislopolozky" class="col-sm-4 col-form-label">Číslo položky</label>
                        <div class="col-sm-8">
                            <input class="form-control" type="text" name="cislopolozky" required="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="nazev" class="col-sm-4 col-form-label">Název</label>
                        <div class="col-sm-8">
                            <input class="form-control" type="text" name="nazev" required="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="castka" class="col-sm-4 col-form-label">Pořizovací cena</label>
                        <div class="col-sm-8">
                            <input class="form-control" type="number" min="0" name="castka" required="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="datum" class="col-sm-4 col-form-label">Datum zařazení</label>
                        <div class="col-sm-8">
                            <input class="form-control" type="date" name="datum" required="">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="datumvyrazeni" class="col-sm-4 col-form-label">Datum vyřazení</label>
                        <div class="col-sm-8">
                            <input class="form-control" type="date" name="datumvyrazeni">
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="dan" class="col-sm-4 col-form-label">Daň</label>
                        <div class="col-sm-8">
                            <select name="dan" class="form-control" required="">
                                <option value="">--Vybrat--</option>
                                <option value="Ano">Ano</option>
                                <option value="Ne">Ne</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="odpis" class="col-sm-4 col-form-label">Odpisová skupina</label>
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
                        <label for="zpusob" class="col-sm-4 col-form-label">Způsob odpisu</label>
                        <div class="col-sm-8">
                            <select name="zpusob" class="form-control" required="">
                                <option value="">--Vybrat--</option>
                                <option value="Rovnoměrný">Rovnoměrný</option>
                                <option value="Zrychlený">Zrychlený</option>
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