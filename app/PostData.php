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

}

