<?php

use App\TwigConfig;
use App\PostData;
use App\Models\Database;
use App\Models\Photos;
use App\Helpers\Functions;


class BodyController 
{

    private $db;
    private $data;
    private $photos;

    public function __construct()
    {
        $this->db = new Database();
        $this->data = new PostData();
        $this->photos = new Photos();
    }


    public function home($id)
    {
        $items = $this->db->getAll('body', ['parent_id' => $id['id']]);
       
        echo TwigConfig::getTwig()->render('admin/body.twig', [
            'items' => $items,
            'section' => $id['id']
        ]);
    }
    
    public function create($id)
    {
        if (isset($_POST['submit'])) {
            $postItems = $this->data->getPostDataBody();
            
            if (isset($postItems['errors'])) {
                $errorMessages = $postItems['errors'];
            } else {
                $this->db->save('body', $postItems);
                header('Location: /admin/body/'.$id['id'].'');
                exit;
            }
        }

        echo TwigConfig::getTwig()->render('admin/bodyForm.twig', [
            'section' => $id['id'],
            'error' => $errorMessages ?? []
        ]);
    }

    public function update($id)
    {
        $errorMessages = [];
        $currentData = $this->db->getAllById('body', $id['id']);
    
        if (isset($_POST['submit'])) {
            $postItems = $this->data->getPostDataBody($currentData);
    
            if (isset($postItems['errors']) && $postItems['errors']) {
                $errorMessages = $postItems['errors'];
            } else {
                $this->db->edit('body', $postItems, $id['id']);
                header('Location: /admin/body/' . $id['id']);
                exit;
            }
        }
    
        echo TwigConfig::getTwig()->render('admin/bodyForm.twig', [
            'section' => $id['id'],
            'edit' => $currentData,
            'formAction' => 'update',
            'error' => $errorMessages
        ]);
    }
    

    //kasowanie wiersza
    public function destroy($id)
    {
        // Pobierz dane rekordu
        $record = $this->db->getAllById('body', $id['id']);
        //Functions::debug($record);

        if ($record) {
            // Usuń wszystkie zdjęcia z serwera
            $this->photos->deletePhotos($record);

            // Usuń rekord z tabeli body
            $this->db->delete('body', ['id' => $id['id']]);

            echo "Usunięto rekord o id:". $id['id'];
        } else {
            echo "Rekord o id: $id nie istnieje.";
        }
    }


}