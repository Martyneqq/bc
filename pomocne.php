<?php

include 'databaseConnection.php';
if (isset($_POST['id_info'])) {
    $infoID = $_POST['id_info'];
    $select0 = $connect->prepare("SELECT * FROM incomeexpense WHERE assetID=? ORDER BY datum");
    $select0->bind_param('i', $infoID);
    $select0->execute();
    $result0 = $select0->get_result();
    $opravky = 0;
}
/* //slouží pro zápis odpisů
  while ($line = mysqli_fetch_array($result)) {
  $line['popis'] = 'Odpis';
  $line['assetID'] = 90;
  $save = "INSERT INTO incomeexpense(nazev,datum,prijemvydaj,castka,popis,userID,assetID)VALUES(?,?,?,?,?,?,?)";
  $query = mysqli_prepare($connect, $save);
  $bind = mysqli_stmt_bind_param($query, "sssisii", $line['nazev'], $withdrawal, $line['prijemvydaj'], $sum, $line['popis'], $line['userID'], $line['assetID']);
  if (($execute = mysqli_stmt_execute($query)) != true) {
  echo "Error";
  }
  }

  //SELECT * FROM `assets` WHERE `datumvyrazeni` = '0000-00-00'; // takhle poznám co se dá odepsat
 */
