<?php

require_once dirname(__DIR__) . '/models/Provider.php';
require_once dirname(__DIR__) . '../../core/Response.php';

class ProviderController
{
    public function create(){
        $response = new Response();

        $name = $_POST['name'] ?? null;

        try {
            if (!$name) {
                throw new Exception("Nombre es requerido");
            }

            $provider = Provider::create($name);

            $response->setStatusCode(201);
            $response->setBody(['id' => $provider->getIdProvider(), 'message' => 'Proveedor creado exitosamente']);
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
                throw new Exception("ID de proveedor inválido");
            }

            $provider = Provider::getById($id);

            $response->setStatusCode(200);
            $response->setBody([
                'idProvider' => $provider->getIdProvider(),
                'name' => $provider->getName()
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
            $providers = Provider::getAll();

            $response->setStatusCode(200);
            $response->setBody($providers);
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
            $provider = Provider::getById($id);

            $name = $data['name'] ?? $provider->getName();

            if (!$name) {
                throw new Exception("Nombre es requerido");
            }

            $provider->setName($name);
            $provider->update();

            $response->setStatusCode(200);
            $response->setBody(['id' => $provider->getIdProvider(), 'message' => 'Proveedor actualizado exitosamente']);
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
                throw new Exception("ID de proveedor inválido");
            }

            Provider::getById($id);
            Provider::delete($id);

            $response->setStatusCode(200);
            $response->setBody(['message' => 'Proveedor eliminado exitosamente']);
        } catch (Exception $e) {
            $response->setStatusCode(400);
            $response->setBody(['error' => $e->getMessage()]);
        }

        $response->send();
    }
}
