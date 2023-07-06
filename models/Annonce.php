<?php

require_once "./server/_connect/_connect.php";
require_once "./models/Category.php";

class AnnonceModel
{
    private $db;
    private $categoryModel;

    public function __construct()
    {
        $this->db = _connect::getInstance()->getConnection();
        $this->categoryModel = new CategoryModel;
    }

    public function getAnnoncesByCategory($categoryId)
    {
        // Effectuer la requête SQL pour récupérer les annonces d'une catégorie spécifique
        $query = "SELECT * FROM annonce WHERE category_id = :categoryId";
        $statement = $this->db->prepare($query);
        $statement->execute(['categoryId' => $categoryId]);

        // Traiter les résultats et retourner les annonces
        $annonces = [];
        while ($row = $statement->fetch(PDO::FETCH_ASSOC)) {
            $annonce = new Annonce();
            // ... Initialisez les propriétés de l'annonce à partir des données de la requête ...
            $annonces[] = $annonce;
        }

        return $annonces;
    }

    public function getAllAnnonces()
    {
        $query = "SELECT * FROM annonce";
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        $annonces = [];

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $annonce = new Annonce();
            $annonce->setId($row['id']);
            $annonce->setTitle($row['title']);
            $annonce->setDescription($row['description']);
            $annonce->setPrice($row['price']);
            $annonce->setVendorId($row['vendor_id']);
            $annonce->setVille($row['ville']);
            $annonce->setRegion($row['region']);
            $categoryId = $row['category_id'];

            // Appeler la méthode du modèle CategoryModel pour récupérer la catégorie par l'ID
            $categoryModel = new CategoryModel();
            $category = $categoryModel->getCategoryById($categoryId);

            // Vérifier si la catégorie existe
            if ($category) {
                // Définir la catégorie de l'annonce
                $annonce->setCategoryId($category->getId());
            }

            $annonces[] = $annonce;
        }

        return $annonces;
    }


    public function createAnnonce(Annonce $annonce)
    {
        $query = "INSERT INTO annonce (vendor_id, title, description, price, city, created_at, region, category_id)
                  VALUES (:vendor_id, :title, :description, :price, :city, :createdAt, :region, :category_id)";
    
        $statement = $this->db->prepare($query);
        $statement->execute([
            'vendor_id' => $annonce->getVendorId(),
            'title' => $annonce->getTitle(),
            'description' => $annonce->getDescription(),
            'price' => $annonce->getPrice(),
            'city' => $annonce->getVille(),
            'createdAt' => date('Y-m-d H:i:s'), // Utiliser la date et l'heure actuelles
            'region' => $annonce->getRegion(),
            'category_id' => $annonce->getCategoryId()
        ]);
    
        if ($statement->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }
    // Autres méthodes de la classe AnnonceModel

    // ...
}
