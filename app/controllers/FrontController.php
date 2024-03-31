<?php

use App\TwigConfig;
use App\Helpers\Functions;

class FrontController
{
    public function index($uri)
    {
        //blokowanie login form po zalogowaniu
        if ($uri['title'] == 'admin') {
            
            if (Functions::isLoggedIn()) {
                header('Location: /');
                exit;
            } else {
                echo TwigConfig::getTwig()->render('front/login.twig');
            }
        } else {
            echo 'inna tresc';
        }
        
    }

    public function show($matches)
    {
        $id = $matches['id'];
        echo "Wyświetlanie artykułu o ID: $id";
    }

    public function home()
    {
        echo TwigConfig::getTwig()->render('front/index.twig');
    }

}

