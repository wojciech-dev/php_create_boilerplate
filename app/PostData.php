<?php

namespace App;
use App\Helpers\Functions;
use App\Models\Database;

class PostData {


    public static function gePostDataMenu()
    {
        $db = new Database();
    

        $errors = [];

        if (empty($_POST['title'])) {
            $errors[] = 'Tytuł jest wymagany';
        }

        if ($errors) {
            return ['errors' => $errors];
        }


        return [
            'title' => $_POST['title'],
            'slug' => Functions::slugify($_POST['title']),
            'parent_id' => $_POST['parent_id'] ?? 0,
            'status' => $_POST['status']
        ];

    }

    public static function getPostDataBody()
    {
        $errors = [];

        // Walidacja wymaganych pól
        if (empty($_POST['name'])) {
            $errors[] = 'Nazwa jest wymagana';
        }
        if (empty($_POST['title'])) {
            $errors[] = 'Tytuł jest wymagany';
        }

        // Sprawdzanie, czy są błędy
        if ($errors) {
            return ['errors' => $errors];
        }

        // Pobieranie danych formularza
        $data = [
            'parent_id' => $_POST['parent_id'],
            'name' => $_POST['name'],
            'title' => $_POST['title'],
            'description' => $_POST['description'] ?? '',
            'slug' => Functions::slugify($_POST['title']),
            'status' => isset($_POST['status']) ? 1 : 0,
            'more' => isset($_POST['more']) ? 1 : 0,
            'photo1' => $_FILES['photo1']['name'] ?? null,
            'photo2' => $_FILES['photo2']['name'] ?? null,
            'photo3' => $_FILES['photo3']['name'] ?? null,
            'photo4' => $_FILES['photo4']['name'] ?? null,
        ];

        return $data;
    }


}

