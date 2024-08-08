<?php

require_once dirname(__DIR__) . '/config/config.php';

class Database
{
    // Objeto de conexión MySQLi
    private $mysqli;

    // Instancia única de la clase Database (uso de patrón Singleton)
    private static $instanceDB;



    // Método para establecer la conexión a la base de datos
    public function connect()
    {
        // Crear una nueva conexión MySQLi
        $this->mysqli = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME, DB_PORT);
        // Verificar si hay errores de conexión
        if ($this->mysqli->connect_error) {
            // Lanzar una excepción en caso de error
            throw new Exception('Error de conexión: ' . $this->mysqli->connect_error);
        }
    }

    // Método estático para obtener la instancia única de la clase Database
    public static function getInstanceDB()
    {
        // Verificar si la instancia no existe
        if (!self::$instanceDB) {
            // Crear una nueva instancia de Database
            self::$instanceDB = new Database();
            // Establecer la conexión llamando al método connect()
            self::$instanceDB->connect();
        }

        // Devolver el objeto mysqli para su uso
        return self::$instanceDB->mysqli;
    }

    // Método para prevenir la clonación de la instancia del objeto $instanceDB
    public function __clone()
    {
        // Vacío para prevenir la clonación
    }

    // Método para prevenir la deserialización de la instancia del objeto $instanceDB
    public function __wakeup()
    {
        // Vacío para prevenir la deserialización
    }
}