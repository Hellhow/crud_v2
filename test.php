<?php
require_once "./inc/header.php";
// vérification de connexion
session_start();
if ($_SESSION['login'] != true) {
    header("Location:./page/log/login.php");
}
// var lié au flux rss
// $url = "./RSS/1mode.xml";
$url = "https://www.lemonde.fr/rss/une.xml";
$xml = simplexml_load_file($url);
// $max = count($xml) - 6;
$i = 0;
foreach ($xml->channel->item as $item) {
    $titres[$i] = ($item->title);
    $dates[$i] = ($item->pubDate);
    $commentaires[$i] = ($item->description);
    $urls[$i] = ($item->link);
    $i++;
}
foreach ($dates as $key => $value) {
    // jour en FR
    switch (substr($value, 0, 3)) {
        case 'Mon':
            $value = str_replace("Mon", "Lun", $value);
            break;
        case 'Tue':
            $value = str_replace("Tue", "Mar", $value);
            break;
        case 'Wed':
            $value = str_replace("Wed", "Mer", $value);
            break;
        case 'Thu':
            $value = str_replace("Thu", "Jeu", $value);
            break;
        case 'Fri':
            $value = str_replace("Fri", "Ven", $value);
            break;
        case 'Sat':
            $value = str_replace("Sat", "Sam", $value);
            break;
        case 'Sun':
            $value = str_replace("Sun", "Dim", $value);
            break;
        default:
            break;
    }
    // heure de Paris
    $value = substr($value, 0, 17) . (intval(substr($value, 17, 2)) + 1) . substr($value, 19, 6);
    // mois en FR
    switch (substr($value, 8, 3)) {
        case 'Jan':
            $value = str_replace("Jan", "Janv.", $value);
            break;
        case 'Feb':
            $value = str_replace("Feb", "Févr.", $value);
            break;
        case 'Mar':
            $value = str_replace("Mar", "Mars", $value);
            break;
        case 'Apr':
            $value = str_replace("Apr", "Avril", $value);
            break;
        case 'May':
            $value = str_replace("May", "Mai", $value);
            break;
        case 'Jun':
            $value = str_replace("June", "Juin", $value);
            break;
        case 'Jul':
            $value = str_replace("July", "Juil.", $value);
            break;
        case 'Aug':
            $value = str_replace("Aug", "août", $value);
            break;
        case 'Sep':
            $value = str_replace("Sept", "Sept.", $value);
            break;
        case 'Oct':
            $value = str_replace("Oct", "Oct.", $value);
            break;
        case 'Nov':
            $value = str_replace("Nov", "Nov.", $value);
            break;
        case 'Dec':
            $value = str_replace("Dec", "Déc.", $value);
            break;
        default:
            break;
    }
    $tempo[$key] = $value;
}
$dates = $tempo;
try {
    // connexion db avec ça création si elle existe
    $db = new Database();
    $conn = $db->getPDO();
    unset($db);

    // enregistre les données
    $sth = $conn->prepare("
        INSERT IGNORE INTO " . TABLE_RSS . "(titre, date, commentaire, url) 
        VALUES (:titre, :date, :commentaire, :url);
        ");
    $params = [':titre', ':date', ':commentaire', ':url'];
    $i = 0;
    foreach ($xml->channel->item as $item) {
        $vars = [txtSlashes($titres[$i]), $dates[$i], txtSlashes($commentaires[$i]), trim($urls[$i])];
        $sthF = dbInsert($sth, $params, $vars);
        $i++;
    }
    header("Location:./index.php");
}
// pour les erreurs de co à la base
catch (PDOException $e) {
    die("<p>Impossible de se connecter au serveur " . DB_DATABASE . " : " . $e->getMessage() . "</p>");
}
// On ferme la co
unset($conn);
