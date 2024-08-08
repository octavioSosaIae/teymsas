<?php

require_once dirname(__DIR__) . '../../core/Database.php';

class City
{
    private $idCity;
    private $nameCity;
    private $idDepartment;

    public function __construct($idCity = null, $nameCity = null, $idDepartment = null)
    {
        $this->idCity = $idCity;
        $this->nameCity = $nameCity;
        $this->idDepartment = $idDepartment;
    }

    public static function create($nameCity, $idDepartment)
    {
        $mysqli = Database::getInstanceDB();
        $stmt = $mysqli->prepare("INSERT INTO cities (name_city, id_departament) VALUES (?, ?)");
        $stmt->bind_param("si", $nameCity, $idDepartment);
        if ($stmt->execute()) {
            return new self($stmt->insert_id, $nameCity, $idDepartment);
        } else {
            throw new Exception("Error al crear la ciudad: " . $stmt->error);
        }
    }

    public static function getAll()
    {
        $mysqli = Database::getInstanceDB();
        $stmt = $mysqli->prepare("SELECT * FROM cities");
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $cities = [];
            while ($row = $result->fetch_assoc()) {
                $cities[] = [
                    'idCity' => $row['id_city'],
                    'nameCity' => $row['name_city'],
                    'idDepartment' => $row['id_departament']
                ];    
            }
            return $cities;
        } else {
            throw new Exception("Error al obtener las ciudades: " . $stmt->error);
        }
    }

    public static function getById($idCity)
    {
        $mysqli = Database::getInstanceDB();
        $stmt = $mysqli->prepare("SELECT * FROM cities WHERE id_city = ?");
        $stmt->bind_param("i", $idCity);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                return new self($row['id_city'], $row['name_city'], $row['id_departament']);
            } else {
                throw new Exception("Ciudad no encontrada");
            }
        } else {
            throw new Exception("Error al obtener la ciudad: " . $stmt->error);
        }
    }

    public function update()
    {
        $mysqli = Database::getInstanceDB();
        $stmt = $mysqli->prepare("UPDATE cities SET name_city = ?, id_departament = ? WHERE id_city = ?");
        $stmt->bind_param("sii", $this->nameCity, $this->idDepartment, $this->idCity);
        if (!$stmt->execute()) {
            throw new Exception("Error al actualizar la ciudad: " . $stmt->error);
        }
    }

    public static function delete($idCity)
    {
        $mysqli = Database::getInstanceDB();
        $stmt = $mysqli->prepare("DELETE FROM cities WHERE id_city = ?");
        $stmt->bind_param("i", $idCity);
        if (!$stmt->execute()) {
            throw new Exception("Error al eliminar la ciudad: " . $stmt->error);
        }
    }

    // Getters y Setters
    public function getIdCity() {
        return $this->idCity;
    }

    public function getNameCity() {
        return $this->nameCity;
    }

    public function getIdDepartment() {
        return $this->idDepartment;
    }

    public function setNameCity($nameCity) {
        $this->nameCity = $nameCity;
    }

    public function setIdDepartment($idDepartment) {
        $this->idDepartment = $idDepartment;
    }
}
