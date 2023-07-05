<?php
//include "./src/Post.php";
include "./models/Post.php";

class ForumController
{
    private $postModel;
    private $post;


    public function __construct()
    {
        $this->postModel = new PostModel();
        $this->post = new Post();
    }


    public function index()
    {
        session_start();
        $message = 'Bienvenue sur le forum!';

        if (isset($_SESSION['auth_token']) && isset($_SESSION['user'])) {
            // L'utilisateur est connecté
            $userId = $_SESSION['user']['id'];
            $username = $_SESSION['user']['username'];

            $message .= ' Vous êtes connecté en tant que ' . $username . ' (ID utilisateur : ' . $userId . "<= id " . $_SESSION['auth_token'] . ').';
        }

        //echo $message;
        echo json_encode(["message" => $message]);
    }

    public function createTopic()
    {
        // Code pour créer un nouveau sujet sur le forum
        echo "Création d'un nouveau sujet...";
    }

    public function viewTopic($topicId)
    {
        // Code pour afficher un sujet spécifique du forum
        echo "Affichage du sujet avec l'ID : " . $topicId;
    }


    public function listPosts()
    {
        // Récupérer tous les posts
        $posts = $this->postModel->getAllPost();

        // Afficher les posts
        foreach ($posts as $post) {
            echo "Titre : " . $post->getTitle() . "<br>";
            echo "Contenu : " . $post->getContent() . "<br>";
            echo "Créé le : " . $post->getCreatedAt() . "<br>";
            echo "Nombre de likes : " . $post->getCountLike() . "<br>";
            echo "Slug : " . $post->getSlug() . "<br>";
            echo "Image : " . $post->getImage() . "<br>";
            echo "ID de l'auteur : " . $post->getAuthorId() . "<br>";
            echo "<br>";
        }
    }

    public function createPost()
    {
        //avant tout inicialisation des variable automatiques
        $createdAt = date('Y-m-d H:i:s');
        // Vérifier si l'utilisateur est connecté
        session_start();
        if (!isset($_SESSION['auth_token'])) {
            echo json_encode(['message' => 'User not logged in']);
            return;
        } else {
            echo json_encode(['message' => 'Utilisateur Connecté, creation de\'une publication']);
        }
        // Récupérer les données du formulaire ou de la requête
        $title = $_GET['title'];
        $content = $_GET['content'];
        $slug = $this->generateSlug($title);
        // Récupérer les informations de l'utilisateur connecté (par exemple, à partir de la base de données)
        $userId = $_SESSION['user']['id'];
        $username = $_SESSION['user']['username'];
        // Créer un nouvel objet Post
        $this->post->setTitle($title);
        $this->post->setContent($content);
        $this->post->setAuthorId($userId);
        $this->post->setCreatedAt($createdAt);
        $this->post->setSlug($slug);
        $this->post->setCountLike(0); // Initialise countLike à 0
        // Appeler la méthode du modèle pour créer le post

        if ($this->postModel->createPost($this->post)) {
            echo json_encode(['message' => 'Publication crée avec succés']);
        } else {
            echo json_encode(['message' => 'une erreur est survenu veuillez reesayer']);
        }
    }


    public function showPost($postId)
    {
        // Appeler la méthode du modèle pour récupérer les détails du post
        $post = $this->postModel->getPostById($postId);
        if ($post) {
            // Afficher les détails du post
            echo json_encode(['post' => $post]);
        } else {
            echo json_encode(['message' => 'La publication est introuvable ressayez']);
        }
    }


    public function editPost($postId)
    {
        // Code pour modifier un message existant sur le forum
        session_start();
        if (!isset($_SESSION['auth_token'])) {
            echo json_encode(['message' => 'Lutilisateur n\'est pas connecter']);
            return;
        }

        // Récupérer les données du formulaire ou de la requête
        $title = $_GET['title'];
        $newContent = $_GET['content'];

        // Appeler la méthode du modèle pour modifier le post
        if ($this->postModel->updatePost($this->post)) {
            echo json_encode(['message' => 'Publication modifier avec succés']);
        } else {
            echo json_encode(['message' => 'impossible de modifier la publication']);
        }
        // echo "Modification du message avec l'ID : " . $postId;
    }

    public function deletePost($postId)
    {
        // Vérifier si l'utilisateur est connecté
        session_start();
        if (!isset($_SESSION['auth_token'])) {
            echo json_encode(['message' => 'Lutilisateur n\'est pas connecter']);
            return;
        }


        if ($this->postModel->deletePost($postId)) {
            echo json_encode(['message' => 'publication suprié avec succés']);
        } else {
            echo json_encode(['message' => 'impossible de suprimer la publication']);
        }
    }



    public function handleRequest($action, $params)
    {
        switch ($action) {
            case 'index':
                $this->index();
                break;
            case 'createTopic':
                $this->createTopic();
                break;
            case 'viewTopic':
                $topicId = $params[0] ?? null;
                $this->viewTopic($topicId);
                break;
            case 'createPost':
                $topicId = $params[0] ?? null;
                $this->createPost();
                break;
            case 'editPost':
                $postId = $params[0] ?? null;
                $this->editPost($postId);
                break;
            case 'showPost':
                $postId = $params[0] ?? null;
                $this->showPost($postId);
                break;
            case 'deletePost':
                $postId = $params[0] ?? null;
                $this->deletePost($postId);
                break;
            default:
                echo "Action not found";
                break;
        }
    }

    function generateSlug($title)
    {
        $slug = strtolower($title); // Convertit le titre en minuscules
        $slug = preg_replace('/[^a-z0-9]+/', '-', $slug); // Remplace les caractères non alphanumériques par des tirets
        $slug = trim($slug, '-'); // Supprime les tirets en début et fin de chaîne
        return $slug;
    }
}




