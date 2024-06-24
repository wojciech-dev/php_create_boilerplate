<?php

use App\TwigConfig;
use App\PostData;
use App\Models\UserModel;
use App\Models\Database;
use App\Helpers\Functions;
use App\Models\Photos;
use App\Helpers\MenuGenerator;

class MenuController 
{

    private $db;
    private $photos;
    private $menu;

    public function __construct()
    {
        $this->db = new Database();
        $this->photos = new Photos();
        $this->menu = $this->generateMenu();
    }

    private function generateMenu()
    {
        $menuData = $this->db->find('menu');
        $menuGenerator = new MenuGenerator($menuData);
        return $menuGenerator->generateMenu();
    }

    public function login()
    {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $userModel = new UserModel();
        $error = $userModel->login($username, $password);
        echo TwigConfig::getTwig()->render('front/login.twig', [
            'error' => $error,
        ]);
    }

    public function home()
    {
        $menuItems = $this->db->find('menu');
        $counts = [];
    
        foreach ($menuItems as $item) {
            $counts[$item['id']] = $this->db->countBodyRecordsLinkedToMenu($item['id']);
        }
    
        echo TwigConfig::getTwig()->render('admin/menu/menu.twig', [
            'data' => $menuItems,
            'counts' => $counts,
            'menu' => $this->menu
        ]);
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

        $menuItems = $this->db->find('menu', [],['id', 'parent_id', 'title']);
        echo TwigConfig::getTwig()->render('admin/menu/menuForm.twig', [
            'data' => $menuItems, 
            'error' => $errorMessages ?? [],
            'menu' => $this->menu
        ]);
    }

    public function update($id)
    {
        if (isset($_POST['submit'])) {
            $postItems = PostData::gePostDataMenu();
            
            if (isset($postItems['errors']) && $postItems['errors']) {
                $errorMessages = $postItems['errors'];
            } else {
                $this->db->edit('menu', $postItems, $id['id']);
                header('Location: /admin/menu');
            }
        }

        $menuById = $this->db->find('menu', ['id' => $id['id']], null, true);
        $menuItems = $this->db->find('menu', [],['id', 'parent_id', 'title']);
        echo TwigConfig::getTwig()->render('admin/menu/menuForm.twig', [
            'data' => $menuItems, 
            'edit' => $menuById, 
            'formAction' => 'update',
            'error' => $errorMessages ?? [],
            'menu' => $this->menu
        ]);
    }

    public function destroy($id)
    {
        $record = $this->db->find('menu', ['id' => $id['id']], null, true);
        $this->photos->deleteMenuAndRelatedBodies($record['id']);
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