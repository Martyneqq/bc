<?php

require_once 'class/Records.php';
class TaxRecordsPage extends Records
{
    protected $connect;
    protected $userData;
    protected $userID;
    protected $dbHelper;
    protected $year;
    protected $pageTitle;
    protected $title;
    protected $rie;
    protected $rdd;
    protected $ra;
    protected $head;
    protected $header;
    protected $alert;
    protected $auth;
    public function __construct($connect, $year, $pageTitle, $title)
    {
        $this->auth = new Authenticator($connect, $title);
        $this->userData = $this->auth->Check();
        $this->userID = $this->userData['id'];
        $this->head = new Head($title);
        $this->header = new Header($connect, $this->userData, $this->userID);
        $this->alert = new Alert();
        $this->dbHelper = new DatabaseHelper($connect, $this->userID);
        $this->rie = new RecordsIncomeExpense($connect, $pageTitle, $title);
        $this->rdd = new RecordsDemandDebt($connect, $pageTitle, $title);
        $this->ra = new RecordsAssets($connect, $pageTitle, $title);
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
        <h1>
            <?php echo $this->pageTitle; ?>
        </h1>
        <?php
    }
    public function GetYearData($selectedYear)
    {
        ?>
        <form method="post">
            <div class="container-subchild-4">
                <select name="dateSelect" class="form-control form-select-lg mb-3" required="">
                    <?php
                    $years = $this->dbHelper->TotalYearlyValue();
                    if (empty($years)) {
                        ?>
                        <option value="" disabled selected>Data nejsou k dispozici</option>
                        <?php
                    } else {
                        foreach ($years as $this->year)
                            if ($selectedYear == $this->year) {
                                echo '<option value="' . $this->year . '" selected="selected">' . $this->year . '</option>';
                            } else {
                                echo '<option value="' . $this->year . '">' . $this->year . '</option>';
                            }
                        echo $this->year;
                    }
                    ?>
                </select>
            </div>
            <div class="container-subchild-4">
                <input class="btn btn-primary" value="Potvrdit" type="submit">
            </div>
        </form>
        <?php
    }
    public function RenderQuickNavigation()
    {
        $this->rie->AddIncomeExpense();
        $this->rdd->AddDemandDebt();
        $this->ra->AddAsset();

        ?>
        <div class="row text-center">
            <div class="col">
                <h5>Zrychlený přístup</h5>
            </div>
        </div>
        <div class="row text-center">
            <div class="col">
                <form action="evidence_prijmy_a_vydaje.php" method="post">
                    <?php
                    $this->rie->RenderButtonAdd();
                    ?>
                    <button class="btn btn-primary" type="submit">Zobrazit</button>
                </form>
                <p>Příjmy nebo výdaje</p>
            </div>
            <div class="col">
                <form action="evidence_pohledavky_a_dluhy.php" method="post">
                    <?php
                    $this->rdd->RenderButtonAdd();
                    ?>
                    <button class="btn btn-primary" type="submit">Zobrazit</button>
                </form>
                <p>Pohledávky nebo závazky</p>
            </div>
            <div class="col">
                <form action="majetek_dlouhodoby.php" method="post">
                    <?php
                    $this->ra->RenderButtonAdd();
                    ?>
                    <button class="btn btn-primary" type="submit">Zobrazit</button>
                </form>
                <p>Dlouhodobý majetek</p>
            </div>
        </div>
        <?php
    }
    public function RenderYearlyTotal($selectedYear)
    {
        ?>
        <div class="container-subchild-4">
            <?php
            $yearlyData = $this->dbHelper->YearlyIncomeExpense($selectedYear);

            $yearlyIncomeTax = $yearlyData['yearlyIncomeTax'][$selectedYear];
            $yearlyIncome = $yearlyData['yearlyIncome'][$selectedYear];
            $yearlyExpenseTax = $yearlyData['yearlyExpenseTax'][$selectedYear];
            $yearlyExpense = $yearlyData['yearlyExpense'][$selectedYear];

            echo "<p>Daňové příjmy za rok $selectedYear: " . number_format((float) $yearlyIncomeTax, 2, ".", ",") . " Kč</p>";
            echo "<p>Nedaňové příjmy za rok $selectedYear: " . number_format((float) $yearlyIncome, 2, ".", ",") . " Kč</p>";
            echo "<p>Daňové výdaje za rok $selectedYear: " . number_format((float) $yearlyExpenseTax, 2, ".", ",") . " Kč</p>";
            echo "<p>Nedaňové výdaje za rok $selectedYear: " . number_format((float) $yearlyExpense, 2, ".", ",") . " Kč</p>";
            ?>
        </div>
        <?php
    }
    public function RenderGraph()
    {
        ?>
        <h4>Celkové příjmy a výdaje za všechny roky:</h4>
        <?php
        $graphData = $this->dbHelper->YearlyDataForGraph();
        $graphIncome = $graphData['income'];
        $graphExpense = $graphData['expense'];
        $graphYears = $graphData['years'];

        $data = array(
            array(
                'x' => $graphYears,
                'y' => $graphIncome,
                'name' => 'Příjmy',
                'type' => 'bar'
            ),
            array(
                'x' => $graphYears,
                'y' => $graphExpense,
                'name' => 'Výdaje',
                'type' => 'bar'
            )
        );

        $json_data = json_encode($data);
        ?>
        <div class="graph" id="myDiv">
            <script src='https://cdn.plot.ly/plotly-2.20.0.min.js'></script>
            <script>
                function createPlot() {
                    var data = <?php echo $json_data; ?>;

                    Plotly.newPlot('myDiv', data, { responsive: true });
                }

                createPlot();

                window.addEventListener('resize', createPlot);
            </script>
        </div>
        <?php
    }
    public function RenderChild6($year)
    {
        ?>
        <h4>Daňový kalendář</h4>
        <?php
    }
    public function Render($year)
    {
        $this->dbHelper->SubmitInsertData('ulozit1');
        $this->dbHelper->SubmitInsertData('ulozit2');
        $this->dbHelper->SubmitInsertData('ulozit3');
        ?>
        <div class="container-parent">
            <?php
            $alert = $this->alert->Alert();
            echo $alert;
            ?>
            <div class="container-upper">
                <?php
                $this->RenderTitle();
                ?>
                <div class="container-child-4">
                    <?php
                    $this->GetYearData($year);
                    ?>
                </div>
                <div class="container-child-5">
                    <?php
                    $this->RenderQuickNavigation();
                    ?>
                </div>
                <div class="container-child-1">
                    <?php
                    $this->RenderYearlyTotal($year);
                    ?>
                </div>
            </div>
            <div class="container-lower">
                <div class="container-child-6">
                    <?php
                    $this->RenderGraph();
                    ?>
                </div>
                <div class="container-child-3">
                    <?php
                    //$this->RenderChild6($year);
                    ?>
                </div>
            </div>
        </div>
    <?
    }
    public function RenderHTML($year)
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
            $this->Render($year);
            ?>
            <script src="js/restrictAddAsset.js"></script>
        </body>

        </html>
        <?php
    }
}