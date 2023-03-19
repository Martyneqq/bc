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

            <table class="table table-striped">
                <tr>
                <form method="post">
                    <th><input class="form-control" type="date" name="start" required="" value='<?php echo date('Y-m-d'); ?>'></th>
                    <th><input class="form-control" type="date" name="end" required="" value='<?php echo date('Y-m-d'); ?>'></th>
                    <th><input type="submit" class='btn btn-success' name="submit" value="Potvrdit" /></th>
                </form>
                <?php
                for ($i = 0; $i < 3; $i++) {
                    echo "<th></th>";
                }
                ?>
                </tr>
                <tr>
                    <th>Datum</th>
                    <th>Doklad</th>
                    <th>Název</th>
                    <th>Příjmy</th>
                    <th>Výdaje</th>
                </tr>
                <?php
                $userID = $userData['id'];
                
                if (isset($_POST['submit'])) {
                    $start = date('Y-m-d', strtotime($_POST['start']));
                    $end = date('Y-m-d', strtotime($_POST['end']));
                    $select = $connect->prepare("SELECT * FROM assets WHERE userID = ? AND datumf >= '$start' AND datumf <= '$end'");
                    $select->bind_param('s', $userID);
                    $select->execute();
                    $result = $select->get_result();

                    while ($row = mysqli_fetch_array($result)) {
                        ?>
                        <tr>
                            <td><?php echo $row['datumf']; ?></td>
                            <td><?php echo secure($row['dokladf']); ?></td>
                            <td><?php echo secure($row['nazevf']); ?></td>
                            <td><?php echo number_format($row['castkaf']); ?></td>
                            <td></td>
                        </tr>
                        <?php
                    }
                }
                ?>

            </table>
        </div>
    </body>
</html>