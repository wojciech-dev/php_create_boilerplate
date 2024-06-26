<?php

namespace App\Helpers;
use App\TwigConfig;

class Functions {

  //sprawdzenie czy uzytkonik jest zalogowany
    public static function isLoggedIn()
    {
        return isset($_SESSION['logged_in']);
    }

    //sprawdzenie czy rzadanie orzyszło metodą GET czy POST
    public static function checkRequestMethod()
    {
      if ($_SERVER['REQUEST_METHOD'] === 'GET') {
          return 'GET';
      } elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
          return 'POST';
      } else {
          return null;
      }
    }

    //generowanie slug
  public static function slugify($text)
  {
    $text = preg_replace('~[^\pL\d]+~u', '-', $text);
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);
    $text = preg_replace('~[^-\w]+~', '', $text);
    $text = trim($text, '-');
    $text = preg_replace('~-+~', '-', $text);
    $text = strtolower($text);
    if (empty($text)) {
      return 'n-a';
    }
    return $text;
  }

//debug
  public static function debug($data) 
  {
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    exit;
  }

  //pobiera ID z abresu url
  public function getIdFromUrl($url)
  {
      $path = parse_url($url, PHP_URL_PATH);
  
      if (preg_match('/(\d+)$/', $path, $matches)) {
          return (int)$matches[1];
      }
  
      return null;
  }

  /*
  sprawdza czy zmienna $_POST['parent_id'] jest taka sama jak id w adresie url
  jak tak do zapisuje 0 jak nie to wartość pola
  potrzebne do edycji drzewka menu
  */
  public static function checkParentIdWithUrlId($parentId) 
  {
    $url = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
    $urlComponents = parse_url($url);
    $path = $urlComponents['path'];

    $pathParts = explode('/', trim($path, '/'));
    $urlId = end($pathParts);

    if ($parentId == $urlId) {
        return true;
    }

    return false;
}


  public static function mini_audyt_strony($url)
  {
    // Funkcja do pobierania zawartości strony
    function pobierz_zawartosc($url) {
        $curl = curl_init($url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        $html = curl_exec($curl);
        curl_close($curl);
        return $html;
    }

    // Pobranie zawartości strony
    $html = pobierz_zawartosc($url);

    // Liczenie ilości zapytań HTTP
    $ilosc_zapytan = substr_count($html, '<script') + substr_count($html, '<img') + substr_count($html, '<link') + substr_count($html, '<iframe') + substr_count($html, '<object') + substr_count($html, '<embed') + substr_count($html, '<video') + substr_count($html, '<audio') + substr_count($html, '<source') + substr_count($html, '<track') + substr_count($html, '<input') + substr_count($html, '<script') + substr_count($html, '<meta');

    // Liczenie ilości obrazów
    $ilosc_obrazow = substr_count($html, '<img');

    // Liczenie ilości styli CSS
    $ilosc_css = substr_count($html, '<link');

    // Liczenie ilości skryptów JavaScript
    $ilosc_skryptow = substr_count($html, '<script');

    // Liczenie ilości zapytań MySQL
    $ilosc_zapytan_mysql = substr_count($html, 'mysql_query') + substr_count($html, 'mysqli_query') + substr_count($html, 'PDO::query');

    // Wyświetlanie wyników
    echo "Ilość zapytań HTTP: $ilosc_zapytan<br>";
    echo "Ilość załadowanych obrazów: $ilosc_obrazow<br>";
    echo "Ilość załadowanych stylów CSS: $ilosc_css<br>";
    echo "Ilość załadowanych skryptów JavaScript: $ilosc_skryptow<br>";
    echo "Ilość zapytań MySQL: $ilosc_zapytan_mysql<br>";
}






}

