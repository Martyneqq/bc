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
        <div style="margin: 1%">
            <h3>Deník</h3>
            <table id="table4" class="table table-striped table-hover table-sortable">
                <thead>
                    <tr>
                        <?php
                        if (isset($_POST['start'])) {
                            $start = date('Y-m-d', strtotime($_POST['start']));
                            $end = date('Y-m-d', strtotime($_POST['end']));
                        } else {
                            $start = date('Y-m-d');
                            $end = date('Y-m-d');
                        }
                        ?>
                <form method="post">
                    <th><input class="form-control" type="date" name="start" required="" value='<?php echo $start; ?>'></th>
                    <th><input class="form-control" type="date" name="end" required="" value='<?php echo $end; ?>'></th>
                    <th><input type="submit" class='btn btn-success' name="submit" value="Potvrdit" /></th>
                </form>
                <?php
                for ($i = 0; $i < 4; $i++) { // if an extention of columns is needed
                    echo "<th></th>";
                }
                ?>
                </tr>

                <tr>
                    <th onclick="sort('table4', 0)">Datum</th>
                    <th onclick="sort('table4', 1)">Název</th>
                    <th onclick="sort('table4', 2)">Doklad</th>
                    <th onclick="sort('table4', 3)" colspan="2">Příjmy</th>
                    <th onclick="sort('table4', 4)" colspan="2">Výdaje</th>
                </tr>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>Daňové</td>
                    <td>Nedaňové</td>
                    <td>Daňové</td>
                    <td>Nedaňové</td>
                </tr>
                </thead>
                <tbody>
                    <?php
                    $userID = $userData['id'];

                    if (isset($_POST['submit'])) {

                        $result = mysqli_query($connect, "SELECT datum, nazev, doklad, prijemvydaj, castka, dan FROM incomeexpense WHERE userID = '$userID' AND datum >= '$start' AND datum <= '$end' ORDER BY datum");

                        $sumA = 0.0;
                        $sumB = 0.0;
                        $sumC = 0.0;
                        $sumD = 0.0;

                        while ($row = mysqli_fetch_array($result)) {
                            ?>
                            <tr class="info-row1" data-id='<?php echo $row['id'] ?>' data-name="<?php echo $row['nazev'] ?>">
                                <td><?= $row['datum']; ?></td>
                                <td><?= secure($row['nazev']); ?></td>
                                <td><?= secure($row['doklad']); ?></td>
                                <td style="color: <?= getColor($row['prijemvydaj']) ?>;"><?php
                                    if ($row['prijemvydaj'] == 'Příjem') {
                                        if ($row['dan'] == 'Ano') {
                                            echo number_format((float) $row['castka'], 2, ".", ",");
                                            $sumA += $row['castka'];
                                        }
                                    }
                                    ?></td>
                                <td style="color: <?= getColor($row['prijemvydaj']) ?>;"><?php
                                    if ($row['prijemvydaj'] == 'Příjem') {
                                        if ($row['dan'] == 'Ne') {
                                            echo number_format((float) $row['castka'], 2, ".", ",");
                                            $sumB += $row['castka'];
                                        }
                                    }
                                    ?></td>
                                <td style="color: <?= getColor($row['prijemvydaj']) ?>;"><?php
                                    if ($row['prijemvydaj'] == 'Výdaj') {
                                        if ($row['dan'] == 'Ano') {
                                            echo number_format((float) $row['castka'], 2, ".", ",");
                                            $sumC += $row['castka'];
                                        }
                                    }
                                    ?></td>
                                <td style="color: <?= getColor($row['prijemvydaj']) ?>;"><?php
                                    if ($row['prijemvydaj'] == 'Výdaj') {
                                        if ($row['dan'] == 'Ne') {
                                            echo number_format((float) $row['castka'], 2, ".", ",");
                                            $sumD += $row['castka'];
                                        }
                                    }
                                    ?></td>
                            </tr>

                            <?php
                        }
                        ?>
                        <tr>
                            <td>Celkem</td>
                            <td></td>
                            <td></td>
                            <td><?= number_format((float) $sumA, 2, ".", ","); ?></td>
                            <td><?= number_format((float) $sumB, 2, ".", ","); ?></td>
                            <td><?= number_format((float) $sumC, 2, ".", ","); ?></td>
                            <td><?= number_format((float) $sumD, 2, ".", ","); ?></td>
                        </tr>
                        <tr>
                            <td>Daňový základ</td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td><?= number_format((float) $sumA - $sumC, 2, ".", ","); ?></td>
                            <td></td>
                        </tr>
                        <?php
                    }
                    ?>

                </tbody>
            </table>
        </div>
    </body>
</html>