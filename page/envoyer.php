<?php
require_once "../inc/header.php";

// vérification de connexion
session_start();
if ($_SESSION['login'] != true) {
    header("Location:./log/login.php");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // var du form
    $title = txtSlashes($_POST['title']);
    $resume = txtSlashes($_POST['resume']);
    /* $_FILES est contenu dans POST, tableau qui contient : 
            $_FILES = [
                'name_file(n)' => [
                    'name' => string 'nomFichier.extensions',
                    'type' => string 'type/format',
                    'tmp_name' => string 'adresse local temporaire du fichier en .tmp',
                    'error' => int 0 à 8 sans 5,
                    'size' => int le poids
                    ]
                ]
            */
    $pic = $_FILES['pic'];

    // var utilitaire
    $date = date("Y-m-j H:i:s");

    // contrôle du form
    if (empty($title) || strlen($_POST['title']) > 20) {
        die("<p>S'il vous plais entrez un titre valide de moins de 16 caractères</p>");
    }
    if (empty($resume) || strlen($_POST['resume']) > 250) {
        die("<p>S'il vous plais entrez un commentaire valide de moins de 250 caractères</p>");
    }
    if (empty($pic)) {
        die("<p>S'il vous plais sélectionner une photo</p>");
    }

    // on s'assure que le fichier envoyé est bien de type image
    if (!isset($_FILES['pic']['type'])) {
        // obtenir le type MIME de l'image
        $mime_type = exif_imagetype($_FILES['pic']['tmp_name']);
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

    try {
        // connexion db avec ça création si elle existe
        $db = new Database();
        $conn = $db->getPDO();
        unset($db);

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
            $fullNameFile = $_FILES['pic']['name'];
            $nameFile = pathinfo($fullNameFile, PATHINFO_FILENAME);
            $fileExtension = pathinfo($fullNameFile, PATHINFO_EXTENSION);

            // rename img : fct
            $nameFile = strtolower(str_to_noaccent($nameFile));
            $fullNameFile = $nameFile . '_' . time() . '.' . $fileExtension;

            // save l'image vers le chemin choisi
            $chemin_destination = "../pic/";
            move_uploaded_file($_FILES['pic']['tmp_name'], $chemin_destination . $fullNameFile);
        } else {
            die("<p>Erreur inattendu : $picError</p>");
        }

        // insertion des données
        $sth = new Crud();
        $sth->create(TABLE_POST, $title, $date, $resume, $fullNameFile);
        unset($sth);
        header("Location:./poster.php");
    }

    // On capt les exceptions si une exception est lancée et on affiche les info relat à celle-ci
    catch (PDOException $e) {
        die("<p>Impossible de se connecter au serveur $serverName : " . $e->getMessage() . "</p>");
    }

    // On ferme la co
    unset($conn);
}
