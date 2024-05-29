<?php

use App\TwigConfig;
use App\PostData;
use App\Models\UserModel;
use App\Models\Database;
use App\Helpers\Functions;
use App\Models\Photos;

class MenuController 
{

    private $db;
    private $photos;

    public function __construct()
    {
        $this->db = new Database();
        $this->photos = new Photos();
    }

    public function login()
    {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $userModel = new UserModel();
        $error = $userModel->login($username, $password);
        echo TwigConfig::getTwig()->render('front/login.twig', ['error' => $error]);
    }

    public function home()
    {
        $menuItems =  $this->db->getAll('menu');
        echo TwigConfig::getTwig()->render('admin/menu.twig', ['data' => $menuItems]);
    }
    
    public function create()
    {
        if (isset($_POST['submit'])) {
            $postItems = PostData::gePostDataMenu();
            if (isset($postItems['errors']) && $postItems['errors']) {
                $errorMessages = $postItems['errors'];
            } else {
                $this->db->save('menu', $postItems);
                header('Location: /admin/menu');
            }
        }

        $menuItems = $this->db->getAllMenusTitle();
        echo TwigConfig::getTwig()->render('admin/menuForm.twig', [
            'data' => $menuItems, 
            'error' => $errorMessages ?? []
        ]);
    }

    public function update($id)
    {
        if (isset($_POST['submit'])) {
            $postItems = PostData::gePostDataMenu();
            

            Functions::debug($postItems);
            if (isset($postItems['errors']) && $postItems['errors']) {
                $errorMessages = $postItems['errors'];
            } else {
                $this->db->edit('menu', $postItems, $id['id']);
                header('Location: /admin/menu');
            }
        }

        $menuById = $this->db->getAllById('menu', $id['id']);
        $menuItems = $this->db->getAllMenusTitle();
        echo TwigConfig::getTwig()->render('admin/menuForm.twig', [
            'data' => $menuItems, 
            'edit' => $menuById, 
            'formAction' => 'update',
            'error' => $errorMessages ?? []
        ]);
    }

    public function destroy($id)
    {
        $record = $this->db->getAllById('menu', $id['id']);
   

        //Functions::debug($record['id']);
        $this->photos->deleteMenuAndRelatedBodies($record['id']);
        //$this->db->delete('body', ['parent_id' => $id['id']]);
        //$this->db->delete('menu', ['id' => $id['id']]);
        $this->db->resetChildrenParentId('menu', $id['id']);

            header('Location: /admin/menu');
            exit;
        
    }

    public function logout()
    {

        unset($_SESSION['logged_in']);
        session_destroy();
        header('Location: /admin');
        exit;
    }

}