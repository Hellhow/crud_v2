<?php
// ANCHOR Fonction permettant de renommer un ficher sans accent et sans caractères non autorisés, à ajouter à votre fichier de fonctions personnelles
function str_to_noaccent(string $str)
{
    $url = $str;
    /* gestion des espacements ----------------------  */
    $url = preg_replace('#   #', '_', $url);
    $url = preg_replace('#  #', '_', $url);
    $url = preg_replace('# #', '_', $url);
    $url = preg_replace('#_-_#', '_', $url);
    $url = preg_replace('#___#', '_', $url);
    $url = preg_replace('#__#', '_', $url);
    $url = preg_replace("#'#", '-', $url);
    $url = preg_replace("#¨#", '', $url);
    /* caractères accentués -------------------------- */
    $url = preg_replace('#Ç#', 'C', $url);
    $url = preg_replace('#ç#', 'c', $url);
    $url = preg_replace('#è|é|ê|ë#', 'e', $url);
    $url = preg_replace('#È|É|Ê|Ë#', 'E', $url);
    $url = preg_replace('#à|á|â|ã|ä|å#', 'a', $url);
    $url = preg_replace('#@|À|Á|Â|Ã|Ä|Å#', 'A', $url);
    $url = preg_replace('#ì|í|î|ï#', 'i', $url);
    $url = preg_replace('#Ì|Í|Î|Ï#', 'I', $url);
    $url = preg_replace('#ð|ò|ó|ô|õ|ö#', 'o', $url);
    $url = preg_replace('#Ò|Ó|Ô|Õ|Ö#', 'O', $url);
    $url = preg_replace('#ù|ú|û|ü#', 'u', $url);
    $url = preg_replace('#Ù|Ú|Û|Ü#', 'U', $url);
    $url = preg_replace('#ý|ÿ#', 'y', $url);
    $url = preg_replace('#Ý#', 'Y', $url);
    $url = preg_replace('#ñ#', 'n', $url);
    $url = preg_replace('#Ñ#', 'N', $url);
    /* autres ----------------------------  */
    return ($url);
}

/*insertion de données
attention les array doivent avoir la même clé, donc utilisé les default key
$sth = $conn->prepare("requête sql d'INSERT")
appelle : $sthF =dbInsert($sth, ..., ...);*/
function dbInsert($sth, array $params, array $vars)
{
    foreach ($params as $key => $value) {
        $sth->bindParam($value, $vars[$key]);
    }
    return $sth->execute(); // order 66
}

// fct SUM
function sum(...$nb)
{
    $result = 0;
    foreach ($nb as $value) {
        $result += $value;
    }
    return $result;
}

// fct Prod
function prod(...$nb)
{
    $result = 1;
    foreach ($nb as $value) {
        $result *= $value;
    }
    return $result;
}

// fct factorielle
function fact($nb)
{
    $result = 1;
    for ($i = 1; $i <= $nb; $i++) {
        $result *= $i;
    }
    return $result;
}

// ANCHOR traitement de txt (protège aussi des injections)
// penser à stripslashes($txt) pour l'afficher
function txtSlashes(string $txt)
{
    return $txt = htmlentities(addslashes(trim($txt)), ENT_QUOTES);
}

// ANCHORS fct qui renvoie les infos utiles du site
function infos_spy()
{
    // tab des cle utilisé pour connaître les infos utiles 
    $env = array(
        'remote_addr', 'http_accept_language', 'http_host', 'http_user_agent', 'script_filename', 'server_addr', 'server_name', 'server_signature', 'server_software', 'request_method', 'query_string', 'request_uri', 'script_name'
    );
    // tab qui va contenir les valeurs avec la clé associé des infos utiles
    $retour = array();
    foreach ($env as $key) {
        $retour[$key] = getenv($key);
    }
    return $retour;
}

// ANCHOR Contrôler si l'image est valide
function moveImage($image)
{
    if (isset($image) and $image['error'] == 0) {
        echo "====> Fichier reçu 👍<br>";
        // Testons si le fichier n'est pas trop gros
        if ($image['size'] <= 5000000) {
            echo "====> Taille Fichier < 5Mo 👍<br>";
            // Testons si l'extension est autorisée
            $infosfichier = pathinfo($image['name']);
            $extension_upload = $infosfichier['extension'];
            $extensions_autorisees = array('jpg', 'jpeg', 'gif', 'png');
            if (in_array($extension_upload, $extensions_autorisees)) {
                echo "====> Extension Autorisée 👍<br>";
                // On peut valider le fichier et le stocker définitivement
                move_uploaded_file($image['tmp_name'], $_SERVER['DOCUMENT_ROOT'] . '/php_exo5eurocom/upload/image/' . basename($image['name']));
                //  FIXME Attention la même image peut pas être téléversée 2 fois
                echo "====> Téléversement de <strong>" . $image['name'] . "</strong> terminé 👍<br>";
                return $image['name'];
            } else {
                echo "⚠ Erreur: Ce format de fichier n'est pas autorisé";
            }
        } else {
            echo "⚠ Erreur: le fichier dépasse 1 Mo";
        }
    } else {
        echo "⚠ Erreur: Aucune photo reçue";
        return "";
    }
}

require_once($_SERVER['DOCUMENT_ROOT'] . '/php-sql_poste_Vincent_v2/classes/database.class.php');
// require_once($_SERVER['DOCUMENT_ROOT'] . '/sirjacques.vincent/php-sql_poste_Vincent_v2/classes/database.class.php');

// ANCHOR fct de protection des champs (adapter pour $field = $_POST)
function fieldProtect($field)
{
    // regex
    $pw_regex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[a-zA-Z\d@$!%*?&]{8,}$/i';
    // $txt_regex = '/^[\w_-]{3,15}$/i';
    $name_regex = "/[\a'-]{3,15}/i";
    $test = true;
    foreach ($field as $key => $value) {
        if (empty($value)) {
            // champ vide
            echo ('<div class="alert alert-danger alert-dismissible"><span>Erreur : champ ' . $key . ' est vide.</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
            $test = false;
            break;
        }
        if ($key == 'nom' && preg_match($name_regex, $value)) {
            // regex user
            echo ('<div class="alert alert-danger alert-dismissible">
            <span>Erreur : votre ' . $key . ' est invalide, il faux entre 3 et 15 caractères sans caractère spécial autre que "-" et "_".</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
            $test = false;
            break;
        }
        if ($key == 'email' && !filter_var(trim($value, " \n\r\t\v\x00...\x1F"), FILTER_VALIDATE_EMAIL)) {
            echo ('<div class="alert alert-danger alert-dismissible"><span>Erreur : champ ' . $key . ' est invalide.</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
            $test = false;
            break;
        }
        if ($key == 'password' && preg_match($pw_regex, $value) == 0) {
            // regex mdp
            echo ('<div class="alert alert-danger alert-dismissible">
            <span>Erreur : votre ' . $key . ' est invalide, il faux 8 caractères minimum avec une lettre en minuscule, une en majuscule, un nombre et un caractère spécial.</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
            $test = false;
            break;
        }
        if ($field['password'] != $field['pwd']) {
            // confirm du mdp
            echo ('<div class="alert alert-danger alert-dismissible"><span>Erreur : les champ mot de passe et confirmer votre mot de passe ne sont pas identique.</span>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
            $test = false;
            break;
        }
    }
    foreach ($field as $key => $value) {
        switch ($key) {
            case 'password':
                $field[$key] = password_hash($value, PASSWORD_BCRYPT);
                break;
            default:
                // sécurisation des caractères
                $field[$key] = txtSlashes($value);
                break;
        }
    }
    return $test;
}

// ANCHOR fct pour chercher un objet dans une colonne d'un tableau
function getObject($table, $column, $object)
{
    // conn à la db
    $db = new Database();
    $conn = $db->getPDO();

    $sql = "SELECT * FROM $table WHERE $column = :object";
    $req = $conn->prepare($sql);
    $req->bindParam(':object', $object, PDO::PARAM_STR);
    $req->execute();
    $row = $req->fetch();
    return $row;
}

// ANCHOR fct d'inscription
function inscription($table, $lastName, $firstName, $email, $password1, $password2)
{
    try {
        // conn à la db
        $db = new Database();
        $conn = $db->getPDO();
        // nameVerif($lastName, $firstName);
        $row = getObject($table, 'user_email', $email);
        if (!$row) {
            // requête insertion des données
            $sth = $conn->prepare("
           INSERT IGNORE INTO " . TABLE_LOGIN . "( user_nom, user_prenom, user_email, user_pwd) 
           VALUES ( :nom, :prenom, :email, :password);
           ");
            $password = password_hash($password1, PASSWORD_BCRYPT);
            // insertion des données
            $sth->bindParam(':nom', $lastName, PDO::PARAM_STR);
            $sth->bindParam(':prenom', $firstName, PDO::PARAM_STR);
            $sth->bindParam(':email', $email, PDO::PARAM_STR);
            $sth->bindParam(':password', $password, PDO::PARAM_STR);
            $sth->execute();
            return true;
        } else {
            // existences de l'user
            echo ('<div class="alert alert-danger alert-dismissible"><span>Erreur : ' . $email . ' existe déjà.</span>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>');
            return false;
        }
    } catch (PDOException $e) {
        echo "Erreur." . $e->getMessage();
    }
}
