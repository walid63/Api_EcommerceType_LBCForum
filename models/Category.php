<?php


require_once "./server/_connect/_connect.php";
require_once "./src/Category.php";


class CategoryModel
{
    private $db;

    public function __construct()
    {
        $this->db = _connect::getInstance()->getConnection();
    }

    public function createCategory(Category $category)
    {
        $query = "INSERT INTO category (name) VALUES (:name)";
        $statement = $this->db->prepare($query);
        $statement->execute([
            'name' => $category->getName()
        ]);

        if ($statement->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }


    public function getCategoryIdByName($name)
    {
        $query = "SELECT id FROM categories WHERE name = :name";
        $statement = $this->db->prepare($query);
        $statement->execute(['name' => $name]);

        $result = $statement->fetch(PDO::FETCH_ASSOC);
        if ($result) {
            return $result['id'];
        } else {
            return null;
        }
    }

    public function getCategoryById($categoryId)
    {
        $query = "SELECT * FROM category WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$categoryId]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $category = new Category();
            $category->setId($row['id']);
            $category->setName($row['name']);

            return $category;
        } else {
            return null;
        }
    }

    // Autres méthodes du modèle CategoryModel

    // ...
}
