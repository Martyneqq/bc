<?php
ob_start();
session_start();
include 'functions.php';
include 'databaseConnection.php';
$userData = check($connect);

//echo $idf;
if (isset($_POST['update4'])) {
    $ide = $_POST['ide3'];
    $select = $connect->prepare("SELECT * FROM assets WHERE id = ?");
    $select->bind_param('i', $ide);
    $select->execute();
    $result = $select->get_result();
    $row = mysqli_fetch_array($result);
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
            <div class="container">
                <form class="default-form" id="default-form" method="post" action="">
                    <h3>Upravit položku "<?php echo $row['nazev'] ?>"</h3>
                    <div class="default-field">
                        <table class="table" id="default-table">
                            <div class="form-group row">
                                <label for="cislopolozky" class="col-sm-4 col-form-label">Číslo položky</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="text" name="doklad" required="" value="<?php echo $row['doklad']; ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="nazev" class="col-sm-4 col-form-label">Název</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="text" name="nazev" required="" value="<?php echo $row['nazev']; ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="castka" class="col-sm-4 col-form-label">Pořizovací cena</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="number" min="0" name="castka" required="" value="<?php echo $row['castka']; ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="datum" class="col-sm-4 col-form-label">Datum zařazení</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="date" name="datum" required="" value="<?php echo $row['datum']; ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="dan" class="col-sm-4 col-form-label">Daň</label>
                                <div class="col-sm-8">
                                    <select name="dan" class="form-control" required="">
                                        <option value="">--Vybrat--</option>
                                        <option value="Ano" <?php echo ($row['dan'] == 'Ano') ? "selected" : ""; ?>>Ano</option>
                                        <option value="Ne" <?php echo ($row['dan'] == 'Ne') ? "selected" : ""; ?>>Ne</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="odpis" class="col-sm-4 col-form-label">Odpisová skupina</label>
                                <div class="col-sm-8">
                                    <select name="odpis" class="form-control" required="">
                                        <option value="">--Vybrat--</option>
                                        <?php
                                        for ($i = 1; $i <= 6; $i++) {
                                            echo '<option value="' . $i . '"';
                                            echo ($row['odpis'] == $i) ? "selected" : "";
                                            echo '>' . $i . '</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="zpusob" class="col-sm-4 col-form-label">Způsob odpisu</label>
                                <div class="col-sm-8">
                                    <select name="zpusob" class="form-control" required="">
                                        <option value="">--Vybrat--</option>
                                        <option value="Rovnoměrný" <?php echo ($row['zpusob'] == 'Rovnoměrný') ? "selected" : ""; ?>>Rovnoměrný</option>
                                        <option value="Zrychlený" <?php echo ($row['zpusob'] == 'Zrychlený') ? "selected" : ""; ?>>Zrychlený</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="popis" class="col-sm-4 col-form-label">Popis</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="text" name="popis" value="<?php echo $row['popis']; ?>">
                                </div>
                            </div>
                        </table>
                    </div>
                    <div class="text-center">
                        <input type="hidden" name="id" value="<?php echo $ide ?>">
                        <button type="submit" name="update5" class="btn btn-success">Uložit</button>
                        <a href="majetek_dlouhodoby.php" class="btn btn-danger">Zrušit</a>
                    </div>
                </form>
            </div>

            <!-- Add the Bootstrap JS file -->
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
        </body>
        <script>
            window.onload = function () {
                var castka = document.querySelector('input[name="castka"]');
                var odpis = document.querySelector('select[name="odpis"]');
                var zpusob = document.querySelector('select[name="zpusob"]');

                function disableFields() {
                    castka.disabled = true;
                    odpis.disabled = true;
                    zpusob.disabled = true;
                }
                disableFields();
                document.getElementById("default-form").addEventListener("submit", function () {
                    // Re-enable the fields before submitting the form in order to save the disabled data to the database as well
                    castka.disabled = false;
                    odpis.disabled = false;
                    zpusob.disabled = false;
                });
            };
        </script>
    </html>
    <?php
} elseif (isset($_POST['update5'])) {
    $id = $_POST['id'];
    $userID = $userData['id']; //$_SESSION['userid']
    $doklad = $_POST['doklad'];
    $nazev = $_POST['nazev'];
    $castka = $_POST['castka'];
    $datumZarazeni = $_POST['datum'];
    $dan = $_POST['dan'];
    $prijemvydaj = "Výdaj";
    $odpis = $_POST['odpis'];
    $zpusob = $_POST['zpusob'];
    $popis = $_POST['popis'];

    $select = $connect->prepare("UPDATE `assets` SET `userID`=?, `doklad` = ?, `nazev`=?,`castka`=?,`datum`=?,`dan`=?,`odpis`=?,`zpusob`=?,`popis`=? WHERE id=?");
    $select->bind_param('issississi', $userID, $doklad, $nazev, $castka, $datumZarazeni, $dan, $odpis, $zpusob, $popis, $id);
    $select->execute();

    if ($select === false) {
        echo 'Error';
    } else {
        header("Location: majetek_dlouhodoby.php");
    }
} else {
    header("Location: majetek_dlouhodoby.php");
    die();
}
ob_end_flush();
?>