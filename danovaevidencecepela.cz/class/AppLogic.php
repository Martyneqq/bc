<?php
class AppLogic
{
    private $connect;

    public function __construct($connect)
    {
        $this->connect = $connect;
    }

    /*function check($connect)
    {
        if (isset($_SESSION['id'])) {
            $id = $_SESSION['id'];
            $save = $connect->prepare("SELECT * FROM users WHERE id = ?");
            $save->bind_param('i', $id);
            $save->execute();
            $result = $save->get_result();
            if ($result && mysqli_num_rows($result) > 0) {
                $userData = $result->fetch_assoc();
                $this->executeDepreciation($connect, $userData['id']); // works only after refresh
                return $userData;
            }
        }
        header("Location: login.php");
        die();
    }*/

    public function executeDepreciation($userID)
    {
        $select = $this->connect->prepare("SELECT * FROM assets WHERE userID=?");
        $select->bind_param('i', $userID);
        $select->execute();
        $result = $select->get_result();
        if (mysqli_num_rows($result) < 1) {
            return;
        }
        while ($line = $result->fetch_assoc()) {
            $j = $line["odpis"]; // array numbering
            $popis = $line['popis'];
            $initialPrice = $line["castka"];
            $uhrada = $line["uhrada"];
            $remainingValue = $initialPrice;

            $timeToDepreciate = [3, 5, 10, 20, 30, 50];
            $ttd = $timeToDepreciate[$j - 1];

            $thisYear = date('Y');
            $today = date('Y-m-d');
            $dateOfDepreciation = $line["datum"];
            $yearOfDepreciation = date("Y", strtotime($dateOfDepreciation));
            $diff = abs($yearOfDepreciation - $thisYear);

            $assetID = $line['id'];
            $dan = 'Ano';
            $lastYear = 0;
            $hiddenSlot = "Odpis";

            $val = 0;
            $sum = 0;

            for ($i = 1; $i <= $ttd && $i <= $diff; $i++) {
                if ($line["zpusob"] == "Rovnoměrný") {
                    $firstYearCoeficient = [20.0, 11.0, 5.5, 2.15, 1.4, 1.02];
                    $consequentYearsCoeficient = [40.0, 22.25, 10.5, 5.15, 3.4, 2.02];
                    if ($i == 1) {
                        $val = ($initialPrice * $firstYearCoeficient[$j - 1]) / 100;
                        $sum = $val;
                        $remainingValue -= $sum;
                        if (!$this->checkIfDepreciationThisYear($this->connect, $userID, $assetID, $yearOfDepreciation)) {
                            $this->saveDepreciation($this->connect, $userID, $assetID, $val, $remainingValue, "$yearOfDepreciation-12-31", 'Výdaj', $dan, $popis, $i, $sum, $lastYear, $hiddenSlot, $line['doklad'], $line['nazev'], $line['zpusob'], $uhrada);
                            $_SESSION['successDEP'] = "Proběhl odpis u položky " . $line['nazev'] . ".";
                        }
                    } elseif ($i > 1) {
                        $val = ($initialPrice * $consequentYearsCoeficient[$j - 1]) / 100;
                        $sum = $val;
                        $remainingValue -= $sum;
                        if (!$this->checkIfDepreciationThisYear($this->connect, $userID, $assetID, $yearOfDepreciation)) {
                            if ($i == $ttd) {
                                $lastYear = 1;
                            }
                            $this->saveDepreciation($this->connect, $userID, $assetID, $val, $remainingValue, "$yearOfDepreciation-12-31", 'Výdaj', $dan, $popis, $i, $sum, $lastYear, $hiddenSlot, $line['doklad'], $line['nazev'], $line['zpusob'], $uhrada);
                            $_SESSION['successDEP'] = "Proběhl odpis u položky " . $line['nazev'] . ".";
                        }
                    }
                } else if ($line["zpusob"] == "Zrychlený") {
                    $firstYearCoeficient = [3, 5, 10, 20, 30, 50];
                    $consequentYearsCoeficient = [4, 6, 11, 21, 31, 51];
                    if ($i == 1) {
                        $val = $initialPrice / $firstYearCoeficient[$j - 1];
                        $sum = $val;
                        $remainingValue -= $sum;
                        if (!$this->checkIfDepreciationThisYear($this->connect, $userID, $assetID, $yearOfDepreciation)) {
                            $this->saveDepreciation($this->connect, $userID, $assetID, $val, $remainingValue, "$yearOfDepreciation-12-31", 'Výdaj', $dan, $popis, $i, $sum, $lastYear, $hiddenSlot, $line['doklad'], $line['nazev'], $line['zpusob'], $uhrada);
                            $_SESSION['successDEP'] = "Proběhl odpis u položky " . $line['nazev'] . ".";
                        }
                    } else if ($i > 1) {
                        for ($k = 1; $k < $i; $k++) {
                            $val = (2 * ($remainingValue)) / ($consequentYearsCoeficient[$j - 1] - $k);
                        }
                        $sum = $val;
                        $remainingValue -= $sum;
                        if (!$this->checkIfDepreciationThisYear($this->connect, $userID, $assetID, $yearOfDepreciation)) {
                            if ($i == $ttd) {
                                $lastYear = 1;
                            }
                            $this->saveDepreciation($this->connect, $userID, $assetID, $val, $remainingValue, "$yearOfDepreciation-12-31", 'Výdaj', $dan, $popis, $i, $sum, $lastYear, $hiddenSlot, $line['doklad'], $line['nazev'], $line['zpusob'], $uhrada);
                            $_SESSION['successDEP'] = "Proběhl odpis u položky " . $line['nazev'] . ".";
                        }
                    }
                }
                $yearOfDepreciation++;
            }
            if (isset($_POST['likvidace']) || isset($_POST['id_asset'])) {
                $assetID_e = (isset($_POST['likvidace']))?$_POST['rowIDeliminate']:$_POST['id_asset'];
                if (!$this->isLastYear($this->connect, $assetID_e, $userID) && $assetID == $assetID_e) {
                    if ($initialPrice > 0) {
                        $val = $remainingValue;
                    } else {
                        $val = 0;
                    }

                    $remainingValue = 0;
                    $lastYear = 1;
                    $this->saveDepreciation($this->connect, $userID, $assetID_e, $val, $remainingValue, $today, 'Výdaj', $dan, $popis, $i, $sum, $lastYear, "Vyřazení", $line['doklad'], $line['nazev'], $line['zpusob'], $uhrada);
                    $_SESSION['successAL'] = "Majetek " . $line['nazev'] . " úspěšně vyřazen.";
                }
            }
        }
    }

    private function saveDepreciation($connect, $userID, $assetID, $castka, $zbyva, $datum, $prijemvydaj, $dan, $popis, $i, $opravky, $lastYear, $hiddenSlot, $doklad, $nazev, $zpusob, $uhrada)
    {
        $select = $this->connect->prepare("INSERT INTO `asset_depreciation` (`userID`, `assetID`, `row`, `castka`, `zbyva`, `datum`, `excuses`, `lastYear`, `prijemvydaj`, `dan`, `popis`, `doklad`, `nazev`, `zpusob`, `uhrada`, `hiddenSlot`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $select->bind_param('iiiiisssssssssss', $userID, $assetID, $i, $castka, $zbyva, $datum, $opravky, $lastYear, $prijemvydaj, $dan, $popis, $doklad, $nazev, $zpusob, $uhrada, $hiddenSlot);
        $select->execute();
    }
    private function checkIfDepreciationThisYear($connect, $userID, $assetID, $dateOfDepreciation)
    {
        $select = $this->connect->prepare("SELECT `datum` FROM `asset_depreciation` WHERE `userID` = ? AND `assetID` = ? AND YEAR(datum)=?");
        $select->bind_param("iis", $userID, $assetID, $dateOfDepreciation);
        $select->execute();
        $result = $select->get_result();
        if ($result->num_rows > 0) {
            return true;
        }
        return false;
    }
    private function isLastYear($connect, $assetID, $userID)
    {
        $select = $this->connect->prepare("SELECT lastYear FROM asset_depreciation WHERE lastYear=1 AND assetID=? AND userID=?");
        $select->bind_param("ii", $assetID, $userID);
        $select->execute();
        $result = $select->get_result();
        if ($result->num_rows > 0) {
            return true;
        }
        return false;
    }
    /*function getDepreciation($connect, $assetID)
    {
        $select = $this->connect->prepare("SELECT * FROM asset_depreciation WHERE assetID=?");
        $select->bind_param('i', $assetID);
        $select->execute();
        $line = $select->get_result();
        return $line;
    }
    function checkIfDepreciation($line){
        return $line->num_rows < 1;
    }*/
    //not needed yet
/*function findDepreciation($connect, $userID)
{ // vrací pole $odpisy_db[$rok]=$odepsana_castka;
    $select = $this->connect->prepare("SELECT `castka`, `datum`, `yearOfDepreciation` FROM `asset_depreciation` WHERE `userID`=? ORDER BY `datum`");
    $select->bind_param('i', $userID);
    $select->execute();
    $result = $select->get_result();

    $assets = [];

    while ($line = mysqli_fetch_array($result)) {
        $date = date("Y", strtotime($line["datum"]));
        $yearOfDepreciation = $line["yearOfDepreciation"];
        $castka = $line['castka'];
        $assets[$date][$yearOfDepreciation] = $castka;
    }

    return $assets;
}*/
}