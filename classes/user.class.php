<?php
class User
{
    protected $user_name;
    protected $user_pass;

    public function __construct($n, $p)
    {
        $this->user_name = $n;
        $this->user_pass = $p;
    }
    public function __destruct()
    {
    }

    // ANCHOR fct pour chercher un objet dans une colonne d'un tableau
    public function getObject($table, $column, $object)
    {
        // conn à la db
        $db = new Database();
        $conn = $db->getPDO();

        $sql = "SELECT * FROM $table WHERE $column = :object LIMIT 1";
        $req = $conn->prepare($sql);
        $req->bindParam(':object', $object, PDO::PARAM_STR);
        $req->execute();
        $row = $req->fetch();
        return $row;
    }

    // ANCHOR fct de connexion
    public function connexion($table)
    {
        try {
            $row = $this->getObject($table, 'log_user', $this->user_name);
            if ($row === false) {
                // user n'existe pas
                echo '<div class="alert alert-danger alert-dismissible"><span>Erreur : ' . $this->user_name . ' est inexistant.</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                return false;
            } elseif (!password_verify($this->user_pass, $row['log_password'])) {
                // pas le bon mdp
                echo '<div class="alert alert-danger alert-dismissible"><span>Erreur : mot de passe incorrect.</span>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
                return false;
            } else {
                // co de l'user
                session_start();
                $_SESSION['login'] = true;
                $_SESSION['job'] = $row['id_job'];
                $_SESSION['user'] = $row['log_user'];
                return true;
            }
        } catch (PDOException $e) {
            echo "Erreur." . $e->getMessage();
        }
    }

    // ANCHOR fct inscription
    public function signup($table, $email)
    {
        try {
            // conn à la db
            $db = new Database();
            $conn = $db->getPDO();

            $row = $this->getObject($table, 'log_user', $this->user_name);
            if (!$row) {
                // requête insertion des données
                $sth = $conn->prepare("
                    INSERT IGNORE INTO " . TABLE_TEMPO . "( log_user, log_mail, log_password, id_job , token) 
                    VALUES (:user, :email, :password, :id_job, :token);
                    ");
                $token = md5(uniqid(rand(), TRUE)) . md5(uniqid(rand(), TRUE));
                $password = password_hash($this->user_pass, PASSWORD_BCRYPT);
                // insertion des données
                $sth->bindParam(':user', $this->user_name, PDO::PARAM_STR);
                $sth->bindParam(':email', $email, PDO::PARAM_STR);
                $sth->bindParam(':password', $password, PDO::PARAM_STR);
                $sth->binParam(':id_job', 1);
                $sth->binParam(':token', $token);
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
}
