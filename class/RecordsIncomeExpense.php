<?php

require_once 'class/Records.php';

class RecordsIncomeExpense extends Records
{
    protected $connect;
    protected $userData;
    protected $userID;
    protected $pageTitle;
    protected $dbHelper;
    protected $alert;
    protected $head;
    protected $header;
    protected $title;
    protected $auth;
    protected $sumA = 0.0;
    protected $sumB = 0.0;
    protected $sumC = 0.0;
    protected $sumD = 0.0;
    protected $items = ["Datum uhrazení", "Číslo dokladu", "Název", "Příjem nebo výdaj", "Daňové příjmy", "Nedaňové příjmy", "Daňové výdaje", "Nedaňové výdaje", "Způsob platby", "Popis"];
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
    public function TableHead()
    {
        ?>
        <tr>
            <?php
            for ($i = 0; $i < count($this->items); $i++) { ?>
                <th onclick="sort('incomeexpense', <?php echo $i ?>, 2)">
                    <?php echo $this->items[$i] ?>
                </th>
            <?php } ?>
            <th>Úpravy</th>
        </tr>
        <?php
    }
    public function TableBody()
    {
        /*$update = $this->connect->prepare("UPDATE incomeexpense ie INNER JOIN assets a ON ie.id = a.id SET ie.dan = a.dan SET ie.nazve WHERE ad.lastYear = 1;");
        $update->execute();*/

        $select = $this->connect->prepare("SELECT id, datum, castka, doklad, nazev, prijemvydaj, dan, popis, uhrada, hiddenSlot FROM incomeexpense
        WHERE userID = ? ORDER BY id DESC");
        $select->bind_param('i', $this->userID);
        $select->execute();
        $result = $select->get_result();

        //$documentCounter = ["HP" => 0, "HV" => 0, "BP" => 0, "BV" => 0];

        while ($row = mysqli_fetch_array($result)) {
            ?>
            <tr>
                <td>
                    <?php echo date("d.m.Y", strtotime($row['datum'])); ?>
                </td>
                <?php $document = $this->GenerateDocument($row, "uhrada", "prijemvydaj", "Hotovost", "Příjem");
                echo '<td>' . $document . '</td>'; ?>
                <td>
                    <?php
                    if ($row['hiddenSlot'] == "Vyřazení") {
                        echo "Vyřazení: " . $this->secure($row['nazev']);
                    } else if ($row['hiddenSlot'] == "Odpis") {
                        echo "Dlouhodobý majetek: " . $this->secure($row['nazev']);
                    } else if ($row['hiddenSlot'] == "Drobný majetek") {
                        echo "Drobný majetek: " . $this->secure($row['nazev']);
                    } else {
                        echo $this->secure($row['nazev']);
                    } ?>
                </td>
                <td>
                    <?php echo $row['prijemvydaj']; ?>
                </td>
                <td>
                    <?php
                    if ($row['prijemvydaj'] == "Příjem" && $row['dan'] == 'Ano') {
                        echo number_format((float) $row['castka'], 2, ".", ",");
                        $this->sumA += $row['castka'];
                    }
                    ?>
                </td>
                <td>
                    <?php
                    if ($row['prijemvydaj'] == "Příjem" && $row['dan'] == 'Ne') {
                        echo number_format((float) $row['castka'], 2, ".", ",");
                        $this->sumB += $row['castka'];
                    }
                    ?>
                </td>
                <td>
                    <?php
                    if ($row['prijemvydaj'] == "Výdaj" && $row['dan'] == 'Ano') {
                        echo number_format((float) $row['castka'], 2, ".", ",");
                        $this->sumC += $row['castka'];
                    }
                    ?>
                </td>
                <td>
                    <?php
                    if ($row['prijemvydaj'] == "Výdaj" && $row['dan'] == 'Ne') {
                        echo number_format((float) $row['castka'], 2, ".", ",");
                        $this->sumD += $row['castka'];
                    }
                    ?>
                </td>
                <td>
                    <?php echo $this->secure($row['uhrada']); ?>
                </td>
                <td>
                    <?php echo $this->secure($row['popis']); ?>
                </td>
                <td class="buttons">
                    <form action="edit1.php" method="post" style="display:inline-block;">
                        <input type="hidden" name="ide" value="<?php echo $row['id']; ?>">
                        <input class="btn btn-primary btn-sm" type="submit" name="update0" value="Upravit" <?php echo ($row['hiddenSlot'] == "Odpis" || $row['hiddenSlot'] == "Vyřazení") ? "disabled" : ""; ?>>
                    </form>
                    <form action="" method="post" style="display:inline-block;"
                        onsubmit="return confirm('Smazat položku <?php echo $row['nazev']; ?>?')">
                        <input type="hidden" name="idd" value="<?php echo $row['id']; ?>">
                        <input type="hidden" name="nazev" value="<?php echo $row['nazev']; ?>">
                        <input class="btn btn-danger btn-sm" data-id="<?php echo $row['id']; ?>"
                            data-name="<?php echo $row['nazev'] ?>" type="submit" name="delete" data-dismiss="modal" value="Smazat"
                            <?php echo ($row['hiddenSlot'] == "Odpis" || $row['hiddenSlot'] == "Vyřazení") ? "disabled" : ""; ?>>
                    </form>
                </td>
            </tr>
            <?php
        }
    }
    public function TableFooter()
    {
        ?>
        <tr>
            <td>Celkem</td>
            <td></td>
            <td></td>
            <td></td>
            <td>
                <?php echo number_format((float) $this->sumA, 2, ".", ","); ?>
            </td>
            <td>
                <?php echo number_format((float) $this->sumB, 2, ".", ","); ?>
            </td>
            <td>
                <?php echo number_format((float) $this->sumC, 2, ".", ","); ?>
            </td>
            <td>
                <?php echo number_format((float) $this->sumD, 2, ".", ","); ?>
            </td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        <?php
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
        <button type="button" id="openAddIncomeExpenseModal" class="btn btn-success" data-toggle="modal"
            data-target="#addIncomeExpenseModal">Přidat
        </button>
        <?php
    }
    public function ModalBodyAdd()
    {
        ?>
        <div class="default-field">
            <table class="table" id="default-table">
                <div class="form-group row">
                    <label class="col-sm-4 col-form-label">Datum uhrazení</label>
                    <div class="col-sm-8">
                        <input class="form-control" type="date" id="datum" name="datum" max="<?php echo date('Y-m-d'); ?>"
                            required="">
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
                    <label class="col-sm-4 col-form-label">Částka</label>
                    <div class="col-sm-8">
                        <input class="form-control" type="number" min="0" name="castka" required="">
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
                    <label class="col-sm-4 col-form-label">Příjem nebo výdaj</label>
                    <div class="col-sm-8">
                        <select name="prijemvydaj" class="form-control" required="">
                            <option value="">--Vybrat--</option>
                            <option value="Příjem">Příjem</option>
                            <option value="Výdaj">Výdaj</option>
                        </select>
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
                    <label class="col-sm-4 col-form-label">Popis</label>
                    <div class="col-sm-8">
                        <input class="form-control" type="text" name="popis">
                    </div>
                </div>
            </table>
        </div>
        <?php
    }
    public function AddIncomeExpense()
    {
        ?>
        <div class="modal fade" id="addIncomeExpenseModal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Přidat příjmy nebo výdaje</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <form class="default-form" id="default-form" method="post" action="">
                        <div class="modal-body">
                            <?php
                            $this->ModalBodyAdd();
                            ?>
                        </div>
                        <div class="modal-footer">
                            <input class="btn btn-success" type="submit" name="ulozit1" id="ulozitButton" value="Uložit">
                            <!--<input class="btn btn-primary" type="submit" name="ulozit1adalsi" id="ulozitADalsiButton"
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
    //unused
    /*public function ModalTitleEdit($row)
    {
        ?>
        <h3>Upravit položku "
            <?php echo $row['nazev'] ?>"
        </h3>
        <?php
    }
    //unused
    public function ModalBodyEdit($row)
    {
        ?>
        <div class="container">
            <form class="default-form" id="default-form" method="post" action="">
                <div class="default-field">
                    <table class="table" id="default-table">
                        <div class="form-group row">
                            <label for="dokladUpdate" class="col-sm-4 col-form-label">Číslo dokladu</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" name="doklad" required=""
                                    value="<?php echo $row['doklad']; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="nazevUpdate" class="col-sm-4 col-form-label">Název</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" name="nazev" required=""
                                    value="<?php echo $row['nazev']; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="datumUpdate" class="col-sm-4 col-form-label">Datum uhrazení</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="date" name="datum" required=""
                                    value="<?php echo $row['datum']; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="prijemvydajUpdate" class="col-sm-4 col-form-label">Příjem nebo výdaj</label>
                            <div class="col-sm-8">
                                <select name="prijemvydaj" class="form-control" required="">
                                    <option value="">--Vybrat--</option>
                                    <option value="Příjem" <?php echo ($row['prijemvydaj'] == 'Příjem') ? "selected" : ""; ?>>
                                        Příjem</option>
                                    <option value="Výdaj" <?php echo ($row['prijemvydaj'] == 'Výdaj') ? "selected" : ""; ?>>
                                        Výdaj
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="castkaUpdate" class="col-sm-4 col-form-label">Částka</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="number" min="0" name="castka" required=""
                                    value="<?php echo $row['castka']; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="danUpdate" class="col-sm-4 col-form-label">Daňová uznatelnost</label>
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
                                    <option value="Z účtu" <?php echo ($row['uhrada'] == 'Z účtu') ? "selected" : ""; ?>>Z
                                        účtu
                                    </option>
                                    <option value="Hotovost" <?php echo ($row['uhrada'] == 'Hotovost') ? "selected" : ""; ?>>
                                        Hotovost</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="popisUpdate" class="col-sm-4 col-form-label">Popis</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" name="popis" value="<?php echo $row['popis']; ?>">
                                <input type="hidden" name="hiddenSlot" value="<?php echo $row['hiddenSlot']; ?>">
                            </div>
                        </div>
                    </table>
                    <div class="text-center">
                        <input type="hidden" name="id" value="<?php echo $row['id'] ?>">
                        <button type="submit" name="update1" class="btn btn-success">Uložit</button>
                        <a href="evidence_prijmy_a_vydaje.php" class="btn btn-danger">Zrušit</a>
                    </div>
                </div>
            </form>
        </div>

        <script>
            window.onload = function () {
                var uhrada = document.querySelector('select[name="uhrada"]');
                var hiddenSlot = document.querySelector('input[name="hiddenSlot"]');
                function disableFields() {
                    if (hiddenSlot.value.includes("Drobný majetek")) {
                        uhrada.disabled = true;
                    }
                }
                document.getElementById("default-form").addEventListener("submit", disableFields());
            }
        </script>
        <?php
    }
    //unused
    public function ModalFooterEdit($row)
    {
        ?>
        <div class="text-center">
            <input type="hidden" id="id" value="<?php echo $row['id'] ?>">
            <button type="submit" name="update1" class="btn btn-success">Uložit</button>
            <a href="evidence_prijmy_a_vydaje.php" class="btn btn-danger">Zrušit</a>
        </div>
        <?php
    }*/
    public function EditIncomeExpense()
    {
        $row = $this->dbHelper->GetTableRow('incomeexpense');
        ?>
        <div class="container">
            <form class="default-form" id="default-form" method="post" action="">
                <h3>Upravit položku "
                    <?php echo $row['nazev'] ?>"
                </h3>
                <div class="default-field">
                    <table class="table" id="default-table">
                        <div class="form-group row">
                            <label for="nazevUpdate" class="col-sm-4 col-form-label">Název</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" name="nazev" required=""
                                    value="<?php echo $row['nazev']; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="dokladUpdate" class="col-sm-4 col-form-label">Doklad</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" name="doklad" required=""
                                    value="<?php echo $row['doklad']; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="datumUpdate" class="col-sm-4 col-form-label">Datum uhrazení</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="date" name="datum" required=""
                                    value="<?php echo $row['datum']; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="prijemvydajUpdate" class="col-sm-4 col-form-label">Příjem nebo výdaj</label>
                            <div class="col-sm-8">
                                <select name="prijemvydaj" class="form-control" required="">
                                    <option value="">--Vybrat--</option>
                                    <option value="Příjem" <?php echo ($row['prijemvydaj'] == 'Příjem') ? "selected" : ""; ?>>
                                        Příjem</option>
                                    <option value="Výdaj" <?php echo ($row['prijemvydaj'] == 'Výdaj') ? "selected" : ""; ?>>
                                        Výdaj
                                    </option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="castkaUpdate" class="col-sm-4 col-form-label">Částka</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="number" min="0" name="castka" required=""
                                    value="<?php echo $row['castka']; ?>">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="danUpdate" class="col-sm-4 col-form-label">Daňová uznatelnost</label>
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
                            <label for="popisUpdate" class="col-sm-4 col-form-label">Popis</label>
                            <div class="col-sm-8">
                                <input class="form-control" type="text" name="popis" value="<?php echo $row['popis']; ?>">
                                <input type="hidden" name="hiddenSlot" value="<?php echo $row['hiddenSlot']; ?>">
                            </div>
                        </div>
                    </table>
                    <div class="text-center">
                        <input type="hidden" name="id" value="<?php echo $row['id'] ?>">
                        <button type="submit" name="update1" class="btn btn-success">Uložit</button>
                        <a href="evidence_prijmy_a_vydaje.php" class="btn btn-danger">Zrušit</a>
                    </div>
                </div>
            </form>
        </div>
        <?php
        //$this->UpdateTableRow();
        ?>
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <!--<script>
            window.onload = function () {
                var uhrada = document.querySelector('select[name="uhrada"]');
                var hiddenSlot = document.querySelector('input[name="hiddenSlot"]');
                function disableFields() {
                    if (hiddenSlot.value.includes("Drobný majetek")) {
                        uhrada.disabled = true;
                    }
                }
                function enableFields() {
                    uhrada.disabled = false;
                }
                disableFields();
                document.getElementById("default-form").addEventListener("submit", disableFields());
            }
        </script>-->
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
                <table id="incomeexpense" class="table table-striped table-hover">
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
            <?php
            $this->AddIncomeExpense();
            ?>
            <div class="modal fade" id="editIncomeExpenseModal" tabindex="-1" role="dialog">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <?php
                        //$row = $this->dbHelper->GetTableRow(); ?>
                        <div class="modal-header">
                            <?php
                            //$this->ModalTitleEdit($row);
                            ?>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <form class="default-form" id="default-form" method="post" action="">
                            <div class="modal-body">
                                <?php
                                //$this->ModalBodyEdit($row);
                                //$this->dbHelper->UpdateTableRow();
                                ?>
                            </div>
                            <div class="modal-footer">
                                <?php
                                //$this->ModalFooterEdit($row);
                                ?>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    public function Render()
    {
        if (strpos($_SERVER['REQUEST_URI'], 'edit1.php') !== false) {
            $this->EditIncomeExpense();
        } else if (strpos($_SERVER['REQUEST_URI'], 'evidence_prijmy_a_vydaje.php') !== false) {
            $this->dbHelper->SubmitInsertData('ulozit1');
            $this->dbHelper->SubmitDelete('incomeexpense');
            $this->RenderBodyContent();
        }
    }
    public function RenderHTML()
    {
        $this->dbHelper->SubmitEdit('incomeexpense');
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
            <script src="js/restrictAddAssetMinor.js"></script>
        </body>

        </html>
        <?php
    }
}