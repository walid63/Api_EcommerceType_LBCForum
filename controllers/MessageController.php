<?php
include "./models/Message.php";
class MessageController
{
    private $messageModel;

    public function __construct()
    {
        $this->messageModel = new MessageModel();
    }

    public function createMessage($content, $receiverId, $senderId)
    {
        $message = new Message();
        $message->setContent($content);
        $message->setReceiverId($receiverId);
        $message->setSenderId($senderId);

        if ($this->messageModel->save($message)) {
            echo json_encode(['message' => 'Message created successfully']);
        } else {
            echo json_encode(['message' => 'Failed to create message']);
        }
    }

    public function updateMessage($messageId, $content)
    {
        $message = $this->messageModel->getMessageById($messageId);

        if ($message) {
            $message->setContent($content);
            $message->setUpdatedAt(date('Y-m-d H:i:s'));

            if ($this->messageModel->update($message)) {
                echo json_encode(['message' => 'Message updated successfully']);
            } else {
                echo json_encode(['message' => 'Failed to update message']);
            }
        } else {
            echo json_encode(['message' => 'Message not found']);
        }
    }

    public function listReceivedMessages()
    {
        // Récupérer l'identifiant de l'utilisateur connecté
        session_start();
        $userId = $_SESSION['user']['id'];

        // Récupérer les messages reçus par l'utilisateur
        $messages = $this->messageModel->getReceivedMessagesByUserId($userId);

        // Afficher les messages reçus
        foreach ($messages as $message) {
            echo "Contenu : " . $message->getContent() . "<br>";
            echo "Créé le : " . $message->getCreatedAt() . "<br>";
            // Afficher d'autres informations si nécessaire
            echo "<br>";
        }
    }

    public function listSentMessages()
    {
        // Récupérer l'identifiant de l'utilisateur connecté
        session_start();
        $userId = $_SESSION['user']['id'];

        // Récupérer les messages envoyés par l'utilisateur
        $messages = $this->messageModel->getSentMessagesByUserId($userId);

        // Afficher les messages envoyés
        foreach ($messages as $message) {
            echo "Contenu : " . $message->getContent() . "<br>";
            echo "Créé le : " . $message->getCreatedAt() . "<br>";
            // Afficher d'autres informations si nécessaire
            echo "<br>";
        }
    }

    public function handleRequest()
    {
        // Vérifier l'action demandée
        $action = $_GET['action'] ?? '';

        switch ($action) {
            case 'list_received':
                $this->listReceivedMessages();
                break;
            case 'list_sent':
                $this->listSentMessages();
                break;
            default:
                // Action par défaut
                break;
        }
    }
}
