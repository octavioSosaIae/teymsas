<?php

require_once dirname(__DIR__) . '/models/Category.php';
require_once dirname(__DIR__) . '../../core/Response.php';

class CategoryController
{
    public function create(){
        $response = new Response();

        $description = $_POST['description'] ?? null;

        try {
            if (!$description) {
                throw new Exception("Descripción es requerida");
            }

            $category = Category::create($description);

            $response->setStatusCode(201);
            $response->setBody(['id' => $category->getIdCategory(), 'message' => 'Categoría creada exitosamente']);
        } catch (Exception $e) {
            $response->setStatusCode(400);
            $response->setBody(['error' => $e->getMessage()]);
        }

        $response->send();
    }

    public function getById($id){
        $response = new Response();

        try {
            $id = intval($id[0]);
            if (!is_numeric($id)) {
                throw new Exception("ID de categoría inválido");
            }

            $category = Category::getById($id);

            $response->setStatusCode(200);
            $response->setBody([
                'idCategory' => $category->getIdCategory(),
                'description' => $category->getDescription()
            ]);
        } catch (Exception $e) {
            $response->setStatusCode(404);
            $response->setBody(['error' => $e->getMessage()]);
        }

        $response->send();
    }

    public function getAll(){
        $response = new Response();

        try {
            $categories = Category::getAll();

            $response->setStatusCode(200);
            $response->setBody($categories);
        } catch (Exception $e) {
            $response->setStatusCode(500);
            $response->setBody(['error' => $e->getMessage()]);
        }

        $response->send();
    }

    public function update($id){
        $response = new Response();
        $data = json_decode(file_get_contents('php://input'), true);
        $id = intval($id[0]);

        try {
            $category = Category::getById($id);

            $description = $data['description'] ?? $category->getDescription();

            if (!$description) {
                throw new Exception("Descripción es requerida");
            }

            $category->setDescription($description);
            $category->update();

            $response->setStatusCode(200);
            $response->setBody(['id' => $category->getIdCategory(), 'message' => 'Categoría actualizada exitosamente']);
        } catch (Exception $e) {
            $response->setStatusCode(400);
            $response->setBody(['error' => $e->getMessage()]);
        }

        $response->send();
    }

    public function delete($id){
        $response = new Response();
        $id = intval($id[0]);

        try {
            if (!is_numeric($id)) {
                throw new Exception("ID de categoría inválido");
            }

            Category::getById($id);
            Category::delete($id);

            $response->setStatusCode(200);
            $response->setBody(['message' => 'Categoría eliminada exitosamente']);
        } catch (Exception $e) {
            $response->setStatusCode(400);
            $response->setBody(['error' => $e->getMessage()]);
        }

        $response->send();
    }
}
