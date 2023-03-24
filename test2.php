<?php
require_once "./inc/header.php";

// vérification de connexion
session_start();
if ($_SESSION['login'] != true) {
    header("Location:./page/log/login.php");
}
try {
    // connexion db avec ça création si elle existe
    $db = new Database();
    $conn = $db->getPDO();
    unset($db);

    // enregistre les données
    $sql = "DELETE FROM " . TABLE_RSS;
    $conn->exec($sql);
    header("Location:./index.php");
}
// pour les erreurs de co à la base
catch (PDOException $e) {
    die("<p>Impossible de se connecter au serveur " . DB_DATABASE . " : " . $e->getMessage() . "</p>");
}
// On ferme la co
unset($conn);
