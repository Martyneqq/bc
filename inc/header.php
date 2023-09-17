<nav class="navbar navbar-expand-sm navbar-dark" style="background-color: #234;min-width: 20%;max-width: 100%;">
    <div class="container-fluid">
        <ul class="navbar-nav mr-auto">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <a class="bi-house-door nav-link" href="index.php" style="margin: 0 2px; font-size: 1.5rem; color: whitesmoke;"></a>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Přidat
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="pridat_prijem_nebo_vydaj.php">Příjem nebo výdaj</a></li>
                        <li><a class="dropdown-item" href="pridat_pohledavka_nebo_dluh.php">Pohledávka nebo závazek</a></li>
                        <li><a class="dropdown-item" href="pridat_dlouhodoby_majetek.php">Dlouhodobý nebo drobný majetek</a></li>
                    </ul>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Evidence
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="evidence_prijmy_a_vydaje.php">Deník příjmů a výdajů</a></li>
                        <li><a class="dropdown-item" href="evidence_pohledavky_a_dluhy.php">Pohledávky a závazky</a></li>
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
                    <a class="nav-link" aria-current="page" href="denik.php">Deník</a>
                </li>
            </div>
        </ul>
        <?php
        if (stripos($_SERVER['REQUEST_URI'], 'login.php') || stripos($_SERVER['REQUEST_URI'], 'signup.php')) {
            
        } else {
            ?>
            <ul class="navbar-nav">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?php echo secure($userData['username']) ?? null; ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <li><a class="dropdown-item" href="#">Profil</a></li> <!-- TODO -->
                        <li><a class="dropdown-item" href="#">Nápověda</a></li> <!-- TODO -->
                        <!--<li>
                            <form method="POST">
                                <a type="submit" name="toggle" id="toggleButton">Změnit pozadí</a>
                            </form>
                        </li>-->
                        <div class="dropdown-divider"></div>
                        <li><a class="dropdown-item" href="logout.php">Odhlásit se</a></li>
                    </ul>
                </li>
            </ul>
        <?php }
        ?>
    </div>
</nav>