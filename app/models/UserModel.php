<?php

namespace App\Models;

use App\Models\Database;

class UserModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function login($username, $password)
    {
        $user = $this->db->getUserByUsername($username);

        if (!$user) {
            return 'Nieprawidłowy login lub hasło';
        }

        if (!password_verify($password, $user['password'])) {
            return 'Nieprawidłowy login lub hasło';
        }

        $stmt = $this->db->getConnection()->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();
    
        if ($user && password_verify($password, $user['password'])) {
            session_start();
            $_SESSION['logged_in'] = true;
            header('Location: /admin/menu');
            exit;
        } else {
            header('Location: /admin?error=1');
            exit;
        }

        return null; 
    }
    
    
}


