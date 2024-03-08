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
        // Prepare and execute query to fetch user data
        $stmt = $this->db->getConnection()->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
    
        // Fetch user data
        $user = $stmt->fetch();
    
        // Check if user exists and verify password
        if ($user && password_verify($password, $user['password'])) {
            // Start session and set logged_in flag
            session_start();
            $_SESSION['logged_in'] = true;
            // Redirect to admin dashboard
            header('Location: /admin/dashboard');
            exit;
        } else {
            // If login fails, redirect back to login page or show error message
            header('Location: /admin?error=1');
            exit;
        }
    }
    
    
}
