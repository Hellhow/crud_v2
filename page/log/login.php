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

        // msg d'inscription
        if (!empty($_GET)) {
            echo base64_decode($_GET['m']);
        }
        // $tryCo = 0;
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            // var du form de co
            $user = $_POST['username'];
            $password = $_POST['password'];
            $dateCo = date("H:i:s d-m-Y");
            try {
                // connexion db avec ça création si elle existe
                $db = new Database();
                $conn = $db->getPDO();

                // vérification de connexion
                $sql = "SELECT log_user, log_password, id_job FROM " . TABLE_LOGIN . " WHERE log_user = '$user' LIMIT 1;";
                $sqlResult = $conn->query($sql);
                $tuple = $sqlResult->fetch(PDO::FETCH_ASSOC);
                if ($tuple === false) {
                    // user n'existe pas
                    echo ('<div class="alert alert-danger alert-dismissible"><span>Erreur : ' . $user . ' est inexistant.</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                } elseif (!password_verify($password, $tuple['log_password'])) {
                    // pas le bon mdp
                    echo ('<div class="alert alert-danger alert-dismissible"><span>Erreur : mot de passe incorrect.</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                    // $tryCo++;
                } else {
                    // co de l'user
                    session_start();
                    $_SESSION['login'] = true;
                    $_SESSION['job'] = $tuple['id_job'];
                    header("Location:../../index.php?");
                }
            }

            // pour les erreurs de co à la base
            catch (PDOException $e) {
                die("<p>Impossible de se connecter au serveur " . DB_DATABASE . " : " . $e->getMessage() . "</p>");
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