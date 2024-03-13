<?php

require dirname(__DIR__) . '/vendor/autoload.php';
require_once('../Core/Router.php');

require_once('../App/Controllers/AdminController.php');
require_once('../App/Controllers/FrontController.php');

// Przykładowe użycie
$router = new Router();


$router->with('/admin', function ($router, $prefix) {
    $router->respondWithController(['GET', $prefix.'/menu', 'AdminController@home']);
    $router->respondWithController(['POST', $prefix.'/login', 'AdminController@login']);
    $router->respondWithController(['GET', $prefix.'/logout', 'AdminController@logout']);
});


// Dodanie tras do kontrolera artykułów
$router->respondWithController(['GET', '/', 'FrontController@home']);
$router->respondWithController(['GET', '/{title}', 'FrontController@index']);
$router->respondWithController(['GET', '/{title}/{id}', 'FrontController@show']);


$router->dispatch();

/*
http://mycms.vot.pl/admin/menu
http://mycms.vot.pl/admin/menu/create
http://mycms.vot.pl/admin/menu/edit/1
*/
