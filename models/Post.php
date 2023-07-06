<?php

require_once "./server/_connect/_connect.php";
require_once "./src/Post.php";

class PostModel
{
    private $db;

    public function __construct()
    {
        $this->db = _connect::getInstance()->getConnection();
    }

    public function getAllPost()
    {
        $query = "SELECT * FROM post";
        $stmt = $this->db->prepare($query);
        $stmt->execute();

        $posts = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $post = new Post();
            $post->setId($row['id']);
            $post->setTitle($row['title']);
            $post->setContent($row['content']);
            $post->setCreatedAt($row['created_at']);
            $post->setCountLike($row['count_like']);
            $post->setSlug($row['slug']);
            $post->setImage($row['image']);
            $post->setAuthorId($row['author_id']);

            $posts[] = $post;
        }

        return $posts;
    }

    public function createPost(Post $post)
    {
        $query = "INSERT INTO post (title, content, created_at, count_like, slug, image, author_id) 
                  VALUES (:title, :content, :created_at, :count_like, :slug, :image, :author_id)";

        $statement = $this->db->prepare($query);

        $statement->execute([
            'title' => $post->getTitle(),
            'content' => $post->getContent(),
            'created_at' => $post->getCreatedAt(),
            'count_like' => $post->getCountLike(),
            'slug' => $post->getSlug(),
            'image' => $post->getImage(),
            'author_id' => $post->getAuthorId()
        ]);

        if ($statement->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function getPostById($postId)
    {
        $query = "SELECT * FROM posts WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$postId]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $post = new Post();
            $post->setId($row['id']);
            $post->setTitle($row['title']);
            $post->setContent($row['content']);
            $post->setCreatedAt($row['created_at']);

            return $post;
        } else {
            return null;
        }
    }

    public function updatePost(Post $post)
    {
        // Préparer la requête SQL pour mettre à jour le post
        $query = "UPDATE posts SET content = :content WHERE id = :id";

        // Préparer les valeurs à lier aux paramètres de la requête
        $params = [
            'id' => $post->getId(),
            'content' => $post->getContent()
        ];

        // Exécuter la requête préparée avec les paramètres
        $stmt = $this->db->prepare($query);
        $stmt->execute($params);

        // Vérifier si la mise à jour a été effectuée avec succès
        if ($stmt->rowCount() > 0) {
            return true; // La mise à jour a réussi
        } else {
            return false; // La mise à jour a échoué
        }
    }

    public function deletePost($postId)
    {
        $query = "DELETE FROM posts WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$postId]);

        return $stmt->rowCount() > 0;
    }

    public function getPostBySlug($slug)
    {
        $query = "SELECT * FROM post WHERE slug = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$slug]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $post = new Post();
            // Remplir les propriétés du post à partir des valeurs de la base de données
            // ...
            return $post;
        } else {
            return null;
        }
    }
}
