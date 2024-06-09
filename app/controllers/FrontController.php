<?php

use App\TwigConfig;
use App\Helpers\Functions;
use App\Helpers\MenuGenerator;
use App\Models\Database;

class FrontController
{
    private $db;
    private $menu;

    public function __construct()
    {
        $this->db = new Database();
        $this->menu = $this->generateMenu();
    }

    private function generateMenu()
    {
        $menuData = $this->db->find('menu');
        $menuGenerator = new MenuGenerator($menuData);
        return $menuGenerator->generateMenu();
    }

    public function index($uri)
    {
    switch ($uri['title']) {
        case 'admin':
            if (Functions::isLoggedIn()) {
                header('Location: /');
                exit;
            } else {
                echo TwigConfig::getTwig()->render('front/login.twig');
            }
            break;
        
            default:

            $menuRecords = $this->db->find('menu', ['slug' => end($uri)], ['id'], true);
            if ($menuRecords) {
                $bodyRecords = $this->db->find('body', ['parent_id' => $menuRecords['id']], null, false);
                echo TwigConfig::getTwig()->render('front/index.twig', [
                    'menu' => $this->menu,
                    'bodyRecords' => $bodyRecords,
                    'url' => $_SERVER['REQUEST_URI']
                ]);
            }
            break;
    }
    }

    public function show($matches)
    {
     
        $bodyRecords = $this->db->find('body', ['id' => $matches['id']], null, true);

        echo TwigConfig::getTwig()->render('front/show.twig', [
            'menu' => $this->menu,
            'item' => $bodyRecords,
        ]);
    }

    // Kontroler uruchamiany na stronie głównej frontu http://localhost:8888/
    public function home()
    {
        echo TwigConfig::getTwig()->render('front/index.twig', [
            'menu' => $this->menu
        ]);
    }
}


