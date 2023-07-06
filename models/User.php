<?php

require_once "./server/_connect/_connect.php";
require_once "./src/User.php";
class UserModel
{
    private $db;

    public function __construct()
    {
        $this->db = _connect::getInstance()->getConnection();
    }

    public function getAllUsers()
    {
        $query = "SELECT * FROM user";
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function createUser(User $user)
    {
        $query = "INSERT INTO users (firstname, lastname, username, adresse, ville, codepostal, telephone, email, password, isAdmin, isBanned, status)
                  VALUES (:firstname, :lastname, :username, :adresse, :ville, :codepostal, :telephone, :email, :password, :isAdmin, :isBanned, :status)";

        // Préparer la requête
        $statement = $this->db->prepare($query);

        // Générer des valeurs par défaut pour les colonnes isAdmin, isBanned et status
        $isAdmin = false;
        $isBanned = false;
        $status = ['active'];

        // Exécuter la requête avec les valeurs des paramètres
        $statement->execute([
            'firstname' => $user->getFirstname(),
            'lastname' => $user->getLastname(),
            'username' => $user->getUsername(),
            'adresse' => $user->getAddress(),
            'ville' => $user->getCity(),
            'codepostal' => $user->getZipCode(),
            'telephone' => $user->getTel(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'isAdmin' => $isAdmin,
            'isBanned' => $isBanned,
            'status' => json_encode($status)
        ]);

        // Vérifier si l'insertion a réussi
        if ($statement->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function updateUser(User $user)
    {
        $query = "UPDATE users SET 
                  firstname = :firstname, 
                  lastname = :lastname, 
                  username = :username, 
                  adresse = :adresse, 
                  ville = :ville, 
                  codepostal = :codepostal, 
                  telephone = :telephone, 
                  email = :email, 
                  password = :password, 
                  isAdmin = :isAdmin, 
                  isBanned = :isBanned, 
                  status = :status 
                  WHERE id = :id";

        $statement = $this->db->prepare($query);

        $statement->bindValue(':firstname', $user->getFirstname());
        $statement->bindValue(':lastname', $user->getLastname());
        $statement->bindValue(':username', $user->getUsername());
        $statement->bindValue(':adresse', $user->getAddress());
        $statement->bindValue(':ville', $user->getCity());
        $statement->bindValue(':codepostal', $user->getZipCode());
        $statement->bindValue(':telephone', $user->getTel());
        $statement->bindValue(':email', $user->getEmail());
        $statement->bindValue(':password', $user->getPassword());
        $statement->bindValue(':isAdmin', $user->getIsAdmin());
        $statement->bindValue(':isBanned', $user->getIsBanned());
        $statement->bindValue(':status', json_encode($user->getStatus()));
        $statement->bindValue(':id', $user->getId());

        return $statement->execute();
    }

    public function getUserByEmail($email)
    {
        $query = "SELECT * FROM user WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':email', $email);
        $stmt->execute();

        $userData = $stmt->fetch(\PDO::FETCH_ASSOC);

        if ($userData) {
            $user = new User(
                $userData['firstname'],
                $userData['lastname'],
                $userData['username'],
                $userData['email'],
                $userData['password'],
                $userData['tel'],
                $userData['address'],
                $userData['ville'],
                $userData['code_postal']
                //$userData['created_at']
            );

            $user->setId($userData['id']);
            $user->setIsAdmin($userData['is_admin']);
            $user->setIsBanned($userData['is_banned']);

            if ($userData['status'] !== null) {
                $user->setStatus(json_decode($userData['status'], true));
            }

            return $user;
        }

        return null;
    }

    public function isEmailTaken($email)
    {
        $query = "SELECT COUNT(*) as count FROM user WHERE email = :email";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':email', $email);
        $stmt->execute();

        $result = $stmt->fetch(\PDO::FETCH_ASSOC);

        return $result['count'] > 0;
    }

    // Autres méthodes de la classe UserModel

    public function deleteUser($userId)
    {
        $query = "DELETE FROM user WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id', $userId);

        return $stmt->execute();
    }

    public function getUserById($userId)
    {
        $query = "SELECT * FROM users WHERE id = :id";
        $statement = $this->db->prepare($query);
        $statement->execute([
            'id' => $userId
        ]);

        $user = $statement->fetch(PDO::FETCH_ASSOC);

        return $user;
    }



    public function checkFriendship($userId, $friendId)
    {
        $query = "SELECT * FROM friends WHERE user_id = :user_id AND friend_id = :friend_id";
        $statement = $this->db->prepare($query);
        $statement->execute([
            'user_id' => $userId,
            'friend_id' => $friendId
        ]);

        $friendship = $statement->fetch(PDO::FETCH_ASSOC);

        return $friendship ? true : false;
    }



    public function addFriend($userId, $friendId)
    {
        // Vérifier si l'utilisateur existe
        if (!$this->getUserById($userId)) {
            // Utilisateur introuvable
            return false;
        }

        // Vérifier si l'ami existe
        if (!$this->getUserById($friendId)) {
            // Ami introuvable
            return false;
        }

        // Vérifier si l'amitié existe déjà
        if ($this->checkFriendship($userId, $friendId)) {
            // L'amitié existe déjà
            return false;
        }

        // Ajouter l'amitié dans la table d'amis
        $query = "INSERT INTO friends (user_id, friend_id) VALUES (:user_id, :friend_id)";
        $statement = $this->db->prepare($query);
        $statement->execute([
            'user_id' => $userId,
            'friend_id' => $friendId
        ]);

        if ($statement->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }
}
