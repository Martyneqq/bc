<?php

session_start();
//not post->header(majetek_dlouhodoby.php)
include_once 'databaseConnection.php';
include_once 'functions.php';

$assetCastka = $_POST['value'];
$assetID = $_POST['id_info'];
//sem dát funkci kontrola() - jsou v databázi všechny potřebné odpisy? else INSERT < tento rok, ale ne co je uloženo
$p = kontrola($connect, $assetID);
$odpisyDb = zjistiOdpisy($connect, $assetID);

if (count($odpisyDb) < 1) {
    echo "Zatím neproběhly žádné odpisy.";
} else {
    $i = 1; // row numbering
    $zbyva = $assetCastka;
    $output = '<table class="table table-striped table-hover" id="depreciationTable">';
    $output .= '<tr>
                        <th>Pořadí</th>
                        <th>Částka</th>
                        <th>Zbývající hodnota</th>
                        <th>Rok odpisu</th>
                    </tr>';
    foreach ($odpisyDb as $key => $value) {
        $zbyva -= $value;
        $output .= '<tr>' . '<td>' . $i . '</td>' . '<td>' . $value . '</td>' . '<td>' . $zbyva . '</td>' . '<td>' . $key . '</td>' . '</tr>';
        $i++;
    }
    $output .= "</table>";

//$output .= implode(' ', $_POST);
    echo $output;
}