<?php
session_start();
include 'functions.php';
include 'databaseConnection.php';
$userData = check($connect);
?>
<!DOCTYPE html>
<html>
    <head><?php
        include 'inc/head.php';
        ?>
        <style>
            .graph-container {
                width: 100%;
                min-height: 200px;
                margin: 0 auto;
            }
        </style>
    </head>
    <header>
        <?php
        include 'inc/header.php';
        ?>
    </header>
    <body>
        <div style="margin: 2%">
            <h1>Vítejte <?php echo $userData['username'] ?? ''; ?></h1>
            <?php
            $year = isset($_POST['dateSelect']) ? $_POST['dateSelect'] : date('Y');
            ?>
            <form method="post">
                <div class="row">
                    <div class="col-sm-2">
                        <select name="dateSelect" class="form-control form-select-lg mb-3" required="">
                            <?php
                            foreach (range(date('Y'), 1900) as $x) {
                                echo '<option value="' . $x . '"' . ($x == $year ? ' selected="selected"' : '') . '>' . $x . '</option>';
                            }
                            ?>
                        </select>
                    </div>
                    <div class="col-sm-2">
                        <input class="btn btn-primary" value="Potvrdit" type="submit">
                    </div>
                </div>
            </form>
            <?php
            $userID = $userData['id'];
            if (!$connect) {
                die("Connection failed: " . mysqli_connect_error());
            }

            $resultIncome = mysqli_query($connect, "SELECT sum(inc.castka) AS totalIncome FROM incomeexpense inc WHERE inc.userID = '$userID' AND inc.prijemvydaj = 'Příjem' AND year(inc.datum)='$year'")
                    or die(mysqli_error());
            $resultExpense = mysqli_query($connect, "SELECT sum(inc.castka) AS totalExpense FROM incomeexpense inc WHERE inc.userID = '$userID' AND inc.prijemvydaj = 'Výdaj' AND year(inc.datum)='$year'")
                    or die(mysqli_error());
            $carryIncome = 0.0;
            $carryExpense = 0.0;
            while ($rows = mysqli_fetch_array($resultIncome)) {
                ?>
                <p>Celkové příjmy za rok <?= $year ?>: <?php echo number_format((float) $rows['totalIncome'], 2, ".", ","); ?> Kč</p>
                <?php
                $carryIncome += $rows['totalIncome'];
            }
            while ($rows = mysqli_fetch_array($resultExpense)) {
                ?>
                <p>Celkové výdaje za rok <?= $year ?>: <?php echo number_format((float) $rows['totalExpense'], 2, ".", ","); ?> Kč</p>
                <?php
                $carryExpense += $rows['totalExpense'];
            }
            $profit = $carryIncome - $carryExpense;
            $maxValue = max($carryIncome, $carryExpense);
            ?>
            <p>Celkový profit v roce <?= $year ?>: <?php echo number_format((float) $profit, 2, ".", ","); ?> Kč</p>
        </div>
        <?php
        $uDi = $userData['id'];
        $select = $connect->prepare("SELECT sum(case when prijemvydaj = 'Výdaj' then castka else 0 end) as y_vydaj,
        sum(case when prijemvydaj = 'Příjem' then castka else 0 end) as y_prijeti,
        year(datum) as x
        FROM incomeexpense
        WHERE userID = ?
        GROUP BY year(datum)
        ORDER BY x");
        $select->bind_param('i', $uDi);
        $select->execute();
        $result = $select->get_result();
        $yearArray = mysqli_fetch_all($result, MYSQLI_ASSOC);

        $arrIncome = array();
        $arrExpense = array();
        foreach ($yearArray as $key => $val) {
            array_push($arrIncome, $val['x']);
            array_push($arrExpense, $val['y_vydaj']);
        }

        $data = array(
            array(
                'x' => $arrIncome,
                'y' => array_column($yearArray, 'y_prijeti'),
                'name' => 'Příjmy',
                'type' => 'bar'
            ),
            array(
                'x' => $arrIncome,
                'y' => $arrExpense,
                'name' => 'Výdaje',
                'type' => 'bar'
            )
        );

        $layout = array(
            'barmode' => 'group',
            'xaxis' => array(
                'ticktext' => $arrIncome,
                'tickvals' => $arrIncome
            ),
            'responsive' => true
        );

        $json_data = json_encode($data);
        $json_layout = json_encode($layout);
        ?>

        <div class="graph-container" id="myDiv"></div>
        <!-- Load plotly.js into the DOM -->
        <script src='https://cdn.plot.ly/plotly-2.20.0.min.js'></script>
        <script>
            function createPlot() {
                var data = <?php echo $json_data; ?>;
                var layout = <?php echo $json_layout; ?>;
                Plotly.newPlot('myDiv', data, layout, {responsive: true});
            }

            createPlot();

            window.addEventListener('resize', createPlot);
        </script>
    </body>
</html>