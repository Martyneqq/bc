<?php

function secure($value) {
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function connectToExpensesTable($connect, $userData) {
    if (isset($_POST['ulozit'])) {
        $userID = $userData['id']; //$_SESSION['userid']
        $nazevf = $_POST['nazevf'];
        $datumf = $_POST['datumf'];
        $prijemvydajf = $_POST['prijemvydajf'];
        $castkaf = $_POST['castkaf'];
        $danf = $_POST['danf'];
        $dokladf = $_POST['dokladf'];
        $uhradaf = $_POST['uhradaf'];
        $popisf = $_POST['popisf'];

        $save = "INSERT INTO assets(nazevf,userID,datumf,prijemvydajf,castkaf,danf,dokladf,uhradaf,popisf)VALUES(?,?,?,?,?,?,?,?,?)";
//$query = mysqli_query($connect, $save);
        $query = mysqli_prepare($connect, $save);
        $bind = mysqli_stmt_bind_param($query, "sissdssss", $nazevf, $userID, $datumf, $prijemvydajf, $castkaf, $danf, $dokladf, $uhradaf, $popisf);
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
        $nazevf = $_POST['nazevf'];
        $datumf = $_POST['datumf'];
        $prijemvydajf = "Výdaj";
        $castkaf = $_POST['castkaf'];
        $danf = "Ano";
        $odpisf = $_POST['odpisf'];
        $zpusobf = $_POST['zpusobf'];
        $popisf = $_POST['popisf'];

        $save = "INSERT INTO assets(nazevf,userID,datumf,prijemvydajf,castkaf,danf,odpisf,zpusobf,popisf)VALUES(?,?,?,?,?,?,?,?,?)";
        $query = mysqli_prepare($connect, $save);
        $bind = mysqli_stmt_bind_param($query, "sissisiss", $nazevf, $userID, $datumf, $prijemvydajf, $castkaf, $danf, $odpisf, $zpusobf, $popisf);
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
