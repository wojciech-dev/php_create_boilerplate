<?php

namespace App\Models;

use PDO;
use PDOException;
use Exception;
use App\Helpers\Functions;

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

    // Funkcja do obsługi błędów PDO
    private function handlePDOException(PDOException $e) {
        error_log('PDOException: ' . $e->getMessage());
        return false;
    }

    /**metody do tworzenia nowej nbazy danych */

    public function createDatabase()
    {
        try {
            $sql = "CREATE DATABASE IF NOT EXISTS `{$this->dbname}` CHARACTER SET utf8mb4 COLLATE utf8mb4_0900_ai_ci";
            $this->conn->exec($sql);
        } catch (PDOException $e) {
            echo 'Error creating database: ' . $e->getMessage();
        }
    }

    public function useDatabase()
    {
        try {
            $this->conn->exec("USE `{$this->dbname}`");
        } catch (PDOException $e) {
            echo 'Error selecting database: ' . $e->getMessage();
        }
    }

    public function getConnection()
    {
        return $this->conn;
    }

    public function exec($sql)
    {
        return $this->conn->exec($sql);
    }

    public function prepare($sql)
    {
        return $this->conn->prepare($sql);
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

    //oblicza liczbę wierszy i zwraca liczbę. Do okreslania ile jest rekordów w danej kategotii
    public function countRecordsLinkedToMenu($menuId, $table)
    {
        $sql = "SELECT COUNT(*) as count FROM $table WHERE parent_id = :parent_id";
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

    public function find($table, $conditions = [], $fields = ['*'], $single = false, $orderBy = null)
    {
        try {
            if (!is_array($fields)) {
                $fields = ['*'];
            }
    
            $fieldsStr = implode(", ", $fields);
            $sql = "SELECT $fieldsStr FROM $table";
    
            if (!empty($conditions)) {
                $sql .= " WHERE ";
                $sql .= implode(" AND ", array_map(function($key) {
                    if (strpos($key, ' ') !== false) {
                        return "$key :$key";
                    }
                    return "$key = :$key";
                }, array_keys($conditions)));
            }
    
            if ($orderBy) {
                $sql .= " ORDER BY $orderBy";
            }
    
            $stmt = $this->conn->prepare($sql);
            foreach ($conditions as $key => $value) {
                if (strpos($key, ' ') !== false) {
                    $stmt->bindValue(":$key", $value, PDO::PARAM_INT);
                } else {
                    $stmt->bindValue(":$key", $value);
                }
            }
            $stmt->execute();
    
            if ($single) {
                return $stmt->fetch(PDO::FETCH_ASSOC);
            } else {
                return $stmt->fetchAll(PDO::FETCH_ASSOC);
            }
        } catch (PDOException $e) {
            echo "Error executing query: " . $e->getMessage();
            return false;
        }
    }

    //update posortowanych rekordów(drag&drop) tabeli, coś innego niż 'edit'
    public function update($table, $data, $conditions) {
        $dataStr = implode(", ", array_map(function($key) {
            return "$key = :$key";
        }, array_keys($data)));

        $conditionStr = implode(" AND ", array_map(function($key) {
            return "$key = :cond_$key";
        }, array_keys($conditions)));

        $sql = "UPDATE $table SET $dataStr WHERE $conditionStr";

        $stmt = $this->conn->prepare($sql);
        foreach ($data as $key => $value) {
            $stmt->bindValue(":$key", $value);
        }
        foreach ($conditions as $key => $value) {
            $stmt->bindValue(":cond_$key", $value);
        }

        return $stmt->execute();
    }

    //do pobierania rekordów poprzedzajacych i następnych względem przesuwanego. Dotyczy sortowania.
    public function getItemsBySortingDirection($table, $sorting, $direction) {
        if ($direction === 'above') {
            $operator = '<';
            $order = 'DESC';
        } elseif ($direction === 'below') {
            $operator = '>';
            $order = 'ASC';
        } else {
            throw new Exception('Invalid direction. Must be "above" or "below".');
        }
    
        $sql = "SELECT id, sorting FROM $table WHERE sorting $operator :sorting ORDER BY sorting $order LIMIT 1";
        $stmt = $this->conn->prepare($sql);
        $stmt->bindParam(':sorting', $sorting, PDO::PARAM_INT);
        $stmt->execute();
    
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    //odpowienik autoincrement dla wiersza 'sorting'. Do sortowania.
    public function getNextSortingValue($table) {
        $sql = "SELECT MAX(sorting) AS max_sorting FROM $table";
        $stmt = $this->conn->query($sql);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        return ($result['max_sorting'] !== null) ? $result['max_sorting'] + 1 : 1;
    }




    
}



