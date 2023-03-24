<?php
ob_start();
session_start();
include 'inc/head.php';
include 'functions.php';
include 'databaseConnection.php';
$userData = check($connect);
include 'inc/header.php';

if (isset($_POST['update2'])) {
    $ide2 = $_POST['ide2'];
    $select = $connect->prepare("SELECT * FROM demanddebt WHERE idp = ?");
    $select->bind_param('i', $ide2);
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
                    Upravit položku "<?php echo $row['nazevp'] ?>"
                    <hr>
                    <div class="default-field">
                        <table id="default-table">
                            <tr>
                                <th>Název</th>
                                <th>Číslo dokladu</th>
                                <th>Firma</th>
                                <th>Datum vystavení</th>
                                <th>Pohledávka/dluh</th>
                                <th>Hodnota</th>
                                <th>Daňová položka</th>
                                <th>Popis</th>
                            </tr>
                            <tr>
                                <td><input class="form-control" type="text" name="nazevp" required="" value="<?php echo $row['nazevp']; ?>"></td>
                                <td><input class="form-control" type="text" name="cislodokladp" required="" value="<?php echo $row['cislodokladp']; ?>"></td>
                                <td><input class="form-control" type="text" name="firmap" required="" value="<?php echo $row['firmap']; ?>"></td>
                                <td><input class="form-control" type="date" name="datump" required="" value="<?php echo $row['datump']; ?>"></td>
                                <td>
                                    <select name="pohledavkadluhp" class="form-control" required="">
                                        <option value="">--Vybrat--</option>
                                        <option value="Pohledávka" <?php echo ($row['pohledavkadluhp'] == 'Pohledávka') ? "selected" : ""; ?>>Pohledávka</option>
                                        <option value="Dluh" <?php echo ($row['pohledavkadluhp'] == 'Dluh') ? "selected" : ""; ?>>Dluh</option>
                                    </select>
                                    <!-- <td><input class="form-control" type="text" name="dan[]" required=""></td> -->
                                </td>
                                <td><input class="form-control" type="text" name="hodnotap" required="" value="<?php echo $row['hodnotap']; ?>"></td>
                                <td>
                                    <select name="danp" class="form-control" required="">
                                        <option value="">--Vybrat--</option>
                                        <option value="Ano" <?php echo ($row['danp'] == 'Ano') ? "selected" : ""; ?>>Ano</option>
                                        <option value="Ne" <?php echo ($row['danp'] == 'Ne') ? "selected" : ""; ?>>Ne</option>
                                    </select>
                                    <!-- <td><input class="form-control" type="text" name="uhrada[]" required=""></td> -->
                                </td>

                                <td><input class="form-control" type="text" name="popisp" value="<?php echo $row['popisp']; ?>"></td>
                            </tr>
                        </table>
                        <center>
                            <input type="hidden" name="idp" value="<?php echo $ide2 ?>">
                            <button type="submit" name="update3" class="btn btn-success">Uložit</button>
                            <a href="evidence_pohledavky_a_dluhy.php" class="btn btn-danger">Zrušit</a>
                        </center>
                    </div>
                </form>
            </div>
        </body>
    </html>
    <?php
} elseif (isset($_POST['update3'])) {
    $idp = $_POST['idp'];
    $userID = $userData['id']; //$_SESSION['userid']
    $nazevp = $_POST['nazevp'];
    $cislodokladp = $_POST['cislodokladp'];
    $firmap = $_POST['firmap'];
    $datump = $_POST['datump'];
    $pohledavkadluhp = $_POST['pohledavkadluhp'];
    $hodnotap = $_POST['hodnotap'];
    $danp = $_POST['danp'];
    $popisp = $_POST['popisp'];

    //UPDATE `demanddebt` SET `userID`='[value-2]',`nazevp`='[value-3]',`cislodokladp`='[value-4]',`firmap`='[value-5]',`datump`='[value-6]',`pohledavkadluhp`='[value-7]',`hodnotap`='[value-8]',`danp`='[value-9]',`popisp`='[value-10]' WHERE idp=?
    $select = $connect->prepare("UPDATE `demanddebt` SET `userID`=?,`nazevp`=?,`cislodokladp`=?,`firmap`=?,`datump`=?,`pohledavkadluhp`=?,`hodnotap`=?,`danp`=?,`popisp`=? WHERE idp=?");
    $select->bind_param('isssssissi', $userID, $nazevp, $cislodokladp, $firmap, $datump, $pohledavkadluhp, $hodnotap, $danp, $popisp, $idp);
    $select->execute();

    //$query = mysqli_prepare($connect, $edit);
    //$bind = mysqli_stmt_bind_param($query, "sisdsss", $nazevf, $userID, $datumf, $castkaf, $dokladf, $uhradaf, $popisf);
    if ($select === false) {
        echo 'Error';
    } else {
        header("Location: evidence_pohledavky_a_dluhy.php");
    }
} else {
    header("Location: evidence_pohledavky_a_dluhy.php");
    die();
}
ob_end_flush();
?>