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
            <form action="pridat_pohledavka_nebo_dluh.php" method="post" style="display:inline-block;">
                <button type="submit" class="btn btn-success">Přidat</button>
            </form>
            <table id="table2" class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th onclick="sort(0, 'table2')">Název</th>
                        <th onclick="sort(1, 'table2')">Číslo dokladu</th>
                        <th onclick="sort(2, 'table2')">Firma</th>
                        <th onclick="sort(3, 'table2')">Datum vystavení</th>
                        <th onclick="sort(4, 'table2')">Pohledávka/dluh</th>
                        <th onclick="sort(5, 'table2')">Hodnota</th>
                        <th onclick="sort(6, 'table2')">Daňová položka</th>
                        <th>Popis</th>
                        <th>Úpravy</th>
                    </tr>
                </thead>
                <tbody>
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
                            <td><?php echo number_format((float) $row['hodnotap'], 2, ".", ",") ?></td>
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
                </tbody>
            </table>
        </div>
    </body>
</html>