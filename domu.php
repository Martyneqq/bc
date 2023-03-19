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
            <h1>Vítejte <?php echo secure($userData['username']) ?? null; ?></h1>
            <?php
            $userID = $userData['id'];
            if (!$connect) {
                die("Connection failed: " . mysqli_connect_error());
            }
            connectToExpensesTable($connect, $userData['id']);
            $rok = date('Y');

            $resultIncome = mysqli_query($connect, "SELECT sum(castkaf) FROM assets WHERE userID = '$userID' AND prijemvydajf = 'Příjem' AND year(datumf)='$rok'")
                    or die(mysqli_error());
            $resultExpense = mysqli_query($connect, "SELECT sum(castkaf) FROM assets WHERE userID = '$userID' AND prijemvydajf = 'Výdaj' AND year(datumf)='$rok'")
                    or die(mysqli_error());
            $carryIncome = '0';
            $carryExpense = '0';
            while ($rows = mysqli_fetch_array($resultIncome)) {
                ?>
                <p>Celkové příjmy tento rok: <?php echo number_format($rows['sum(castkaf)']); ?> Kč</p>
                <?php
                $carryIncome .= $rows['sum(castkaf)'];
            }
            while ($rows = mysqli_fetch_array($resultExpense)) {
                ?>
                <p>Celkové výdaje tento rok: <?php echo number_format($rows['sum(castkaf)']); ?> Kč</p>
                <?php
                $carryExpense .= $rows['sum(castkaf)'];
            }
            $profit = $carryIncome - $carryExpense;
            ?>
            <p>Celkový profit tento rok: <?php echo number_format($profit); ?> Kč</p>
            <?php
            ?>
        </div>
    </body>
</html>