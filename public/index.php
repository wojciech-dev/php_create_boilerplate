<?php
require __DIR__ . '/../vendor/autoload.php';
use App\Models\Database;
use App\TwigConfig;

$db = new Database();

// Remove query string from URI
$uri = strtok($_SERVER['REQUEST_URI'], '?');

// Define the base namespace for controllers
$controllerNamespace = 'App\Controllers\\';

// Remove leading and trailing slashes
$uri = trim($uri, '/');

// If URI is empty, default to 'home'
$uri = $uri ?: 'home';

// Convert URI segments to controller class name
$segments = explode('/', $uri);
$controllerClass = $controllerNamespace . implode('\\', array_map('ucfirst', $segments)) . 'Controller';

// Check if the controller class exists
if (class_exists($controllerClass)) {
    $controller = new $controllerClass($db);
    $controller->index();
} else {
    $twig = TwigConfig::getTwig();
    echo $twig->render('404.twig');
}
