<?php

namespace App\Models;

use PDO;
use PDOException;
use Exception;

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
    
    //ogolne pobieranie rekordów z warunkami
    public function find($table, $conditions = [], $fields = null, $singleRecord = false)
    {
        if (is_null($fields)) {
            $fieldsStr = '*';
        } else {
            $fieldsStr = implode(", ", $fields);
        }

        $sql = "SELECT $fieldsStr FROM $table";
        
        if (!empty($conditions)) {
            $sql .= " WHERE ";
            $sql .= implode(" AND ", array_map(function($key) {
                return "$key = :$key";
            }, array_keys($conditions)));
        }

        try {
            $stmt = $this->conn->prepare($sql);
            $stmt->execute($conditions);
            
            if ($singleRecord) {
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (PDOException $e) {
            echo 'Error: ' . $e->getMessage();
            return false;
        }
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

    //usuwanie wiersza
    public function delete($table, $conditions)
    {
        $sql = "DELETE FROM $table WHERE ";
        $sql .= implode(" AND ", array_map(function($key) {
            return "$key = :$key";
        }, array_keys($conditions)));

        $stmt = $this->conn->prepare($sql);
        $params = [];
        foreach ($conditions as $key => $value) {
            if (is_array($value)) {
                throw new Exception("Invalid value for $key: arrays are not allowed");
            }
            $params[":$key"] = $value;
        }

        $stmt->execute($params);
    }

    //oblicza liczbę wierszy i zwraca liczbę
    public function countBodyRecordsLinkedToMenu($menuId)
    {
        $sql = "SELECT COUNT(*) as count FROM body WHERE parent_id = :parent_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute(['parent_id' => $menuId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['count'];
    }
    
    //po usunieciu rekordu ustawia parent_id na 0 dla dzieci
    public function resetChildrenParentId($table, $parentId)
    {
        $sql = "UPDATE $table SET parent_id = 0 WHERE parent_id = :parent_id";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':parent_id', $parentId);
        $stmt->execute();
    }

}



