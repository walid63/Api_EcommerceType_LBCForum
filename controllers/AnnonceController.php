<?php

require_once "./models/Annonce.php";
require_once "./models/Category.php";
require_once "./src/Annonce.php";
require_once "./src/Category.php";

class AnnonceController
{
    private $annonceModel;
    private $categoryModel;
    private $annonceEntity;
    private $categoryEntity;

    public function __construct()
    {
        $this->annonceModel = new AnnonceModel();
        $this->categoryModel = new CategoryModel();
        $this->annonceEntity = new Annonce();
        $this->categoryEntity = new Category();
    }

    public function createAnnonce()
    {
        session_start();

        // Vérifier si l'utilisateur est connecté
        if ($_SESSION['user'] == null) {
            echo json_encode(['message' => 'Vous n\'êtes pas connecté, veuillez vous connecter']);
            header('location: /');
            return;
        }
        // Récupérer les données de la requête
        $title = $_GET['title'];
        $description = $_GET['description'];
        $price = $_GET['price'];
        $vendorId = $_SESSION['user']['id'];
        $ville = $_GET['ville'];
        $region = $_GET['region'];
        $category = $_GET['categoryName'];

        // Créer un nouvel objet Annonce
        $annonce = new Annonce();
        $annonce->setTitle($title);
        $annonce->setDescription($description);
        $annonce->setPrice($price);
        $annonce->setVendorId($vendorId);
        $annonce->setVille($ville);
        $annonce->setRegion($region);
        $annonce->setCategory($category);

        // Appeler la méthode du modèle AnnonceModel pour créer l'annonce
        if ($this->annonceModel->createAnnonce($annonce)) {
            echo json_encode(['message' => 'Annonce créée avec succès']);
        } else {
            echo json_encode(['message' => 'Une erreur est survenue lors de la création de l\'annonce']);
        }
    }
}
    // ...
