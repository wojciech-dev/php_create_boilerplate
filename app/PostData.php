<?php

namespace App;
use App\Helpers\Functions;
use App\Models\Database;
use App\Models\Photos;

class PostData {

    public static function gePostDataMenu()
    {
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
            'parent_id' => Functions::checkParentIdWithUrlId($_POST['parent_id']) ? 0 : $_POST['parent_id'],
            'status' => $_POST['status']
        ];
    }

    public function getPostDataBody($existingData = [])
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
            'photo1' => Photos::uploadPhoto('photo1', $errors, $existingData['photo1'] ?? null) ?? $existingData['photo1'] ?? null,
            'photo2' => Photos::uploadPhoto('photo2', $errors, $existingData['photo2'] ?? null) ?? $existingData['photo2'] ?? null,
            'photo3' => Photos::uploadPhoto('photo3', $errors, $existingData['photo3'] ?? null) ?? $existingData['photo3'] ?? null,
            'photo4' => Photos::uploadPhoto('photo4', $errors, $existingData['photo4'] ?? null) ?? $existingData['photo4'] ?? null,
        ];
    
        if (!empty($errors)) {
            return ['errors' => $errors];
        }
    
        return $data;
    }
    


    
    
    
    


}

