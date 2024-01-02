<?php

session_start();

include_once 'databaseConnection.php';

$assetID = $_POST['id_info'];

$select = $connect->prepare("SELECT * FROM asset_depreciation WHERE assetID=?");
$select->bind_param('i', $assetID);
$select->execute();
$line = $select->get_result();

if ($line->num_rows < 1) {
    echo "Zatím neproběhly žádné odpisy.";
} else {
    $output = '<table class="table table-striped table-hover" id="depreciationTable">';
    $output .= '<tr>
                        <th>Pořadí</th>
                        <th>Částka</th>
                        <th>Zbývající hodnota</th>
                        <th>Rok odpisu</th>
                        </tr>';
    while ($asset = $line->fetch_assoc()) {
        $output .= '<tr>' . '<td>' . $asset['row'] . '</td>' . '<td>' . number_format((float) $asset['castka'], 2, ".", ",") . '</td>' . '<td>' . number_format((float) $asset['zbyva'], 2, ".", ",") . '</td>' . '<td>' . date('Y', strtotime($asset['datum'])) . '</td>' . '</tr>';
    }
    $output .= "</table>";

    echo $output;
}