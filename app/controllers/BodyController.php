<?php

use App\TwigConfig;
use App\PostData;
use App\Models\Database;
use App\Helpers\Functions;


class BodyController 
{

    private $db;

    public function __construct()
    {
        $this->db = new Database();
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
            $postItems = PostData::getPostDataBody();
            
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
            $postItems = PostData::getPostDataBody($currentData);
    
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
    

    public function destroy($id)
    {
        echo "Usunięcie o id: $id";
    }



}