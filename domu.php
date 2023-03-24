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
        <div style="margin: 2%">
            <?php
            echo '<select name="dateSelect" class="form-select form-select-lg mb-3" required="">';
            $year = date('Y');
            foreach (range($year, $earliest_year) as $x) {
                echo '<option value="' . $x . '"' . ($x === $year ? ' selected="selected"' : '') . '>' . $x . '</option>';
            }
            echo '</select>';
            //if (isset($_POST['dataSelect'])) { // TODO
            $userID = $userData['id'];
            if (!$connect) {
                die("Connection failed: " . mysqli_connect_error());
            }
            connectToExpensesTable($connect, $userData['id']);

            $resultIncome = mysqli_query($connect, "SELECT sum(inc.castka+ast.castka) AS totalIncome FROM incomeexpense inc, assets ast WHERE inc.userID = '$userID' AND ast.userID = '$userID' AND inc.prijemvydaj = 'Příjem' AND year(inc.datum)='$year' AND year(ast.datum)='$year'")
                    or die(mysqli_error());
            $resultExpense = mysqli_query($connect, "SELECT sum(inc.castka+ast.castka) AS totalExpense FROM incomeexpense inc, assets ast WHERE inc.userID = '$userID' AND ast.userID = '$userID' AND inc.prijemvydaj = 'Výdaj' AND year(inc.datum)='$year' AND year(ast.datum)='$year'")
                    or die(mysqli_error());
            $carryIncome = '0';
            $carryExpense = '0';
            while ($rows = mysqli_fetch_array($resultIncome)) {
                ?>
                <p>Celkové příjmy za rok <?= $year ?>: <?php echo number_format((float) $rows['totalIncome'], 2, ".", ","); ?> Kč</p>
                <?php
                $carryIncome .= $rows['totalIncome'];
            }
            while ($rows = mysqli_fetch_array($resultExpense)) {
                ?>
                <p>Celkové výdaje za rok <?= $year ?>: <?php echo number_format((float) $rows['totalExpense'], 2, ".", ","); ?> Kč</p>
                <?php
                $carryExpense .= $rows['totalExpense'];
            }
            $profit = $carryIncome - $carryExpense;
            $maxValue = max($carryIncome, $carryExpense);
            ?>
            <p>Celkový profit v roce <?= $year ?>: <?php echo number_format((float) $profit, 2, ".", ","); ?> Kč</p>
            <?php
            // TODO
            echo "<script> function(); </script>";
            //}
            ?>
        </div>
        <canvas id="myChart" style="width:100%;max-width:700px"></canvas>
        <script>
            var chartData = {
                type: 'bar',
                series: [
                    {values: [35, 43, 70, 65]},
                    {values: [25, 57, 49, 60]}
                ]
            };
            zingchart.render({
                id: 'myChart',
                data: chartData,
                height: 400,
                width: 600
            });
        </script>
    </body>
</html>