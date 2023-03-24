<?php
class Crud
{
    // ANCHOR afficher tous les postes
    private function getAll($table)
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
    private function getSingle($table, $id)
    {
        try {
            // conn à la db
            $db = new Database();
            $conn = $db->getPDO();

            $sql = "SELECT * FROM $table WHERE id = :id";
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
    public function create($table, $titre, $date, $commentaire, $image)
    {
        try {
            // conn à la db
            $db = new Database();
            $connexion = $db->getPDO();

            $sql = "INSERT INTO $table (titre, date, commentaire, photo) VALUES (:titre, :date, :commentaire, :image)";
            $req = $connexion->prepare($sql);
            $req->bindParam(':titre', $titre, PDO::PARAM_STR);
            $req->bindParam(':date', $date);
            $req->bindParam(':commentaire', $commentaire, PDO::PARAM_STR);
            $req->bindParam(':image', $image, PDO::PARAM_STR);
            $req->execute();
        } catch (PDOException $e) {
            echo "Erreur: " . $e->getMessage();
        }
    }

    // ANCHOR UPDATE Modifier
    public function update($table, $id, $titre, $contenu, $prix, $image, $userID)
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
    public function delete($table, $id)
    {
        try {
            // conn à la db
            $db = new Database();
            $connexion = $db->getPDO();

            $sql = "DELETE FROM $table WHERE id = :id";
            $req = $connexion->prepare($sql);
            $req->bindParam(':id', $id, PDO::PARAM_INT);
            $req->execute();
        } catch (PDOException $e) {
            echo "Erreur: " . $e->getMessage();
        }
    }

    // ANCHOR DELETE photo
    public function delPic($table, $id)
    {
        try {
            $row = $this->getSingle($table, $id);

            // suppression du fichier
            $fileLocal = '../pic/' . $row['photo'];
            if (file_exists($fileLocal)) {
                unlink($fileLocal);
            }
        } catch (PDOException $e) {
            echo "Erreur: " . $e->getMessage();
        }
    }

    // SECTION fct avec bootstrap

    // ANCHOR Afficher en poste
    public function displayPosts($table)
    {
        $rows = $this->getAll($table);
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
    private function getHeaderTable($table)
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
    public function displayTable($table)
    {
        $headers = $this->getHeaderTable($table);
        $rows = $this->getAll($table);
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
    public function displayCards($table)
    {
        $rows = $this->getAll($table);
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
    public function displayRss($table)
    {
        $rows = $this->getAll($table);
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
                </div>
            </figcaption>
        </figure>
    </article>
        ';
        endforeach;
        echo '</div>';
    }

    // !SECTION avec css card

}
