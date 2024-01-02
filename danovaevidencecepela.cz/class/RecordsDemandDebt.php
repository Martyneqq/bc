<?php

require_once 'class/Records.php';
class RecordsDemandDebt extends Records
{
    protected $connect;
    protected $pageTitle;
    protected $dbHelper;
    protected $alert;
    protected $sumDemand;
    protected $sumDebt;
    protected $head;
    protected $header;
    protected $title;
    protected $auth;
    protected $convertDDtoIE = ['Pohledávka'=>'Příjem', 'Závazek'=>'Výdaj'];
    protected $items = ["Datum vystavení", "Datum splatnosti", "Číslo dokladu", "Název", "Firma", "Typ", "Hodnota", "Daňová uznatelnost", "Způsob platby", "Uhrazeno", "Popis"];
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
    }
    public function GetHead()
    {
        return $this->head;
    }
    public function GetHeader()
    {
        return $this->header;
    }
    public function RenderTitle()
    {
        ?>
        <div class="row">
            <div class="col text-center">
                <h3>
                    <?php echo $this->pageTitle ?>
                </h3>
            </div>
        </div>
        <?php
    }
    public function RenderButtonAdd()
    {
        ?>
        <button type="button" id="openAddClaimDebtModal" class="btn btn-success" data-toggle="modal"
            data-target="#addClaimDebtModal">Přidat
        </button>
        <?php
    }
    public function TableHead()
    {
        ?>
        <tr>
            <?php
            for ($i = 0; $i < count($this->items); $i++) { ?>
                <th onclick="sort('demanddebt', <?php echo $i ?>, 3)">
                    <?php echo $this->items[$i] ?>
                </th>
            <?php } ?>
            <th>Úpravy</th>
        </tr>
        <?php
    }
    public function TableBody()
    {
        $userID = $this->userData['id'];
        $select = $this->connect->prepare("SELECT * FROM demanddebt WHERE userID = ? ORDER BY id DESC");
        $select->bind_param('s', $userID);
        $select->execute();
        $result = $select->get_result();
        while ($row = mysqli_fetch_array($result)) {
            if($row['uhrazeno']=="Ano"){
                $mappedValue = $this->convertDDtoIE[$row['pohledavkadluh']];
                $this->dbHelper->InsertAsIncomeExpense($row['nazev'], $row['datum'], $mappedValue, $row['hodnota'], $row['dan'], $row['uhrada'], $row['popis'], $row['userID'], $row['id']);
            }
            ?>
            <tr>
                <td>
                    <?php echo date("d.m.Y", strtotime($row['datum'])); ?>
                </td>
                <td>
                    <?php echo date("d.m.Y", strtotime($row['datums'])); ?>
                </td>
                    <?php $document = $this->GenerateDocument($row, "uhrada", "pohledavkadluh", "Hotovost", "Pohledávka");
                echo '<td>' . $document . '</td>'; ?>
                <td>
                    <?php echo $this->secure($row['nazev']); ?>
                </td>
                <td>
                    <?php echo $this->secure($row['firma']); ?>
                </td>
                <td>
                    <?php echo $this->secure($row['pohledavkadluh']); ?>
                </td>
                <td>
                    <?php
                    echo number_format((float) $row['hodnota'], 2, ".", ",");
                    ($row['pohledavkadluh'] == 'Pohledávka') ? $this->sumDemand += $row['hodnota'] : $this->sumDebt += $row['hodnota'];
                    ?>
                </td>
                <td>
                    <?php echo $row['dan']; ?>
                </td>
                <td>
                    <?php echo $row['uhrada']; ?>
                </td>
                <td style="color: <?= $this->getColor($row['uhrazeno'], 'Ano', 'Ne') ?>;">
                    <?php echo $row['uhrazeno']; ?>
                </td>
                <td>
                    <?php echo $this->secure($row['popis']); ?>
                </td>
                <td class="buttons">
                    <form action="edit2.php" method="post" style="display:inline-block;">
                        <input type="hidden" name="ide2" value="<?php echo $row['id']; ?>">
                        <input class="btn btn-primary btn-sm" type="submit" name="update2" value="Upravit" <?php echo ($row['uhrazeno']=="Ano") ? "disabled" : "" ?>>
                    </form>
                    <form action="" method="post" style="display:inline-block;"
                        onsubmit="return confirm('Smazat položku <?php echo $row['nazev']; ?>?')">
                        <input type="hidden" name="idd2" value="<?php echo $row['id']; ?>">
                        <input type="hidden" name="nazev" value="<?php echo $row['nazev']; ?>">
                        <input class="btn btn-danger btn-sm" type="submit" name="delete2" value="Smazat" <?php echo ($row['uhrazeno']=="Ano") ? "disabled" : "" ?>>
                    </form>
                </td>
            </tr>
            <?php
        }
    }
    public function ModalBodyAdd()
    {
        ?>
        <div class="default-field">
            <table class="table" id="default-table">
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Datum vystavení</label>
                    <div class="col-sm-8">
                        <input class="form-control" type="date" name="datum" max="<?php echo date('Y-m-d'); ?>"  required="">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Datum splatnosti</label>
                    <div class="col-sm-8">
                        <input class="form-control" type="date" name="datums" required="">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Název</label>
                    <div class="col-sm-8">
                        <input class="form-control" type="text" id="nazev" name="nazev" required="">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Číslo dokladu</label>
                    <div class="col-sm-8">
                        <input class="form-control" type="number" min="0" name="doklad" required="">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Firma</label>
                    <div class="col-sm-8">
                        <input class="form-control" type="text" name="firma" required="">
                    </div>
                </div>

                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Typ</label>
                    <div class="col-sm-8">
                        <select name="pohledavkadluh" class="form-control" required="">
                            <option value="">--Vybrat--</option>
                            <option value="Pohledávka">Pohledávka</option>
                            <option value="Závazek">Závazek</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Hodnota</label>
                    <div class="col-sm-8">
                        <input class="form-control" type="number" min="0" name="hodnota" required="">
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Daňová uznatelnost</label>
                    <div class="col-sm-8">
                        <select name="dan" class="form-control" required="">
                            <option value="">--Vybrat--</option>
                            <option value="Ano">Ano</option>
                            <option value="Ne">Ne</option>
                        </select>
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
                    <label class="col-sm-4 col-form-label">Částka uhrazena?</label>
                    <div class="col-sm-8">
                        <select name="uhrazeno" class="form-control" required="">
                            <option value="">--Vybrat--</option>
                            <option value="Ano">Ano</option>
                            <option value="Ne">Ne</option>
                        </select>
                    </div>
                </div>
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Popis</label>
                    <div class="col-sm-8">
                        <input class="form-control" type="text" name="popis">
                    </div>
                </div>
            </table>
        </div>
        <?php
    }
    public function EditDemandDebt()
    {
        $row = $this->dbHelper->GetTableRow('demanddebt');
        ?>
        <div class="container">
            <form class="default-form" id="default-form" method="post" action="">
                <h3>Upravit položku "<?php echo $row['nazev'] ?>"
                </h3>
                <div class="default-field">
                    <table class="table" id="default-table">
                        <div class="form-group row">
                            <label for="nazev" class="col-sm-4 col-form-label">Název</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" name="nazev" required=""
                                    value="<?php echo $row['nazev']; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="doklad" class="col-sm-4 col-form-label">Číslo dokladu</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" name="doklad" required=""
                                    value="<?php echo $row['doklad']; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="firma" class="col-sm-4 col-form-label">Firma</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" name="firma" required=""
                                    value="<?php echo $row['firma']; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="datum" class="col-sm-4 col-form-label">Datum vystavení</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="date" name="datum" required=""
                                    value="<?php echo $row['datum']; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="datums" class="col-sm-4 col-form-label">Datum splatnosti</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="date" name="datums" required=""
                                    value="<?php echo $row['datums']; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="pohledavkadluh" class="col-sm-4 col-form-label">Typ</label>
                            <div class="col-sm-8">
                                <select name="pohledavkadluh" class="form-control" required="">
                                    <option value="">--Vybrat--</option>
                                    <option value="Pohledávka" <?php echo ($row['pohledavkadluh'] == 'Pohledávka') ? "selected" : ""; ?>>Pohledávka</option>
                                    <option value="Závazek" <?php echo ($row['pohledavkadluh'] == 'Závazek') ? "selected" : ""; ?>>
                                        Závazek
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="hodnota" class="col-sm-4 col-form-label">Hodnota</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="number" min="0" name="hodnota" required=""
                                    value="<?php echo $row['hodnota']; ?>">
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
                            <label for="uhradaUpdate" class="col-sm-4 col-form-label">Druh úhrady</label>
                            <div class="col-sm-8">
                                <select name="uhrada" class="form-control" required="">
                                    <option value="">--Vybrat--</option>
                                    <option value="Banka" <?php echo ($row['uhrada'] == 'Banka') ? "selected" : ""; ?>>Banka
                                    </option>
                                    <option value="Hotovost" <?php echo ($row['uhrada'] == 'Hotovost') ? "selected" : ""; ?>>
                                        Hotovost</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="uhrazeno" class="col-sm-4 col-form-label">Částka uhrazena?</label>
                            <div class="col-sm-8">
                                <select name="uhrazeno" class="form-control" required="">
                                    <option value="">--Vybrat--</option>
                                    <option value="Ano" <?php echo ($row['uhrazeno'] == 'Ano') ? "selected" : ""; ?>>Ano</option>
                                    <option value="Ne" <?php echo ($row['uhrazeno'] == 'Ne') ? "selected" : ""; ?>>Ne</option>
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
                    <button type="submit" name="update3" class="btn btn-success">Uložit</button>
                    <a href="evidence_pohledavky_a_dluhy.php" class="btn btn-danger">Zrušit</a>
                </div>
            </form>
        </div>
        <?php
    }
    public function AddDemandDebt()
    {
        ?>
        <div class="modal fade" id="addClaimDebtModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Přidat pohledávku nebo závazek</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <form class="default-form" id="default-form" method="post" action="">
                        <div class="modal-body">
                            <?php
                            $this->ModalBodyAdd();
                            ?>
                        </div>
                        <div class="modal-footer">
                            <input class="btn btn-success" type="submit" name="ulozit2" value="Uložit">
                            <!--<input class="btn btn-primary" type="submit" name="ulozit2adalsi" value="Uložit a další">-->
                            <input type="submit" class="btn btn-danger" data-dismiss="modal" value="Zavřít">
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <?php
    }
    public function TableFooter()
    {
        ?>
        <tr>
            <td>Pohledávky celkem</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>
                <?php echo number_format((float) $this->sumDemand, 2, ".", ","); ?>
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <tr>
            <td>Závazky celkem</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>
                <?php echo number_format((float) $this->sumDebt, 2, ".", ","); ?>
            </td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <?php
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
                <table id="demanddebt" class="table table-striped table-hover">
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
                    <tfoot>
                        <?php
                        $this->TableFooter();
                        ?>
                    </tfoot>
                </table>
            </div>
            <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
            <?php
            $this->AddDemandDebt();
            ?>
        </div>
        <?php
    }
    public function Render()
    {
        if (strpos($_SERVER['REQUEST_URI'], 'edit2.php') !== false) {
            $this->EditDemandDebt();
        } else if (strpos($_SERVER['REQUEST_URI'], 'evidence_pohledavky_a_dluhy.php') !== false) {
            $this->dbHelper->SubmitInsertData('ulozit2');
            $this->dbHelper->SubmitDelete('demanddebt');
            $this->RenderBodyContent();
        }
    }
    public function RenderHTML()
    {
        $this->dbHelper->SubmitEdit('demanddebt');
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
        </body>

        </html>
        <?php
    }
}