<?php 
include "./models/User.php";
include "./src/User.php";


class HomeController
{
    private $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

    public function index()
    {
        
        // Logique pour obtenir tous les utilisateurs de votre application
        $users = $this->userModel->getAllUsers();
        echo "API - Bienvenue /n Vous etes connecter a l'Api";
    }



    public function isTableExists($table)
    {
        $db = _connect::getInstance();
        $tables = $db->getTables();
        
        if(in_array($table,$tables))
        {
          echo "";
        }else{
            echo "error [db007] Désolé, la table ${table} n'existe pas";
        }
    }

 /*   public function handleRequest($request)
    {
        switch ($request) {
            case 'GET':
                $this->getUsers();
                break;
            case 'POST':
                $this->createUser();
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
    }*/
}