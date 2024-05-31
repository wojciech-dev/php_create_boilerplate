<?php

use App\TwigConfig;
use App\Helpers\Functions;
use App\Helpers\MenuGenerator;
use App\Models\Database;

class FrontController
{

    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }
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

    //controler uruchmiany na stronie głownej frontu http://localhost:8888/
    public function home()
    {
        $menuData =  $this->db->find('menu');
        $menuGenerator = new MenuGenerator($menuData);

      
        $menu = $menuGenerator->generateMenu();


        echo TwigConfig::getTwig()->render('front/index.twig', ['menu' => $menu]);
    }

}

