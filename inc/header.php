<nav class="navbar navbar-expand-sm navbar-dark" style="background-color: #234;">
    <div class="container-fluid">
        <ul class="navbar-nav mr-auto">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <a class="bi-house-door" href="domu.php" style="margin: 4px; font-size: 1.5rem; color: whitesmoke;"></a>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Přidat
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="pridat_prijem_nebo_vydaj.php">Příjem nebo výdaj</a></li>
                            <li><a class="dropdown-item" href="pridat_pohledavka_nebo_dluh.php">Pohledávka nebo dluh</a></li>
                            <li><a class="dropdown-item" href="pridat_dlouhodoby_majetek.php">Dlouhodobý majetek</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Evidence
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="evidence_prijmy_a_vydaje.php">Deník příjmů a výdajů</a></li>
                            <li><a class="dropdown-item" href="evidence_pohledavky_a_dluhy.php">Pohledávky a dluhy</a></li>
                        </ul>
                    </li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            Majetek
                        </a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="majetek_dlouhodoby.php">Dlouhodobý majetek</a></li>
                            <li><a class="dropdown-item" href="majetek_drobny.php">Drobný majetek</a></li>
                        </ul>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="prehled.php">Přehled</a>
                    </li>
                </ul>
            </div>
        </ul>
        <?php
        if (stripos($_SERVER['REQUEST_URI'], 'login.php') || stripos($_SERVER['REQUEST_URI'], 'signup.php')) {
            echo '<p id="language_icon" onclick="changeLanguage()">en</p>'; // TODO
        } else {
            ?>
            <ul class="navbar-nav">
                <p id="language_icon" onclick="changeLanguage()">en</p> <!-- TODO -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php echo secure($userData['username']) ?? null; ?>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item">Tmavý režim</a></li> <!-- TODO -->
                        <li><a class="dropdown-item" href="#">Nápověda</a></li> <!-- TODO -->
                        <li><a class="dropdown-item" href="logout.php">Odhlásit se</a></li>
                    </ul>
                </li>
            </ul>
        <?php } ?>
    </div>
</nav>