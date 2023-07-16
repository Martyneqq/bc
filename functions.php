<?php

function secure($value) {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function connectToExpensesTable($connect, $userData) {
    if (isset($_POST['ulozit'])) {
        $userID = $userData['id']; //$_SESSION['userid']
        $nazev = $_POST['nazev'];
        $doklad = $_POST['doklad'];
        $datum = $_POST['datum'];
        $prijemvydaj = $_POST['prijemvydaj'];
        $castka = $_POST['castka'];
        $dan = $_POST['dan'];
        $uhrada = $_POST['uhrada'];
        $popis = $_POST['popis'];

        $save = "INSERT INTO incomeexpense(nazev,doklad,datum,prijemvydaj,castka,dan,uhrada,popis, userID)VALUES(?,?,?,?,?,?,?,?,?)";
//$query = mysqli_query($connect, $save);
        $query = mysqli_prepare($connect, $save);
        $bind = mysqli_stmt_bind_param($query, "ssssisssi", $nazev, $doklad, $datum, $prijemvydaj, $castka, $dan, $uhrada, $popis, $userID);
        if (($execute = mysqli_stmt_execute($query)) != true) {
            echo "Error";
        }
        echo "Položka úspěšně přidána!";
    }
}

function connectToDemandDebtTable($connect, $userData) {
    if (isset($_POST['ulozit'])) {
        $userID = $userData['id']; //$_SESSION['userid']
        $nazevp = $_POST['nazevp'];
        $cislodokladp = $_POST['cislodokladp'];
        $firmap = $_POST['firmap'];
        $datump = $_POST['datump'];
        $pohledavkadluhp = $_POST['pohledavkadluhp'];
        $hodnotap = $_POST['hodnotap'];
        $danp = $_POST['danp'];
        $popisp = $_POST['popisp'];

        $save = "INSERT INTO demanddebt(nazevp,userID,cislodokladp,firmap,datump,pohledavkadluhp,hodnotap,danp,popisp)VALUES(?,?,?,?,?,?,?,?,?)";
        $query = mysqli_prepare($connect, $save);
//$query = mysqli_query($connect, $save);
        $bind = mysqli_stmt_bind_param($query, "sissssdss", $nazevp, $userID, $cislodokladp, $firmap, $datump, $pohledavkadluhp, $hodnotap, $danp, $popisp);
        if (($execute = mysqli_stmt_execute($query)) != true) {
            echo "Error";
        }
        echo "Položka úspěšně přidána!";
    }
}

function connectToAssetsTable($connect, $userData) {
    if (isset($_POST['ulozit'])) {
        $userID = $userData['id']; //$_SESSION['userid']
        $nazev = $_POST['nazev'];
        $castka = $_POST['castka'];
        $datum = $_POST['datum'];
        $prijemvydaj = "Výdaj";
        $popis = "Drobný majetek";

        if ($castka < 80000) {
            // Save data to "minorassets" table
            $sql = "INSERT INTO incomeexpense (userID, nazev, castka, datum, prijemvydaj, popis) VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $connect->prepare($sql);
            $stmt->bind_param("isisss", $userID, $nazev, $castka, $datum, $prijemvydaj, $popis);
            $stmt->execute();
        } else {
            $cislopolozky = $_POST['cislopolozky'];
            $dan = $_POST['dan'];
            $odpis = $_POST['odpis'];
            $zpusob = $_POST['zpusob'];
            $datumvyrazeni= $_POST['datumvyrazeni'];

            $sql = "INSERT INTO assets (userID, cislopolozky, nazev, castka, datum, datumvyrazeni, prijemvydaj, dan, odpis, zpusob, popis) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $connect->prepare($sql);
            $stmt->bind_param("ississssiss", $userID, $cislopolozky, $nazev, $castka, $datum, $datumvyrazeni, $prijemvydaj, $dan, $odpis, $zpusob, $popis);
            $stmt->execute();
        }
        echo "Položka úspěšně přidána!";
    }
}

function check($connect) {
    if (isset($_SESSION['id'])) {
        $id = $_SESSION['id'];
        $save = "SELECT * FROM users WHERE id = '$id'";

        $result = mysqli_query($connect, $save);
        if ($result && mysqli_num_rows($result) > 0) {
            $userData = mysqli_fetch_assoc($result);
            return $userData;
        }
    }
    header("Location: login.php");
    die();
}

function getColor($prijemvydajf) {
    if ($prijemvydajf == 'Příjem') {
        return '#32cd32';
    } else if ($prijemvydajf == 'Výdaj') {
        return '#FF0000';
    }
}

function kontrola($connect, $assetID) { // zapisování odpisů, které ještě nejsou v databázi, ale měly by být ke konci loňského roku už zapsané v databázi
    $select = $connect->prepare("SELECT * FROM assets WHERE id=?");
    $select->bind_param('i', $assetID);
    $select->execute();
    $result = $select->get_result();
    if (mysqli_num_rows($result) < 1) { // asset neexistuje
        return;
    }
    $line = mysqli_fetch_array($result); // while neni potřeba
    $odpisyDb = zjistiOdpisy($connect, $assetID);
    // teď simulace
    $thisYear = date('Y');
    $i = 1; // row numberting
    $j = $line["odpis"]; // array numbering
    $initialPrice = $line["castka"];
    $timeToDepreciate = [3, 5, 10, 20, 30, 50];
    $ttd = $timeToDepreciate[$j - 1];
    $rokPorizeni = date("Y", strtotime($line["datum"]));
    $diff = abs($rokPorizeni - $thisYear);
    $posledniRokSimulace = $thisYear - 1;
    $iRok = $rokPorizeni;
    $prijemvydaj = 'Výdaj';
    //$pocet = 0;
    while ($i <= $diff || $i <= $ttd) {
        $sum = 0;
        if ($line["zpusob"] == "Rovnoměrný") {
            $firstYearCoeficient = [20.0, 11.0, 5.5, 2.15, 1.4, 1.02];
            $consequentYearsCoeficient = [40.0, 22.25, 10.5, 5.15, 3.4, 2.02];
            $straightLineValue1 = ($initialPrice * $firstYearCoeficient[$j - 1]) / 100;
            $straightLineValue2 = ($initialPrice * $consequentYearsCoeficient[$j - 1]) / 100;
            $remainingValue = $initialPrice - $straightLineValue1;
            if ($i == 1) {
                if ($iRok <= $posledniRokSimulace && !isset($odpisyDb[$iRok])) {
                    ulozOdpis($connect, $assetID, "$iRok-12-31", $straightLineValue1, $line["nazev"], $line['dan'], $prijemvydaj);
                    //$pocet++;
                }
                //$output .= number_format((float) $straightLineValue1, 2, ".", ","); // 2nd output
                //$output .= '<td>' . number_format((float) $remainingValue, 2, ".", ",") . '</td>';
                $sum += $straightLineValue1;
            } elseif ($i > 1) {
                for ($k = 1; $k < $i; $k++) {
                    $remainingValue -= $straightLineValue2;

                    if ($iRok <= $posledniRokSimulace && !isset($odpisyDb[$iRok])) {
                        ulozOdpis($connect, $assetID, "$iRok-12-31", $straightLineValue2, $line["nazev"], $line['dan'], $prijemvydaj);
                        //$pocet++;
                    }
                }
                //$output .= number_format((float) $straightLineValue2, 2, ".", ","); // 2nd output
                //$output .= '<td>' . number_format((float) $remainingValue, 2, ".", ",") . '</td>';
                $sum += $straightLineValue2;
            }
        } else if ($line["zpusob"] == "Zrychlený") {
            $firstYearCoeficient = [3, 5, 10, 20, 30, 50];
            $consequentYearsCoeficient = [4, 6, 11, 21, 31, 51];
            $acceleratedValue1 = $initialPrice / $firstYearCoeficient[$j - 1];
            $remainingValue = $initialPrice - $acceleratedValue1;

            if ($i == 1) {
                if ($iRok <= $posledniRokSimulace && !isset($odpisyDb[$iRok])) {
                    ulozOdpis($connect, $assetID, "$iRok-12-31", $acceleratedValue1, $line["nazev"], $line['dan'], $prijemvydaj);
                    //$pocet++;
                }
                //$output .= number_format((float) $acceleratedValue1, 2, ".", ","); // 2nd output
                //$output .= '<td>' . number_format((float) $remainingValue, 2, ".", ",") . '</td>';
                $sum += $acceleratedValue1;
            } else if ($i > 1) {
                for ($k = 1; $k < $i; $k++) {
                    $acceleratedValue2 = (2 * ($remainingValue)) / ($consequentYearsCoeficient[$j - 1] - $k);
                    $remainingValue -= $acceleratedValue2;

                    if ($iRok <= $posledniRokSimulace && !isset($odpisyDb[$iRok])) {
                        ulozOdpis($connect, $assetID, "$iRok-12-31", $acceleratedValue2, $line["nazev"], $line['dan'], $prijemvydaj);
                        //$pocet++;
                    }
                }
                //$output .= number_format((float) $acceleratedValue2, 2, ".", ","); // 2nd output
                //$output .= '<td>' . number_format((float) $remainingValue, 2, ".", ",") . '</td>';
                $sum += $acceleratedValue2;
            }
        }
        //$output .= '</td><td>' . $withdrawal = date("Y-m-d", strtotime($line["datum"] . "+ {$i} years")) . '</td></tr>'; // 3rd output
        //$opravky += $sum;

        /* if ($i == $ttd) {
          $output .= '<a style="color:red"">Vyřazeno ' . $withdrawal . '<a><br><a style="color:red"">Oprávky: ' . $opravky . '<a>';

          $select = $connect->prepare("UPDATE `assets` SET `datumvyrazeni`=? WHERE id=?");
          $select->bind_param('si', $withdrawal, $infoID);
          $select->execute();
          if ($select === false) {
          echo 'Error';
          }
          } */
        $i++;
        $iRok++;
    }
    //return $pocet;
}

function zjistiOdpisy($connect, $assetID) { // vrací pole $odpisy_db[$rok]=$odepsana_castka;
    $select = $connect->prepare("SELECT castka, datum FROM incomeexpense WHERE assetID=? AND popis='Odpis' ORDER BY datum");
    $select->bind_param('i', $assetID);
    $select->execute();
    $result = $select->get_result();

    $odpisy_db = array();
    while ($line = mysqli_fetch_array($result)) {
        $date = date("Y", strtotime($line["datum"]));
        $odpisy_db[$date] = $line['castka'];
    }

    return $odpisy_db;
}

function ulozOdpis($connect, $assetID, $datum, $castka, $nazev, $dan, $prijemvydaj) {
    $userID = $_SESSION['id'];
    $select = $connect->prepare("INSERT INTO incomeexpense (userID, nazev, datum, castka, popis, dan, prijemvydaj, assetID) values(?, ?, ?, ?, 'Odpis', ?, ?, ?)");
    $select->bind_param('ississi', $userID, $nazev, $datum, $castka, $dan, $prijemvydaj, $assetID);
    $select->execute();
    // kontrola zápisu
}
