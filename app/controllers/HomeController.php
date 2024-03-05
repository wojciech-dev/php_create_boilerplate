<?php

namespace App\Controllers;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class HomeController
{
    public function index()
    {
        // Twig setup
        $loader = new FilesystemLoader('../app/views');
        $twig = new Environment($loader);

        // Example data
        $data = [
            'title' => 'Welcome to My Website',
            'content' => 'This is a simple PHP MVC boilerplate with Twig templates.',
        ];

        // Render Twig template
        echo $twig->render('home.twig', $data);
    }
}
