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
        $items = $this->db->find('body', ['parent_id' => $id['id']], null);
       
        echo TwigConfig::getTwig()->render('admin/body.twig', [
            'items' => $items,
            'section' => $id['id'],
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
        $currentData = $this->db->find('body', ['id' => $id['id']], null, true);
    
        if (isset($_POST['submit'])) {
            $postItems = $this->data->getPostDataBody($currentData);
            if (isset($postItems['errors']) && $postItems['errors']) {
                $errorMessages = $postItems['errors'];
            } else {
                $this->db->edit('body', $postItems, $id['id']);
                header('Location: /admin/body/' . $currentData['parent_id']);
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
        $record = $this->db->find('body', ['id' => $id['id']], null, true);

        if ($record) {
            $this->photos->deletePhotos($record);
            $this->db->delete('body', ['id' => $id['id']]);
            header('Location: /admin/body/' . $record ['parent_id']);
            exit;
        }
    }

}