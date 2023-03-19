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
                                <th>Datum začátku používání</th>
                                <th>Počáteční cena</th>
                                <th>Odpisová skupina</th>
                                <th>Způsob odpisu</th>
                                <th>Popis</th>
                            </tr>
                            <tr>
                                <td><input class="form-control" type="text" name="nazevf" required="" value="<?php echo $row['nazevf']; ?>"></td>
                                <td><input class="form-control" type="date" name="datumf" required="" value="<?php echo $row['datumf']; ?>"></td>
                                <td><input class="form-control" type="text" name="castkaf" required="" value="<?php echo $row['castkaf']; ?>"></td>
                                <td>
                                    <select name="odpisf" class="form-control" required="">
                                        <option value="">--Vybrat--</option>
                                        <?php
                                        for($i=1; $i<=6; $i++){
                                            echo '<option value="'.$i.'"';
                                            echo ($row['odpisf'] == $i) ? "selected" : ""; 
                                            echo '>'.$i.'</option>';
                                        }
                                        ?>
                                    </select>
                                    <!-- <td><input class="form-control" type="text" name="prijemvydaj[]" required=""></td> -->
                                </td>
                                <td>
                                    <select name="zpusobf" class="form-control" required="">
                                        <option value="">--Vybrat--</option>
                                        <option value="Rovnoměrný" <?php echo ($row['zpusobf'] == 'Rovnoměrný') ? "selected" : ""; ?>>Rovnoměrný</option>
                                        <option value="Zrychlený" <?php echo ($row['zpusobf'] == 'Zrychlený') ? "selected" : ""; ?>>Zrychlený</option>
                                    </select>
                                </td>
                                <td><input class="form-control" type="text" name="popisf" value="<?php echo $row['popisf']; ?>"></td>
                            </tr>
                        </table>

                        <center>
                            <input type="hidden" name="idf" value="<?php echo $ide ?>">
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
    $idf = $_POST['idf'];
    $userID = $userData['id']; //$_SESSION['userid']
    $nazevf = $_POST['nazevf'];
    $datumf = $_POST['datumf'];
    $castkaf = $_POST['castkaf'];
    $odpisf = $_POST['odpisf'];
    $zpusobf = $_POST['zpusobf'];
    $popisf = $_POST['popisf'];

    $select = $connect->prepare("UPDATE `assets` SET `userID`=?, `nazevf`=?,`datumf`=?,`castkaf`=?,`odpisf`=?,`zpusobf`=?,`popisf`=? WHERE idf=?");
    $select->bind_param('issiissi', $userID, $nazevf, $datumf, $castkaf, $odpisf, $zpusobf, $popisf, $idf);
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