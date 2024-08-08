<?php

require_once dirname(__DIR__) . '../../core/Database.php';

class PaymentMethod
{
    private $idPaymentMethod;
    private $namePaymentMethod;

    public function __construct($idPaymentMethod = null, $namePaymentMethod = null)
    {
        $this->idPaymentMethod = $idPaymentMethod;
        $this->namePaymentMethod = $namePaymentMethod;
    }

    public static function create($namePaymentMethod)
    {
        $mysqli = Database::getInstanceDB();
        $stmt = $mysqli->prepare("INSERT INTO payment_methods (name_payment_method) VALUES (?)");
        $stmt->bind_param("s", $namePaymentMethod);
        if ($stmt->execute()) {
            return new self($stmt->insert_id, $namePaymentMethod);
        } else {
            throw new Exception("Error al crear el método de pago: " . $stmt->error);
        }
    }

    public static function getAll()
    {
        $mysqli = Database::getInstanceDB();
        $stmt = $mysqli->prepare("SELECT * FROM payment_methods");
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $methods = [];
            while ($row = $result->fetch_assoc()) {
                $methods[] = [
                    'idPaymentMethod' =>$row['id_payment_method'], 
                    'namePaymentMethod' => $row['name_payment_method']
                ];
            }
            return $methods;
        } else {
            throw new Exception("Error al obtener los métodos de pago: " . $stmt->error);
        }
    }

    public static function getById($idPaymentMethod)
    {
        $mysqli = Database::getInstanceDB();
        $stmt = $mysqli->prepare("SELECT * FROM payment_methods WHERE id_payment_method = ?");
        $stmt->bind_param("i", $idPaymentMethod);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                return new self($row['id_payment_method'], $row['name_payment_method']);
            } else {
                throw new Exception("Método de pago no encontrado");
            }
        } else {
            throw new Exception("Error al obtener el método de pago: " . $stmt->error);
        }
    }

    public function update()
    {
        $mysqli = Database::getInstanceDB();
        $stmt = $mysqli->prepare("UPDATE payment_methods SET name_payment_method = ? WHERE id_payment_method = ?");
        $stmt->bind_param("si", $this->namePaymentMethod, $this->idPaymentMethod);
        if (!$stmt->execute()) {
            throw new Exception("Error al actualizar el método de pago: " . $stmt->error);
        }
    }

    public static function delete($idPaymentMethod)
    {
        $mysqli = Database::getInstanceDB();
        $stmt = $mysqli->prepare("DELETE FROM payment_methods WHERE id_payment_method = ?");
        $stmt->bind_param("i", $idPaymentMethod);
        if (!$stmt->execute()) {
            throw new Exception("Error al eliminar el método de pago: " . $stmt->error);
        }
    }

    // Getters y Setters
    public function getIdPaymentMethod()
    {
        return $this->idPaymentMethod;
    }

    public function getNamePaymentMethod()
    {
        return $this->namePaymentMethod;
    }

    public function setNamePaymentMethod($namePaymentMethod)
    {
        $this->namePaymentMethod = $namePaymentMethod;
    }
}
