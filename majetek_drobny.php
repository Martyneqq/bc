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
            <form action="pridat_dlouhodoby_majetek.php" method="post" style="display:inline-block;">
                <button type="submit" class="btn btn-success">Přidat</button>
            </form>
            <table id="table2" class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th onclick="sort('table2', 0)">Název</th>
                        <th onclick="sort('table2', 1)">Částka</th>
                        <th onclick="sort('table2', 2)">Datum pořízení</th>
                        <th>Popis</th>
                        <th>Úpravy</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $userID = $userData['id'];
                    $popis = 'Drobný majetek';
                    $select = $connect->prepare("SELECT * FROM incomeexpense WHERE userID = ? AND popis=?");
                    $select->bind_param('is', $userID, $popis);
                    $select->execute();
                    $result = $select->get_result();
                    while ($row = mysqli_fetch_array($result)) {
                        ?>
                        <tr>
                            <td><?php echo secure($row['nazev']); ?></td>
                            <td><?php echo secure($row['castka']); ?></td>
                            <td><?php echo secure($row['datum']); ?></td>
                            <td><?php echo secure($row['popis']); ?></td>
                            <td>
                                <form action="edit1.php" method="post" style="display:inline-block;">
                                    <input type="hidden" name="ide" value="<?php echo $row['id']; ?>">
                                    <input class="btn btn-primary btn-sm" type="submit" name="update0" value="Upravit">
                                </form>
                                <form action="delete1.php" method="post" style="display:inline-block;">
                                    <input type="hidden" name="idd" value="<?php echo $row['id']; ?>">
                                    <input class="btn btn-danger btn-sm" type="submit" name="delete0" value="Smazat">
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