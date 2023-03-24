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

        // id transité par url
        $id = base64_decode($_GET['m']);
        echo '<div class="txt-center py-1 border-dark mw-1320 m-auto mb-1 bg-red"><span>Modification de l\'article n°' . $id . '</span></div>';

        try {
            // connexion db avec ça création si elle existe
            $db = new Database();
            $conn = $db->getPDO();
            unset($db);

            // récupération des donner
            $requete = "SELECT * FROM " . TABLE_POST . " WHERE `id` = $id";
            $stResult = $conn->query($requete);
            if ($stResult === false) {
                die("Erreur");
            }
            $row = $stResult->fetch(PDO::FETCH_ASSOC);
            // nom de la photo d'avant
            $oldPicName = $row['photo'];
            // génération du formulaire à éditer
            echo '
                <form action="' . htmlspecialchars($_SERVER["PHP_SELF"]) . '?m=' . urlencode(base64_encode($id)) . '" method="post" class="card__form""  enctype="multipart/form-data">
                <fieldset class="card__formBlock">
                    <legend>Formulaire de modification du contenu du Blog</legend>
                    <div class="card__formBlock">
                        <label for="title">Titre :</label>
                        <input type="text" id="title" name="title" value=' . str_replace(" ", "&#160;", stripslashes($row['titre'])) . '>
                    </div>
                    <div class="card__formBlock">
                        <label for="resume">Commentaire :</label>
                        <textarea name="resume" id="resume" cols="30" rows="10">' . stripslashes($row['commentaire']) . '</textarea>
                    </div>
                    <div class="card__formBlock">
                <label for="pic">Choisissez une photo avec une taille inférieur à 2 Mo :</label>
                <!-- accept tous les fichier images quand /* sinon lister la limite -->
                <input type="file" id="pic" name="pic" accept="image/*">
            </div>
                    <div class="card__formBlock">
                        <button class="btn txt-light bg-blue t_deco-none" type="submit">Envoyer</button>
                        <a class="btn txt-center t_deco-none txt-dark" href="../index.php">Annuler</a>
                    </div>
                </fieldset>
            </form>
                ';

            if ($_SERVER["REQUEST_METHOD"] == "POST") {

                // contrôle du form
                if (empty($_POST['title']) || strlen($_POST['title']) > 20) {
                    die("<p>S'il vous plais entrez un titre valide de moins de 16 caractères</p>");
                }
                if (empty($_POST['resume']) || strlen($_POST['resume']) > 250) {
                    die("<p>S'il vous plais entrez un commentaire valide de moins de 250 caractères</p>");
                }

                // var du form
                $title = htmlentities(addslashes($_POST['title']), ENT_QUOTES);
                $resume = htmlentities(addslashes($_POST['resume']), ENT_QUOTES);

                // contrôle du form
                if (empty($title) || strlen($_POST['title']) > 20) {
                    die("<p>S'il vous plais entrez un titre valide de moins de 16 caractères</p>");
                }
                if (empty($resume) || strlen($_POST['resume']) > 250) {
                    die("<p>S'il vous plais entrez un commentaire valide de moins de 250 caractères</p>");
                }

                // si la photo est modifier ou non
                if (empty($_FILES['pic']['name'])) {
                    $fullNameFile = $oldPicName;
                } else {
                    $pic = $_FILES['pic'];
                    // on s'assure que le fichier envoyé est bien de type image
                    if (!isset($pic['type'])) {
                        // obtenir le type MIME de l'image
                        $mime_type = exif_imagetype($pic['tmp_name']);
                        // vérifier si le type MIME correspond à une image
                        if ($mime_type === false || ($mime_type != IMAGETYPE_JPEG && $mime_type != IMAGETYPE_PNG && $mime_type != IMAGETYPE_GIF)) {
                            // afficher un message d'erreur à l'utilisateur
                            echo "Vous ne pouvez transférer que des images JPEG, PNG ou GIF.";
                            die("Seules les images de type JPG et PNG sont autorisées");
                        } else {
                            // Le fichier est une image valide, nous pouvons le transférer
                            // et ajouter du code de traitement ici...
                        }
                    }
                    // recherche d'erreur du fichier image
                    if ($picError = $pic['error'] != 0) {
                        switch ($picError) {
                            case 1:
                                die("<p>La taille du fichier dépasse la valeur maximal autorisé par php.ini . Veuillez compresser votre image.</p>");
                                break;
                            case 2:
                                die("<p>La taille du fichier dépasse la valeur maximal autorisé par le site. Veuillez compresser votre image.</p>");
                                break;
                            case 3:
                                die("<p>Erreur de téléversement, téléversement incomplet.</p>");
                                break;
                            case 4:
                                die("<p>Le fichier n'a pas été téléversé./p>");
                                break;
                            default:
                                die("<p>Erreur technique. Code erreur : $picError</p>");
                                break;
                        }
                    } elseif ($picError == 0) {
                        // rename img : var utilisé
                        $fullNameFile = $pic['name'];
                        $nameFile = pathinfo($fullNameFile, PATHINFO_FILENAME);
                        $fileExtension = pathinfo($fullNameFile, PATHINFO_EXTENSION);

                        // rename img : fct
                        $nameFile = strtolower(str_to_noaccent($nameFile));
                        $fullNameFile = $nameFile . '_' . time() . '.' . $fileExtension;

                        // save l'image vers le chemin choisi
                        $chemin_destination = "../pic/";
                        move_uploaded_file($pic['tmp_name'], $chemin_destination . $fullNameFile);

                        // suppression du fichier
                        $fileLocal = $chemin_destination . $oldPicName;
                        if (file_exists($fileLocal)) {
                            unlink($fileLocal);
                        }
                    } else {
                        die("<p>Erreur inattendu : $picError</p>");
                    }
                }

                // update de la data base
                // NOTE bug avec édite espace
                // $titre = str_replace("&nbsp;", " ", $titre);
                $sql = "UPDATE " . TABLE_POST . " SET titre = '$title', commentaire = '$resume', photo = '$fullNameFile' WHERE id = '$id'";
                $conn->exec($sql);

                header('Location:../index.php');
            }
        }

        // pour les erreurs de co à la base
        catch (PDOException $e) {
            die("<p>Impossible de se connecter au serveur $serverName : " . $e->getMessage() . "</p>");
        }

        // On ferme la co
        unset($conn);
        ?>
    </main>


</body>

</html>