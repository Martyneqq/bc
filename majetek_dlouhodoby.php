<?php
session_start();

include 'functions.php';
include 'databaseConnection.php';
$userData = check($connect);
?>
<!DOCTYPE html>
<html> 
    <head>
        <?php
        include 'inc/head.php';
        ?>
    </head>
    <header>
        <?php
        include 'inc/header.php';
        ?>
    </header>
    <body>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <div style="margin: 1%">
            <h3>Dlouhodobý majetek</h3>
            <form action="pridat_dlouhodoby_majetek.php" method="post" style="display:inline-block;">
                <button type="submit" class="btn btn-success">Přidat</button>
            </form>
            <table id="table3" class="table table-striped table-hover table-sortable">
                <thead>
                    <tr>
                        <th onclick="sort('table3', 0)">Číslo položky</th>
                        <th onclick="sort('table3', 1)">Název</th>
                        <th onclick="sort('table3', 2)">Počáteční hodnota</th>
                        <th onclick="sort('table3', 3)">Datum zařazení</th>
                        <th onclick="sort('table3', 4)">Datum vyřazení</th>
                        <th onclick="sort('table3', 5)">Odpisová skupina</th>
                        <th onclick="sort('table3', 6)">Způsob odpisu</th>
                        <th>Popis</th>
                        <th>Úpravy</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $userID = $userData['id'];

                    $select = $connect->prepare("SELECT * FROM assets WHERE userID = ?");
                    $select->bind_param('i', $userID);
                    $select->execute();
                    $result = $select->get_result();

                    while ($row = mysqli_fetch_array($result)) {
                        ?>
                        <tr class="info-row" data-id='<?php echo $row['id'] ?>' data-name="<?php echo $row['nazev'] ?>" data-value="<?php echo $row['castka'] ?>">
                            <?php
                            $timeToDepreciate = [3, 5, 10, 20, 30, 50];
                            $numberOfGroup = $row['odpis'];
                            $originDate = $row['datum'];
                            $yearsToAdd = $timeToDepreciate[$numberOfGroup - 1];
                            $newDate = date('Y-m-d', strtotime($originDate . ' +' . $yearsToAdd . ' years'));
                            ?>
                            <td><?php echo secure($row['doklad']); ?></td>
                            <td><?php echo secure($row['nazev']); ?></td>
                            <td><?php echo number_format((float) $row["castka"], 2, ".", ","); ?></td>
                            <td><?php echo $originDate; ?></td>
                            <td><?php echo $newDate; ?></td>
                            <td><?php echo $numberOfGroup; ?></td>
                            <td><?php echo $row['zpusob']; ?></td>
                            <td><?php echo secure($row['popis']); ?></td>
                            <td>
                                <form action="edit3.php" method="post" style="display:inline-block;">
                                    <input type="hidden" name="ide3" value="<?php echo $row['id']; ?>">
                                    <input class="btn btn-primary btn-sm" type="submit" name="update4" value="Upravit">
                                </form>
                                <form action="" method="post" style="display:inline-block;">
                                    <input type="hidden" name="vyradit" value="<?php echo $row['id']; ?>">
                                    <input class="btn btn-secondary btn-sm" type="submit" name="" value="Vyřadit">
                                </form>
                                <form action="delete3.php" method="post" style="display:inline-block;" onsubmit="return confirm('Smazat položku <?php echo $row['nazev']; ?>?')">
                                    <input type="hidden" name="idd3" value="<?php echo $row['id']; ?>">
                                    <input class="btn btn-danger btn-sm" type="submit" name="delete3" value="Smazat"> <!-- nelze smazat, pokud už proběhly odpisy? -->
                                </form>
                            </td>
                        </tr>
                        <?php
                    }
                    ?> 
                </tbody>
            </table>
        </div>
        <div class="modal fade" id="infoModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"></h4>
                        <button type="button" class="close" data-bs-dismiss="modal">&times;</button>
                    </div>
                    <div class = "modal-body">
                        <!--script goes here-->
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-bs-dismiss="modal">Zavřít</button>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>