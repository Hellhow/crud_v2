<?php
require_once "./inc/header.php";
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
    <title>Poste</title>
    <meta name="description" content="...">

    <!-- SECTION CSS -->
    <!-- ANCHOR icon de la page -->
    <!-- <link rel="shortcut icon" href="..." type="image/x-icon"> -->

    <!-- ANCHOR CSS Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- ANCHOR CSS framework -->
    <!-- <link rel="stylesheet" href="css/1_reset.css"> -->
    <!-- <link rel="stylesheet" href="css/2_normalize.css"> -->
    <!-- <link rel="stylesheet" href="./asset/bootstrap.css"> -->

    <link rel="stylesheet" href="./css/mode.css">

    <!-- ANCHOR CSS Custom-->
    <link rel="stylesheet" href="./css/main.css">
    <!-- !SECTION CSS -->

    <!-- SECTION JS__head -->
    <!-- <script src="./asset/bootstrap.js"></script> -->
    <script src="./js/main.js" async></script>
    <!-- !SECTION JS__head -->

</head>

<body>

    <header>
        <h1>EXO publication de poste</h1>
    </header>
    <main>
        <?php
        // vérification de connexion
        session_start();
        if ($_SESSION['login'] != true) {
            header("Location:./page/log/login.php");
        }
        // if (!empty($_GET)) {
        //     echo base64_decode($_GET['m']);
        // }
        echo '<h2 class="txt-center border-dark">Bonjour M. ';
        switch ($_SESSION['job']) {
            case 0:
                echo "l'administrateur";
                break;
            case 1:
                echo "l'inscrit";
                break;
            case 9:
                echo "BADBEAFORE";
                break;
            default:
                break;
        }
        echo ".</h2>";

        // fermeture de la session après confirmation
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            session_destroy();
            header("Location:./page/log/login.php");
        }

        try {
            // connexion db avec ça création si elle existe
            $db = new Database();
            $conn = $db->getPDO();
            unset($db);

            displayCards(TABLE_POST);

            displayRss(TABLE_RSS);
        }

        // pour les erreurs de co à la base
        catch (PDOException $e) {
            die("<p>Impossible de se connecter au serveur " . DB_DATABASE . " : " . $e->getMessage() . "</p>");
        }

        // On ferme la co
        unset($conn);
        ?>
    </main>
    <footer class="d-flex g-1">
        <a class="btn txt-light bg-blue t_deco-none" href="./page/form.php">Ajouter un article</a>
        <!-- php if table de RSS n'existe pas : afficher le btn
            Sinon : pas afficher le btn -->
        <a class="btn txt-light bg-blue t_deco-none" href="./test.php">Ajouter les articles RSS</a>
        <a class="btn txt-light bg-red t_deco-none" href="./test2.php">Supprimer les articles RSS</a>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
            <button class="btn bg-red txt-light" type="submit">Déconnexion</button>
        </form>

    </footer>

</body>

</html>