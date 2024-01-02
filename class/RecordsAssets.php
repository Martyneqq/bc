<?php

require_once 'class/Records.php';
class RecordsAssets extends Records
{
    protected $connect;
    protected $pageTitle;
    protected $dbHelper;
    protected $alert;
    protected $head;
    protected $header;
    protected $title;
    protected $auth;
    protected $appLogic;
    protected $items = ["Datum zařazení", "Datum vyřazení", "Číslo dokladu", "Název", "Počáteční hodnota", "Typ", "Odpisová skupina", "Způsob platby", "Způsob odpisu", "Daňová uznatelnost", "Popis"];
    public function __construct($connect, $pageTitle, $title)
    {
        $this->auth = new Authenticator($connect, $title);
        $this->userData = $this->auth->Check();
        $this->userID = $this->userData['id'];
        $this->head = new Head($title);
        $this->header = new Header($connect, $this->userData, $this->userID);
        $this->alert = new Alert();
        $this->dbHelper = new DatabaseHelper($connect, $this->userID);
        $this->connect = $connect;
        $this->pageTitle = $pageTitle;
        $this->appLogic = new AppLogic($connect);
    }
    public function RenderTitle()
    {
        ?>
        <div class="row">
            <div class="col text-center">
                <h3>
                    <?php echo $this->pageTitle; ?>
                </h3>
            </div>
        </div>
        <?php
    }
    public function RenderButtonAdd()
    {
        ?>
        <button type="button" id="openAddAssetModal" class="btn btn-success add-asset-button" data-toggle="modal"
            data-target="#addAssetModal">Přidat
        </button>
        <?php
    }
    public function TableHead()
    {
        ?>
        <tr>
            <?php
            for ($i = 0; $i < count($this->items); $i++) { ?>
                <th onclick="sort('assets', <?php echo $i ?>, 1)">
                    <?php echo $this->items[$i] ?>
                </th>
            <?php } ?>
            <th>Úpravy</th>
        </tr>
        <?php
    }
    public function TableBody()
    {
        $update = $this->connect->prepare("UPDATE assets a INNER JOIN asset_depreciation ad ON a.id = ad.assetID SET a.datum_vyrazeni = ad.datum WHERE ad.lastYear = 1;");
        $update->execute();

        $select = $this->connect->prepare("SELECT * FROM assets WHERE userID = ? ORDER BY id DESC");
        $select->bind_param('i', $this->userID);
        $select->execute();
        $result = $select->get_result();

        while ($row = mysqli_fetch_array($result)) {
            ?>
            <tr>
                <?php
                ?>
                <input type="hidden" class="asset_id" name="asset_id" value="<?php echo $row['id']; ?>">
                <td>
                    <?php echo date("d.m.Y", strtotime($row['datum'])); ?>
                </td>
                <td>
                    <?php echo ($row['datum_vyrazeni'] != null) ? date("d.m.Y", strtotime($row['datum_vyrazeni'])) : null; ?>
                </td>
                <?php $document = $this->GenerateDocument($row, "uhrada", "prijemvydaj", "Hotovost", "Příjem");
                echo '<td>' . $document . '</td>'; ?>
                <td class="label_info">
                    <?php
                    if ($row["castka"] < 80000) {
                        echo "Nehmotný majetek: " . $this->secure($row['nazev']);
                    } else {
                        echo $this->secure($row['nazev']);
                    } ?>
                </td>
                <td>
                    <?php echo number_format((float) $row["castka"], 2, ".", ","); ?>
                </td>
                <td>
                    <?php echo $row['typ']; ?>
                </td>
                <td>
                    <?php echo $row['odpis']; ?>
                </td>
                <td>
                    <?php echo $row['uhrada']; ?>
                </td>
                <td>
                    <?php echo $row['zpusob']; ?>
                </td>
                <td>
                    <?php echo $row['dan']; ?>
                </td>
                <td>
                    <?php echo $this->secure($row['popis']); ?>
                </td>
                <td class="buttons">
                    <form action="edit3.php" method="post" style="display:inline-block;">
                        <input type="hidden" name="ide3" value="<?php echo $row['id']; ?>">
                        <input type="submit" class="btn btn-primary btn-sm edit-button" value="Upravit" name="update4">
                    </form>

                    <form action="" method="post" style="display:inline-block;"
                        onsubmit="return confirm('Smazat položku <?php echo $row['nazev']; ?>?')">
                        <input type="hidden" name="idd3" value="<?php echo $row['id']; ?>">
                        <input type="hidden" name="nazev" value="<?php echo $row['nazev']; ?>">
                        <input type="submit" class="btn btn-danger btn-sm delete-button" value="Smazat" name="delete3" <?php echo (date('Y-m-d', strtotime($row['datum'])) < date('2023-12-31') || $row['datum_vyrazeni'] != null) ? "disabled" : "" ?>>
                    </form>

                    <button type="button" name="asset_elimination_button" class="btn btn-secondary btn-sm dropdown-toggle"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" <?php echo ($row['datum_vyrazeni'] != null) ? "disabled" : "" ?>>
                        Vyřadit
                    </button>
                    <div class="dropdown-menu">
                        <form action="" method="post">
                            <input type="hidden" name="rowIDeliminate" value="<?php echo $row['id']; ?>">
                            <input type="hidden" name="action" id="selectedAction" value="">
                            <input type="submit" name="likvidace" class="dropdown-item" value="Likvidace">
                        </form>
                        <input type="submit" id="openSale" class="dropdown-item prodej" value="Prodej" data-toggle="modal"
                            data-target="#sale">
                    </div>

                    <button class="btn btn-info btn-sm asset_depreciation" data-toggle="modal"
                        data-target="#infoModal">Odpisy</button>
                </td>
            </tr>
            <?php
            if (isset($_POST['ulozitProdej'])) {
                $this->dbHelper->InsertAsIncomeExpense($row['nazev'], $_POST['datumPost'], "Příjem", $_POST['castkaPost'], $row['dan'], $row['uhrada'], $row['popis'], $row['userID'], $row['id']);
                $_SESSION['successAL'] = "Prodej položky úspěšně uložen jako příjem.";
            }
        }
    }
    //unused
    /*public function ModalBodyAsset()
    {
        $assetID = $_POST['id_info'];
        executeDepreciation($this->connect, $this->assetID, $this->userID);
        $odpisyDb = findDepreciation($this->connect, $this->userID);

        $select = "SELECT * FROM assets_depreciation WHERE assetID=?";
        $select = $this->connect->bind_param('i', $assetID);
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

            $output .= '<tr>' . '<td>' . $line['yearOfDepreciation'] . '</td>' . '<td>' . $line['castka'] . '</td>' . '<td>' . $line['zbyva'] . '</td>' . '<td>' . $line['datum'] . '</td>' . '</tr>';

            $output .= "</table>";

            echo $output;
        }
    }*/
    public function ModalBodyAdd()
    {
        ?>
        <div class="default-field">
            <table class="table" id="default-table">
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Datum zařazení</label>
                    <div class="col-sm-8">
                        <input class="form-control" type="date" name="datum" max="<?php echo date('Y-m-d'); ?>" required="">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Název</label>
                    <div class="col-sm-8">
                        <input class="form-control" type="text" name="nazev" required="">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Číslo dokladu</label>
                    <div class="col-sm-8">
                        <input class="form-control" type="number" min="0" name="doklad" required="">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Pořizovací cena</label>
                    <div class="col-sm-8">
                        <input class="form-control" type="number" min="0" name="castka1" required="">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Druh úhrady</label>
                    <div class="col-sm-8">
                        <select name="uhrada" class="form-control" required="">
                            <option value="">--Vybrat--</option>
                            <option value="Banka">Banka</option>
                            <option value="Hotovost">Hotovost</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Typ majetku</label>
                    <div class="col-sm-8">
                        <select name="typ1" class="form-control" required="">
                            <option value="">--Vybrat--</option>
                            <option value="Hmotný">Hmotný</option>
                            <option value="Nehmotný">Nehmotný</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Odpisová skupina</label>
                    <div class="col-sm-8">
                        <select name="odpis" class="form-control" required="">
                            <option value="">--Vybrat--</option>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                            <option value="4">4</option>
                            <option value="5">5</option>
                            <option value="6">6</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Způsob odpisu</label>
                    <div class="col-sm-8">
                        <select name="zpusob" class="form-control" required="">
                            <option value="">--Vybrat--</option>
                            <option value="Rovnoměrný">Rovnoměrný</option>
                            <option value="Zrychlený">Zrychlený</option>
                        </select>
                    </div>
                </div>
                <!--<div class="form-group row">
                    <label class="col-sm-4 col-form-label">Daňová uznatelnost</label>
                    <div class="col-sm-8">
                        <select name="dan" class="form-control" required="">
                            <option value="">--Vybrat--</option>
                            <option value="Ano">Ano</option>
                            <option value="Ne">Ne</option>
                        </select>
                    </div>
                </div>-->
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Popis</label>
                    <div class="col-sm-8">
                        <input class="form-control" type="text" name="popis">
                    </div>
                </div>
            </table>
        </div>
        <?php
        $this->appLogic->executeDepreciation($this->userID);
    }
    public function AddAsset()
    {
        ?>
        <div class="modal fade" id="addAssetModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Přidat dlouhodobý majetek</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <form class="default-form" id="default-form" method="post" action="">
                        <div class="modal-body">
                            <?php
                            $this->ModalBodyAdd();
                            ?>
                        </div>
                        <div class="modal-footer">
                            <input class="btn btn-success" type="submit" name="ulozit3" id="ulozitButton" value="Uložit">
                            <!--<input class="btn btn-primary" type="submit" name="ulozit3adalsi" id="ulozitADalsiButton"
                                value="Uložit a další">-->
                            <input class="btn btn-danger" type="submit" data-dismiss="modal" name="zavrit" id="zavrit"
                                value="Zavřít">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php
    }
    public function EditAsset()
    {
        $row = $this->dbHelper->GetTableRow('assets');
        ?>
        <div class="container">
            <form class="default-form" id="default-form" method="post" action="">
                <h3>Upravit položku "<?php echo $row['nazev'] ?>"
                </h3>
                <div class="default-field">
                    <table class="table" id="default-table">
                        <div class="form-group row">
                            <label for="cislopolozky" class="col-sm-4 col-form-label">Číslo položky</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" name="doklad" required=""
                                    value="<?php echo $row['doklad']; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="nazev" class="col-sm-4 col-form-label">Název</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" name="nazev" required=""
                                    value="<?php echo $row['nazev']; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="castka" class="col-sm-4 col-form-label">Pořizovací cena</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="number" min="0" name="castka" required=""
                                    value="<?php echo $row['castka']; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="typ" class="col-sm-4 col-form-label">Typ majetku</label>
                            <div class="col-sm-8">
                                <select name="typ" class="form-control" required="">
                                    <option value="">--Vybrat--</option>
                                    <option value="Hmotný" <?php echo ($row['typ'] == 'Hmotný') ? "selected" : ""; ?>>
                                        Hmotný</option>
                                    <option value="Nehmotný" <?php echo ($row['typ'] == 'Nehmotný') ? "selected" : ""; ?>>
                                        Nehmotný</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="datum" class="col-sm-4 col-form-label">Datum zařazení</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="date" name="datum" required=""
                                    value="<?php echo $row['datum']; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="odpis" class="col-sm-4 col-form-label">Odpisová skupina</label>
                            <div class="col-sm-8">
                                <select name="odpis" class="form-control" required="">
                                    <option value="">--Vybrat--</option>
                                    <?php
                                    for ($i = 1; $i <= 6; $i++) {
                                        echo '<option value="' . $i . '"';
                                        echo ($row['odpis'] == $i) ? "selected" : "";
                                        echo '>' . $i . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="zpusob" class="col-sm-4 col-form-label">Způsob odpisu</label>
                            <div class="col-sm-8">
                                <select name="zpusob" class="form-control" required="">
                                    <option value="">--Vybrat--</option>
                                    <option value="Rovnoměrný" <?php echo ($row['zpusob'] == 'Rovnoměrný') ? "selected" : ""; ?>>
                                        Rovnoměrný</option>
                                    <option value="Zrychlený" <?php echo ($row['zpusob'] == 'Zrychlený') ? "selected" : ""; ?>>
                                        Zrychlený</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="dan" class="col-sm-4 col-form-label">Daňová uznatelnost</label>
                            <div class="col-sm-8">
                                <select name="dan" class="form-control" required="">
                                    <option value="">--Vybrat--</option>
                                    <option value="Ano" <?php echo ($row['dan'] == 'Ano') ? "selected" : ""; ?>>Ano</option>
                                    <option value="Ne" <?php echo ($row['dan'] == 'Ne') ? "selected" : ""; ?>>Ne</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="popis" class="col-sm-4 col-form-label">Popis</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" name="popis" value="<?php echo $row['popis']; ?>">
                            </div>
                        </div>
                    </table>
                </div>
                <div class="text-center">
                    <input type="hidden" name="id" value="<?php echo $row['id'] ?>">
                    <button type="submit" name="update5" class="btn btn-success">Uložit</button>
                    <a href="majetek_dlouhodoby.php" class="btn btn-danger">Zrušit</a>
                </div>
            </form>
        </div>
        <?php
        $this->RestrictEditAsset();
    }
    public function RenderBodyContent()
    {
        ?>
        <div class="universal-table">
            <?php
            $alert = $this->alert->Alert();
            echo $alert;

            $this->RenderButtonAdd();
            $this->RenderTitle();
            ?>
            <div class="table-container">
                <script src="js/sort.js"></script>
                <table id="assets" class="table table-striped table-hover table-sortable">
                    <thead>
                        <?php
                        $this->TableHead();
                        ?>
                    </thead>
                    <tbody>
                        <?php
                        $this->TableBody();
                        ?>
                    </tbody>
                </table>
            </div>
            <div class="modal fade" id="infoModal" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="showAssetTableTitle"></h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>
                        <div class="modal-body">
                            <div class="show-depreciation">
                                <?php
                                //$this->ModalBodyAsset();
                                ?>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-dismiss="modal">Zavřít</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="sale" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title">Prodej</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                            <?php
                            //$_SESSION['successSale'] = "Položka úspěšně vyřazena.";
                            ?>
                        </div>
                        <form class="default-form" id="default-form" method="post" action="">
                            <div class="modal-body">
                                <div class="show-asset-sale">
                                    <?php
                                    $this->ModalBodySale();
                                    ?>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <input class="btn btn-success" type="submit" name="ulozitProdej" id="ulozitProdej"
                                    value="Uložit">
                                <input class="btn btn-danger" type="submit" data-dismiss="modal" name="zavrit" id="zavrit"
                                    value="Zavřít">
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php
        $this->AddAsset();
    }
    public function ModalBodySale()
    {
        ?>
        <div class="default-field">
            <table class="table" id="default-table">
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Datum uhrazení</label>
                    <div class="col-sm-8">
                        <input class="form-control" type="date" id="datum" name="datumPost" max="<?php echo date('Y-m-d'); ?>"
                            required="">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Částka obdržena</label>
                    <div class="col-sm-8">
                        <input class="form-control" type="number" min="0" name="castkaPost" required="">
                    </div>
                </div>
            </table>
        </div>
        <?php
    }
    //unused
    public function RestrictAddAsset()
    {
        ?>
        <script>
            document.addEventListener('DOMContentLoaded', function () {

                var castkaInput = document.querySelector('input[name="castka1"]');
                var typInput = document.querySelector('select[name="typ"]');
                var errorMessage = document.querySelector('#error-message');
                var saveButton = document.getElementById('ulozitButton');
                var saveAndNextButton = document.getElementById('ulozitADalsiButton');

                function UpdateFields() {
                    if (castkaInput.value < 80000 && typInput.value === 'Hmotný') {
                        saveButton.disabled = true;
                        saveAndNextButton.disabled = true;
                        //console.log(castkaInput.value);
                    } else {
                        saveButton.disabled = false;
                        saveAndNextButton.disabled = false;
                        //console.log(castkaInput.value);
                    }
                }

                if (castkaInput && typInput) {
                    castkaInput.addEventListener('input', UpdateFields);
                    typInput.addEventListener('change', UpdateFields);
                }
            });
        </script>
        <?php
    }
    //unused
    public function RestrictEditAsset()
    {
        ?>
        <script>
            window.onload = function () {
                var castka = document.querySelector('input[name="castka"]');
                var datum = document.querySelector('input[name="datum"]');
                var odpis = document.querySelector('select[name="odpis"]');
                var zpusob = document.querySelector('select[name="zpusob"]');
                var typ = document.querySelector('select[name="typ"]');

                disableFields();

                var buttonName = document.querySelector('button[name="update5"]');
                buttonName.addEventListener('click', function () {
                    enableFields();
                });

                function disableFields() {
                    castka.disabled = true;
                    datum.disabled = true;
                    odpis.disabled = true;
                    zpusob.disabled = true;
                    typ.disabled = true;
                }
                function enableFields() {
                    castka.disabled = false;
                    datum.disabled = false;
                    odpis.disabled = false;
                    zpusob.disabled = false;
                    typ.disabled = false;
                }
            };
        </script>
        <?php
    }
    public function Render()
    {
        if (strpos($_SERVER['REQUEST_URI'], 'edit3.php') !== false) {
            $this->EditAsset();
        } else if (strpos($_SERVER['REQUEST_URI'], 'majetek_dlouhodoby.php') !== false) {
            $this->dbHelper->SubmitInsertData('ulozit3');
            $this->dbHelper->SubmitDelete('assets');
            $this->RenderBodyContent();
        }
    }
    public function RenderHTML()
    {
        $this->dbHelper->SubmitEdit('assets');

        ?>
        <!DOCTYPE html>
        <html>

        <head>
            <?php
            $this->head->RenderHead();
            ?>
        </head>
        <header>
            <?php
            $this->header->RenderHeader();
            ?>
        </header>

        <body>
            <?php
            $this->Render();
            ?>
            <script src="js/showDepreciation.js"></script>
            <script src="js/getAssetIDSale.js"></script>
            <script src="js/restrictAddAsset.js"></script>
            <script src="js/restrictEditAsset.js"></script>
        </body>

        </html>
        <?php
    }
}