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
    /* $ide = $_POST['ide'];
      $select = "SELECT * FROM assets WHERE idf=$ide LIMIT 1";
      $result = mysqli_query($connect, $select);
      $row = mysqli_fetch_assoc($result); */

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
            <div style="margin: 10px">

                <form class="default-form" id ="default-form" method="post" action="">
                    <hr>
                    Upravit položku "<?php echo $row['nazev'] ?>"
                    <hr>
                    <div class="default-field">
                        <table id="default-table">
                            <tr>
                                <th>Číslo položky</th>
                                <th>Název</th>
                                <th>Počáteční cena</th>
                                <th>Datum zařazení</th>
                                <th>Datum vyřazení</th>
                                <th>Odpisová skupina</th>
                                <th>Způsob odpisu</th>
                                <th>Popis</th>
                            </tr>
                            <tr>
                                <td><input class="form-control" type="text" name="cislopolozky" required="" value="<?php echo $row['cislopolozky']; ?>"></td>
                                <td><input class="form-control" type="text" name="nazev" required="" value="<?php echo $row['nazev']; ?>"></td>
                                <td><input class="form-control" type="text" name="castka" required="" value="<?php echo $row['castka']; ?>"></td>
                                <td><input class="form-control" type="date" name="datum" required="" value="<?php echo $row['datum']; ?>"></td>
                                <td><input class="form-control" type="date" name="datumvyrazeni" value="<?php echo $row['datumvyrazeni']; ?>"></td>
                                <td>
                                    <select name="odpis" class="form-control" required="">
                                        <option value="">--Vybrat--</option>
                                        <?php
                                        for($i=1; $i<=6; $i++){
                                            echo '<option value="'.$i.'"';
                                            echo ($row['odpis'] == $i) ? "selected" : ""; 
                                            echo '>'.$i.'</option>';
                                        }
                                        ?>
                                    </select>
                                    <!-- <td><input class="form-control" type="text" name="prijemvydaj[]" required=""></td> -->
                                </td>
                                <td>
                                    <select name="zpusob" class="form-control" required="">
                                        <option value="">--Vybrat--</option>
                                        <option value="Rovnoměrný" <?php echo ($row['zpusob'] == 'Rovnoměrný') ? "selected" : ""; ?>>Rovnoměrný</option>
                                        <option value="Zrychlený" <?php echo ($row['zpusob'] == 'Zrychlený') ? "selected" : ""; ?>>Zrychlený</option>
                                    </select>
                                </td>
                                <td><input class="form-control" type="text" name="popis" value="<?php echo $row['popis']; ?>"></td>
                            </tr>
                        </table>

                        <center>
                            <input type="hidden" name="id" value="<?php echo $ide ?>">
                            <button type="submit" name="update5" class="btn btn-success">Uložit</button>
                            <a href="majetek_dlouhodoby.php" class="btn btn-danger">Zrušit</a>
                        </center>
                    </div>
                </form>
            </div>
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