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

// ANCHOR afficher tous les postes
function getAll($table)
{
    try {
        // conn à la db
        $db = new Database();
        $conn = $db->getPDO();

        $sql = "SELECT * FROM $table";
        return $rows = $conn->query($sql)->fetchAll();
    } catch (PDOException $e) {
        echo "Erreur." . $e->getMessage();
    }
}

//ANCHOR READ Afficher un post
function getSingle($table, $id)
{
    try {
        // conn à la db
        $db = new Database();
        $conn = $db->getPDO();

        $sql = "SELECT * FROM $table WHERE ms_id = :id";
        $req = $conn->prepare($sql);
        $req->bindParam(':id', $id, PDO::PARAM_INT);
        $req->execute();
        $row = $req->fetch();
        return $row;
    } catch (PDOException $e) {
        echo "Erreur." . $e->getMessage();
    }
}

// ANCHOR CREATE Créer
function create($table, $titre, $contenu, $prix, $image, $userID)
{
    try {
        // conn à la db
        $db = new Database();
        $connexion = $db->getPDO();

        $sql = "INSERT INTO $table (ms_titre, ms_contenu, ms_prix, ms_image, user_id) VALUES (:titre, :contenu, :prix, :image, :userID)";
        $req = $connexion->prepare($sql);
        $req->bindParam(':titre', $titre, PDO::PARAM_STR);
        $req->bindParam(':contenu', $contenu, PDO::PARAM_STR);
        $req->bindParam(':prix', $prix, PDO::PARAM_INT);
        $req->bindParam(':image', $image, PDO::PARAM_STR);
        $req->bindParam(':userID', $userID, PDO::PARAM_INT);
        $req->execute();
    } catch (PDOException $e) {
        echo "Erreur: " . $e->getMessage();
    }
}

// ANCHOR UPDATE Modifier
function update($table, $id, $titre, $contenu, $prix, $image, $userID)
{
    try {
        // conn à la db
        $db = new Database();
        $connexion = $db->getPDO();

        if (!empty($image)) {
            $sql = "UPDATE $table SET ms_titre = :titre, ms_contenu = :contenu, ms_prix = :prix, ms_image = :image, user_id = :userID WHERE microservice_id = :id ";
            $req = $connexion->prepare($sql);
            $req->bindParam(':titre', $titre, PDO::PARAM_STR);
            $req->bindParam(':contenu', $contenu, PDO::PARAM_STR);
            $req->bindParam(':prix', $prix, PDO::PARAM_INT);
            $req->bindParam(':image', $image, PDO::PARAM_STR);
            $req->bindParam(':userID', $userID, PDO::PARAM_INT);
            $req->bindParam(':id', $id, PDO::PARAM_INT);
            $req->execute();
        } else {
            $sql = "UPDATE $table SET ms_titre = :titre, ms_contenu = :contenu, ms_prix = :prix, user_id = :userID WHERE microservice_id = :id ";
            $req = $connexion->prepare($sql);
            $req->bindParam(':titre', $titre, PDO::PARAM_STR);
            $req->bindParam(':contenu', $contenu, PDO::PARAM_STR);
            $req->bindParam(':prix', $prix, PDO::PARAM_INT);
            $req->bindParam(':userID', $userID, PDO::PARAM_INT);
            $req->bindParam(':id', $id, PDO::PARAM_INT);
            $req->execute();
        }
    } catch (PDOException $e) {
        echo "Erreur: " . $e->getMessage();
    }
}


// ANCHOR DELETE Supprimer
function delete($table, $id)
{
    try {
        // conn à la db
        $db = new Database();
        $connexion = $db->getPDO();

        $sql = "DELETE FROM $table WHERE ms_id = :id";
        $req = $connexion->prepare($sql);
        $req->bindParam(':id', $id, PDO::PARAM_INT);
        $req->execute();
    } catch (PDOException $e) {
        echo "Erreur: " . $e->getMessage();
    }
}

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

// ANCHOR fct de connexion
function connexion($table, $email, $password)
{
    try {
        $row = getObject($table, 'user_email', $email);
        if ($row === false) {
            // user n'existe pas
            echo '<div class="alert alert-danger alert-dismissible"><span>Erreur : ' . $email . ' est inexistant.</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        } elseif (!password_verify($password, $row['user_pwd'])) {
            // pas le bon mdp
            echo '<div class="alert alert-danger alert-dismissible"><span>Erreur : mot de passe incorrect.</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
        } else {
            // co de l'user
            $_SESSION['login'] = true;
            $_SESSION['job'] = $row['user_role'];
            $_SESSION['user'] = $row['user_prenom'];
        }
    } catch (PDOException $e) {
        echo "Erreur." . $e->getMessage();
    }
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

// SECTION fct avec bootstrap

// ANCHOR Afficher en poste
function displayPosts($table)
{
    $rows = getAll($table);
    foreach ($rows as $row) :
        echo '
        <div class="col-md-4 p-2">
                <article class="shadow border border-secondary">
                    <div>
                        <img class="img-fluid" src="upload/image/' . stripcslashes($row["photo"]) . '" alt="image_du_post">
                    </div>
                    <div class="p-2">
                        <h3>' . stripcslashes($row['titre']) . '</h3>
                        <p>' . stripcslashes($row['commentaire']) . '</p>
                        <span class="btn btn-light">Publier le <strong>' . stripcslashes($row['date']) . '</strong></span>
                        <a class="link-secondary" href="./page/post.php?id=' . stripcslashes($row['id']) . '">En savoir plus</a>
                    </div>
                </article>
            </div>
        ';
    endforeach;
    // urlencode(base64_encode(
}

// ANCHOR Afficher l'en-tête de la table
function getHeaderTable($table)
{
    try {
        $db = new Database();
        $connexion = $db->getPDO();
        $sql = "SELECT * FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = :table_name ORDER BY ORDINAL_POSITION";
        $req = $connexion->prepare($sql);
        $req->bindParam(':table_name', $table, PDO::PARAM_STR);
        $req->execute();
        $rows = $req->fetchAll();
        return $rows;
    } catch (PDOException $e) {
        echo "Erreur: " . $e->getMessage();
    }
}

// ANCHOR dashboard | Afficher un tableau
function displayTable($table)
{
    $headers = getHeaderTable($table);
    $rows = getAll($table);
?>
    <table class="table table-hover table-light">
        <thead>
            <tr>
                <?php
                foreach ($headers as $header) :
                ?>
                    <th scope="col"><?= $header['COLUMN_NAME'] ?></th>
                <?php
                endforeach;
                ?>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach ($rows as $row) :
                // var_dump(array_key_first($row) ? 'yes' : $row);
            ?>
                <tr class="position-relative">
                    <td scope="col">
                        <a class="btn btn-link stretched-link text-decoration-none" href="add-ms.php?id=<?= stripslashes($row['ms_id']) ?>">
                            <i class="bi bi-pencil-square"></i><?= stripslashes($row['ms_id']) ?>
                        </a>
                    </td>
                    <td scope="col">
                        <?= stripslashes($row['ms_titre']) ?>
                    </td>
                    <td scope="col">
                        <?= stripslashes($row['ms_contenu']) ?>
                    </td>
                    <td scope="col">
                        <?= stripslashes($row['ms_prix']) ?>
                    </td>
                    <td scope="col text-center">
                        <img src="<?= '../upload/image/' . stripslashes($row['ms_image']) ?>" alt="<?= substr(stripslashes($row['ms_contenu']), 0, 80) ?>" width="120">
                    </td>
                    <td scope="col">
                        <?= $row['user_id'] ?>
                    </td>
                </tr>
            <?php
            endforeach;
            ?>
        </tbody>

    </table>
<?php
}

// !SECTION fct avec bootstrap

// SECTION avec css card

// ANCHOR Afficher en carte
function displayCards($table)
{
    $rows = getAll($table);
    echo '<div class="collection__cards">';
    foreach ($rows as $row) :
        echo '
        <article class="card" id="card_' . $row['id'] . '">
                <figure class="card__type">
                    <div class="card__header">
                        <h3>' . stripslashes($row['titre']) . '</h3>
                        <div class="card__score">
                            <span class="card__score--value">0</span>
                            <i class="fa fa-star"></i>
                        </div>
                    </div>
                    <div class="card__img txt-center">
                        <img src="./pic/' . $row['photo'] . '" alt="bg_card">
                    </div>
                    <figcaption>
                        <h4 class="card__caract">
                            <span class="card__gender">Catégorie du scénario</span>
                            <span class="card__date">' . $row['date'] . '</span>
                        </h4>
                        <div class="card__description">
                            <p class="card__text">
                            ' . stripslashes($row['commentaire']) . '
                            </p>
                            <span class="d-flex g-2">
                                <a href="./page/dell.php?m=' . urlencode(base64_encode($row['id'])) . '" class="card__link">delete</a>
                                <a href="./page/edit.php?m=' . urlencode(base64_encode($row['id'])) . '" class="card__link">edit</a>
                            </span>
                        </div>
                        <h5 class="card__auteur">DUPONT Jean</h5>
                    </figcaption>
                </figure>
            </article>
        ';
    endforeach;
    echo '</div>';
}

// ANCHOR fct affichage du flux RSS
function displayRss($table)
{
    $rows = getAll($table);
    echo '<div class="collection__cards">';
    foreach ($rows as $row) :
        echo '
        <article class="card" id="card_' . $row['id'] . '_RSS">
        <figure class="card__type">
            <div class="card__header">
                <h3>' . stripslashes($row['titre']) . '</h3>
            </div>
            <div class="card__img txt-center">
                <img src="./pic/gc.jpg" alt="bg_card">
            </div>
            <figcaption>
                <h4 class="card__caract">
                    <span class="card__date">' . $row['date'] . '</span>
                </h4>
                <div class="card__description">
                    <p class="card__text">
                    ' . stripslashes($row['commentaire']) . ' <a href="' . $row['url'] . '" class="card__link">lien vers la page</a>
                    </p>
                    <span class="d-flex g-2">
                        <a href="./page/dell.php?m=' . urlencode(base64_encode($row['id'])) . '" class="card__link">delete</a>
                        <a href="./page/edit.php?m=' . urlencode(base64_encode($row['id'])) . '" class="card__link">edit</a>
                    </span>
                </div>
            </figcaption>
        </figure>
    </article>
        ';
    endforeach;
    echo '</div>';
}

// !SECTION avec css card