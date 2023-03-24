<?php

include 'databaseConnection.php';

if (isset($_POST['id_info'])) {
    $infoID = $_POST['id_info'];
    $select = $connect->prepare("SELECT * FROM assets WHERE id = ?");
    $select->bind_param('i', $infoID);
    $select->execute();
    $result = $select->get_result();
    $sum = 0;

    $output = '<table class="table table-striped table-hover" id="depreciationTable">';
    while ($line = mysqli_fetch_array($result)) {
        $output .= '<tr>
                        <th>Pořadí</th>
                        <th>Částka</th>
                        <th>Zbývající hodnota</th>
                        <th>Datum odpisu</th>
                    </tr>';
        $thisYear = date('Y');
        $i = 1; // row numberting
        $j = $line["odpis"]; // array numbering
        $initialPrice = $line["castka"];
        $timeToDepreciate = [3, 5, 10, 20, 30, 50];
        $ttd = $timeToDepreciate[$j - 1];
        $diff = abs(date("Y", strtotime($line["datum"])) - $thisYear);
        //echo "ttd: " . $ttd;
        while ($i <= $diff && $i <= $ttd) {
            $output .= '<tr><td>' . $i . '</td><td>'; // 1st output
            if ($line["zpusob"] == "Rovnoměrný") {
                $firstYearCoeficient = [20.0, 11.0, 5.5, 2.15, 1.4, 1.02];
                $consequentYearsCoeficient = [40.0, 22.25, 10.5, 5.15, 3.4, 2.02];
                $straightLineValue1 = ($initialPrice * $firstYearCoeficient[$j - 1] / 100);
                $straightLineValue2 = ($initialPrice * $consequentYearsCoeficient[$j - 1]) / 100;
                $remainingValue = $initialPrice - $straightLineValue1;
                if ($i == 1) {
                    $output .= number_format((float) $straightLineValue1, 2, ".", ","); // 2nd output
                    $output .= '<td>' . number_format((float) $remainingValue, 2, ".", ",") . '</td>';
                    $sum += $straightLineValue1;
                } elseif ($i > 1) {
                    for ($k = 1; $k < $i; $k++) {
                        $remainingValue -= $straightLineValue2;
                    }
                    $output .= number_format((float) $straightLineValue2, 2, ".", ","); // 2nd output
                    $output .= '<td>' . number_format((float) $remainingValue, 2, ".", ",") . '</td>';
                    $sum += $straightLineValue2;
                }
            } else if ($line["zpusob"] == "Zrychlený") {
                $firstYearCoeficient = [3, 5, 10, 20, 30, 50];
                $consequentYearsCoeficient = [4, 6, 11, 21, 31, 51];
                $acceleratedValue1 = $initialPrice / $firstYearCoeficient[$j - 1];
                $remainingValue = $initialPrice - $acceleratedValue1;

                if ($i == 1) {
                    $output .= number_format((float) $acceleratedValue1, 2, ".", ","); // 2nd output
                    $output .= '<td>' . number_format((float) $remainingValue, 2, ".", ",") . '</td>';
                    $sum += $acceleratedValue1;
                } else if ($i > 1) {
                    for ($k = 1; $k < $i; $k++) {
                        $acceleratedValue2 = (2 * ($remainingValue)) / ($consequentYearsCoeficient[$j - 1] - $k);
                        $remainingValue -= $acceleratedValue2;
                    }
                    $output .= number_format((float) $acceleratedValue2, 2, ".", ","); // 2nd output
                    $output .= '<td>' . number_format((float) $remainingValue, 2, ".", ",") . '</td>';
                    $sum += $acceleratedValue2;
                }
            }
            $output .= '</td><td>' . date("Y-m-d", strtotime($line["datum"] . "+ {$i} years")) . '</td></tr>'; // 3rd output
            $i++;
            if ($i == $ttd) {
                $output .= '<a style="color:red"">Vyřazeno ' . $withdrawal = date("Y-m-d", strtotime($line["datum"] . "+ {$i} years")) . '<a>';
                $select = $connect->prepare("UPDATE `assets` SET `datumvyrazeni`=? WHERE id=?");
                $select->bind_param('si', $withdrawal, $infoID);
                $select->execute();
                if ($select === false) {
                    echo 'Error';
                }
            }
        }
    }
    //$output .= '<input type='hidden' name='modal-sum" value=" . echo $sum . '>';
    $output .= "</table>";
    echo $output;
}