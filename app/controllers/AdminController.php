<?php

use App\TwigConfig;
use App\PostData;
use App\Models\UserModel;
use App\Models\Database;
use App\Helpers\Functions;


class AdminController 
{

    private $db;

    public function __construct()
    {
        $this->db = new Database();
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
    
    //tworzenie nowej pozycji menu
    public function create()
    {
        if (isset($_POST['submit'])) {
            $postItems = PostData::gePostDataMenu();
            //Functions::debug($postItems);
            if (isset($postItems['errors']) && $postItems['errors']) {
                $result = NULL;
            } else {
                $result = $this->db->save('menu', $postItems);
                header('Location: /admin/menu');
            }
        }

        $menuItems = $this->db->getAllMenusTitle();
        echo TwigConfig::getTwig()->render('admin/menuForm.twig', [
            'data' => $menuItems, 
            'error' => $postItems['errors'] ?? []
        ]);
    }

    //edycja menu
    public function update($id)
    {
        if (isset($_POST['submit'])) {
            $postItems = PostData::gePostDataMenu();
            

            //Functions::debug($postItems);
            if (isset($postItems['errors']) && $postItems['errors']) {
                $result = NULL;
            } else {
                $result = $this->db->edit('menu', $postItems, $id['id']);
                header('Location: /admin/menu');
            }
        }

        $menuById = $this->db->getAllById('menu', $id['id']);
        $menuItems = $this->db->getAllMenusTitle();
        echo TwigConfig::getTwig()->render('admin/menuForm.twig', [
            'data' => $menuItems, 
            'edit' => $menuById, 
            'formAction' => 'update',
            'error' => $postItems['errors'] ?? []
        ]);
    }

    public function destroy($id)
    {
        echo "Usunięcie o id: $id";
    }

    public function logout()
    {

        unset($_SESSION['logged_in']);
        session_destroy();
        header('Location: /admin');
        exit;
    }

}