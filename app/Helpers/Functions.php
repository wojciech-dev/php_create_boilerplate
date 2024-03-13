<?php

namespace App\Helpers;


class Functions {

    public static function isLoggedIn()
    {
        return isset($_SESSION['logged_in']);
    }

    public function debugFunc($data) 
    {
      echo "<pre>";
      print_r($data);
      echo "</pre>";
      exit;
    }
}
