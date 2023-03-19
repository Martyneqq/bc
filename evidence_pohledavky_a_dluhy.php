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
                    <th>Firma</th>
                    <th>Datum vystavení</th>
                    <th>Pohledávka/dluh</th>
                    <th>Hodnota</th>
                    <th>Daňová položka</th>
                    <th>Popis</th>
                    <th>Úpravy</th>
                </tr>
                <?php
                $userID = $userData['id'];
                $select = $connect->prepare("SELECT * FROM demanddebt WHERE userID = ?");
                $select->bind_param('s', $userID);
                $select->execute();
                $result = $select->get_result();
                while ($row = mysqli_fetch_array($result)) {
                    ?>
                    <tr>
                        <td><?php echo secure($row['nazevp']); ?></td>
                        <td><?php echo secure($row['cislodokladp']); ?></td>
                        <td><?php echo secure($row['firmap']); ?></td>
                        <td><?php echo $row['datump']; ?></td>
                        <td><?php echo secure($row['pohledavkadluhp']); ?></td>
                        <td><?php echo number_format($row['hodnotap']); ?></td>
                        <td><?php echo $row['danp']; ?></td>
                        <td><?php echo secure($row['popisp']); ?></td>
                        <td>
                            <form action="edit2.php" method="post" style="display:inline-block;">
                                <input type="hidden" name="ide2" value="<?php echo $row['idp']; ?>">
                                <input class="btn btn-primary" type="submit" name="update2" value="Upravit">
                            </form>
                            <form action="delete2.php" method="post" style="display:inline-block;">
                                <input type="hidden" name="idd2" value="<?php echo $row['idp']; ?>">
                                <input class="btn btn-danger" type="submit" name="delete2" value="Smazat">
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