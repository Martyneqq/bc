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

    </head>
    <header>

    </header>
    <body>
        <div style="margin: 1%">
            <form action="pridat_prijem_nebo_vydaj.php" method="post" style="display:inline-block;">
                <button type="submit" class="btn btn-success">Přidat</button>
            </form>
            <table id="table1" class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th onclick="sort(0, 'table1')">Název</th>
                        <th onclick="sort(1, 'table1')">Číslo dokladu</th>
                        <th onclick="sort(2, 'table1')">Datum uhrazení</th>
                        <th onclick="sort(3, 'table1')">Příjem nebo výdaj</th>
                        <th onclick="sort(4, 'table1')">Částka</th>
                        <th onclick="sort(5, 'table1')">Daňová položka</th>
                        <th onclick="sort(6, 'table1')">Způsob platby</th>
                        <th>Popis</th>
                        <th>Úpravy</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $userID = $userData['id'];
                    /*
                      $save = "SELECT incomeexpense.*, assets.* FROM incomeexpense LEFT JOIN assets ON incomeexpense.userID = assets.userID WHERE incomeexpense.userID = assets.userID AND incomeexpense.userID = ?";
                      $result = mysqli_query($connect, $save);
                     */
                    $select = $connect->prepare("SELECT id, userID, nazev, doklad, datum, prijemvydaj, castka, dan, uhrada, popis FROM incomeexpense UNION SELECT id, userID, nazev, NULL AS doklad, datum, prijemvydaj, castka, NULL AS dan, NULL AS uhrada, popis FROM assets WHERE userID=?");
                    $select->bind_param('i', $userID);
                    $select->execute();
                    $result = $select->get_result();

                    while ($row = mysqli_fetch_array($result)) {
                        ?>
                        <tr>
                            <td><?php echo secure($row['nazev']); ?></td>
                            <td><?php echo secure($row['doklad']); ?></td>
                            <td><?php echo $row['datum']; ?></td>
                            <td><?php echo $row['prijemvydaj']; ?></td>
                            <td style="color: <?= getColor($row['prijemvydaj']) ?>;"><?= number_format((float) $row["castka"], 2, ".", ",") ?></td>
                            <td><?php echo $row['dan']; ?></td>
                            <td><?php echo secure($row['uhrada']); ?></td>
                            <td><?php echo secure($row['popis']); ?></td>

                            <?php if (is_null($row['dan']) && is_null($row['uhrada']) && is_null($row['doklad'])) { ?>
                                <td>
                                    <form action="edit3.php" method="post" style="display:inline-block;">
                                        <input type="hidden" name="ide3" value="<?php echo $row['id']; ?>">
                                        <input class="btn btn-primary" type="submit" name="update4" value="Upravit">
                                    </form>
                                    <form action="delete3.php" method="post" style="display:inline-block;">
                                        <input type="hidden" name="idd3" value="<?php echo $row['id']; ?>">
                                        <input class="btn btn-danger" type="submit" name="delete3" value="Smazat">
                                    </form>
                                </td><?php } else { ?>
                                <td>
                                    <form action="edit1.php" method="post" style="display:inline-block;">
                                        <input type="hidden" name="ide" value="<?php echo $row['id']; ?>">
                                        <input class="btn btn-primary" type="submit" name="update0" value="Upravit">
                                    </form>
                                    <form action="delete1.php" method="post" style="display:inline-block;">
                                        <input type="hidden" name="idd" value="<?php echo $row['id']; ?>">
                                        <input class="btn btn-danger" type="submit" name="delete" value="Smazat">
                                    </form>
                                </td>
                            <?php } ?>

                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </body>
</html>