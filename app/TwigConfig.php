<?php

namespace App;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class TwigConfig
{
    private static $twig;

    public static function getTwig()
    {
        if (!isset(self::$twig)) {
            $loader = new FilesystemLoader('../app/views');
            self::$twig = new Environment($loader);
        }
        return self::$twig;
    }
}
