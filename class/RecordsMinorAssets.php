<?php

require_once 'class/Records.php';
class RecordsMinorAssets extends Records
{
    protected $connect;
    protected $userData;
    protected $userID;
    protected $pageTitle;
    protected $title;
    protected $head;
    protected $header;
    protected $dbHelper;
    protected $rie;
    protected $items = ["Datum zařazení", "Číslo dokladu", "Název", "Částka", "Způsob platby", "Popis"];
    public function __construct($connect, $pageTitle, $title)
    {
        $this->auth = new Authenticator($connect, $title);
        $this->userData = $this->auth->Check();
        $this->userID = $this->userData['id'];
        $this->head = new Head($title);
        $this->header = new Header($connect, $this->userData, $this->userID);
        $this->alert = new Alert();
        $this->dbHelper = new DatabaseHelper($connect, $this->userID);
        $this->rie = new RecordsIncomeExpense($connect, "Drobný majetek", "Daňová evidence");
        $this->connect = $connect;
        $this->pageTitle = $pageTitle;
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
    public function TableHead()
    {
        ?>
        <tr>
            <?php
            for ($i = 0; $i < count($this->items); $i++) { ?>
                <th onclick="sort('minorassets', <?php echo $i ?>, 1)">
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
        $hiddenSlot = 'Drobný majetek';
        $select = $this->connect->prepare("SELECT * FROM incomeexpense WHERE userID = ? AND hiddenSlot=? ORDER BY datum DESC");
        $select->bind_param('is', $userID, $hiddenSlot);
        $select->execute();
        $result = $select->get_result();
        while ($row = mysqli_fetch_array($result)) {
            ?>
            <tr>
                <td>
                    <?php echo date("d.m.Y", strtotime($row['datum'])); ?>
                </td>
                <?php $document = $this->GenerateDocument($row, "uhrada", "prijemvydaj", "Hotovost", "Příjem");
                echo '<td>' . $document . '</td>'; ?>
                <td>
                    <?php echo $this->secure($row['nazev']); ?>
                </td>
                <td>
                    <?php echo $this->secure($row['castka']); ?>
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
                        <input class="btn btn-primary btn-sm" type="submit" name="update0" value="Upravit">
                    </form>
                    <form action="" method="post" style="display:inline-block;"
                        onsubmit="return confirm('Smazat položku <?php echo $row['nazev']; ?>?')">
                        <input type="hidden" name="idd" value="<?php echo $row['id']; ?>">
                        <input type="hidden" name="nazev" value="<?php echo $row['nazev']; ?>">
                        <input class="btn btn-danger btn-sm" type="submit" name="delete0" value="Smazat">
                    </form>
                </td>
            </tr>
            <?php
        }
    }
    public function RenderBodyContent()
    {
        ?>
        <div class="universal-table">
            <?php
            $alert = $this->alert->Alert();
            echo $alert;

            $this->rie->RenderButtonAdd();
            $this->RenderTitle();
            ?>
            <div class="table-container">
                <script src="js/sort.js"></script>
                <table id="minorassets" class="table table-striped table-hover">
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
        </div>
        <?php
        $this->rie->AddIncomeExpense();
        //$this->RestrictAddAssetMinor();
    }
    /*public function RestrictAddAssetMinor()
    {
        ?>
        <script>
            window.onload = function () {
                var castkaInput = document.querySelector('input[name="castka"]');
                var errorMessage = document.querySelector('#error-message');

                if (castkaInput.value >= 80000) {
                    disableFields();
                }
                var saveButton = document.querySelector('button[name=ulozitButton]');
                saveButton.addEventListener('click', function () {
                    enableFields();
                });

                function disableFields() {
                    saveButton.disabled = true;
                }
                function enableFields() {
                    saveButton.disabled = false;
                }
            };
        </script>
        <?php
    }*/
    public function Render()
    {
        if (strpos($_SERVER['REQUEST_URI'], 'edit1.php') !== false) {
            $this->rie->EditIncomeExpense();
        } else if (strpos($_SERVER['REQUEST_URI'], 'majetek_drobny.php') !== false) {
            $this->dbHelper->SubmitInsertData('ulozit1');
            $this->dbHelper->SubmitDelete('incomeexpense');
            $this->RenderBodyContent();
        }
    }
    public function RenderHTML()
    {
        $this->dbHelper->SubmitEdit('minorassets');
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