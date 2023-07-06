<?php

include 'vendor/autoload.php';
include "./models/User.php";


class AuthController
{

    private $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function register()
    {
        // Récupérer les données de la requête
        // $userId = $_POST['user_id'];
        $firstname = $_GET['firstname'];
        $lastname = $_GET['lastname'];
        $username = $_GET['username'];
        $email = $_GET['email'];
        $password = $_GET['password'];
        $tel = $_GET['tel'];
        $address = $_GET['address'];
        $city = $_GET['city'];
        $zipCode = $_GET['zip_code'];


        // Effectuer des validations et des traitements sur les données
        if (empty($firstname) || empty($lastname) || empty($username) || empty($email) || empty($password) || empty($tel) || empty($address) || empty($city) || empty($zipCode)) {
            echo json_encode(['message' => 'Missing required data']);
            return;
        }


        if ($this->userModel->isEmailTaken($email)) {
            echo json_encode(['message' => 'Email already taken']);
            return; // Arrêter l'exécution de la méthode
        }
        $userModel = new UserModel();
        $userData = $userModel->getUserByEmail($email);

        // Stocker les données de l'utilisateur dans la session
        session_start();
      //  $_SESSION['user'] = $userData;


        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        // Créer un nouvel utilisateur
        $user = new User($firstname, $lastname, $username, $email, $hashedPassword, $tel, $address, $city, $zipCode);
        $userModel = new UserModel;
        $result = $userModel->createUser($user);

        if ($result) {
            echo "L'utilisateur a été créé avec succès.";
        } else {
            echo `Une erreur s'est produite lors de la création de: ${$user}.`;
        }

        // Enregistrer l'utilisateur en base de données ou effectuer d'autres opérations nécessaires

        // Retourner la réponse au format JSON
        echo json_encode(['message' => `utilisateur inserer avec succes`]);
    }

    public function login()
    {
        
        if (isset($_SESSION['auth_token'])) {
            // L'utilisateur est déjà connecté, renvoyer un message indiquant qu'il est connecté
            echo json_encode(['message' => 'Vous etes deja connecter '.$_SESSION['user']]);
            return;
        }
        // Récupérer les données de la requête
        $email = $_GET['email'];
        $password = $_GET['password'];

        // Effectuer des validations et des traitements sur les données
       
        // Vérifier les informations de connexion de l'utilisateur
        if ($this->verifyCredentials($email, $password)) {
            // Les informations de connexion sont valides

            // Générer un jeton d'authentification
            $token = $this->generateAuthToken();

            // Stocker le jeton d'authentification dans la session de l'utilisateur
            session_start();
            $_SESSION['auth_token'] = $token;
            $userModel = new UserModel();
            $userData = $userModel->getUserByEmail($email);
            
            // Stocker les données de l'utilisateur dans la session
           // session_start();
           $_SESSION['user'] = [
            'id' => $userData->getId(),
            'firstname' =>  $userData->getFirstname(),
            'lastname' =>  $userData->getLastname(),
            'username' => $userData->getUsername(),
            'email' => $userData->getEmail(),
            'password' => $userData->getPassword(),
            'tel' => $userData->getTel(),
            'address' => $userData->getAddress(),
            'city' => $userData->getCity(),
            'zip_code' => $userData->getZipCode(),
            'is_admin' => $userData->getIsAdmin(),
            'is_banned' => $userData->getIsBanned(),
            'status' => $userData->getStatus(),
            'cratedAt' => $userData->getCreatedAt(),
            // Ajoutez d'autres données utilisateur si nécessaire
        ];

         

            // Retourner la réponse au format JSON avec le jeton ".d'authentification ou d'autres données
           
            echo json_encode(['message' => 'Bienvenue ' . $userData->getFirstname() . ' ' . $userData->getLastname(), 'user' => $_SESSION['user'],'token' => $token]);

        } else {
            // Les informations de connexion sont invalides
            echo json_encode(['message' => 'Invalid credentials']);
        }
    }

    public function logout()
    {
        // Détruire la session existante
        session_start(); // Démarrez la session si ce n'est pas déjà fait
        session_unset(); // Effacez toutes les données de session
        session_destroy(); // Détruisez la session

        // Rediriger l'utilisateur vers la page de déconnexion ou la page d'accueil
        header("Location: /"); // Remplacez "logout.php" par le chemin de votre page de déconnexion ou d'accueil
        exit(); // Arrêtez l'exécution du script
    }

    function generateAuthToken()
    {
        $length = 32; // Longueur du jeton
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $token = '';

        for ($i = 0; $i < $length; $i++) {
            $randomIndex = random_int(0, strlen($characters) - 1);
            $token .= $characters[$randomIndex];
        }

        return $token;
    }

    private function verifyCredentials($email, $password)
    {
        // Récupérer l'utilisateur à partir de l'email (vous pouvez utiliser votre modèle d'utilisateur)
        $user = $this->userModel->getUserByEmail($email);

        // Vérifier si l'utilisateur existe et si le mot de passe est correct
        if ($user && password_verify($password, $user->getPassword())) {
            return true; // Les informations de connexion sont valides
        }

        return false; // Les informations de connexion sont invalides
    }
}
