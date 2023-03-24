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

    // ANCHOR fct pour chercher un objet dans une colonne d'un tableau
    private function getObject($table, $column, $object)
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
}
