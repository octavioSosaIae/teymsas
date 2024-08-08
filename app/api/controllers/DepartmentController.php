<?php

require_once dirname(__DIR__) . '/models/Department.php';
require_once dirname(__DIR__) . '../../core/Response.php';

class DepartmentController
{
    // Método para crear un nuevo departamento
    public function create()
    {
        $response = new Response();
        $nameDepartment = $_POST['name_departament'] ?? null;

        try {
            if (!$nameDepartment) {
                throw new Exception("El nombre del departamento es necesario");
            }

            $department = Department::create($nameDepartment);

            $response->setStatusCode(201);
            $response->setBody(['id' => $department->getIdDepartment(), 'message' => 'Departamento creado exitosamente']);
        } catch (Exception $e) {
            $response->setStatusCode(400);
            $response->setBody(['error' => $e->getMessage()]);
        }

        $response->send();
    }

    // Método para obtener todos los departamentos
    public function getAll()
    {
        $response = new Response();

        try {
            $departments = Department::getAll();

            $response->setStatusCode(200);
            $response->setBody($departments);
        } catch (Exception $e) {
            $response->setStatusCode(500);
            $response->setBody(['error' => $e->getMessage()]);
        }

        $response->send();
    }

    // Método para obtener un departamento por ID
    public function getById($id)
    {
        $response = new Response();
        $id = intval($id[0]);

        try {
            if (!is_numeric($id)) {
                throw new Exception("ID de departamento inválido");
            }

            $department = Department::getById($id);

            $response->setStatusCode(200);
            $response->setBody([
                'idDepartment' => $department->getIdDepartment(),
                'nameDepartment' => $department->getNameDepartment()
            ]);
        } catch (Exception $e) {
            $response->setStatusCode(404);
            $response->setBody(['error' => $e->getMessage()]);
        }

        $response->send();
    }

    // Método para actualizar un departamento
    public function update($id)
    {
        $response = new Response();
        $data = json_decode(file_get_contents('php://input'), true);
        $id = intval($id[0]);

        try {
            if (!is_numeric($id)) {
                throw new Exception("ID de departamento inválido");
            }

            $department = Department::getById($id);
            $department->setNameDepartment($data['name_departament'] ?? $department->getNameDepartment());

            $department->update();

            $response->setStatusCode(200);
            $response->setBody(['message' => 'Departamento actualizado exitosamente']);
        } catch (Exception $e) {
            $response->setStatusCode(400);
            $response->setBody(['error' => $e->getMessage()]);
        }

        $response->send();
    }

    // Método para eliminar un departamento
    public function delete($id)
    {
        $response = new Response();
        $id = intval($id[0]);

        try {
            if (!is_numeric($id)) {
                throw new Exception("ID de departamento inválido");
            }

            // Obtener el cliente por ID
            Department::getById($id);
            Department::delete($id);

            $response->setStatusCode(200);
            $response->setBody(['message' => 'Departamento eliminado exitosamente']);
        } catch (Exception $e) {
            $response->setStatusCode(400);
            $response->setBody(['Ha ocurrido un error' => $e->getMessage()]);
        }

        $response->send();
    }

}
