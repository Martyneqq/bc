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
            <table class="table table-striped table-hover">
                <tr>
                    <th>Název</th>
                    <th>Číslo dokladu</th>
                    <th>Datum uhrazení</th>
                    <th>Příjem nebo výdaj</th>
                    <th>Částka</th>
                    <th>Daňová položka</th>
                    <th>Způsob platby</th>
                    <th>Popis</th>
                    <th>Úpravy</th>
                </tr>
                <?php
                $userID = $userData['id'];

                $select = $connect->prepare("SELECT * FROM assets WHERE userID = ?");
                $select->bind_param('s', $userID);
                $select->execute();
                $result = $select->get_result();

                while ($row = mysqli_fetch_array($result)) {
                    ?>
                    <tr>
                        <td><?php echo secure($row['nazevf']); ?></td>
                        <td><?php echo secure($row['dokladf']); ?></td>
                        <td><?php echo $row['datumf']; ?></td>
                        <td><?php echo $row['prijemvydajf']; ?></td>
                        <td style="color: <?= getColor($row['prijemvydajf']) ?>;"><?= number_format($row["castkaf"]) ?></td>
                        <td><?php echo $row['danf']; ?></td>
                        <td><?php echo secure($row['uhradaf']); ?></td>
                        <td><?php echo secure($row['popisf']); ?></td>
                        <td>
                            <form action="edit1.php" method="post" style="display:inline-block;">
                                <input type="hidden" name="ide" value="<?php echo $row['idf']; ?>">
                                <input class="btn btn-primary" type="submit" name="update0" value="Upravit">
                            </form>
                            <form action="delete1.php" method="post" style="display:inline-block;">
                                <input type="hidden" name="idd" value="<?php echo $row['idf']; ?>">
                                <input class="btn btn-danger" type="submit" name="delete" value="Smazat">
                            </form>
                        </td>
                    </tr>
                    <?php
                }
                ?>
            </table>
        </div>
    </body>
</html>