<?php
// var d'id MySQL
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', '');
define('DB_DATABASE', 'exo_post');
define('TABLE_LOGIN', 'login_table');
define('TABLE_RSS', 'RSS_1monde');
define('TABLE_POST', 'article');
define('TABLE_TEMPO', 'login_temp');

try {
    // connexion db avec ça création si elle existe
    $conn = new PDO("mysql:host=" . DB_HOST, DB_USER, DB_PASSWORD);
    // on def le mode d'erreur de PDO sur Exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    // creation et utilisation d'une base de donné
    $createDB = "CREATE DATABASE IF NOT EXISTS " . DB_DATABASE . " DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
    USE " . DB_DATABASE . ";";
    $conn->exec($createDB);
    // creation et utilisation de la TABLE_POST
    $createTable = "
    CREATE TABLE IF NOT EXISTS " . TABLE_LOGIN . " (
    `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `log_user` varchar(250) NOT NULL,
    `log_mail` varchar(50) NOT NUll,
    `log_password` varchar(250) NOT NULL,
    `id_job` int(11) UNSIGNED NOT NULL,
    `date_inscrit` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    $conn->exec($createTable);
    // creation et utilisation de la TABLE_LOGIN
    $createTable = "
    CREATE TABLE IF NOT EXISTS " . TABLE_POST . " (
    `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `titre` varchar(250) NOT NULL,
    `date` datetime NOT NULL,
    `commentaire` text NOT NULL,
    `photo` varchar(250) NOT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    $conn->exec($createTable);
    // creation et utilisation de la TABLE_RSS
    $createTable = "
    CREATE TABLE IF NOT EXISTS " . TABLE_RSS . " (
    `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `titre` varchar(250) NOT NULL,
    `date` varchar(30) NOT NULL,
    `commentaire` text NOT NULL,
    `url` varchar(250) NOT NULL,
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    $conn->exec($createTable);
    // creation et utilisation de la TABLE_TEMPO
    $createTable = "
    CREATE TABLE IF NOT EXISTS " . TABLE_TEMPO . " (
    `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
    `log_user` varchar(250) NOT NULL,
    `log_mail` varchar(50) NOT NUll,
    `log_password` varchar(250) NOT NULL,
    `id_job` int(11) UNSIGNED NOT NULL,
    `token` varchar(64),
    PRIMARY KEY (`id`)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";
    $conn->exec($createTable);
}
// pour les erreurs de co à la base
catch (PDOException $e) {
    die("<p>Impossible de se connecter au serveur " . DB_DATABASE . " : " . $e->getMessage() . "</p>");
}
// On ferme la co
$conn = null;
