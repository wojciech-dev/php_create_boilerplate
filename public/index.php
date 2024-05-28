<?php
session_start(); 
require dirname(__DIR__) . '/vendor/autoload.php';
require_once('../Core/Router.php');

require_once('../App/Controllers/MenuController.php');
require_once('../App/Controllers/FrontController.php');
require_once('../App/Controllers/BodyController.php');

use App\Helpers\Functions;


$router = new Router();

$router->with('/admin', function ($router, $prefix) {
    if (Functions::isLoggedIn()) {
        $router->respondWithController(['GET', $prefix.'/menu', 'MenuController@home']);
        $router->respondWithController([Functions::checkRequestMethod(), $prefix.'/menu/create', 'MenuController@create']);
        $router->respondWithController([Functions::checkRequestMethod(), $prefix.'/menu/update/{id}', 'MenuController@update']);
        $router->respondWithController(['GET', $prefix.'/menu/delete/{id}', 'MenuController@destroy']);

        $router->respondWithController(['GET', $prefix.'/body/{id}', 'BodyController@home']);
        $router->respondWithController([Functions::checkRequestMethod(), $prefix.'/body/create/{id}', 'BodyController@create']);
        $router->respondWithController([Functions::checkRequestMethod(), $prefix.'/body/update/{id}', 'BodyController@update']);
        $router->respondWithController(['GET', $prefix.'/body/delete/{id}', 'BodyController@destroy']);
    }
    
    $router->respondWithController(['POST', $prefix.'/login', 'MenuController@login']);
    $router->respondWithController(['GET', $prefix.'/logout', 'MenuController@logout']);
    
});


// Dodanie tras do kontrolera artykułów
$router->respondWithController(['GET', '/', 'FrontController@home']);
$router->respondWithController(['GET', '/{title}', 'FrontController@index']);
$router->respondWithController(['GET', '/{title}/{subtitle}', 'FrontController@index']);
$router->respondWithController(['GET', '/{title}/{subtitle}/{subsubtitle}', 'FrontController@index']);




$router->dispatch();



//Functions::mini_audyt_strony($_SERVER['PHP_SELF']);

/*
http://mycms.vot.pl/admin/menu
http://mycms.vot.pl/admin/menu/create
http://mycms.vot.pl/admin/menu/edit/1
*/
