<?php

use App\TwigConfig;
use App\Models\UserModel;

class AdminController 
{

    public function login()
    {

        $username = $_POST['username'];
        $password = $_POST['password'];

        $userModel = new UserModel();
        $error = $userModel->login($username, $password);

        echo TwigConfig::getTwig()->render('admin/login.twig', ['error' => $error]);
    }

    public function home()
    {
        echo "lista menu";
    }
    
    public function create()
    {
        echo "Creating a new menu";
    }

    public function update($title)
    {
        echo "Edycja menu o tytule: $title";
    }

    public function destroy($id)
    {
        echo "Usunięcie o id: $id";
    }

    public function logout()
    {
        session_start();
        unset($_SESSION['logged_in']);
        session_destroy();
        header('Location: /admin');
        exit;
    }

}