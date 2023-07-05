<?php 

include "./models/User.php";
include "./src/User.php";

class UserController
{
    private $userModel;
    private $authController;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->authController = new AuthController();
    }

    public function getUsers()
    {
        // Logique pour obtenir tous les utilisateurs de votre application
        $users = $this->userModel->getAllUsers();

        // Retourner la réponse au format JSON
        echo json_encode($users);
    }

  

    public function updateUser()
    {
        // Récupérer les données mises à jour de l'utilisateur depuis la requête
        $userId = $_POST['user_id'];
        $firstname = $_POST['firstname'];
        $lastname = $_POST['lastname'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $tel = $_POST['tel'];
        $address = $_POST['address'];
        $city = $_POST['city'];
        $zipCode = $_POST['zip_code'];

        // Créer un nouvel objet User avec les données mises à jour
        $user = new User($firstname, $lastname, $username, $email, $password, $tel, $address, $city, $zipCode);
        $user->setId($userId);

        // Appeler la méthode updateUser du UserModel pour mettre à jour l'utilisateur
        $userModel = new UserModel();
        $result = $userModel->updateUser($user);

        // Vérifier le résultat de la mise à jour
        if ($result) {
            echo "L'utilisateur a été mis à jour avec succès.";
        } else {
            echo "Une erreur s'est produite lors de la mise à jour de l'utilisateur.";
        }
    }


    public function deleteUser()
    {
        // Logique pour supprimer un utilisateur spécifié par son ID

        // Récupérer l'ID de l'utilisateur à supprimer depuis la requête
        $userId = $_GET['id'];

        // Supprimer l'utilisateur de la base de données ou effectuer d'autres opérations nécessaires

        // Retourner la réponse au format JSON
        echo json_encode(['message' => 'User deleted successfully']);
    }

    public function handleRequest($request)
    {
        switch ($request) {
            case 'GET':
                $this->getUsers();
                break;
            case 'POST':
                $this->authController->register();
                break;
            case 'PUT':
                $this->updateUser();
                break;
            case 'DELETE':
                $this->deleteUser();
                break;
            default:
                echo json_encode(['message' => 'Invalid request']);
                break;
        }
    }
}