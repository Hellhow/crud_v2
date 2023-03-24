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

    <h1>EXO publication de poste</h1>
    <!-- enctype pour envoyer des données récup par $_FILES -->
    <form action="envoyer.php" method="post" class="card__form" enctype="multipart/form-data">
        <fieldset class="card__formBlock">
            <legend>Formulaire d'ajout de contenu au Blog</legend>
            <div class="card__formBlock">
                <label for="title">Titre :</label>
                <input type="text" id="title" name="title">
            </div>
            <div class="card__formBlock">
                <label for="resume">Commentaire :</label>
                <textarea name="resume" id="resume" cols="30" rows="10"></textarea>
            </div>
            <div class="card__formBlock">
                <label for="pic">Choisissez une photo avec une taille inférieur à 2 Mo :</label>
                <!-- accept tous les fichier images quand /* sinon lister la limite -->
                <input type="file" id="pic" name="pic" accept="image/*">
            </div>
            <div class="card__formBlock">
                <!-- limite à 30 000 Octets de données -->
                <!-- <input type="hidden" name="MAX_FILE_SIZE" value="30000"> -->
                <button class="btn" type="submit">Envoyer</button>
            </div>
        </fieldset>
    </form>
    <footer>
        <ul>
            <li><a class="btn txt-light bg-blue t_deco-none" href="../index.php">Page des postes</a></li>
        </ul>
    </footer>
</body>

</html>