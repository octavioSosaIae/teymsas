<?php

require_once dirname(__DIR__) . '../../core/Database.php';

class Customer
{
    private $idUser;
    private $document;
    private $address;
    private $businessName;
    private $rut;
    private $idCity;

    public function __construct($idUser = null, $document = null, $address = null, $businessName = null, $rut = null, $idCity = null)
    {
        $this->idUser = $idUser;
        $this->document = $document;
        $this->address = $address;
        $this->businessName = $businessName;
        $this->rut = $rut;
        $this->idCity = $idCity;
    }

    public static function create($idUser, $document, $address, $businessName, $rut, $idCity)
    {
        $mysqli = Database::getInstanceDB();
        $stmt = $mysqli->prepare("INSERT INTO customers (id_user_customer, document_customer, address_customer, business_name_customer, rut_customer, id_city) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssi", $idUser, $document, $address, $businessName, $rut, $idCity);
        if ($stmt->execute()) {
            return new self($idUser, $document, $address, $businessName, $rut, $idCity);
        } else {
            throw new Exception("Error al crear el cliente: " . $stmt->error);
        }
    }

    public static function getAll()
    {
        $mysqli = Database::getInstanceDB();
        $stmt = $mysqli->prepare("SELECT * FROM customers");
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $customers = [];
            while ($row = $result->fetch_assoc()) {
                $customers[] = [
                    'idUser' => $row['id_user_customer'],
                    'document' => $row['document_customer'],
                    'address' => $row['address_customer'],
                    'businessName' => $row['business_name_customer'],
                    'rut' => $row['rut_customer'],
                    'idCity' => $row['id_city']
                ];    
            }
            return $customers;
        } else {
            throw new Exception("Error al obtener los clientes: " . $stmt->error);
        }
    }

    public static function getById($idUser)
    {
        $mysqli = Database::getInstanceDB();
        $stmt = $mysqli->prepare("SELECT * FROM customers WHERE id_user_customer = ?");
        $stmt->bind_param("i", $idUser);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                return new self($row['id_user_customer'], $row['document_customer'], $row['address_customer'], $row['business_name_customer'], $row['rut_customer'], $row['id_city']);
            } else {
                throw new Exception("Cliente no encontrado");
            }
        } else {
            throw new Exception("Error al obtener el cliente: " . $stmt->error);
        }
    }

    public function update()
    {
        $mysqli = Database::getInstanceDB();
        $stmt = $mysqli->prepare("UPDATE customers SET document_customer = ?, address_customer = ?, business_name_customer = ?, rut_customer = ?, id_city = ? WHERE id_user_customer = ?");
        $stmt->bind_param("ssssii", $this->document, $this->address, $this->businessName, $this->rut, $this->idCity, $this->idUser);
        if (!$stmt->execute()) {
            throw new Exception("Error al actualizar el cliente: " . $stmt->error);
        }
    }

    public static function delete($idUser)
    {
        $mysqli = Database::getInstanceDB();
        $stmt = $mysqli->prepare("DELETE FROM customers WHERE id_user_customer = ?");
        $stmt->bind_param("i", $idUser);
        if (!$stmt->execute()) {
            throw new Exception("Error al eliminar el cliente: " . $stmt->error);
        }
    }

    // Getters y Setters
    public function getIdUser() {
        return $this->idUser;
    }

    public function getDocument() {
        return $this->document;
    }

    public function getAddress() {
        return $this->address;
    }

    public function getBusinessName() {
        return $this->businessName;
    }

    public function getRut() {
        return $this->rut;
    }

    public function getIdCity() {
        return $this->idCity;
    }

    public function setDocument($document) {
        $this->document = $document;
    }

    public function setAddress($address) {
        $this->address = $address;
    }

    public function setBusinessName($businessName) {
        $this->businessName = $businessName;
    }

    public function setRut($rut) {
        $this->rut = $rut;
    }

    public function setIdCity($idCity) {
        $this->idCity = $idCity;
    }
}
