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
    <title>Formulaire</title>
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
    <?php
    // vérification de connexion
    session_start();
    if ($_SESSION['login'] != true) {
        header("Location:./log/login.php");
    }
    ?>

    <header>
        <h1>EXO publication de poste - article posté</h1>
    </header>
    <main>
        <p>Fichier téléversé avec succès.</p>
        <p>Votre article à bien été enregistré.</p>
    </main>
    <footer class="d-flex g-1">
        <a class="btn txt-light bg-blue t_deco-none" href="./form.php">Ajouter un article</a>
        <a class="btn txt-light bg-blue t_deco-none" href="../index.php">Page des postes</a>
    </footer>
</body>

</html>