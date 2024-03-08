<?php
require __DIR__ . '/../vendor/autoload.php';
use App\TwigConfig;
use App\Models\Database;
use App\Controllers\AdminController;

$db = new Database();

// Remove query string from URI
$uri = strtok($_SERVER['REQUEST_URI'], '?');

// Define the base namespace for controllers
$controllerNamespace = 'App\Controllers\\';

// Remove leading and trailing slashes
$uri = trim($uri, '/');

// If URI is empty, default to 'home'
$uri = $uri ?: 'home';

// Check if the user is trying to access admin section
if ($uri === 'admin') {
    // Instantiate the AdminController
    $controller = new AdminController();
    // If the request method is POST, handle login
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $controller->login();
    } else {
        // Display login form
        $controller->loginForm();
    }
    exit;
}

// Convert URI segments to controller class name
$segments = explode('/', $uri);


// Get controller class name (using first segment as controller name)
$controllerName = ucfirst(array_shift($segments));
$controllerClass = $controllerNamespace . $controllerName . 'Controller';

// Check if the controller class exists
if (class_exists($controllerClass)) {
    $controller = new $controllerClass($db);
    $method = empty($segments) ? 'index' : array_shift($segments);
    $controller->$method(...$segments);
} else {
    $twig = TwigConfig::getTwig();
    echo $twig->render('404.twig');
}