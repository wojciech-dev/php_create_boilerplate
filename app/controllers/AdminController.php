<?php

namespace App\Controllers;

use App\TwigConfig;
use App\Models\MenuModel;
use App\Models\UserModel;

class AdminController
{
    public function loginForm()
    {
        echo TwigConfig::getTwig()->render('admin/login.twig');
    }

    public function login()
    {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $userModel = new UserModel();
        $userModel->login($username, $password);
    }

    public function dashboard()
    {
        session_start();
        if (!isset($_SESSION['logged_in'])) {
            header('Location: /admin');
            exit;
        }

        $menuModel = new MenuModel();
        $menus = $menuModel->getAllMenus();

        echo TwigConfig::getTwig()->render('admin/dashboard.twig', ['menus' => $menus]);
    }


    public function logout()
    {
        session_start();
        session_destroy();
        header('Location: /admin');
        exit;
    }

    public function add_menu()
    {
        $parent = isset($_POST['parent']) ? intval($_POST['parent']) : 0;
        $title = $_POST['title'];
        $slug = $_POST['slug'] = $title;
        $status = $_POST['status'];
        $reverse = $_POST['reverse'];

        $menuModel = new MenuModel();

        $parentSlug = '';
        if ($parent != 0) {
            $parentMenu = $menuModel->getMenuById($parent);
            $parentSlug = $parentMenu['slug'] . '/';
        }

        $fullSlug = $parentSlug . $slug;
        $menuModel->addMenu($parent, $title, $fullSlug, $status, $reverse);

        header('Location: /admin/dashboard');
        exit;
    }

}
