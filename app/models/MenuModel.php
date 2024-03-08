<?php

namespace App\Models;

use App\Models\Database;

class MenuModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAllMenus()
    {
        // Pobierz wszystkie elementy menu z bazy danych
        $stmt = $this->db->getConnection()->query("SELECT * FROM menu");
        return $stmt->fetchAll();
    }

    public function addMenu($parent, $title, $slug, $status, $reverse)
    {
        // Zapisz dane menu do bazy danych
        $stmt = $this->db->getConnection()->prepare("INSERT INTO menu (parent_id, title, slug, status, reverse, created_at) VALUES (?, ?, ?, ?, ?, NOW())");
        $stmt->execute([$parent, $title, $slug, $status, $reverse]);
    }

    public function getMenuById($id)
    {
        // Pobierz szczegóły menu na podstawie jego id z bazy danych
        $stmt = $this->db->getConnection()->prepare("SELECT * FROM menu WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

}
