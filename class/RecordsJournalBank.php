<?php

require_once 'class/RecordsJournal.php';
class RecordsJournalBank extends RecordsJournal
{
    public function __construct($connect, $pageTitle, $title)
    {
        $this->auth = new Authenticator($connect, $title);
        $this->userData = $this->auth->Check();
        $this->userID = $this->userData['id'];
        $this->head = new Head($title);
        $this->header = new Header($connect, $this->userData, $this->userID);
        $this->connect = $connect;
        $this->pageTitle = $pageTitle;
    }

    public function TableHead($start, $end)
    {
        ?>
        <tr>
            <form method="post">
                <th><input class="form-control" type="date" name="start" required="" value='<?php echo $start; ?>'></th>
                <th><input class="form-control" type="date" name="end" required="" value='<?php echo $end; ?>'></th>
                <th><input type="submit" class='btn btn-success' name="submit" value="Potvrdit" /></th>
            </form>
            <?php
            for ($i = 0; $i < 4; $i++) { // if an extention of columns is needed
                echo "<th></th>";
            }
            ?>
        </tr>
        <tr>
            <th>Datum</th>
            <th>Název</th>
            <th>Doklad</th>
            <th colspan="2">Příjmy</th>
            <th colspan="2">Výdaje</th>
        </tr>
        <tr>
            <th></th>
            <th></th>
            <th></th>
            <th>Daňové</th>
            <th>Nedaňové</th>
            <th>Daňové</th>
            <th>Nedaňové</th>
        </tr>
    <?
    }
    public function TableBody($start, $end)
    {
        $userID = $this->userData['id'];

        if (isset($_POST['submit'])) {

            $select = $this->connect->prepare("(SELECT id, userID, datum, castka, doklad, nazev, prijemvydaj, dan, popis, uhrada, hiddenSlot 
        FROM incomeexpense
        WHERE userID = ? AND datum >= ? AND datum <= ? AND uhrada='Banka')
        UNION 
        (SELECT id, userID, datum, castka, doklad, nazev, prijemvydaj, dan, popis, uhrada, hiddenSlot 
        FROM asset_depreciation
        WHERE userID = ? AND datum >= ? AND datum <= ? AND uhrada='Banka')
        ORDER BY datum");
            $select->bind_param('ississ', $userID, $start, $end, $userID, $start, $end);
            $select->execute();
            $result = $select->get_result();

            while ($row = mysqli_fetch_array($result)) {
                ?>
                <tr class="info-row1" data-id='<?php echo $row['id'] ?>' data-name="<?php echo $row['nazev'] ?>">
                    <td>
                        <?= date("d.m.Y", strtotime($row['datum'])); ?>
                    </td>
                    <td>
                        <?php
                        if ($row['hiddenSlot'] == "Vyřazení") {
                            echo "Vyřazení: " . $this->secure($row['nazev']);
                        } else if ($row['hiddenSlot'] == "Odpis") {
                            echo "Odpis: " . $this->secure($row['nazev']);
                        } else if ($row['hiddenSlot'] == "Drobný majetek") {
                            echo "Drobný majetek: " . $this->secure($row['nazev']);
                        } else {
                            echo $this->secure($row['nazev']);
                        } ?>
                    </td>
                    <?php $document = $this->GenerateDocument($row, "uhrada", "prijemvydaj", "Hotovost", "Příjem");
                    echo '<td>' . $document . '</td>'; ?>
                    <td style="color: <?= $this->getColor($row['prijemvydaj'], 'Příjem', 'Výdaj') ?>;">
                        <?php
                        if ($row['prijemvydaj'] == 'Příjem') {
                            if ($row['dan'] == 'Ano') {
                                echo number_format((float) $row['castka'], 2, ".", ",");
                                $this->sumA += $row['castka'];
                            }
                        }
                        ?>
                    </td>
                    <td style="color: <?= $this->getColor($row['prijemvydaj'], 'Příjem', 'Výdaj') ?>;">
                        <?php
                        if ($row['prijemvydaj'] == 'Příjem') {
                            if ($row['dan'] == 'Ne') {
                                echo number_format((float) $row['castka'], 2, ".", ",");
                                $this->sumB += $row['castka'];
                            }
                        }
                        ?>
                    </td>
                    <td style="color: <?= $this->getColor($row['prijemvydaj'], 'Příjem', 'Výdaj') ?>;">
                        <?php
                        if ($row['prijemvydaj'] == 'Výdaj') {
                            if ($row['dan'] == 'Ano') {
                                echo number_format((float) $row['castka'], 2, ".", ",");
                                $this->sumC += $row['castka'];
                            }
                        }
                        ?>
                    </td>
                    <td style="color: <?= $this->getColor($row['prijemvydaj'], 'Příjem', 'Výdaj') ?>;">
                        <?php
                        if ($row['prijemvydaj'] == 'Výdaj') {
                            if ($row['dan'] == 'Ne') {
                                echo number_format((float) $row['castka'], 2, ".", ",");
                                $this->sumD += $row['castka'];
                            }
                        }
                        ?>
                    </td>
                </tr>

                <?php
            }
        }
    }
    public function TableFooter()
    {
        ?>
        <tr>
            <td>Celkem</td>
            <td></td>
            <td></td>
            <td>
                <?= number_format((float) $this->sumA, 2, ".", ","); ?>
            </td>
            <td>
                <?= number_format((float) $this->sumB, 2, ".", ","); ?>
            </td>
            <td>
                <?= number_format((float) $this->sumC, 2, ".", ","); ?>
            </td>
            <td>
                <?= number_format((float) $this->sumD, 2, ".", ","); ?>
            </td>
        </tr>
        <tr>
            <td>Daňový základ</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>
                <?= number_format((float) $this->sumA - $this->sumC, 2, ".", ","); ?>
            </td>
            <td></td>
        </tr>
        <?php
    }
    public function RenderBodyContent()
    {
        ?>
        <div class="universal-table">
            <h3>
                <?php echo $this->pageTitle; ?>
            </h3>
            <div class="table-container">
                <?php
                if (isset($_POST['start'])) {
                    $start = date('Y-m-d', strtotime($_POST['start']));
                    $end = date('Y-m-d', strtotime($_POST['end']));
                } else {
                    $start = date('Y-m-d');
                    $end = date('Y-m-d');
                }
                ?>
                <table id="journal" class="table table-striped table-hover table-sortable">
                    <thead>
                        <?php
                        $this->TableHead($start, $end);
                        ?>
                    </thead>
                    <tbody>
                        <?php
                        $this->TableBody($start, $end);
                        ?>
                    </tbody>
                    <tfoot>
                        <?php
                        $this->TableFooter();
                        ?>
                    </tfoot>
                </table>
            </div>
        </div>
        <?php
    }
    public function RenderHTML()
    {
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
            $this->RenderBodyContent();
            ?>
        </body>

        </html>
        <?php
    }
}