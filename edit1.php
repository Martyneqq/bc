<?php
ob_start();
session_start();
include 'functions.php';
include 'databaseConnection.php';
$userData = check($connect);

//echo $idf;
if (isset($_POST['update0'])) {
    /* $ide = $_POST['ide'];
      $select = "SELECT * FROM assets WHERE idf=$ide LIMIT 1";
      $result = mysqli_query($connect, $select);
      $row = mysqli_fetch_assoc($result); */

    $ide = $_POST['ide'];
    $select = $connect->prepare("SELECT * FROM incomeexpense WHERE id = ?");
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
                                <label for="doklad" class="col-sm-4 col-form-label">Číslo dokladu</label>
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
                                <label for="datum" class="col-sm-4 col-form-label">Datum uhrazení</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="date" name="datum" required="" value="<?php echo $row['datum']; ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="prijemvydaj" class="col-sm-4 col-form-label">Příjem nebo výdaj</label>
                                <div class="col-sm-8">
                                    <select name="prijemvydaj" class="form-control" required="">
                                        <option value="">--Vybrat--</option>
                                        <option value="Příjem" <?php echo ($row['prijemvydaj'] == 'Příjem') ? "selected" : ""; ?>>Příjem</option>
                                        <option value="Výdaj" <?php echo ($row['prijemvydaj'] == 'Výdaj') ? "selected" : ""; ?>>Výdaj</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="castka" class="col-sm-4 col-form-label">Částka</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="number" min="0" name="castka" required="" value="<?php echo $row['castka']; ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="dan" class="col-sm-4 col-form-label">Daňová položka</label>
                                <div class="col-sm-8">
                                    <select name="dan" class="form-control" required="">
                                        <option value="">--Vybrat--</option>
                                        <option value="Ano" <?php echo ($row['dan'] == 'Ano') ? "selected" : ""; ?>>Ano</option>
                                        <option value="Ne" <?php echo ($row['dan'] == 'Ne') ? "selected" : ""; ?>>Ne</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="uhrada" class="col-sm-4 col-form-label">Druh úhrady</label>
                                <div class="col-sm-8">
                                    <select name="uhrada" class="form-control" required="">
                                        <option value="">--Vybrat--</option>
                                        <option value="Z účtu" <?php echo ($row['uhrada'] == 'Z účtu') ? "selected" : ""; ?>>Z účtu</option>
                                        <option value="Hotovost" <?php echo ($row['uhrada'] == 'Hotovost') ? "selected" : ""; ?>>Hotovost</option>
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
                        <button type="submit" name="update1" class="btn btn-success">Uložit</button>
                        <a href="evidence_prijmy_a_vydaje.php" class="btn btn-danger">Zrušit</a>
                    </div>
                </form>
            </div>

            <!-- Add the Bootstrap JS file -->
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>

            <script>
                window.onload = function () {
                    var dan = document.querySelector('select[name="dan"]');
                    //var doklad = document.querySelector('input[name="doklad"]');
                    var uhrada = document.querySelector('select[name="uhrada"]');
                    var popis = document.querySelector('input[name="popis"]');
                    var errorMessage = document.querySelector('#error-message');

                    function disableFields() {
                        dan.disabled = true;
                        //doklad.disabled = true;
                        uhrada.disabled = true;
                        errorMessage.style.display = 'block';
                    }

                    function enableFields() {
                        dan.disabled = false;
                        //doklad.disabled = false;
                        uhrada.disabled = false;
                        errorMessage.style.display = 'none';
                    }

                    function checkPopisValue() {
                        if (popis.value === "Drobný majetek") {
                            disableFields();
                        } else {
                            enableFields();
                        }
                    }

                    checkPopisValue();
                    var initialPopisValue = popis.value;
                    popis.addEventListener('input', function () {
                        if (popis.value !== initialPopisValue) {
                            initialPopisValue = popis.value;
                            checkPopisValue();
                        }
                    });
                };
            </script>
        </body>
    </html>
    <?php
} elseif (isset($_POST['update1'])) {
    $id = $_POST['id'];
    $userID = $userData['id']; //$_SESSION['userid']
    $nazev = $_POST['nazev'];
    $datum = $_POST['datum'];
    $prijemvydaj = $_POST['prijemvydaj'];
    $castka = $_POST['castka'];
    $dan = $_POST['dan'];
    $doklad = $_POST['doklad'];
    $uhrada = $_POST['uhrada'];
    $popis = $_POST['popis'];

    if ($popis == "Drobný majetek") {
        // Save only nazev, castka, datum, and popis
        $select = $connect->prepare("UPDATE `incomeexpense` SET `doklad`=?, `nazev`=?, `castka`=?, `datum`=?, `popis`=? WHERE id=?");
        $select->bind_param('sssssi', $doklad, $nazev, $castka, $datum, $popis, $id);

        $select->execute();

        if ($select === false) {
            echo 'Error';
        } else {
            header("Location: majetek_drobny.php");
        }
    } else {
        // Save all fields
        $select = $connect->prepare("UPDATE `incomeexpense` SET `userID`=?, `nazev`=?, `datum`=?, `prijemvydaj`=?, `castka`=?, `dan`=?, `doklad`=?, `uhrada`=?, `popis`=? WHERE id=?");
        $select->bind_param('isssissssi', $userID, $nazev, $datum, $prijemvydaj, $castka, $dan, $doklad, $uhrada, $popis, $id);

        $select->execute();

        if ($select === false) {
            echo 'Error';
        } else {
            header("Location: evidence_prijmy_a_vydaje.php");
        }
    }
}

ob_end_flush();
?>