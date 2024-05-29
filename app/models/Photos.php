<?php

namespace App\Models;

use App\Models\Database;
use App\Helpers\Functions;
use Exception;

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
    
            if ($photoSize > $maxSize) {
                $errors[] = 'Plik ' . $photoName . ' przekracza maksymalny rozmiar (5MB)';
                return null;
            }
    
            if (!in_array($photoType, $allowedTypes)) {
                $errors[] = 'Plik ' . $photoName . ' ma nieprawidłowy typ. Dozwolone są jedynie obrazy JPG, PNG lub PDF.';
                return null;
            }
    
            $extension = pathinfo($photoName, PATHINFO_EXTENSION);
            $uniqueName = uniqid('photo_') . '.' . $extension;
            $targetFile = $uploadDir . $uniqueName;
    
            if (move_uploaded_file($_FILES[$photoKey]['tmp_name'], $targetFile)) {
                if ($existingPhoto && file_exists($uploadDir . $existingPhoto)) {
                    unlink($uploadDir . $existingPhoto);
                }
                return $uniqueName;
            } else {
                $errors[] = 'Wystąpił błąd podczas przesyłania pliku ' . $photoName;
                return null;
            }
        } elseif (isset($_FILES[$photoKey]['name']) && $_FILES[$photoKey]['error'] != UPLOAD_ERR_NO_FILE) {
            $errors[] = 'Wystąpił błąd podczas przesyłania pliku ' . $_FILES[$photoKey]['name'];
        }
    
        return null;
    }
    
    //usuwanie zdjęc z serwera
    public function deletePhotos($record)
    {
        $photos = ['photo1', 'photo2', 'photo3', 'photo4'];
      
        $hasPhotos = false;
        foreach ($photos as $photo) {
            if (!empty($record[$photo])) {
                $hasPhotos = true;
                break;
            }
        }
    
        if (!$hasPhotos) {
            echo "Brak zdjęć do usunięcia.\n";
            return;
        }
    
        foreach ($photos as $photo) {
            if (!empty($record[$photo])) {
                $imagePath = 'uploads/' . $record[$photo];
    
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
    
    public function deleteMenuAndRelatedBodies($menuId)
    {
        $conn = $this->db->getConnection();

        try {
            $conn->beginTransaction();

            // Usuń wszystkie rekordy 'body' powiązane z danym menuId
            $this->deleteBodies($menuId);

            // Usuń rekord z tabeli 'menu'
            $this->db->delete('menu', ['id' => $menuId]);

            $conn->commit();
            echo 'Menu and related records deleted successfully';
        } catch (Exception $e) {
            $conn->rollBack();
            echo 'Failed to delete menu and related bodies: ' . $e->getMessage();
        }
    }

    private function deleteBodies($menuId)
    {
        // Pobierz wszystkie rekordy 'body' powiązane z danym menuId
        $relatedBodies = $this->db->find('body', ['parent_id' => $menuId], ['id', 'photo1', 'photo2', 'photo3', 'photo4']);

        // Iteracyjne usunięcie rekordów 'body' i ich powiązanych zdjęć
        foreach ($relatedBodies as $body) {
            // Usuń zdjęcia powiązane z rekordem 'body'
            for ($i = 1; $i <= 4; $i++) {
                $photoField = 'photo' . $i;
                if (!empty($body[$photoField])) {
                    $photoPath = 'uploads/' . $body[$photoField];
                    if (file_exists($photoPath)) {
                        unlink($photoPath);
                    }
                }
            }

            // Usuń rekord 'body'
            $this->db->delete('body', ['id' => $body['id']]);
        }
    }
        
}