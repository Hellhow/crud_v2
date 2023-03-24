<?php
require_once "../../inc/header.php";
?>
<!DOCTYPE html>

<!-- ANCHOR Signature

    ._________.
    | > \   < |
    | \\[T]// |
    |  \|O|/  |
    |   |Y|   |
    |  _|||_  |
    |_________|

 -->

<html lang="FR-fr">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="author" content="SIRJACQUES Vincent">
    <meta name="copyright" content="SIRJACQUES Vincent">
    <meta name="robots" content="index, follow">
    <meta name="rating" content="general">

    <!-- ANCHOR titre -->
    <title>Exercice de connexion - login</title>
    <meta name="description" content="...">

    <!-- ANCHOR icon de la page -->
    <!-- <link rel="shortcut icon" href="..." type="image/x-icon"> -->

    <!-- SECTION CSS -->

    <!-- ANCHOR CSS Icon -->
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css"> -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.3/font/bootstrap-icons.css">


    <!-- ANCHOR CSS framework -->
    <!-- <link rel="stylesheet" href="css/1_reset.css"> -->
    <!-- <link rel="stylesheet" href="css/2_normalize.css"> -->
    <link rel="stylesheet" href="../../asset/bootstrap.css">

    <!-- ANCHOR css framework custom -->
    <!-- <link rel="stylesheet" href="./css/mode.css"> -->

    <!-- ANCHOR CSS Custom-->
    <link rel="stylesheet" href="../../css/main.css">

    <!-- !SECTION CSS -->

    <!-- SECTION JS__head -->

    <!-- ANCHOR JS framework -->
    <script src="../../asset/bootstrap.js" defer></script>

    <!-- ANCHOR JS custom -->
    <!-- <script src="./js/main.js" async></script> -->

    <!-- !SECTION JS__head -->

</head>

<body class="container text-center">

    <!-- SECTION header -->
    <header class="row my-3">
        <h1>Page de connection</h1>
    </header>
    </aside>
    <!-- !SECTION header -->

    <!-- SECTION main -->
    <main class="row my-3">
        <?php

        // page inutile si déjà connecter
        session_start();
        if (!empty($_SESSION)) {
            header("Location:../../index.php");
        }
        session_destroy();

        // msg d'inscription
        if (!empty($_GET)) {
            echo base64_decode($_GET['m']);
        }
        // $tryCo = 0;
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            // var du form de co
            $userName = $_POST['username'];
            $password = $_POST['password'];

            // connexion de l'utilisateur
            $user = new User($userName, $password);
            $testCo = $user->connexion(TABLE_LOGIN);
            if ($testCo) {
                header("Location:../../index.php?");
            }
        }
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="row my-3 container border rounded">
            <div class="row my-1">
                <!-- champ user -->
                <label class="offset-md-4 col-md-2" for="username">User name :</label>
                <input class="col-md-2" type="text" id="username" name="username" require>
            </div>
            <div class="row my-1">
                <!-- champ mdp -->
                <label class="offset-md-4 col-md-2" for="password">password :</label>
                <input class="col-md-2" type="password" id="password" name="password" require>
            </div>
            <div class="row my-1">
                <!-- btn de co ou inscri -->
                <button class="offset-md-4 col-md-2 btn btn-primary" type="submit">Connexion</button>
                <a class="col-md-2 btn btn-secondary" href="./sign_in.php">Inscrivez-vous</a>
            </div>
        </form>
    </main>
    <!-- !SECTION main -->

    <!-- SECTION footer -->
    <footer>

    </footer>
    <!-- !SECTION footer -->
    <?php
    // On ferme la co
    $conn = null;
    ?>
</body>

</html>