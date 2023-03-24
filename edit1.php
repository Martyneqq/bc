<?php
ob_start();
session_start();
include 'inc/head.php';
include 'functions.php';
include 'databaseConnection.php';
$userData = check($connect);
include 'inc/header.php';

//echo $idf;
if (isset($_POST['update0'])) {
    /*$ide = $_POST['ide'];
    $select = "SELECT * FROM assets WHERE idf=$ide LIMIT 1";
    $result = mysqli_query($connect, $select);
    $row = mysqli_fetch_assoc($result);*/

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

        </head>
        <header>
            
        </header>
        <body>
            <div style="margin: 10px">

                <form class="default-form" id ="default-form" method="post" action="">
                    <hr>
                    Upravit položku "<?php echo $row['nazev'] ?>"
                    <hr>
                    <div class="default-field">
                        <table id="default-table">
                            <tr>
                                <th>Název</th>
                                <th>Datum uhrazení</th>
                                <th>Příjem nebo výdaj</th>
                                <th>Částka</th>
                                <th>Daňová položka</th>
                                <th>Číslo dokladu</th>
                                <th>Druh úhrady</th>
                                <th>Popis</th>
                                <th></th>
                            </tr>
                            <tr>
                                <td><input class="form-control" type="text" name="nazev" required="" value="<?php echo $row['nazev']; ?>"></td>
                                <td><input class="form-control" type="date" name="datum" required="" value="<?php echo $row['datum']; ?>"></td>
                                <td>
                                    <select name="prijemvydaj" class="form-control" required="">
                                        <option value="">--Vybrat--</option>
                                        <option value="Příjem" <?php echo ($row['prijemvydaj'] == 'Příjem') ? "selected" : ""; ?>>Příjem</option>
                                        <option value="Výdaj" <?php echo ($row['prijemvydaj'] == 'Výdaj') ? "selected" : ""; ?>>Výdaj</option>
                                    </select>
                                    <!-- <td><input class="form-control" type="text" name="prijemvydaj[]" required=""></td> -->
                                </td>
                                <td><input class="form-control" type="text" name="castka" required="" value="<?php echo $row['castka']; ?>"></td>
                                <td>
                                    <select name="dan" class="form-control" required="">
                                        <option value="">--Vybrat--</option>
                                        <option value="Ano" <?php echo ($row['dan'] == 'Ano') ? "selected" : ""; ?>>Ano</option>
                                        <option value="Ne" <?php echo ($row['dan'] == 'Ne') ? "selected" : ""; ?>>Ne</option>
                                    </select>
                                    <!-- <td><input class="form-control" type="text" name="dan[]" required=""></td> -->
                                </td>
                                <td><input class="form-control" type="text" name="doklad" required="" value="<?php echo $row['doklad']; ?>"></td>
                                <td>
                                    <select name="uhrada" class="form-control" required="">
                                        <option value="">--Vybrat--</option>
                                        <option value="Z účtu" <?php echo ($row['uhrada'] == 'Z účtu') ? "selected" : ""; ?>>Z účtu</option>
                                        <option value="Hotovost" <?php echo ($row['uhrada'] == 'Hotovost') ? "selected" : ""; ?>>Hotovost</option>
                                    </select>
                                </td>
                                <td><input class="form-control" type="text" name="popis" value="<?php echo $row['popis']; ?>"></td>
                            </tr>
                        </table>

                        <center>
                            <input type="hidden" name="id" value="<?php echo $ide ?>">
                            <button type="submit" name="update1" class="btn btn-success">Uložit</button>
                            <a href="evidence_prijmy_a_vydaje.php" class="btn btn-danger">Zrušit</a>
                        </center>
                    </div>
                </form>
            </div>
        </body>
    </html>
    <?php
} elseif (isset($_POST['update1'])) {
    $idf = $_POST['id'];
    $userID = $userData['id']; //$_SESSION['userid']
    $nazev = $_POST['nazev'];
    $datum = $_POST['datum'];
    $prijemvydaj = $_POST['prijemvydaj'];
    $castka = $_POST['castka'];
    $dan = $_POST['dan'];
    $doklad = $_POST['doklad'];
    $uhrada = $_POST['uhrada'];
    $popis = $_POST['popis'];

    $select = $connect->prepare("UPDATE `incomeexpense` SET `userID`=?, `nazev`=?,`datum`=?,`prijemvydaj`=?,`castka`=?,`dan`=?,`doklad`=?,`uhrada`=?,`popis`=? WHERE id=?");
    $select->bind_param('isssissssi',$userID, $nazev, $datum, $prijemvydaj, $castka, $dan, $doklad, $uhrada, $popis, $id);
    $select->execute();

    //$query = mysqli_prepare($connect, $edit);
    //$bind = mysqli_stmt_bind_param($query, "sisdsss", $nazevf, $userID, $datumf, $castkaf, $dokladf, $uhradaf, $popisf);
    if ($select === false) {
        echo 'Error';
    } else {
        header("Location: evidence_prijmy_a_vydaje.php");
    }
} else {
    header("Location: evidence_prijmy_a_vydaje.php");
    die();
}
ob_end_flush();
?>