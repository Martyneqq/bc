<?php
class DatabaseHelper
{
    private $connect;
    private $userID;

    public function __construct($connect, $userID)
    {
        $this->connect = $connect;
        $this->userID = $userID;
    }
    public function GetConnect()
    {
        return $this->connect;
    }

    public function GetUserID()
    {
        return $this->userID;
    }
    public function TotalYearlyValue()
    {
        $selectYear = $this->connect->prepare("SELECT DISTINCT userID, YEAR(datum) as year FROM incomeexpense WHERE userID = ?
        ORDER BY year DESC");
        $selectYear->bind_param('i', $this->userID);
        $selectYear->execute();
        $resultYear = $selectYear->get_result();
        $years = [];

        while ($row = mysqli_fetch_array($resultYear)) {
            $years[] = $row['year'];
        }

        return $years;
    }

    public function YearlyIncomeExpense($year)
    {
        $query = "SELECT
            YEAR(datum) AS year,
            SUM(CASE WHEN prijemvydaj = 'Příjem' AND dan = 'Ano' THEN castka ELSE 0 END) AS yearlyIncomeTax,
            SUM(CASE WHEN prijemvydaj = 'Příjem' AND dan = 'Ne' THEN castka ELSE 0 END) AS yearlyIncome,
            SUM(CASE WHEN prijemvydaj = 'Výdaj' AND dan = 'Ano' THEN castka ELSE 0 END) AS yearlyExpenseTax,
            SUM(CASE WHEN prijemvydaj = 'Výdaj' AND dan = 'Ne' THEN castka ELSE 0 END) AS yearlyExpense
            FROM incomeexpense
            WHERE userID = ? AND YEAR(datum) = ? 
            GROUP BY YEAR(datum)";

        $select = $this->connect->prepare($query);
        $select->bind_param('ii', $this->userID, $year);
        $select->execute();
        $result = $select->get_result();

        $yearlyIncomeArrayTax = [];
        $yearlyIncomeArray = [];
        $yearlyExpenseArrayTax = [];
        $yearlyExpenseArray = [];

        while ($row = mysqli_fetch_assoc($result)) {
            $yearlyIncomeArrayTax[$row['year']] = $row['yearlyIncomeTax'];
            $yearlyIncomeArray[$row['year']] = $row['yearlyIncome'];
            $yearlyExpenseArrayTax[$row['year']] = $row['yearlyExpenseTax'];
            $yearlyExpenseArray[$row['year']] = $row['yearlyExpense'];
        }
        if (!isset($yearlyIncomeArrayTax[$year])) {
            $yearlyIncomeArrayTax[$year] = 0;
        }
        if (!isset($yearlyIncomeArray[$year])) {
            $yearlyIncomeArray[$year] = 0;
        }
        if (!isset($yearlyExpenseArrayTax[$year])) {
            $yearlyExpenseArrayTax[$year] = 0;
        }
        if (!isset($yearlyExpenseArray[$year])) {
            $yearlyExpenseArray[$year] = 0;
        }

        return [
            'yearlyIncomeTax' => $yearlyIncomeArrayTax,
            'yearlyIncome' => $yearlyIncomeArray,
            'yearlyExpenseTax' => $yearlyExpenseArrayTax,
            'yearlyExpense' => $yearlyExpenseArray
        ];
    }

    public function TotalFlow()
    {
        $totalQuery = "(SELECT 
        SUM(CASE WHEN prijemvydaj = 'Příjem' AND uhrada = 'Hotovost' THEN castka ELSE 0 END) AS totalIncomeCash,
        SUM(CASE WHEN prijemvydaj = 'Výdaj' AND uhrada = 'Hotovost' THEN castka ELSE 0 END) AS totalExpenseCash,
        SUM(CASE WHEN prijemvydaj = 'Příjem' AND uhrada = 'Banka' THEN castka ELSE 0 END) AS totalIncomeBank,
        SUM(CASE WHEN prijemvydaj = 'Výdaj' AND uhrada = 'Banka' THEN castka ELSE 0 END) AS totalExpenseBank
        FROM incomeexpense
        WHERE userID=?)
        UNION
        (SELECT 
        SUM(CASE WHEN prijemvydaj = 'Příjem' AND uhrada = 'Hotovost' THEN castka ELSE 0 END) AS totalIncomeCash,
        SUM(CASE WHEN prijemvydaj = 'Výdaj' AND uhrada = 'Hotovost' THEN castka ELSE 0 END) AS totalExpenseCash,
        SUM(CASE WHEN prijemvydaj = 'Příjem' AND uhrada = 'Banka' THEN castka ELSE 0 END) AS totalIncomeBank,
        SUM(CASE WHEN prijemvydaj = 'Výdaj' AND uhrada = 'Banka' THEN castka ELSE 0 END) AS totalExpenseBank
        FROM assets
        WHERE userID=?)";

        $query = "SELECT 
        SUM(totalIncomeCash) AS totalIncomeCash,
        SUM(totalIncomeBank) AS totalIncomeBank,
        SUM(totalExpenseCash) AS totalExpenseCash,
        SUM(totalExpenseBank) AS totalExpenseBank
        FROM ($totalQuery) AS subquery";

        $select = $this->connect->prepare($query);
        $select->bind_param('ii', $this->userID, $this->userID);
        $select->execute();
        $result = $select->get_result();

        $cash = 0;
        $bank = 0;

        if ($row = mysqli_fetch_assoc($result)) {
            $cash = $row['totalIncomeCash'] - $row['totalExpenseCash'];
            $bank = $row['totalIncomeBank'] - $row['totalExpenseBank'];
        }

        return ['cash' => $cash, 'bank' => $bank];
    }

    public function YearlyDataForGraph()
    {
        $select = $this->connect->prepare("SELECT sum(case when prijemvydaj = 'Výdaj' then castka else 0 end) as y_vydaj,
            sum(case when prijemvydaj = 'Příjem' then castka else 0 end) as y_prijeti,
            year(datum) as x
            FROM incomeexpense
            WHERE userID = ?
            GROUP BY year(datum)
            ORDER BY x");

        $select->bind_param('i', $this->userID);
        $select->execute();
        $result = $select->get_result();
        $yearArray = mysqli_fetch_all($result, MYSQLI_ASSOC);

        $arrIncome = [];
        $arrExpense = [];
        $arrYears = [];

        foreach ($yearArray as $key => $val) {
            $arrIncome[] = $val['y_prijeti'];
            $arrExpense[] = $val['y_vydaj'];
            $arrYears[] = $val['x'];
        }

        return [
            'income' => $arrIncome,
            'expense' => $arrExpense,
            'years' => $arrYears,
        ];
    }

    public function SubmitInsertData($buttonName)
    {
        if (isset($_POST[$buttonName]) || isset($_POST[$buttonName . 'adalsi'])) {
            $userID = $this->userID;

            switch ($buttonName) {
                case 'ulozit1':
                    $nazev = $_POST['nazev'];
                    $datum = $_POST['datum'];
                    $prijemvydaj = $_POST['prijemvydaj'];
                    $castka = $_POST['castka'];
                    $dan = $_POST['dan'];
                    $doklad = $_POST['doklad'];
                    $uhrada = $_POST['uhrada'];
                    $popis = $_POST['popis'];

                    /*$this->GenerateDocument($uhrada, $prijemvydaj, date("y", strtotime($datum)));*/
                    if (strpos($_SERVER['REQUEST_URI'], 'majetek_drobny.php') == false) {
                        $save = "INSERT INTO incomeexpense(nazev, doklad,datum,prijemvydaj,castka,dan,uhrada,popis, userID) VALUES(?,?,?,?,?,?,?,?,?)";
                        $select = $this->connect->prepare($save);
                        $select->bind_param("sississsi", $nazev, $doklad, $datum, $prijemvydaj, $castka, $dan, $uhrada, $popis, $userID);
                        $redirectPage = 'evidence_prijmy_a_vydaje.php';
                    } else {
                        $hiddenSlot = "Drobný majetek";
                        $sql = "INSERT INTO incomeexpense (userID, nazev, doklad, castka, datum, uhrada, prijemvydaj, popis, dan, hiddenSlot) VALUES (?,?, ?, ?, ?, ?, ?, ?, ?, ?)";
                        $select = $this->connect->prepare($sql);
                        $select->bind_param("isiissssss", $userID, $nazev, $doklad, $castka, $datum, $uhrada, $prijemvydaj, $popis, $dan, $hiddenSlot);
                        $redirectPage = 'majetek_drobny.php';
                    }
                    break;

                case 'ulozit2':
                    $nazev = $_POST['nazev'];
                    $doklad = $_POST['doklad'];
                    $firma = $_POST['firma'];
                    $datum = $_POST['datum'];
                    $datums = $_POST['datums'];
                    $pohledavkadluh = $_POST['pohledavkadluh'];
                    $hodnota = $_POST['hodnota'];
                    $dan = $_POST['dan'];
                    $uhrada = $_POST['uhrada'];
                    $uhrazeno = $_POST['uhrazeno'];
                    $popis = $_POST['popis'];

                    $save = "INSERT INTO demanddebt(nazev,userID,doklad,firma,datum, datums,pohledavkadluh,hodnota,dan,uhrada,uhrazeno,popis)VALUES(?,?,?,?,?,?,?,?,?,?,?,?)";
                    $select = $this->connect->prepare($save);
                    $select->bind_param("siissssdssss", $nazev, $userID, $doklad, $firma, $datum, $datums, $pohledavkadluh, $hodnota, $dan, $uhrada, $uhrazeno, $popis);
                    $redirectPage = 'evidence_pohledavky_a_dluhy.php';
                    break;

                case 'ulozit3':
                    $nazev = $_POST['nazev'];
                    $castka = $_POST['castka1'];
                    $typ = $_POST['typ1'];
                    $uhrada = $_POST['uhrada'];
                    $datum = $_POST['datum'];
                    $prijemvydaj = "Výdaj";
                    $doklad = $_POST['doklad'];
                    $dan = "Ne";
                    $popis = $_POST['popis'];
                    $hiddenSlot = "Odpis";
                    $odpis = $_POST['odpis'];
                    $zpusob = $_POST['zpusob'];
                    $datumVyrazeni = null;
                    $sql = "INSERT INTO assets (userID, doklad, nazev, castka, typ, datum, datum_vyrazeni, prijemvydaj, odpis, zpusob, uhrada, dan, popis, hiddenSlot) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $select = $this->connect->prepare($sql);
                    $select->bind_param("iisissssssssss", $userID, $doklad, $nazev, $castka, $typ, $datum, $datumVyrazeni, $prijemvydaj, $odpis, $zpusob, $uhrada, $dan, $popis, $hiddenSlot);

                    $result = $select->execute();

                    $save = "INSERT INTO incomeexpense(nazev, doklad, datum, prijemvydaj, castka, dan, uhrada, popis, userID, hiddenSlot) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    $select = $this->connect->prepare($save);
                    $select->bind_param("sississsis", $nazev, $doklad, $datum, $prijemvydaj, $castka, $dan, $uhrada, $popis, $userID, $hiddenSlot);

                    $redirectPage = 'majetek_dlouhodoby.php';
                    break;

                default:
                    return; // Invalid operation type
            }

            $result = $select->execute();
            if ($result) {
                $_SESSION['success'] = "Položka \"" . $nazev . "\" úspěšně přidána.";
            } else {
                $_SESSION['error'] = "Chyba při přidávání položky: " . $select->error . ".";
            }

            if (isset($_POST[$buttonName])) {
                //header("Location: $redirectPage");
            }
        }
    }
    public function GetTableRow($tableName)
    {
        $ide = null;
        $select = null;

        switch ($tableName) {
            case 'incomeexpense':
                if (isset($_POST['update0'])) {
                    $ide = $_POST['ide'];
                    $select = $this->connect->prepare("SELECT * FROM incomeexpense WHERE id = ?");
                }
                break;
            case 'demanddebt':
                if (isset($_POST['update2'])) {
                    $ide = $_POST['ide2'];
                    $select = $this->connect->prepare("SELECT * FROM demanddebt WHERE id = ?");
                }
                break;
            case 'assets':
                if (isset($_POST['update4'])) {
                    $ide = $_POST['ide3'];
                    $select = $this->connect->prepare("SELECT * FROM assets WHERE id = ?");
                }
                break;
            default:
                return null;
        }

        if ($ide && $select) {
            $select->bind_param('i', $ide);
            $select->execute();
            $result = $select->get_result();
            if ($result) {
                $row = $result->fetch_array();
                return $row;
            }
        }

        return null;
    }
    public function SubmitEdit($tableName)
    {
        $id = null;
        $userID = $this->userID;
        $doklad = null;
        $nazev = null;
        $castka = null;
        $typ = null;
        $datumZarazeni = null;
        $odpis = null;
        $zpusob = null;
        $popis = null;

        switch ($tableName) {
            case 'incomeexpense':
            case 'minorassets';
                if (isset($_POST['update1'])) {
                    $id = $_POST['id'];
                    $nazev = $_POST['nazev'];
                    $datum = $_POST['datum'];
                    $prijemvydaj = $_POST['prijemvydaj'];
                    $castka = $_POST['castka'];
                    $dan = $_POST['dan'];
                    $doklad = $_POST['doklad'];
                    $popis = $_POST['popis'];
                }
                break;
            case 'demanddebt':
                if (isset($_POST['update3'])) {
                    $id = $_POST['id'];
                    $nazev = $_POST['nazev'];
                    $doklad = $_POST['doklad'];
                    $firma = $_POST['firma'];
                    $datum = $_POST['datum'];
                    $datums = $_POST['datums'];
                    $pohledavkadluh = $_POST['pohledavkadluh'];
                    $uhrada = $_POST['uhrada'];
                    $hodnota = $_POST['hodnota'];
                    $dan = $_POST['dan'];
                    $uhrazeno = $_POST['uhrazeno'];
                    $popis = $_POST['popis'];
                }
                break;
            case 'assets':
                if (isset($_POST['update5'])) {
                    $id = $_POST['id'];
                    $doklad = $_POST['doklad'];
                    $nazev = $_POST['nazev'];
                    $castka = $_POST['castka'];
                    $typ = $_POST['typ'];
                    $dan = $_POST['dan'];
                    $datumZarazeni = $_POST['datum'];
                    $odpis = $_POST['odpis'];
                    $zpusob = $_POST['zpusob'];
                    $popis = $_POST['popis'];
                }
                break;
            default:
                return;
        }

        $select = null;

        if ($id) {
            switch ($tableName) {
                case 'incomeexpense':
                    $hiddenSlot = $_POST['hiddenSlot'];
                    if ($hiddenSlot == "Drobný majetek") {
                        $select = $this->connect->prepare("UPDATE `incomeexpense` SET `doklad`=?, `nazev`=?, `castka`=?, `datum`=?, `popis`=? WHERE id=?");
                        $select->bind_param('sssssi', $doklad, $nazev, $castka, $datum, $popis, $id);
                        $redirectPage = "majetek_drobny.php";
                    } else {
                        $uhrada = $_POST['uhrada'];
                        $select = $this->connect->prepare("UPDATE `incomeexpense` SET `userID`=?, `nazev`=?, `datum`=?, `prijemvydaj`=?, `castka`=?, `dan`=?, `doklad`=?, `uhrada`=?, `popis`=? WHERE id=?");
                        $select->bind_param('isssissssi', $userID, $nazev, $datum, $prijemvydaj, $castka, $dan, $doklad, $uhrada, $popis, $id);
                        $redirectPage = "evidence_prijmy_a_vydaje.php";
                    }
                    break;
                case 'demanddebt':
                    $select = $this->connect->prepare("UPDATE `demanddebt` SET `userID`=?,`nazev`=?,`doklad`=?,`firma`=?,`datum`=?,`datums`=?,`pohledavkadluh`=?,`hodnota`=?, `uhrada`=?,`dan`=?, `uhrazeno`=?,`popis`=? WHERE id=?");
                    $select->bind_param('issssssissssi', $userID, $nazev, $doklad, $firma, $datum, $datums, $pohledavkadluh, $hodnota, $uhrada, $dan, $uhrazeno, $popis, $id);
                    $redirectPage = "evidence_pohledavky_a_dluhy.php";
                    break;
                case 'assets':
                    $select = $this->connect->prepare("UPDATE `assets` SET `userID`=?, `doklad` = ?, `nazev`=?,`castka`=?, `typ`=?,`datum`=?,`odpis`=?,`zpusob`=?, `dan`=?,`popis`=? WHERE id=?");
                    $select->bind_param('issississsi', $userID, $doklad, $nazev, $castka, $typ, $datumZarazeni, $odpis, $zpusob, $dan, $popis, $id);
                    $redirectPage = "majetek_dlouhodoby.php";
                    break;
            }

            if ($select->execute()) {
                $_SESSION['edit'] = "Položka \"" . $nazev . "\" úspěšně upravena.";
                header("Location: $redirectPage");
            } else {
                $_SESSION['errorE'] = "Chyba při upravování položky: " . $select->error . ".";
            }
        }
    }
    public function SubmitDelete($tableName)
    {
        $id = null;
        $nazev = null;
        switch ($tableName) {
            case 'incomeexpense':
            case 'minorassets':
                if (isset($_POST['delete']) || isset($_POST['delete0'])) {
                    $id = $_POST['idd'];
                    $nazev = $_POST['nazev'];
                }
                break;
            case 'demanddebt':
                if (isset($_POST['delete2'])) {
                    $id = $_POST['idd2'];
                    $nazev = $_POST['nazev'];
                }
                break;
            case 'assets':
                if (isset($_POST['delete3'])) {
                    $id = $_POST['idd3'];
                    $nazev = $_POST['nazev'];
                }
                break;
            default:
                return;
        }
        if ($id) {
            $delete = $this->connect->prepare("DELETE FROM $tableName WHERE id=?");
            $delete->bind_param("i", $id);
            $delete->execute();

            if ($delete->execute()) {
                $_SESSION["delete"] = "Položka \"" . $nazev . "\" úspěšně smazána.";
            } else {
                $_SESSION["delete"] = "Error.";
            }

            /*if (isset($_POST['delete']) || isset($_POST['delete2'])) {
                header("Location: evidence_prijmy_a_vydaje.php");
            } elseif (isset($_POST['delete0']) || isset($_POST['delete3'])) {
                header("Location: majetek_drobny.php");
            }*/
        }
    }
    public function InsertAsIncomeExpense($nazev, $datum, $prijemvydaj, $castka, $dan, $uhrada, $popis, $userID, $id)
    {
        if (!$this->IsIdUnique($id)) {
            return false;
        }

        $save = "INSERT INTO incomeexpense (nazev, datum, prijemvydaj, castka, dan, uhrada, popis, userID, id) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $insert = $this->connect->prepare($save);
        $insert->bind_param("sssisssii", $nazev, $datum, $prijemvydaj, $castka, $dan, $uhrada, $popis, $userID, $id);

        if ($insert->execute()) {
            $insert->close();
            return true;
        } else {
            $insert->close();
            return false;
        }
    }

    private function IsIdUnique($id)
    {
        $select = $this->connect->prepare("SELECT id FROM incomeexpense WHERE id = ?");
        $select->bind_param('i', $id);

        if ($select->execute()) {
            $result = $select->get_result();

            if ($result && $result->num_rows > 0) {
                $select->close();
                return false;
            } else {
                $select->close();
                return true;
            }
        } else {
            $select->close();
            return false;
        }
    }
}