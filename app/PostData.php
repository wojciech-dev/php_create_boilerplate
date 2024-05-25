<?php

namespace App;
use App\Helpers\Functions;
use App\Models\Database;

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

    public static function getPostDataBody($existingData = [])
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
            'photo1' => self::uploadPhoto('photo1', $errors, $existingData['photo1'] ?? null) ?? $existingData['photo1'] ?? null,
            'photo2' => self::uploadPhoto('photo2', $errors, $existingData['photo2'] ?? null) ?? $existingData['photo2'] ?? null,
            'photo3' => self::uploadPhoto('photo3', $errors, $existingData['photo3'] ?? null) ?? $existingData['photo3'] ?? null,
            'photo4' => self::uploadPhoto('photo4', $errors, $existingData['photo4'] ?? null) ?? $existingData['photo4'] ?? null,
        ];
    
        if (!empty($errors)) {
            return ['errors' => $errors];
        }
    
        return $data;
    }
    

    //upload zdjec na serwer
    private static function uploadPhoto($photoKey, &$errors, $existingPhoto = null)
    {
        $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf'];
        $maxSize = 5 * 1024 * 1024; // 5MB
    
        if (isset($_FILES[$photoKey]) && $_FILES[$photoKey]['error'] == UPLOAD_ERR_OK) {
            $uploadDir = 'uploads/';
    
            $photoName = $_FILES[$photoKey]['name'];
            $photoType = $_FILES[$photoKey]['type'];
            $photoSize = $_FILES[$photoKey]['size'];
    
            // Sprawdzenie rozmiaru pliku
            if ($photoSize > $maxSize) {
                $errors[] = 'Plik ' . $photoName . ' przekracza maksymalny rozmiar (5MB)';
                return null;
            }
    
            // Sprawdzenie typu pliku
            if (!in_array($photoType, $allowedTypes)) {
                $errors[] = 'Plik ' . $photoName . ' ma nieprawidłowy typ. Dozwolone są jedynie obrazy JPG, PNG lub PDF.';
                return null;
            }
    
            // Generowanie unikalnej nazwy pliku
            $extension = pathinfo($photoName, PATHINFO_EXTENSION);
            $uniqueName = uniqid('photo_') . '.' . $extension;
            $targetFile = $uploadDir . $uniqueName;
    
            // Przesłanie pliku na serwer
            if (move_uploaded_file($_FILES[$photoKey]['tmp_name'], $targetFile)) {
                // Usuwanie starego pliku, jeśli istnieje
                if ($existingPhoto && file_exists($uploadDir . $existingPhoto)) {
                    unlink($uploadDir . $existingPhoto);
                }
                return $uniqueName; // Zwrócenie unikalnej nazwy pliku
            } else {
                $errors[] = 'Wystąpił błąd podczas przesyłania pliku ' . $photoName;
                return null;
            }
        } elseif (isset($_FILES[$photoKey]['name']) && $_FILES[$photoKey]['error'] != UPLOAD_ERR_NO_FILE) {
            $errors[] = 'Wystąpił błąd podczas przesyłania pliku ' . $_FILES[$photoKey]['name'];
        }
    
        return null; // Brak przesłanego pliku lub wystąpił błąd podczas przesyłania
    }
    
    
    
    
    


}

