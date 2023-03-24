<?php
require_once "../inc/header.php";
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
    <title>Supprimer</title>
    <meta name="description" content="...">

    <!-- SECTION CSS -->
    <!-- ANCHOR icon de la page -->
    <!-- <link rel="shortcut icon" href="..." type="image/x-icon"> -->

    <!-- ANCHOR CSS Icon -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <!-- ANCHOR CSS framework -->
    <!-- <link rel="stylesheet" href="css/1_reset.css"> -->
    <!-- <link rel="stylesheet" href="css/2_normalize.css"> -->

    <link rel="stylesheet" href="../css/mode.css">

    <!-- ANCHOR CSS Custom-->
    <link rel="stylesheet" href="../css/main.css">
    <!-- !SECTION CSS -->

    <!-- SECTION JS__head -->
    <script src="../js/main.js" async></script>
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
            header("Location:./log/login.php");
        }

        // id du tuples obtenue a la page d'avant
        $id = base64_decode($_GET['m']);
        if ($_SERVER["REQUEST_METHOD"] == "POST") {

            $sth = new Crud();
            // suppression de la photo
            $sth->delPic(TABLE_POST, $id);
            // suppression du tuples
            $sth->delete(TABLE_POST, $id);
            unset($sth);

            echo "<p>Le poste a bien été supprimé avec son image</p>";

            header('Location:../index.php');
        }

        ?>
        <h2>Vous allez supprimé définitivement vous données <?php echo $id ?>, êtes vous sur de les supprimer ?</h2>
        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]) . '?m=' . urlencode(base64_encode($id)); ?>" method="post">
            <button class="btn bg-red txt-light" type="submit">Supprimer</button>
            <a class="btn txt-center t_deco-none txt-dark" href="../index.php">Annuler</a>
        </form>
    </main>

</body>

</html>