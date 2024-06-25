<?php

use App\Models\Database;
use App\TwigConfig;

class MigrationController 
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
        $this->createDatabase();
    }

    private function createDatabase()
    {
        $this->db->createDatabase();
        $this->db->useDatabase();
    }

    public function migrateTables()
    {
        try {
            $this->createBannerTable();
            $this->createBodyTable();
            $this->createBoxTable();
            $this->createMenuTable();
            $this->createUsersTable();

            echo TwigConfig::getTwig()->render('admin/base.twig', [
                'successAlert' => 'Migration completed successfully'
            ]);
        } catch (\PDOException $e) {
            echo TwigConfig::getTwig()->render('admin/base.twig', [
                'dangerAlert' => "Migration failed: " . $e->getMessage()
            ]);
        }
    }

    public function createBannerTable()
    {
        $createBannerTable = "
            CREATE TABLE `banner` (
                `id` int NOT NULL AUTO_INCREMENT,
                `parent_id` int NOT NULL DEFAULT '0',
                `sorting` int DEFAULT '0',
                `name` varchar(100) NOT NULL,
                `title` varchar(100) NOT NULL,
                `description` text,
                `status` tinyint(1) NOT NULL DEFAULT '0',
                `layout` tinyint(1) NOT NULL DEFAULT '1',
                `photo1` varchar(50) DEFAULT NULL,
                `more_link` varchar(50) DEFAULT NULL,
                `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
        ";
        $this->db->exec($createBannerTable);
    }

    public function createBodyTable()
    {
        $createBodyTable = "
            CREATE TABLE `body` (
                `id` int NOT NULL AUTO_INCREMENT,
                `parent_id` int DEFAULT '0',
                `sorting` int DEFAULT '0',
                `name` varchar(100) NOT NULL,
                `title` varchar(100) NOT NULL,
                `description` text,
                `slug` varchar(100) NOT NULL,
                `status` tinyint(1) NOT NULL,
                `more` tinyint(1) NOT NULL,
                `photo1` varchar(50) DEFAULT NULL,
                `photo2` varchar(50) DEFAULT NULL,
                `photo3` varchar(50) DEFAULT NULL,
                `photo4` varchar(50) DEFAULT NULL,
                `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
        ";
        $this->db->exec($createBodyTable);
    }

    public function createBoxTable()
    {
        $createBoxTable = "
            CREATE TABLE `box` (
                `id` int NOT NULL AUTO_INCREMENT,
                `parent_id` int NOT NULL DEFAULT '0',
                `sorting` int DEFAULT '0',
                `name` varchar(100) NOT NULL,
                `description` text,
                `status` tinyint(1) NOT NULL DEFAULT '0',
                `photo1` varchar(50) DEFAULT NULL,
                `more_link` varchar(50) DEFAULT NULL,
                `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
        ";
        $this->db->exec($createBoxTable);
    }

    public function createMenuTable()
    {
        $createMenuTable = "
            CREATE TABLE `menu` (
                `id` int NOT NULL AUTO_INCREMENT,
                `parent_id` int NOT NULL DEFAULT '0',
                `title` varchar(100) NOT NULL,
                `slug` varchar(100) NOT NULL,
                `status` tinyint(1) NOT NULL DEFAULT '0',
                `reverse` tinyint(1) NOT NULL DEFAULT '0',
                `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
        ";
        $this->db->exec($createMenuTable);
    }

    public function createUsersTable()
    {
        $createUsersTable = "
            CREATE TABLE `users` (
                `id` int NOT NULL AUTO_INCREMENT,
                `username` varchar(50) NOT NULL,
                `email` varchar(50) NOT NULL,
                `type` varchar(50) NOT NULL DEFAULT 'user',
                `password` varchar(255) NOT NULL,
                `verified` tinyint(1) NOT NULL DEFAULT '0',
                `token` varchar(255) DEFAULT NULL,
                `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`)
            ) ENGINE=InnoDB AUTO_INCREMENT=36 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
        ";
        $this->db->exec($createUsersTable);

        $insertUsers = "
            INSERT INTO `users` (`id`, `username`, `email`, `type`, `password`, `verified`, `token`, `created_at`) VALUES
            (1, 'admin', 'kontakt@wojciech-kondraciuk.pl', 'admin', '$2y\$10\$BHFmQPA1U.pkoFZuaydCgu2DJYA0WPldlvtLmNhAYsH.y7GCqZzce', 1, '773198ec4caea652505347d4b2de433f272c2dda493413fb9598c21dda29af06239d6cac415a8dc910fef76b52def21a2c0b', '2021-09-23 20:20:05'),
            (2, 'nefren', 'nefren.games@gmail.com', 'user', '$2y\$10\$mEMSL8dKbGI8QuKUB6vIuu6G1y2adg/X/5kFvA9CtvC.KULGzIG6e', 1, '1b14a00a0c6f7398a79ac90109f8a74f92fd1bae8c040bc4999dfceff982ccac5bb84f1e55ce2205425a1bc8d0b1194d7b5b', '2022-01-01 21:51:37'),
            (35, 'admin', 'admin@admin.pl', 'user', '$2y\$10\$l7YyPnpVDBsCE8Vju6QBdepgsYSJt/8WN1HiwUyZqp08o/LzEwPS.', 0, '6b8681a641c3ab635361fd844ca3f3a6b9e2086b6baf88fdee9da4867206df62db29ac627588d2de02c876c659920ff2d40d', '2022-01-02 15:39:01');
        ";

        $this->db->exec($insertUsers);
    }
}