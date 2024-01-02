<?php
include 'databaseConnection.php';

class Header
{
    private $connect;
    private $userData;
    private $userID;
    private $dbHelper;
    public function __construct($connect, $userData, $userID)
    {
        /*$dbConfig = include('dbConfig.php');
        $this->connect = new DatabaseConnect($dbConfig['server'], $dbConfig['username'], $dbConfig['password'], $dbConfig['database']);*/
        $this->connect = $connect;
        $this->userData = $userData;
        $this->userID = $userID;
        $this->dbHelper = new DatabaseHelper($connect, $userID);
    }

    public function CashFlow()
    {
        ?>
        <a class="text-light">Pokladna:
            <?php
            $flow = $this->dbHelper->TotalFlow();
            $fc = $flow['cash'];
            echo number_format((float) $fc, 2, ".", ",");
            ?>
            Kč
        </a>
        <?php
    }
    public function BankFlow()
    {
        ?>
        <a class="text-light">Bankovní účet:
            <?php
            $flow = $this->dbHelper->TotalFlow();
            $fb = $flow['bank'];
            echo number_format((float) $fb, 2, ".", ",");
            ?>
            Kč
        </a>
        <?php
    }
    public function RenderHeader()
    {
        ?>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <a class="navbar-brand rounded text-light mr-auto bi bi-house nav-link bg-dark house-button" href="index.php"></a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item dropdown">
                        <a class="btn text-light dropdown-toggle bg-dark shadow-none" type="button" id="evidenceDropdown"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Doklady
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="evidenceDropdown">
                            <li><a class="dropdown-item" href="evidence_prijmy_a_vydaje.php">Příjmy a výdaje</a></li>
                            <li><a class="dropdown-item" href="evidence_pohledavky_a_dluhy.php">Pohledávky a závazky</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="btn text-light dropdown-toggle bg-dark shadow-none" type="button" id="evidenceDropdown"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Majetek
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="evidenceDropdown">
                            <li><a class="dropdown-item" href="majetek_dlouhodoby.php">Dlouhodobý</a></li>
                            <li><a class="dropdown-item" href="majetek_drobny.php">Drobný</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="btn text-light dropdown-toggle bg-dark shadow-none" type="button" id="evidenceDropdown"
                            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            Deníky
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="evidenceDropdown">
                            <li><a class="dropdown-item" href="denik.php">Deník příjmů a výdajů</a></li>
                            <li><a class="dropdown-item" href="denikP.php">Kniha pokladní</a></li>
                            <li><a class="dropdown-item" href="denikB.php">Kniha bankovní</a></li>
                        </ul>
                    </li>

                </ul>
                <?php
                if (stripos($_SERVER['REQUEST_URI'], 'login.php') || stripos($_SERVER['REQUEST_URI'], 'signup.php')) {

                } else {
                    ?>
                    <!-- User dropdown -->
                    <ul class="navbar-nav mr-auto d-flex justify-content-center">
                        <li class="nav-item">
                            <a class="text-light mr-auto">
                                <?php
                                $this->CashFlow();
                                ?>
                            </a>
                        </li>
                    </ul>
                    <ul class="navbar-nav mr-auto d-flex justify-content-center">
                        <li class="nav-item">
                            <a class="text-light mr-auto">
                                <?php
                                $this->BankFlow();
                                ?>
                            </a>
                        </li>
                    </ul>
                    <ul class="navbar-nav">
                        <li class="nav-item dropdown">
                            <a class="btn text-light dropdown-toggle bg-dark shadow-none" type="button" id="userDropdown"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <?php echo $this->userData['username'] ?? null; ?>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-right" aria-labelledby="userDropdown">
                                <li><a class="dropdown-item" href="#" data-target="#profile" data-toggle="modal">Profil</a></li>
                                <li><a class="dropdown-item" href="#" data-target="#guide" data-toggle="modal">Nápověda</a></li>
                                <!--<li><a class="dropdown-item" href="#" type="toggle" id="going_dark">Tmavý režim</a></li>-->
                                <div class="dropdown-divider"></div>
                                <li><a class="dropdown-item" href="logout.php">Odhlásit se</a></li>
                            </ul>
                        </li>
                    </ul>
                <?php }
                ?>
            </div>
        </nav>
        <div class="modal fade" id="profile" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Profil</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <form class="default-form" id="default-form" method="post" action="">
                        <div class="modal-body">
                            <h3>...</h3>
                        </div>
                        <div class="modal-footer">

                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="modal fade" id="guide" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Nápověda</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <?php
                        $this->Guide();
                        ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Zavřít</button>
                    </div>
                </div>
            </div>
        </div>
        <script src="js/darkMode.js"></script>
        <?php
    }
    public function Guide() //help
    {
        ?>
        <div id="accordion">
            <?php // headers
                    $items = [
                        "Vytvoření uživatelského účtu",
                        "Přihlášení",
                        "Odhlášení",
                        "Zobrazení celkových příjmů a výdajů pro zvolený rok",
                        "Evidence příjmů a výdajů",
                        "Evidence pohledávek a závazků",
                        "Evidence majetku drobného a dlouhodobého",
                        "Deník",
                        "Vyřazení majetku",
                        "Odepisování majetku",
                        "Řazení tabulek",
                        "Typy dokumentů"
                    ];
                    // content
                    $content = [
                        "Na stránce pro přihlášení kliknete na odkaz \"Vytvořit nový účet\". Pro registraci je nutné vyplnit uživatelské jméno, identifikační číslo osoby, email a heslo.
                        Zadané heslo musí obsahovat alespoň 8 znaků, alespoň jednu číslici a jedno písmeno.",
                        "Pro přihlášení zadáte uživatelské jméno a heslo zvolené při registraci. Po vytvoření účtu budete automaticky přesměrování na stránku s přihlášením.",
                        "Pro odhlášení kliknete na své uživatelské jméno v horním menu a zvolíte možnost \"Odhlasit se\".",
                        "Na domovské stránce kliknete na seznam roků. Každý rok reprezentuje hodnotu příjmu nebo výdaje v databázi. Vyberete rok, ze kterého chcete zobrazit součet příjmů a výdajů a kliknete na tlačítko \"Potvrdit\". Dole se poté zobrazí celková hodnota pro příjmy a výdaje zvlášť. Tyto hodnoty jsou dohromady daňové i nedaňové.",
                        "Příjmy a výdaje se nachází v horním menu:<br>
                        <ol>
                        <li> Doklady</li>
                        <li> Příjmy a výdaje</li>
                        </ol>
                        Tato stránka zobrazuje informace o přidaných příjmech a výdajích. Hodnoty příjmů a výdajů v tabulce jsou dále rozděleny na daňové/nedaňové podle toho, zda uživatel při přidávání zvolil daňovou uznatelnost nebo ne. Součet těchto hodnot se nachází v dolní části tabulky.<br><br>
                        Jednotlivé sloupce obsahují:<br>
                        <ol>
                            <li> Datum uhrazení - datum zavedení položky do evidence</li>
                            <li> Číslo dokladu - tento rok + typ dokladu + pořadí (například 23HV3 - v tomto případě se jedná o 3. doklad vystavený v tomto účetním období, placený hotově a jedná se o výdaj)</li>
                            <li> Název - název položky</li>
                            <li> Příjem nebo výdaj - zda se jedná o příjem či výdaj</li>
                            <li> Daňové/nedaňové příjmy - příjmy zahrnující/nezahrnující daň</li>
                            <li> Daňové/nedaňové výdaje - výdaje zahrnující/nezahrnující daň</li>
                            <li> Způsob platby - zda platba proběhla v hotovosti nebo převedením na účet</li>
                            <li> Popis - libovolná poznámka s limitem 128 znaků</li>
                            <li> Úpravy - operace pro upravení/smazání řádku</li>
                        </ol>
                        Příklad: prodali jste čtvrtou elektrocentrálu \"NZ UNI\" za toto účtovací období v den 4.10. Částku jste uhradili hotově a byla 3. v tomto období.<br><br>
                        <table>
                        <thead>
                        <tr>
                        <td>Datum uhrazení</td>
                        <td>Číslo dokladu</td>
                        <td>Název</td>
                        <td>Příjem nebo výdaj</td>
                        <td>Daňové příjmy</td>
                        <td>Způsob platby</td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                        <td>04/10</td>
                        <td>23HV4</td>
                        <td>NZ UNI</td>
                        <td>Příjem</td>
                        <td>4,700.00</td>
                        <td>Z účtu</td>
                        </tr>
                        </tbody>
                        </table>",
                        //
                        "Pohledávky a závazky se nachází v horním menu pod:<br>
                        <ol>
                        <li> Doklady</li>
                        <li> Pohledávky a závazky</li>
                        </ol>
                        Na této stránce jsou zobrazeny jednotlivé pohledávky a dluhy. Částky u jednotlivých pohledávek a dluhů nejsou započítány do celkového poněžního oběhu, dokud není nastavena možnost \"Uhrazeno\" na \"Ano\". V tu chvíli se pohledávka počítá jako příjem a závazek jako výdaj. Ve chvíli, kdy je položka uhrazena, nemůže být upravena ani smazána.<br><br>
                        Jednotlivé sloupce obsahují:<br>
                        <ol>
                            <li> Datum uhrazení - den, kdy částka musí být uhrazena</li>
                            <li> Datum splatnosti - do kdy je potřeba částku uhradit </li>
                            <li> Číslo dokladu - tento rok + typ dokladu + pořadí (například 23HV3 - v tomto případě se jedná o 3. doklad vystavený v tomto účetním období, placený hotově a jedná se o výdaj)</li>
                            <li> Název - název položky</li>
                            <li> Firma - po kom je pohledávka vymáhána nebo komu splatit dluh</li>
                            <li> Typ - pohledávka nebo dluh</li>
                            <li> Hodnota - pohledávaná nebo dlužená částka</li>
                            <li> Daňová uznatelnost - zda hodnota zdanitelná či nikoli</li>
                            <li> Způsob platby - zda platba proběhla v hotovosti nebo převedením na účet</li>
                            <li> Uhrazeno - byla tato částka již uhrazena? (Ano/Ne)</li>
                            <li> Popis - libovolná poznámka s limitem 128 znaků</li>
                            <li> Úpravy - operace pro upravení/smazání řádku</li>
                        </ol>

                            Příklad: firma Vodafone vám poskytuje měsíční kredit na firemní telefon a máte zaplatit 556\,Kč. Tímto měsícem (říjen) se jedná o desátou fakturu přijatou.<br><br>
                            <table>
                            <thead>
                            <tr>
                            <td>Datum vystavení</td>
                            <td>Číslo dokladu</td>
                            <td>Název</td>
                            <td>Firma</td>
                            <td>Typ</td>
                            <td>Hodnota</td>
                            <td>Daňová uznatelnost</td>
                            <td>Uhrazeno</td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                            <td>03/10/2023</td>
                            <td>23BV10</td>
                            <td>Kredit</td>
                            <td>Vodafone</td>
                            <td>Dluh</td>
                            <td>556,00</td>
                            <td>Ano</td>
                            <td>Ano</td>
                            </tr>
                            </tbody>
                            </table>
                            Pozn.: Pokud je sloupec \"Uhrazeno\" označeno jako \"Ano\", pohledávka nebo dluh bude uložen jako příjem nebo výdaj. Po této změně položka nemůže být upravena ani smazána.",
                        "Dlouhodobý a drobný majetek se nachází v horním menu pod:<br>
                        <ol>
                            <li> Majetek</li>
                            <li> Dlouhodobý majetek/Drobný majetek</li>
                        </ol>
                        Dlouhodobý hmotný majetek je majetek firmy, který přesahuje hodnotu 80 000 Kč. Výjimka nastává v případě, kdy je majetek nehmotný - ten může mít hodnotu menší a je také považován za dlouhodobý majetek, nikoli drobný. Dlouhodobý majetek se každý rok automaticky odepisuje a seznam odpisů najdete pod tlačítkem \"Odpisy\". Každý odpis se dále eviduje v Evidenci příjmů a výdajů jako výdaj a najdete ho pod názvem \"Odpis: [název]\".<br> Drobný majetek se automaticky ukládá jako výdaj, jelikož podle zákona nesmí přesahovat hodnotu 80 000 Kč, a najdete ho pod názvem \"Drobný majetek: [název]\" v Evidenci příjmů a výdajů.<br>
                        
                        Pokud je dlouhodobý majetek přidán, můžete upravovat pouze název, doklad a popis. Pokud jste při vytváření majetku například zadali špatnou cenu, je nutné majetek smazat a vytvořit znovu. Majetek dále není možné smazat, pokud již proběhl odpis. Pokud je dále vyřazen, nemůže být kromě odstranění ani upraven.<br><br>
                        
                        Jednotlivé sloupce obsahují:<br>
                        <ol>
                            <li> Datum zařazení - den užívání majetku ve firmě</li>
                            <li> Datum vyřazení - předpokládaný den vyřazení majetku</li>
                            <li> Číslo dokladu - tento rok + typ dokladu + pořadí (například 23HV3 - v tomto případě se jedná o 3. doklad vystavený v tomto účetním období, placený hotově a jedná se o výdaj)</li>
                            <li> Název - název položky</li>
                            <li> Počáteční hodnota - nákupní cena majetku</li>
                            <li> Typ - majetek hmotný nebo nehmotný</li>
                            <li> Odpisová skupina - odpisové skupiny jsou rozděleny podle čísel (1-6), které můžete libovolně zvolit podle toho, kolik let chcete majetek odepisovat s jakou hodnotou.</li>
                            <li> Způsob odpisu - rovnoměrný nebo zrychlený. Můžete si vybrat tu metodu, která vám vyhovuje více. Pro dražší majetek se doporučuje užívat rovnoměrný odpis.</li>
                            <li> Popis - libovolná poznámka s limitem 128 znaků</li>
                            <li> Úpravy - operace pro upravení/smazání řádku</li>
                        </ol>

                        Příklad: koupili jste na firmu auto Škoda Octavia 2 za 290 000 Kč dne 19.10.2023, vybrali jste, že ho chcete odepisovat po dobu 5 let (odpisová skupina 2) a rovnoměrnou metodou. Tento majetek je dále uhrazen bankovním převodem a jeho pořadí v tomto období je 2.<br><br>
                        <table>
                            <thead>
                            <tr>
                            <td>Zařazení</td>
                            <td>Vyřazení</td>
                            <td>Doklad</td>
                            <td>Název</td>
                            <td>Částka</td>
                            <td>Typ</td>
                            <td>Skupina</td>
                            <td>Odpis</td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                            <td>19/10/2023</td>
                            <td>19/10/2028</td>
                            <td>23BV2</td>
                            <td>Škoda Octavia 2</td>
                            <td>290,000.00</td>
                            <td>Hmotný</td>
                            <td>2</td>
                            <td>Rovnoměrný</td>
                            </tr>
                            </tbody>
                            </table>",
                        "<B>Deník příjmů a výdajů</B> najdete pod tlačítkem \"Deníky\" v horním menu. Deník vypočítá částku z příjmů a výdajů potřebnou pro určení základu daně v daném období.<br><br><B>Knihu pokladní</B> najdete pod tlačítkem \"Deníky\" v horním menu. Tento deník vyhledává všechny položky uhrazeny hotově a zobrazí je na základě zvoleného datumu.<br><br><B>Knihu bankovní</B> najdete pod tlačítkem \"Deníky\" v horním menu. Tento deník vyhledává všechny položky uhrazeny bankovním převodem a zobrazí je na základě zvoleného datumu. <br><br> Vypočítaná čáska základu daně pro každou tabulku se nachází ve spodní části tabulky. Toto období zvolíte pomocí polí s datumy - první pole je začátek a druhé konec období. Pokud se v databázi nachází odpis, vyřazení majetku nebo drobný majetek, řádek tabulky, kde se nachází název položky bude změněn na \"Odpis: [název]\",  \"Vyřazení: [název]\" nebo \"Drobný majetek: [název]\".",
                        "Majetek vyřadíte pomocí rozbalovacího tlačítka \"Vyřadit\" kliknutím  na možnosti \"Likvidace/Prodej\". Podle zvolené možnosti se přizpůsobí datum vyřazení. Odepsaná hodnota vyřazeného majetku bude zobrazena pod tlačítkem \"Odpis\" a v Evidenci příjmů a výdajů.",
                        "Majetek se každý rok odepisuje automaticky. Jednotlivé odpisy najdete v Dlouhodobém majteku pod tlačítkem \"Odpisy\". Následující tabulka udává příklady majetků, které by mohli patřit do jedné ze skupin:<br>
                        <table>
                        <thead>
                        <tr>
                        <td>Skupina</td>
                        <td>Příklad</td>
                        <td>Roky</td>
                        <td>a</td>
                        <td>b</td>
                        <td>x</td>
                        <td>y</td>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                        <td>1</td>
                        <td>Počítač, levné nástroje</td>
                        <td>3</td>
                        <td>20</td>
                        <td>40</td>
                        <td>3</td>
                        <td>4</td>
                        </tr>
                        <tr>
                        <td>2</td>
                        <td>Auto, autobus, další nástroje</td>
                        <td>5</td>
                        <td>11</td>
                        <td>22,25</td>
                        <td>5</td>
                        <td>6</td>
                        </tr>
                        <tr>
                        <td>3</td>
                        <td>Parní kotle, tramvaje</td>
                        <td>10</td>
                        <td>5,5</td>
                        <td>10,5</td>
                        <td>10</td>
                        <td>11</td>
                        </tr>
                        <tr>
                        <td>4</td>
                        <td>Budovy, potrubí</td>
                        <td>20</td>
                        <td>2,15</td>
                        <td>5,15</td>
                        <td>20</td>
                        <td>21</td>
                        </tr>
                        <tr>
                        <td>5</td>
                        <td>Průmyslové stavby, byty, zemědělství</td>
                        <td>30</td>
                        <td>1,4</td>
                        <td>3,4</td>
                        <td>30</td>
                        <td>31</td>
                        </tr>
                        <tr>
                        <td>6</td>
                        <td>Administrativní budovy, obchodní domy, hotely</td>
                        <td>50</td>
                        <td>1,02</td>
                        <td>2,02</td>
                        <td>50</td>
                        <td>51</td>
                        </tr>
                        </tbody>
                        </table><br>
                        <ul>
                        <li> <i>a</i> udává procento odečtené ze vstupní ceny v prvním roce u odpisu rovnoměrného</li>
                        <li> <i>b</i> udává procento odečtené ze zbylé hodnoty ve zbylých letech u odpisu rovnoměrného</li>
                        <li> <i>x</i> udává procento odečtené ze vstupní ceny v prvním roce u odpisu zrychleného</li>
                        <li> <i>y</i> udává procento odečtené ze zbylé ceny ve zbývajících letech ve  odpisu zrychleného</li>
                        </ul>
                        ",
                        "Sloupce tabulek můžou být seřazeny po kliknutí na hlavičku příslušné tabulky. Po opětovném kliknutí se tabulka seřadí v opačném pořadí.",
                        "<ul>
                            <li><b>HP</b> - Hotovostní příjem</li>
                            <li><b>HV</b> - Hotovostní výdaj</li>
                            <li><b>BP</b> - Bankovní příjem</li>
                            <li><b>BV</b> - Bankovní výdaj</li>
                        </ul>"
                    ];
                    // header and content structure
                    for ($i = 0; $i < count($items); $i++) {
                        $a = $i + 1;
                        $textColor = 'text-dark';

                        echo '
        <div class="card">
            <div class="card-header" id="heading' . $a . '">
                <h5 class="mb-0">
                    <button class="btn btn-link ' . $textColor . '" data-toggle="collapse" data-target="#collapse' . $a . '" aria-expanded="false" aria-controls="collapse' . $a . '">
                        ' . $a . '. ' . $items[$i] . '
                    </button>
                </h5>
            </div>
            <div id="collapse' . $a . '" class="collapse" aria-labelledby="heading' . $a . '" data-parent="#accordion">
                <div class="card-body">
                    ' . $content[$i] . '
                </div>
            </div>
        </div>';
                    }
                    ?>
        </div>
        <?php
    }
}

?>