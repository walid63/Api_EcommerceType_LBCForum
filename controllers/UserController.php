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


    
    

    public function handleRequest($request)
    {
        switch ($request) {
            case 'POST':
                $this->authController->register();
                break;
            case 'PUT':
                $this->updateUser();
                break;
            default:
                echo json_encode(['message' => 'Invalid request']);
                break;
        }
    }
}