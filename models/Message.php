<?php
require_once "./server/_connect/_connect.php";
class MessageModel
{
    private $db;

    public function __construct()
    {
        $this->db = _connect::getInstance()->getConnection();
    }

    public function save(Message $message)
    {
        $query = "INSERT INTO message (author_id, content, created_at) 
                  VALUES (:author_id, :content, :created_at)";
        // Préparation et exécution de la requête d'insertion

        // Retourner true si l'insertion réussit, sinon false
    }

    public function update(Message $message)
    {
        $query = "UPDATE message SET content = :content, updated_at = :updated_at 
        WHERE id = :id";

        $statement = $this->db->prepare($query);

        $statement->execute([
            'id' => $message->getId(),
            'content' => $message->getContent(),
            'updated_at' => $message->getUpdatedAt()
        ]);

        if ($statement->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getMessagesByUserId($userId)
    {
        $query = "SELECT * FROM message WHERE receiver_id = :userId OR sender_id = :userId";
        $statement = $this->db->prepare($query);
        $statement->execute(['userId' => $userId]);

        $messages = [];
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $message = new Message();
            $message->setId($row['id']);
            $message->setContent($row['content']);
            $message->setCreatedAt($row['created_at']);
            $message->setUpdatedAt($row['updated_at']);
            $message->setReceiverId($row['receiver_id']);
            $message->setSenderId($row['sender_id']);

            $messages[] = $message;
        }

        return $messages;
    }

    public function getMessageById($id)
    {
        $query = "SELECT * FROM message WHERE id = :id";

        $statement = $this->db->prepare($query);

        $statement->execute(['id' => $id]);

        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result) {
            $message = new Message();
            $message->setId($result['id']);
            $message->setContent($result['content']);
            $message->setCreatedAt($result['created_at']);
            $message->setUpdatedAt($result['updated_at']);
            $message->setSenderId($result['sender_id']);
            $message->setReceiverId($result['receiver_id']);

            return $message;
        } else {
            return null;
        }
    }


    public function getReceivedMessagesByUserId($userId)
    {
        $query = "SELECT * FROM message WHERE receiver_id = :user_id";

        $statement = $this->db->prepare($query);

        $statement->execute(['user_id' => $userId]);

        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        $messages = [];

        foreach ($results as $result) {
            $message = new Message();
            $message->setId($result['id']);
            $message->setContent($result['content']);
            $message->setCreatedAt($result['created_at']);
            $message->setUpdatedAt($result['updated_at']);
            $message->setSenderId($result['sender_id']);
            $message->setReceiverId($result['receiver_id']);

            $messages[] = $message;
        }

        return $messages;
    }

    public function getSentMessagesByUserId($userId)
    {
        $query = "SELECT * FROM message WHERE sender_id = :user_id";

        $statement = $this->db->prepare($query);

        $statement->execute(['user_id' => $userId]);

        $results = $statement->fetchAll(PDO::FETCH_ASSOC);

        $messages = [];

        foreach ($results as $result) {
            $message = new Message();
            $message->setId($result['id']);
            $message->setContent($result['content']);
            $message->setCreatedAt($result['created_at']);
            $message->setUpdatedAt($result['updated_at']);
            $message->setSenderId($result['sender_id']);
            $message->setReceiverId($result['receiver_id']);

            $messages[] = $message;
        }

        return $messages;
    }

    // ...
}
