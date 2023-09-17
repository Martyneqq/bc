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
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.2.1/dist/js/bootstrap.min.js" integrity="sha384-B0UglyR+jN6CkvvICOB2joaf5I4l3gm9GU6Hc1og6Ls7i6U/mkkaduKaBhlAXv9k" crossorigin="anonymous"></script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
    </head>
    <header>
        <?php
        include 'inc/header.php';
        ?>
    </header>
    <body>

        <div style="margin: 1%">
            <h3>Příjmy a výdaje</h3>
            <form action="pridat_prijem_nebo_vydaj.php" method="post" style="display:inline-block;">
                <button type="submit" class="btn btn-success">Přidat</button>
            </form>
            <table id="table1" class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th onclick="sort('table1', 0)">Název</th>
                        <th onclick="sort('table1', 1)">Číslo dokladu</th>
                        <th onclick="sort('table1', 2)">Datum uhrazení</th>
                        <th onclick="sort('table1', 3)">Příjem nebo výdaj</th>
                        <th onclick="sort('table1', 4)">Částka</th>
                        <th onclick="sort('table1', 5)">Daňová položka</th>
                        <th onclick="sort('table1', 6)">Způsob platby</th>
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
                    $select = $connect->prepare("SELECT id, userID, nazev, doklad, datum, prijemvydaj, castka, dan, uhrada, popis FROM incomeexpense WHERE userID=?");
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

                            <?php
                            if (!(isset($row['popis']) && $row['popis'] == "Odpis")) {
                                ?>
                                <td>
                                    <form action="edit1.php" method="post" style="display:inline-block;">
                                        <input type="hidden" name="ide" value="<?php echo $row['id']; ?>">
                                        <input class="btn btn-primary btn-sm" type="submit" name="update0" value="Upravit">
                                    </form>
                                    <!--  <button class="btn btn-danger btn-sm delete-button"  data-toggle="modal" data-target="#deleteModal">Smazat</button>-->
                                    <form action="delete1.php" method="post" style="display:inline-block;" onsubmit="return confirm('Smazat položku <?php echo $row['nazev']; ?>?')">
                                        <input type="hidden" name="idd" value="<?php echo $row['id']; ?>">
                                        <input class="btn btn-danger btn-sm" data-id="<?php echo $row['id']; ?>" data-name="<?php echo $row['nazev'] ?>" type="submit" name="delete" data-dismiss="modal" value="Smazat">
                                    </form>
                                </td>
                            <?php } else { ?>
                                <td></td>
                            <?php } ?>

                        </tr>
                        <!-- <div class="modal fade" id="deleteModal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p>Opravdu chcete položku smazat?</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" id="close-modal" data-dismiss="modal">Ne</button> -->

                        <!-- </div>
                   </div>
               </div>
           </div>-->
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>

    </body>
</html>