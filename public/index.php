<?php
session_start(); 
require dirname(__DIR__) . '/vendor/autoload.php';
require_once('../Core/Router.php');

require_once('../App/Controllers/MenuController.php');
require_once('../App/Controllers/FrontController.php');
require_once('../App/Controllers/BodyController.php');
require_once('../App/Controllers/BannerController.php');
require_once('../App/Controllers/MigrationController.php');

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
    
        $router->respondWithController(['GET', $prefix.'/banner/{id}', 'BannerController@home']);
        $router->respondWithController([Functions::checkRequestMethod(), $prefix.'/banner/create/{id}', 'BannerController@create']);
        $router->respondWithController([Functions::checkRequestMethod(), $prefix.'/banner/update/{id}', 'BannerController@update']);
        $router->respondWithController(['GET', $prefix.'/banner/delete/{id}', 'BannerController@destroy']);
    
        $router->respondWithControllerMultiple('POST', $prefix. '/body/{direction}/{id}', 'BodyController@moveItem', 0);
        $router->respondWithControllerMultiple('POST', $prefix. '/banner/{direction}/{id}', 'BannerController@moveItem', 0);

    }
    
    $router->respondWithController(['POST', $prefix.'/login', 'MenuController@login']);
    $router->respondWithController(['GET', $prefix.'/logout', 'MenuController@logout']);

});

$router->respondWithController(['GET', '/migration', 'MigrationController@migrateTables']);

// Dodanie tras do kontrolera artykułów
$router->respondWithControllerMultiple('GET', '/', 'FrontController@home', 0);
$router->respondWithControllerMultiple('GET', '/{title}', 'FrontController', 4);



$router->dispatch();



//Functions::mini_audyt_strony($_SERVER['PHP_SELF']);
