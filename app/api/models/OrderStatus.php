<?php

require_once dirname(__DIR__) . '../../core/Database.php';

class OrderStatus
{
    private $idOrderStatus;
    private $descriptionStatus;

    public function __construct($idOrderStatus = null, $descriptionStatus = null)
    {
        $this->idOrderStatus = $idOrderStatus;
        $this->descriptionStatus = $descriptionStatus;
    }

    public static function create($descriptionStatus)
    {
        $mysqli = Database::getInstanceDB();
        $stmt = $mysqli->prepare("INSERT INTO order_status (description_status) VALUES (?)");
        $stmt->bind_param("s", $descriptionStatus);
        if ($stmt->execute()) {
            return new self($stmt->insert_id, $descriptionStatus);
        } else {
            throw new Exception("Error al crear el estado del pedido: " . $stmt->error);
        }
    }

    public static function getAll()
    {
        $mysqli = Database::getInstanceDB();
        $stmt = $mysqli->prepare("SELECT * FROM order_status");
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $statuses = [];
            while ($row = $result->fetch_assoc()) {
                $statuses[] = [
                    'idOrderStatus' => $row['id_order_status'],
                    'descriptionStatus' => $row['description_status']
                ];
            }
            return $statuses;
        } else {
            throw new Exception("Error al obtener los estados del pedido: " . $stmt->error);
        }
    }

    public static function getById($idOrderStatus)
    {
        $mysqli = Database::getInstanceDB();
        $stmt = $mysqli->prepare("SELECT * FROM order_status WHERE id_order_status = ?");
        $stmt->bind_param("i", $idOrderStatus);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                return new self($row['id_order_status'], $row['description_status']);
            } else {
                throw new Exception("Estado del pedido no encontrado");
            }
        } else {
            throw new Exception("Error al obtener el estado del pedido: " . $stmt->error);
        }
    }

    public function update()
    {
        $mysqli = Database::getInstanceDB();
        $stmt = $mysqli->prepare("UPDATE order_status SET description_status = ? WHERE id_order_status = ?");
        $stmt->bind_param("si", $this->descriptionStatus, $this->idOrderStatus);
        if (!$stmt->execute()) {
            throw new Exception("Error al actualizar el estado del pedido: " . $stmt->error);
        }
    }

    public static function delete($idOrderStatus)
    {
        $mysqli = Database::getInstanceDB();
        $stmt = $mysqli->prepare("DELETE FROM order_status WHERE id_order_status = ?");
        $stmt->bind_param("i", $idOrderStatus);
        if (!$stmt->execute()) {
            throw new Exception("Error al eliminar el estado del pedido: " . $stmt->error);
        }
    }

    // Getters y Setters
    public function getIdOrderStatus()
    {
        return $this->idOrderStatus;
    }

    public function getDescriptionStatus()
    {
        return $this->descriptionStatus;
    }

    public function setDescriptionStatus($descriptionStatus)
    {
        $this->descriptionStatus = $descriptionStatus;
    }
}