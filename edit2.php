<?php
ob_start();
session_start();
include 'functions.php';
include 'databaseConnection.php';
$userData = check($connect);

if (isset($_POST['update2'])) {
    $ide2 = $_POST['ide2'];
    $select = $connect->prepare("SELECT * FROM demanddebt WHERE idp = ?");
    $select->bind_param('i', $ide2);
    $select->execute();
    $result = $select->get_result();
    $row = mysqli_fetch_array($result);
    ?>
    <!DOCTYPE html>
    <html>
            <head>
    <?php
    include 'inc/head.php';
    ?>
    </head>
    <header>
    <?php
    include 'inc/header.php';
    ?>
    </header>
        <body>
            <div class="container">
                <form class="default-form" id="default-form" method="post" action="">
                    <h3>Přidat pohledávku nebo závazek</h3>
                    <div class="default-field">
                        <table class="table" id="default-table">
                            <div class="form-group row">
                                <label for="nazevp" class="col-sm-4 col-form-label">Název</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="text" name="nazevp" required="" value="<?php echo $row['nazevp']; ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="cislodokladp" class="col-sm-4 col-form-label">Číslo dokladu</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="text" name="cislodokladp" required="" value="<?php echo $row['cislodokladp']; ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="firmap" class="col-sm-4 col-form-label">Firma</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="text" name="firmap" required="" value="<?php echo $row['firmap']; ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="datump" class="col-sm-4 col-form-label">Datum vystavení</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="date" name="datump" required="" value="<?php echo $row['datump']; ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="pohledavkadluhp" class="col-sm-4 col-form-label">Pohledávka/dluh</label>
                                <div class="col-sm-8">
                                    <select name="pohledavkadluhp" class="form-control" required="">
                                        <option value="">--Vybrat--</option>
                                        <option value="Pohledávka" <?php echo ($row['pohledavkadluhp'] == 'Pohledávka') ? "selected" : ""; ?>>Pohledávka</option>
                                        <option value="Dluh" <?php echo ($row['pohledavkadluhp'] == 'Dluh') ? "selected" : ""; ?>>Dluh</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="hodnotap" class="col-sm-4 col-form-label">Hodnota</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="number" min="0" name="hodnotap" required="" value="<?php echo $row['hodnotap']; ?>">
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="danp" class="col-sm-4 col-form-label">Daňový?</label>
                                <div class="col-sm-8">
                                    <select name="danp" class="form-control" required="">
                                        <option value="">--Vybrat--</option>
                                        <option value="Ano" <?php echo ($row['danp'] == 'Ano') ? "selected" : ""; ?>>Ano</option>
                                        <option value="Ne" <?php echo ($row['danp'] == 'Ne') ? "selected" : ""; ?>>Ne</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group row">
                                <label for="popisp" class="col-sm-4 col-form-label">Popis</label>
                                <div class="col-sm-8">
                                    <input class="form-control" type="text" name="popisp" value="<?php echo $row['popisp']; ?>">
                                </div>
                            </div>
                        </table>
                    </div>
                    <div class="text-center">
                        <input type="hidden" name="idp" value="<?php echo $ide2 ?>">
                        <button type="submit" name="update3" class="btn btn-success">Uložit</button>
                        <a href="evidence_pohledavky_a_dluhy.php" class="btn btn-danger">Zrušit</a>
                    </div>
                </form>
            </div>
            <!-- Add the Bootstrap JS file -->
            <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
            
        </body>
    </html>
    <?php
} elseif (isset($_POST['update3'])) {
    $idp = $_POST['idp'];
    $userID = $userData['id']; //$_SESSION['userid']
    $nazevp = $_POST['nazevp'];
    $cislodokladp = $_POST['cislodokladp'];
    $firmap = $_POST['firmap'];
    $datump = $_POST['datump'];
    $pohledavkadluhp = $_POST['pohledavkadluhp'];
    $hodnotap = $_POST['hodnotap'];
    $danp = $_POST['danp'];
    $popisp = $_POST['popisp'];

    //UPDATE `demanddebt` SET `userID`='[value-2]',`nazevp`='[value-3]',`cislodokladp`='[value-4]',`firmap`='[value-5]',`datump`='[value-6]',`pohledavkadluhp`='[value-7]',`hodnotap`='[value-8]',`danp`='[value-9]',`popisp`='[value-10]' WHERE idp=?
    $select = $connect->prepare("UPDATE `demanddebt` SET `userID`=?,`nazevp`=?,`cislodokladp`=?,`firmap`=?,`datump`=?,`pohledavkadluhp`=?,`hodnotap`=?,`danp`=?,`popisp`=? WHERE idp=?");
    $select->bind_param('isssssissi', $userID, $nazevp, $cislodokladp, $firmap, $datump, $pohledavkadluhp, $hodnotap, $danp, $popisp, $idp);
    $select->execute();

    //$query = mysqli_prepare($connect, $edit);
    //$bind = mysqli_stmt_bind_param($query, "sisdsss", $nazevf, $userID, $datumf, $castkaf, $dokladf, $uhradaf, $popisf);
    if ($select === false) {
        echo 'Error';
    } else {
        header("Location: evidence_pohledavky_a_dluhy.php");
    }
} else {
    header("Location: evidence_pohledavky_a_dluhy.php");
    die();
}
ob_end_flush();
?>