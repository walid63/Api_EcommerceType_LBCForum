<?php




// Définir une liste de routes avec les URL et les contrôleurs correspondants
$routes = [
    '/' => 'HomeController::index',
    '/login' => 'AuthController::login',
    '/register' => 'AuthController::register',
    '/logout' => 'AuthController::logout',
    '/users' => 'UserController::getUsers',
    '/users/create' => 'UserController::createUser',
    '/users/update' => 'UserController::updateUser',
    '/users/delete' => 'UserController::deleteUser',
    '/forum' => 'ForumController::index',
    '/forum/create' => 'ForumController::createTopic',
    '/forum/topic/{topicId}' => 'ForumController::viewTopic',
    '/forum/post/create' => 'ForumController::createPost',
    '/forum/post/edit/{postId}' => 'ForumController::editPost',
    '/forum/post/delete/{postId}' => 'ForumController::deletePost',
    '/forum/post/{postId}' => 'ForumController::showPost',
    '/forum/posts' => 'ForumController::listPosts'
];

$requestUrl = $_SERVER['REQUEST_URI'];

// Extraire le chemin d'URL sans la chaîne de requête
$parsedUrl = parse_url($requestUrl);
$path = $parsedUrl['path'];
// Récupérer l'URL demandée
//$requestUri = $_SERVER['REQUEST_URI'];

// Vérifier si l'URL correspond à une route définie
if (array_key_exists($path, $routes)) {
    // Extraire le contrôleur et la méthode à partir de la route
    $routeParts = explode('::', $routes[$path]);
    $controllerName = $routeParts[0];
    $methodName = $routeParts[1];

    // Inclure le fichier du contrôleur
    require_once 'controllers/' . $controllerName . '.php';

    // Instancier le contrôleur
    $controller = new $controllerName();

    // Appeler la méthode du contrôleur
    $controller->$methodName();
} else {
    // Gérer la page non trouvée (404)
    echo 'Page not found';
}