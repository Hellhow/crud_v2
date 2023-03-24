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
    <script src="../../asset/bootstrap.js" async></script>

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

        if (!empty($_GET)) {
            echo base64_decode($_GET['m']);
        }

        // var utilitaire
        $dateCo = date("H:i:s d-m-Y");
        $pw_regex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[a-zA-Z\d@$!%*?&]{8,}$/i';
        $txt_regex = '/^[a-Z0-9_-]{3,15}$/i';
        $test = true;

        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // vérification des champs
            foreach ($_POST as $key => $value) {
                if (empty($value)) {
                    // champ vide
                    echo ('<div class="alert alert-danger alert-dismissible"><span>Erreur : champ ' . $key . ' est vide.</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                    $test = false;
                    break;
                }
                if ($key == 'user' && preg_match($txt_regex, $value)) {
                    // regex user
                    echo ('<div class="alert alert-danger alert-dismissible">
                    <span>Erreur : votre ' . $key . ' est invalide, il faux entre 3 et 15 caractères sans caractère spécial autre que "-" et "_".</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                    $test = false;
                    break;
                }
                if ($key == 'email' && !filter_var(trim($value, " \n\r\t\v\x00...\x1F"), FILTER_VALIDATE_EMAIL)) {
                    echo ('<div class="alert alert-danger alert-dismissible"><span>Erreur : champ ' . $key . ' est invalide.</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                    $test = false;
                    break;
                }
                if ($key == 'password' && preg_match($pw_regex, $value) == 0) {
                    // regex mdp
                    echo ('<div class="alert alert-danger alert-dismissible">
                    <span>Erreur : votre ' . $key . ' est invalide, il faux 8 caractères minimum avec une lettre en minuscule, une en majuscule, un nombre et un caractère spécial.</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                    $test = false;
                    break;
                }
                if ($_POST['password'] != $_POST['pwConfirm']) {
                    // confirm du mdp
                    echo ('<div class="alert alert-danger alert-dismissible"><span>Erreur : les champ mot de passe et confirmer votre mot de passe ne sont pas identique.</span>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                    $test = false;
                    break;
                }
            }
            foreach ($_POST as $key => $value) {
                switch ($key) {
                    case 'user':
                        // sécurisation des caractères
                        $_POST[$key] = htmlentities(addslashes(trim($value, " \n\r\t\v\x00...\x1F")), ENT_QUOTES);
                        break;
                        // case 'password':
                        //     $_POST[$key] = password_hash($value, PASSWORD_BCRYPT);
                        //     break;
                    default:
                        break;
                }
            }

            // var du form de co
            $user = $_POST['username'];
            $password = $_POST['password'];
            $email = $_POST['email'];


            if ($test) {
                // si pas d'erreur détecté
                try {
                    // connexion db login
                    $db = new Database();
                    $conn = $db->getPDO();

                    // récupération des données de la DB
                    $sql = "SELECT log_user, log_password FROM " . TABLE_LOGIN . " WHERE log_user = '$user' LIMIT 1;";
                    $sqlResult = $conn->query($sql);
                    $tuple = $sqlResult->fetch(PDO::FETCH_ASSOC);

                    // vérification de d’existante 
                    if (!$tuple) {
                        // enregistre les données
                        // requête 
                        $sth = $conn->prepare("
                    INSERT IGNORE INTO " . TABLE_TEMPO . "( log_user, log_mail, log_password, id_job , token) 
                    VALUES (:user, :email, :password, :id_job, :token);
                    ");
                        $params = [':user', ':email', ':password', ':id_job', 'token'];
                        $vars = [$user, $email, $password, 1, $token];
                        // id_job sera 1 par défaut (rôle à l'inscription)
                        $sth = dbInsert($sth, $params, $vars);

                        // récupération de l'id généré
                        $tuple = new User($user, $password);
                        $tuple = $tuple->getObject(TABLE_TEMPO, 'log_user', $user);
                        $id = $tuple['id'];
                        unset($tuple);

                        // envoie de mail
                        require_once "../../classes/mail.class.php";
                        $send = new Mail();
                        if ($send->Post($email, $user, $id, $token) || $_SERVER['SERVER_NAME'] == "localhost") {
                            $msg = '<div class="alert alert-success alert-dismissible"><span>Mail envoyer avec succès.</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                            header("Location:sign_in.php?m=" .  urlencode(base64_encode($msg)));
                        } else {
                            // erreur d'envoi du mail
                            echo '<div class="alert alert-danger alert-dismissible"><span>Erreur : échec de l\'envoie du mail.</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                        }
                    } else {
                        // existences de l'user
                        echo ('<div class="alert alert-danger alert-dismissible"><span>Erreur : ' . $user . ' existe déjà.</span>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
                    }
                }

                // pour les erreurs de co à la base
                catch (PDOException $e) {
                    die("<p>Impossible de se connecter au serveur " . DB_DATABASE . " : " . $e->getMessage() . "</p>");
                }
            }
        }
        ?>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="row my-3 container border rounded">
            <div class="row my-1">
                <!-- champ mdp confirm -->
                <label class="offset-md-4 col-md-2" for="username">User name :</label>
                <input class="col-md-2" type="text" id="username" name="username" require>
            </div>
            <div class="row my-1">
                <!-- champ email -->
                <label class="offset-md-4 col-md-2" for="email">Email :</label>
                <input class="col-md-2" type="email" id="email" name="email" require>
            </div>
            <div class="row my-1">
                <!-- champ mdp -->
                <label class="offset-md-4 col-md-2" for="password">password :</label>
                <input class="col-md-2" type="password" id="password" name="password" require>
            </div>
            <div class="row my-1">
                <!-- champ mdp confirm -->
                <label class="offset-md-4 col-md-2" for="pwConfirm">Confirm password :</label>
                <input class="col-md-2" type="password" id="pwConfirm" name="pwConfirm" require>
            </div>
            <div class="row my-1">
                <!-- btn de co ou inscri -->
                <button class="offset-md-4 col-md-2 btn btn-primary" type="submit">Inscrivez-vous</button>
                <a class="col-md-2 btn btn-secondary" href="./login.php">Connectez-vous</a>
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