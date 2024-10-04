<?php

require_once dirname(__DIR__) . '/models/City.php';
require_once dirname(__DIR__) . '../../core/Response.php';

class CityController
{
    // Método para crear una nueva ciudad
    public function create(){
        $response = new Response();
        $nameCity = $_POST['name_city'] ?? null;
        $idDepartment = $_POST['id_department'] ?? null;

        try {
            if (!$nameCity || !$idDepartment) {
                throw new Exception("El nombre de la ciudad y el ID del departamento son necesarios");
            }

            $city = City::create($nameCity, $idDepartment);

            $response->setStatusCode(201);
            $response->setBody(['id' => $city->getIdCity(), 'message' => 'Ciudad creada exitosamente']);
        } catch (Exception $e) {
            $response->setStatusCode(400);
            $response->setBody(['error' => $e->getMessage()]);
        }

        $response->send();
    }

    // Método para obtener todas las ciudades
    public function getAll(){
        $response = new Response();

        try {
            $cities = City::getAll();

            $response->setStatusCode(200);
            $response->setBody($cities);
        } catch (Exception $e) {
            $response->setStatusCode(500);
            $response->setBody(['error' => $e->getMessage()]);
        }

        $response->send();
    }

    // Método para obtener una ciudad por ID
    public function getById($id){
        $response = new Response();
        $id = intval($id[0]);

        try {
            if (!is_numeric($id)) {
                throw new Exception("ID de ciudad inválido");
            }

            $city = City::getById($id);

            $response->setStatusCode(200);
            $response->setBody([
                'idCity' => $city->getIdCity(),
                'nameCity' => $city->getNameCity(),
                'idDepartment' => $city->getIdDepartment()
            ]);
        } catch (Exception $e) {
            $response->setStatusCode(404);
            $response->setBody(['error' => $e->getMessage()]);
        }

        $response->send();
    }

    // Método para actualizar una ciudad
    public function update($id){
        $response = new Response();
        $data = json_decode(file_get_contents('php://input'), true);
        $id = intval($id[0]);

        try {
            if (!is_numeric($id)) {
                throw new Exception("ID de ciudad inválido");
            }

            $city = City::getById($id);
            $city->setNameCity($data['name_city'] ?? $city->getNameCity());
            $city->setIdDepartment($data['id_department'] ?? $city->getIdDepartment());

            $city->update();

            $response->setStatusCode(200);
            $response->setBody(['message' => 'Ciudad actualizada exitosamente']);
        } catch (Exception $e) {
            $response->setStatusCode(400);
            $response->setBody(['error' => $e->getMessage()]);
        }

        $response->send();
    }

    // Método para eliminar una ciudad
    public function delete($id){
        $response = new Response();
        $id = intval($id[0]);

        try {
            if (!is_numeric($id)) {
                throw new Exception("ID de ciudad inválido");
            }

            City::getById($id);
            City::delete($id);

            $response->setStatusCode(200);
            $response->setBody(['message' => 'Ciudad eliminada exitosamente']);
        } catch (Exception $e) {
            $response->setStatusCode(400);
            $response->setBody(['error' => $e->getMessage()]);
        }

        $response->send();
    }
}
