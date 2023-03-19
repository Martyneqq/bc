<?php
session_start();
include 'inc/head.php';
include 'functions.php';
include 'databaseConnection.php';
include 'inc/header.php';

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $cpassword = md5($_POST['cpassword']);

    $select = $connect->prepare("SELECT * FROM users WHERE username = ? AND password = ?");
    $select->bind_param('ss', $username, $password);
    $select->execute();
    $result = $select->get_result();

    if (mysqli_num_rows($result) > 0) {
        $error = 'Uživatel již existuje!';
    } else if (!preg_match('/^[a-z0-9]+(\.[a-z0-9]+)*@[a-z0-9]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/', $email)) {
        $error = 'Špatný formát emailu!';
    } else if ($password != $cpassword) {
        $error = 'Hesla se neshodují!';
    } else if (strlen($password) < 8) {
        $error = 'Heslo musí mít alespoň 8 charakterů!';
    } else if (!preg_match('/[a-z]/', $password)) {
        $error = 'Heslo musí obsahovat alespoň jedno písmeno';
    } else if (!preg_match('/[0-9]/', $password)) {
        $error = 'Heslo musí obsahovat alespoň jedno číslo';
    } else {
        $save = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $query = mysqli_prepare($connect, $save);
        $query->bind_param('sss', $username, $email, $password);
        if (($execute = mysqli_stmt_execute($query)) != true) {
            echo "Error";
        }
        echo "Položka úspěšně přidána!";
        header("Location: login.php");
    }
    //die();
}
?>
<!DOCTYPE html>
<html>
    <head>

    </head>
    <header>
        
    </header>
    <body>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
        <div class="container" style="width: 100%; text-align: center; margin: auto; padding: 10%;">
            <form method="post">
                <div style="font-size: 20px; margin: 10px;">Signup</div>
                <?php
                if (isset($error)) {
                    echo '<span style="color: red; font-size: 17px;">' . $error . '</span><br><br>';
                }
                ?>
                <input type="text" name="username" placeholder="Username" required=""><br><br>
                <input type="text" name="email" placeholder="Email" required=""><br><br>
                <input type="password" name="password" placeholder="Your Password" required=""><br><br>
                <input type="password" name="cpassword" placeholder="Confirm your Password" required=""><br><br>

                <input type="submit" class="btn btn-primary" name="signup" value="Signup">
                <input type="reset" class="btn btn-danger" value="Reset"><br><br>
                <a href="login.php">Přihlásit se</a>
            </form>
        </div>
    </body>
</html>