<?php

namespace App\Models;

use PDO;
use PDOException;

class Database
{
    private $host = 'localhost';
    private $username = 'root';
    private $password = '1234w';
    private $dbname = 'cms';
    private $conn;

    public function __construct()
    {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->dbname}";
            $this->conn = new PDO($dsn, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }
    }

    public function getConnection()
    {
        return $this->conn;
    }
    //ogólne pobieranie rekordów
    public function getAll($table, $conditions = [], $fields = ['*'])
    {
        $fieldsStr = implode(", ", $fields);

        $sql = "SELECT $fieldsStr FROM $table";

        if (!empty($conditions)) {
            $sql .= " WHERE ";
            $sql .= implode(" AND ", array_map(function($key) {
                return "$key = :$key";
            }, array_keys($conditions)));
        }

        $stmt = $this->conn->prepare($sql);
        $stmt->execute($conditions);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //logowanie
    public function getUserByUsername($username)
    {
        $stmt = $this->conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        return $stmt->fetch();
    }

    //budowanie drzewka mneu
    public function getAllMenusTitle()
    {
        $stmt = $this->conn->prepare("SELECT id, parent_id, title FROM menu");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    //do wyswietlania w formularzu edycji rekordu
    public function getAllById($tablename, $id) {
        $query = "SELECT * FROM $tablename WHERE id = :id";
        $statement = $this->conn->prepare($query);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetch(PDO::FETCH_ASSOC);
    }

    //zapisywanie rekordu
    public function save($tablename, $data)
    {
    	try {
            foreach ($data as $key => $value) {
                $keys[] = $key;
                $values[] = $value;
            }
        
            $tblKeys = implode(",", $keys);        
            $keyss = ':' . implode(",:", $keys);
            
            $query = $this->conn->prepare("INSERT INTO $tablename ($tblKeys) VALUES ($keyss)");
            
            foreach ($keys as $key) {
                if (is_array($data[$key])) {
                    $query->bindValue(":$key", null, PDO::PARAM_NULL);
                } else {
                    $query->bindValue(":$key", $data[$key]);
                }
            }

            $query->execute();

            
        } catch (PDOException $e) {
            echo 'Error ',  $e->getMessage(), "\n";
        }
    }

    //edycja rekordu
    public function edit($tablename, $data, $id)
    {
        
        try {
            $sql = "UPDATE $tablename SET ";

            foreach ($data as $key => $value) {
                $sql .= "`" . $key . "` = :" . $key . ", ";
            }
    
            $pat = "+-0*/";
            $sql .= $pat;
            $sql = str_replace(", " . $pat, " ", $sql);
            $sql .= " WHERE `id` = $id";
    
            $query = $this->conn->prepare($sql);
    
            foreach ($data as $key => $value) {
                $query->bindParam(":$key", $data[$key]);
            }

            $query->execute();

            return 1;

        } catch (PDOException $e) {
			echo 'Error: ',  $e->getMessage(), "\n";
		}
    }


}



