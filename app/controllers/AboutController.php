<?php

namespace App\Controllers;

use App\Models\Database;
use App\TwigConfig;

class AboutController
{
    private $db;

    public function __construct(Database $db)
    {
        $this->db = $db;
    }

    public function index()
    {
      

        // Get Twig instance
        $twig = TwigConfig::getTwig();

        // Render Twig template with data
        echo $twig->render('about.twig', ['data' => 'ok']);
    }

    public function service()
    {
        // Get data for the service page
        $data = $this->db->getAll('service_page');

        // Render Twig template with data
        echo TwigConfig::getTwig()->render('service.twig', ['data' => $data]);
    }
}
