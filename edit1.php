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
    $select = $connect->prepare("SELECT * FROM assets WHERE idf = ?");
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
                    Upravit položku "<?php echo $row['nazevf'] ?>"
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
                                <td><input class="form-control" type="text" name="nazevf" required="" value="<?php echo $row['nazevf']; ?>"></td>
                                <td><input class="form-control" type="date" name="datumf" required="" value="<?php echo $row['datumf']; ?>"></td>
                                <td>
                                    <select name="prijemvydajf" class="form-control" required="">
                                        <option value="">--Vybrat--</option>
                                        <option value="Příjem" <?php echo ($row['prijemvydajf'] == 'Příjem') ? "selected" : ""; ?>>Příjem</option>
                                        <option value="Výdaj" <?php echo ($row['prijemvydajf'] == 'Výdaj') ? "selected" : ""; ?>>Výdaj</option>
                                    </select>
                                    <!-- <td><input class="form-control" type="text" name="prijemvydaj[]" required=""></td> -->
                                </td>
                                <td><input class="form-control" type="text" name="castkaf" required="" value="<?php echo $row['castkaf']; ?>"></td>
                                <td>
                                    <select name="danf" class="form-control" required="">
                                        <option value="">--Vybrat--</option>
                                        <option value="Ano" <?php echo ($row['danf'] == 'Ano') ? "selected" : ""; ?>>Ano</option>
                                        <option value="Ne" <?php echo ($row['danf'] == 'Ne') ? "selected" : ""; ?>>Ne</option>
                                    </select>
                                    <!-- <td><input class="form-control" type="text" name="dan[]" required=""></td> -->
                                </td>
                                <td><input class="form-control" type="text" name="dokladf" required="" value="<?php echo $row['dokladf']; ?>"></td>
                                <td>
                                    <select name="uhradaf" class="form-control" required="">
                                        <option value="">--Vybrat--</option>
                                        <option value="Z účtu" <?php echo ($row['uhradaf'] == 'Z účtu') ? "selected" : ""; ?>>Z účtu</option>
                                        <option value="Hotovost" <?php echo ($row['uhradaf'] == 'Hotovost') ? "selected" : ""; ?>>Hotovost</option>
                                    </select>
                                    <!-- <td><input class="form-control" type="text" name="uhrada[]" required=""></td> -->
                                </td>
                                <td><input class="form-control" type="text" name="popisf" value="<?php echo $row['popisf']; ?>"></td>
                            </tr>
                        </table>

                        <center>
                            <input type="hidden" name="idf" value="<?php echo $ide ?>">
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
    $idf = $_POST['idf'];
    $userID = $userData['id']; //$_SESSION['userid']
    $nazevf = $_POST['nazevf'];
    $datumf = $_POST['datumf'];
    $prijemvydajf = $_POST['prijemvydajf'];
    $castkaf = $_POST['castkaf'];
    $danf = $_POST['danf'];
    $dokladf = $_POST['dokladf'];
    $uhradaf = $_POST['uhradaf'];
    $odpisf = 0;
    $zpusobf = 0;
    $popisf = $_POST['popisf'];

    //$edit = "UPDATE `assets` SET idf=$idf,`userID`='$userID', `nazevf`='$nazevf',`datumf`='$datumf',`prijemvydajf`='$prijemvydajf',`castkaf`='$castkaf',`danf`='$danf',`dokladf`='$dokladf',`uhradaf`='$uhradaf',`odpisf`='$odpisf',`zpusobf`='$zpusobf',`popisf`='$popisf' WHERE idf=$idf";
    //echo $edit;
    //exit();

    /*
      $save = "UPDATE `assets` SET idf=?, `userID`=?, `nazevf`=?,`datumf`=?,`prijemvydajf`=?,`castkaf`=?,`danf`=?,`dokladf`=?,`uhradaf`=?,`odpisf`=?,`zpusobf`=?,`popisf`=? WHERE idf=?";
      $query = mysqli_prepare($connect, $save);
      $bind = mysqli_stmt_bind_param($query, "iisssdsssiis", $idf, $userID, $nazevf, $datumf, $prijemvydajf, $castkaf, $danf, $dokladf, $uhradaf, $odpisf, $zpusobf, $popisf);
     */

    $select = $connect->prepare("UPDATE `assets` SET `userID`=?, `nazevf`=?,`datumf`=?,`prijemvydajf`=?,`castkaf`=?,`danf`=?,`dokladf`=?,`uhradaf`=?,`odpisf`=?,`zpusobf`=?,`popisf`=? WHERE idf=?");
    $select->bind_param('isssdsssiisi', $userID, $nazevf, $datumf, $prijemvydajf, $castkaf, $danf, $dokladf, $uhradaf, $odpisf, $zpusobf, $popisf, $idf);
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