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
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.6/dist/umd/popper.min.js" integrity="sha384-wHAiFfRlMFy6i5SRaxvfOCifBUQy1xHdJ/yoi7FRNXMRBu5WHdZYu1hA6ZOblgut" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.2.1/dist/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
        <div style="margin: 1%">
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
                            <td><?php echo secure($row['cislopolozky']); ?></td>
                            <td><?php echo secure($row['nazev']); ?></td>
                            <td><?php echo number_format((float) $row["castka"], 2, ".", ",") ?></td>
                            <td><?php echo $row['datum']; ?></td>
                            <td><?php echo $row['datumvyrazeni']; ?></td>
                            <td><?php echo $row['odpis']; ?></td>
                            <td><?php echo $row['zpusob']; ?></td>
                            <td><?php echo secure($row['popis']); ?></td>
                            <td>
                                <form action="edit3.php" method="post" style="display:inline-block;">
                                    <input type="hidden" name="ide3" value="<?php echo $row['id']; ?>">
                                    <input class="btn btn-primary btn-sm" type="submit" name="update4" value="Upravit">
                                </form>
                                <form action="delete3.php" method="post" style="display:inline-block;">
                                    <input type="hidden" name="idd3" value="<?php echo $row['id']; ?>">
                                    <input class="btn btn-danger btn-sm" type="submit" name="delete3" value="Smazat">
                                </form>
                            </td>
                        </tr>
                        <?php
                        ?>
                    <div class="modal fade" id="infoModal" role="dialog">
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
                    <?php
                }
                ?> 
                </tbody>
            </table>
        </div>
    </body>
</html>