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

        // $tryCo = 0;
        if (!empty($_GET)) {
            // recup de var du mail
            $token = $_GET['t'];
            $id = $_GET['id'];
            try {
                // connexion db temporaire
                $db = new Database();
                $conn = $db->getPDO();

                // recherche l'id et le token enregistrer / 
                $sql = "SELECT * FROM " . TABLE_TEMPO . " WHERE id = '$id' AND token = '$token'";
                $result = $conn->query($sql);
                $row = $result->fetch(PDO::FETCH_ASSOC);
                if ($row['id'] == $id && $row['token'] == $token) {
                    // update du token
                    $sqlUpdate = "UPDATE " . TABLE_TEMPO . " SET token = NULL WHERE id = '$id' AND token = '$token'";
                    $conn->exec($sqlUpdate);

                    // récup des donner du tuple
                    $user = $row['log_user'];
                    $email = $row['log_mail'];
                    $password = $row['log_password'];
                    $job = $row['id_job'];

                    // effacement du tuple
                    $sqlDel = "DELETE FROM " . TABLE_TEMPO . " WHERE id = '$id'";
                    $conn->exec($sqlDel);

                    // save du tuple temp vers la nouvelle db
                    $sth = $conn->prepare("
                    INSERT IGNORE INTO " . TABLE_LOGIN . "( log_user, log_mail, log_password, id_job) 
                    VALUES (:user, :email, :password, :id_job);
                    ");
                    $params = [':user', ':email', ':password', ':id_job'];
                    $vars = [$user, $email, $password, $job];
                    $sth = dbInsert($sth, $params, $vars);

                    // message d’inscription réussit
                    echo '<div class="alert alert-success alert-dismissible"><span>Votre inscription a été validé.</span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                    $_SESSION['login'] = true;
                    $_SESSION['job'] = $row['id_job'];
                    echo "Suivez le lien pour continuer : <a href='../../index.php'>cliquer ici.</a>";
                } else {
                    // effacement du tuple
                    $sqlDel = "DELETE FROM " . TABLE_TEMPO . " WHERE id = '$id'";
                    $conn->exec($sqlDel);

                    // message d'erreur token et id !=
                    echo '<div class="alert alert-danger alert-dismissible"><span>Erreurs : votre inscription n\'a été pas validé.</span>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                    echo "Suivez le lien pour recommencer : <a href='./sign_in.php'>cliquer ici.</a>";
                }
            }

            // pour les erreurs de co à la base
            catch (PDOException $e) {
                die("<p>Impossible de se connecter au serveur " . DB_DATABASE . " : " . $e->getMessage() . "</p>");
            }
        } else {
            header("Location:./sign_in.php");
        }
        ?>
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