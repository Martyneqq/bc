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
        $cisloPolozky = $_POST['cislopolozky'];
        $nazev = $_POST['nazev'];
        $castka = $_POST['castka'];
        $datum = $_POST['datum'];
        $datumVyrazeni = $_POST['datumvyrazeni'];
        $prijemvydaj = "Výdaj";
        $odpis = $_POST['odpis'];
        $zpusob = $_POST['zpusob'];
        $popis = $_POST['popis'];

        $save = "INSERT INTO assets(userID,cislopolozky,nazev,castka,datum,datumvyrazeni,prijemvydaj,odpis,zpusob,popis)VALUES(?,?,?,?,?,?,?,?,?,?)";
        $query = mysqli_prepare($connect, $save);
        $bind = mysqli_stmt_bind_param($query, "ississsiss", $userID, $cisloPolozky, $nazev, $castka, $datum, $datumVyrazeni, $prijemvydaj, $odpis, $zpusob, $popis);
        if (($execute = mysqli_stmt_execute($query)) != true) {
            echo "Error";
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
