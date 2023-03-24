# Projet blogue avec un crud et un login
Contenu du fichier db_config.php dans le doc .config, pour générer la Database avec les tables (utilisez vos paramètre de connexion à votre DB et pensez à nommer la DB et les tables)
```php 
<?php
define('DB_HOST', '');
define('DB_USER', '');
define('DB_PASSWORD', '');
define('DB_DATABASE', '');
define('TABLE_LOGIN', '');
define('TABLE_RSS', '');
define('TABLE_POST', '');
define('TABLE_TEMPO', '');


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

```