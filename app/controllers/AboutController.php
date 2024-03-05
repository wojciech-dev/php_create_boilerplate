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
        // Get all data from about_page table
        $data = $this->db->getAll('about_me');

        // Get Twig instance
        $twig = TwigConfig::getTwig();

        // Render Twig template with data
        echo $twig->render('about.twig', ['data' => $data]);
    }
}
