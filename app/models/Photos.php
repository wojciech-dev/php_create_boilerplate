<?php

namespace App\Models;

use App\Models\Database;
use App\Helpers\Functions;

class Photos {

    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    //upload zdjec na serwer
    public static function uploadPhoto($photoKey, &$errors, $existingPhoto = null)
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

    public function deleteChildRecords($parentId)
    {
        // Pobierz wszystkie rekordy, które mają parent_id równe podanemu id
        $childRecords = $this->db->getAll('body', ['parent_id' => $parentId]);

        foreach ($childRecords as $child) {
            // Usuń wszystkie zdjęcia z serwera
            $this->deletePhotos($child);

            // Usuń rekord z tabeli body
            $this->db->delete('body', ['id' => $child['id']]);
        }
    }
    
    public function deletePhotos($record)
    {
        $photos = ['photo1', 'photo2', 'photo3', 'photo4'];
      
        // Sprawdź, czy którykolwiek z pól zdjęć nie jest puste
        $hasPhotos = false;
        foreach ($photos as $photo) {
            if (!empty($record[$photo])) {
                $hasPhotos = true;
                break;
            }
        }
    
        // Jeśli brak zdjęć, nie ma potrzeby kontynuować
        if (!$hasPhotos) {
            echo "Brak zdjęć do usunięcia.\n";
            return;
        }
    
        // Usuń zdjęcia, jeśli istnieją
        foreach ($photos as $photo) {
            if (!empty($record[$photo])) {
                // Zakładam, że zdjęcia są przechowywane w folderze "uploads" w katalogu głównym projektu
                $imagePath = 'uploads/' . $record[$photo];
    
                // Sprawdź, czy plik istnieje i czy można go usunąć
                if (file_exists($imagePath)) {
                    if (unlink($imagePath)) {
                        echo "Usunięto zdjęcie: $imagePath\n";
                    } else {
                        echo "Nie udało się usunąć zdjęcia: $imagePath\n";
                    }
                } else {
                    echo "Zdjęcie nie istnieje: $imagePath\n";
                }
            }
        }
    }
    
    
        
}