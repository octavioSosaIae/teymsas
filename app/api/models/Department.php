<?php

require_once dirname(__DIR__) . '../../core/Database.php';

class Department
{
    private $idDepartment;
    private $nameDepartment;

    public function __construct($idDepartment = null, $nameDepartment = null)
    {
        $this->idDepartment = $idDepartment;
        $this->nameDepartment = $nameDepartment;
    }

    public static function create($nameDepartment)
    {
        $mysqli = Database::getInstanceDB();
        $stmt = $mysqli->prepare("INSERT INTO departments (name_departament) VALUES (?)");
        $stmt->bind_param("s", $nameDepartment);
        if ($stmt->execute()) {
            return new self($stmt->insert_id, $nameDepartment);
        } else {
            throw new Exception("Error al crear el departamento: " . $stmt->error);
        }
    }

    public static function getAll()
    {
        $mysqli = Database::getInstanceDB();
        $stmt = $mysqli->prepare("SELECT * FROM departments");
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $departments = [];
            while ($row = $result->fetch_assoc()) {
                $departments[] = [
                    'idDepartment' => $row['id_departament'],
                    'nameDepartment' => $row['name_departament']
                ];    
            }
            return $departments;
        } else {
            throw new Exception("Error al obtener los departamentos: " . $stmt->error);
        }
    }

    public static function getById($idDepartment)
    {
        $mysqli = Database::getInstanceDB();
        $stmt = $mysqli->prepare("SELECT * FROM departments WHERE id_departament = ?");
        $stmt->bind_param("i", $idDepartment);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                return new self($row['id_departament'], $row['name_departament']);
            } else {
                throw new Exception("Departamento no encontrado");
            }
        } else {
            throw new Exception("Error al obtener el departamento: " . $stmt->error);
        }
    }

    public function update()
    {
        $mysqli = Database::getInstanceDB();
        $stmt = $mysqli->prepare("UPDATE departments SET name_departament = ? WHERE id_departament = ?");
        $stmt->bind_param("si", $this->nameDepartment, $this->idDepartment);
        if (!$stmt->execute()) {
            throw new Exception("Error al actualizar el departamento: " . $stmt->error);
        }
    }

    public static function delete($idDepartment)
    {
        $mysqli = Database::getInstanceDB();
        $stmt = $mysqli->prepare("DELETE FROM departments WHERE id_departament = ?");
        $stmt->bind_param("i", $idDepartment);
        if (!$stmt->execute()) {
            throw new Exception("Error al eliminar el departamento: " . $stmt->error);
        }
    }

    // Getters y Setters
    public function getIdDepartment() {
        return $this->idDepartment;
    }

    public function getNameDepartment() {
        return $this->nameDepartment;
    }

    public function setNameDepartment($nameDepartment) {
        $this->nameDepartment = $nameDepartment;
    }
}
