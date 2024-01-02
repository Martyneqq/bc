<?php
include 'databaseConnection.php';
//include 'functions.php';

class Authenticator
{
    private $connect;
    private $userData;
    private $userID;
    private $alert;
    protected $head;
    protected $header;
    protected $title;
    protected $appLogic;
    public function __construct($connect, $title)
    {
        $dbConfig = include('dbConfig.php');
        $this->connect = $connect;
        $this->head = new Head($title);
        $this->header = new Header($connect, $this->userData, $this->userID);
        $this->alert = new Alert();
        $this->appLogic = new AppLogic($connect);
    }

    public function Check()
    {
        if (isset($_SESSION['id'])) {
            $id = $_SESSION['id'];
            $query = "SELECT * FROM users WHERE id = ?";
            $stmt = mysqli_prepare($this->connect, $query);
            mysqli_stmt_bind_param($stmt, 'i', $id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($result && mysqli_num_rows($result) > 0) {
                $this->userData = mysqli_fetch_assoc($result);
                $this->appLogic->executeDepreciation($this->userData['id']); // works only after refresh
                return $this->userData;
            }
        }
        header("Location: login.php");
        die();
    }

    public function Login()
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $username = addslashes($_POST['username']);
            $password = $_POST['password'];

            $save = $this->connect->prepare("SELECT * FROM users WHERE username = ?");
            $save->bind_param('s', $username);
            $save->execute();
            $result = $save->get_result();

            if ($result && mysqli_num_rows($result) > 0) {
                $this->userData = $result->fetch_assoc();
                if ($this->userData['password'] === md5($password)) {
                    $_SESSION['id'] = $this->userData['id'];
                    header("Location: index.php");
                    die();
                } else {
                    $_SESSION['errorL'] = "Špatné uživatelské jméno nebo heslo";
                }
            } else {
                $_SESSION['errorL'] = "Špatné uživatelské jméno nebo heslo";
            }
        }
    }
    public function Signup()
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST") {
            $username = $_POST['username'];
            $ico = $_POST['ico'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $cpassword = $_POST['cpassword'];

            $queryCheck = "SELECT * FROM users WHERE username = ?";
            $queryUser = mysqli_prepare($this->connect, $queryCheck);
            $queryUser->bind_param('s', $username);
            $queryUser->execute();
            $queryUser->store_result();

            if ($queryUser->num_rows > 0) {
                $_SESSION['errorS'] = 'Toto uživatelské jméno již existuje.';
            } else {
                $result = $this->ValidateInput($password, $email, $cpassword);

                if ($result === true) {
                    $hashedPassword = md5($password);
                    $save = "INSERT INTO users (`username`, ico, `email`, `password`) VALUES (?, ?, ?, ?)";
                    $query = mysqli_prepare($this->connect, $save);
                    $query->bind_param('siss', $username, $ico, $email, $hashedPassword);
                    if ($query->execute()) {
                        $_SESSION['successS'] = "Účet byl úspěšně vytvořen";
                        header("Location: login.php");
                        die();
                    }
                    $_SESSION['errorS'] = "Error";
                } else {
                    $_SESSION['errorS'] = $result;
                }
            }
        }
    }

    private function ValidateInput($password, $email, $cpassword)
    {
        if ($password != $cpassword) {
            return 'Hesla se neshodují!';
        } else if (strlen($password) < 8) {
            return 'Heslo musí mít alespoň 8 znaků!';
        } else if (!preg_match('/^[a-z0-9]+(\.[a-z0-9]+)*@[a-z0-9]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/', $email)) {
            return 'Špatný formát emailu!';
        } else if (!preg_match('/[a-z]/', $password)) {
            return 'Heslo musí obsahovat alespoň jedno písmeno';
        } else if (!preg_match('/[0-9]/', $password)) {
            return 'Heslo musí obsahovat alespoň jedno číslo';
        }
        return true;
    }

    public function Logout()
    {
        if (isset($_COOKIE['id'])) {
            unset($_COOKIE['id']);
            setcookie('id', "", time() - 3600, '/');
        }
        session_destroy();
        header("Location: login.php");
        die();
    }
    public function RenderSignup()
    {
        ?>
        <div class="container" style="width: 100%; text-align: center; margin: auto; padding: 10%;">
            <form method="post">
                <?php
                $alert = $this->alert->Alert();
                echo $alert;
                ?>
                <div style="font-size: 20px; margin: 10px;">Registrace</div>

                <input type="text" name="username" placeholder="Uživatelské jméno" required=""><br><br>
                <input type="number" name="ico" placeholder="IČO" required=""><br><br>
                <input type="text" name="email" placeholder="Email" required=""><br><br>
                <input type="password" name="password" placeholder="Heslo" required=""><br><br>
                <input type="password" name="cpassword" placeholder="Potvrzení hesla" required=""><br><br>

                <input type="submit" class="btn btn-primary" name="signup" value="Potvrdit">
                <input type="reset" class="btn btn-danger" value="Zrušit"><br><br>
                <a href="login.php">Přihlásit se</a>
            </form>
        </div>
        <?php
    }
    public function RenderLogin()
    {
        ?>
        <div class="container" style="width: 100%; text-align: center; margin: auto; padding: 10%;">
            <?php
            $alert = $this->alert->Alert();
            echo $alert;
            ?>
            <div style="font-size: 20px; margin: 10px;">Přihlášení</div>
            <?php
            ?>
            <form method="post">
                <input type="text" name="username" placeholder="Uživatelské jméno" required=""><br><br>
                <input type="password" name="password" placeholder="Heslo" required=""><br><br>
                <input class="btn btn-primary" type="submit" name="login" value="Login"><br><br>
                <a href="signup.php">Vytvořit nový účet</a><br><br>
            </form>
        </div>
        <?php
    }
    public function Render()
    {
        if (strpos($_SERVER['REQUEST_URI'], 'login.php') !== false) {
            $this->RenderLogin();
        } else if (strpos($_SERVER['REQUEST_URI'], 'signup.php') !== false) {
            $this->RenderSignup();
        }
    }
    public function RenderHTML()
    {
        if (strpos($_SERVER['REQUEST_URI'], 'login.php') !== false) {
            $this->Login();
        } else if (strpos($_SERVER['REQUEST_URI'], 'signup.php') !== false) {
            $this->Signup();
        }

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