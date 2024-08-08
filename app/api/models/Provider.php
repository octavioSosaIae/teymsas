<?php

require_once dirname(__DIR__) . '../../core/Database.php';

class Provider
{
    private $idProvider;
    private $name;

    public function __construct($idProvider = null, $name = null)
    {
        $this->idProvider = $idProvider;
        $this->name = $name;
    }

    public static function create($name)
    {
        $mysqli = Database::getInstanceDB();
        $stmt = $mysqli->prepare("INSERT INTO providers (name_provider) VALUES (?)");
        $stmt->bind_param("s", $name);
        if ($stmt->execute()) {
            return new self($stmt->insert_id, $name);
        } else {
            throw new Exception("Error al crear el proveedor: " . $stmt->error);
        }
    }

    public static function getById($idProvider)
    {
        $mysqli = Database::getInstanceDB();
        $stmt = $mysqli->prepare("SELECT * FROM providers WHERE id_provider = ?");
        $stmt->bind_param("i", $idProvider);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                return new self($row['id_provider'], $row['name_provider']);
            } else {
                throw new Exception("Proveedor no encontrado");
            }
        } else {
            throw new Exception("Error al obtener el proveedor: " . $stmt->error);
        }
    }

    public static function getAll()
    {
        $mysqli = Database::getInstanceDB();
        $stmt = $mysqli->prepare("SELECT * FROM providers");
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $providers = [];
            while ($row = $result->fetch_assoc()) {
                $providers[] = [
                    'idProvider' => $row['id_provider'],
                    'name' => $row['name_provider']
                ];    
            }
            return $providers;
        } else {
            throw new Exception("Error al obtener los proveedores: " . $stmt->error);
        }
    }

    public function update()
    {
        $mysqli = Database::getInstanceDB();
        $stmt = $mysqli->prepare("UPDATE providers SET name_provider = ? WHERE id_provider = ?");
        $stmt->bind_param("si", $this->name, $this->idProvider);
        if (!$stmt->execute()) {
            throw new Exception("Error al actualizar el proveedor: " . $stmt->error);
        }
    }

    public static function delete($idProvider)
    {
        $mysqli = Database::getInstanceDB();
        $stmt = $mysqli->prepare("DELETE FROM providers WHERE id_provider = ?");
        $stmt->bind_param("i", $idProvider);
        if (!$stmt->execute()) {
            throw new Exception("Error al eliminar el proveedor: " . $stmt->error);
        }
    }

    // Getters y Setters
    public function getIdProvider() {
        return $this->idProvider;
    }

    public function getName() {
        return $this->name;
    }

    public function setName($name) {
        $this->name = $name;
    }
}
