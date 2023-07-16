<?php
ob_start();
session_start();
include 'inc/head.php';
include 'functions.php';
include 'databaseConnection.php';
$userData = check($connect);
include 'inc/header.php';

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

        </head>
        <header>

        </header>
        <body>
            <div class="container">
                <form class="default-form" id="default-form" method="post" action="">
                    <h2>Upravit položku "<?php echo $row['nazev'] ?>"</h2>
                    <div class="default-field">
                        <table class="table" id="default-table">
                            <div class="form-group row">
                                <label for="cislopolozky" class="col-sm-4 col-form-label">Číslo položky</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="text" name="cislopolozky" required="" value="<?php echo $row['cislopolozky']; ?>">
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
                                <label for="datumvyrazeni" class="col-sm-4 col-form-label">Daň</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="date" name="datumvyrazeni" value="<?php echo $row['datumvyrazeni']; ?>">
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
    </html>
    <?php
} elseif (isset($_POST['update5'])) {
    $id = $_POST['id'];
    $userID = $userData['id']; //$_SESSION['userid']
    $cisloPolozky = $_POST['cislopolozky'];
    $nazev = $_POST['nazev'];
    $castka = $_POST['castka'];
    $datumZarazeni = $_POST['datum'];
    $datumVyrazeni = $_POST['datumvyrazeni'];
    $prijemvydaj = "Výdaj";
    $odpis = $_POST['odpis'];
    $zpusob = $_POST['zpusob'];
    $popis = $_POST['popis'];

    $select = $connect->prepare("UPDATE `assets` SET `userID`=?, `cislopolozky` = ?, `nazev`=?,`castka`=?,`datum`=?,`datumvyrazeni`=?,`odpis`=?,`zpusob`=?,`popis`=? WHERE id=?");
    $select->bind_param('issississi', $userID, $cisloPolozky, $nazev, $castka, $datumZarazeni, $datumVyrazeni, $odpis, $zpusob, $popis, $id);
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