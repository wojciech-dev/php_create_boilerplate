<?php

use App\TwigConfig;

class FrontController
{

    private function isLoggedIn()
    {
        session_start(); 
        return isset($_SESSION['logged_in']);
    }

    public function index($uri)
    {

        if ($uri['title'] == 'admin') {
            
            if ($this->isLoggedIn()) {
                header('Location: /');
                exit;
            } else {
                echo TwigConfig::getTwig()->render('admin/login.twig');
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

