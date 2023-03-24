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
            <table id="table4" class="table table-striped table-hover table-sortable">
                <thead>
                    <tr>
                <form method="post">
                    <th><input class="form-control" type="date" name="start" required="" value='<?php echo date('Y-m-d'); ?>'></th>
                    <th><input class="form-control" type="date" name="end" required="" value='<?php echo date('Y-m-d'); ?>'></th>
                    <th><input type="submit" class='btn btn-success' name="submit" value="Potvrdit" /></th>
                </form>
                <?php
                for ($i = 0; $i < 1; $i++) { // if an extention of columns is needed
                    echo "<th></th>";
                }
                ?>
                </tr>

                <tr>
                    <th onclick="sort(0, 'table4')">Datum</th>
                    <th onclick="sort(1, 'table4')">Název</th>
                    <th onclick="sort(2, 'table4')">Doklad</th>
                    <th onclick="sort(3, 'table4')">Příjmy</th>
                    <th onclick="sort(4, 'table4')">Výdaje</th>
                </tr>
                </thead>
                <tbody>
                    <?php
                    $userID = $userData['id'];

                    if (isset($_POST['submit'])) {
                        $start = date('Y-m-d', strtotime($_POST['start']));
                        $end = date('Y-m-d', strtotime($_POST['end']));
                        $result = mysqli_query($connect, "SELECT datum, nazev, doklad, prijemvydaj, castka FROM incomeexpense UNION SELECT datum, nazev, NULL AS doklad, prijemvydaj, castka FROM assets WHERE userID = '$userID' AND userID = '$userID' AND datum >= '$start' AND datum <= '$end' AND datum >= '$start' AND datum <= '$end'");

                        while ($row = mysqli_fetch_array($result)) {
                            ?>
                            <tr class="info-row1" data-id='<?php echo $row['id'] ?>' data-name="<?php echo $row['nazev'] ?>">
                                <td><?= $row['datum']; ?></td>
                                <td><?= secure($row['nazev']); ?></td>
                                <td><?= secure($row['doklad']); ?></td>
                                <td style="color: <?= getColor($row['prijemvydaj']) ?>;"><?php
                                    if ($row['prijemvydaj'] == 'Příjem') {
                                        echo number_format((float) $row['castka'], 2, ".", ",");
                                    }
                                    ?></td>
                                <td style="color: <?= getColor($row['prijemvydaj']) ?>;"><?php
                                    if ($row['prijemvydaj'] == 'Výdaj') {
                                        echo number_format((float) $row['castka'], 2, ".", ",");
                                    }
                                    ?></td>
                            </tr>
                            <?php
                        }
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </body>
</html>