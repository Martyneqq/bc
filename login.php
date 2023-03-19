<?php
session_start();
include 'inc/head.php';
include 'functions.php';
include 'databaseConnection.php';
include 'inc/header.php';
if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = addslashes($_POST['username']);
    $password = $_POST['password'];

    $error = "Špatné uživatelské jméno nebo heslo";
    $save = $connect->prepare("SELECT * FROM users WHERE username = ?");
    $save->bind_param('s', $username);
    $save->execute();
    $result = $save->get_result();
    while ($userData = $result->fetch_assoc()) {
        if ($result && mysqli_num_rows($result) > 0) {
            if ($userData['password'] === md5($password)) {
                $_SESSION['id'] = $userData['id'];
                header("Location: domu.php");
                die();
            }
        } else {
            echo $error;
        }
    }
}

?>
<!DOCTYPE html>
<html>
    <head>

    </head>
    <header>
        
    </header>
    <body>
        <div class="container" style="width: 100%; text-align: center; margin: auto; padding: 10%;">
            <div style="font-size: 20px; margin: 10px;">Login</div>
            <?php
            if (isset($error)) {
                echo '<span style="color: red; font-size: 17px;">' . $error . '</span><br><br>';
            }
            ?>
            <form method="post">
                <input type="text" name="username" placeholder="Your username" required=""><br><br>
                <input type="password" name="password" placeholder="Password" required=""><br><br>
                <input class="btn btn-primary" type="submit" name="login" value="Login"><br><br>
                <a href="signup.php">Vytvořit nový účet</a><br><br>
            </form>
        </div>
    </body>
</html>