<?php

require_once dirname(__DIR__) . '/models/OrderStatus.php';
require_once dirname(__DIR__) . '../../core/Response.php';

class OrderStatusController
{
    // Método para crear un nuevo estado de pedido
    public function create()
    {
        $response = new Response();
        $descriptionStatus = $_POST['description_status'] ?? null;

        try {
            if (!$descriptionStatus) {
                throw new Exception("La descripción del estado del pedido es necesaria");
            }

            $orderStatus = OrderStatus::create($descriptionStatus);
            $response->setStatusCode(201);
            $response->setBody(['id' => $orderStatus->getIdOrderStatus(), 'message' => 'Estado de pedido creado exitosamente']);
        } catch (Exception $e) {
            $response->setStatusCode(400);
            $response->setBody(['error' => $e->getMessage()]);
        }

        $response->send();
    }

    // Método para obtener todos los estados de pedido
    public function getAll()
    {
        $response = new Response();

        try {
            $orderStatuses = OrderStatus::getAll();
            $response->setStatusCode(200);
            $response->setBody($orderStatuses);
        } catch (Exception $e) {
            $response->setStatusCode(500);
            $response->setBody(['error' => $e->getMessage()]);
        }

        $response->send();
    }

    // Método para obtener un estado de pedido por ID
    public function getById($id)
    {
        $response = new Response();
        $id = intval($id[0]);

        try {
            if (!is_numeric($id)) {
                throw new Exception("ID de estado del pedido inválido");
            }

            $orderStatus = OrderStatus::getById($id);
            $response->setStatusCode(200);
            $response->setBody([
                'idOrderStatus' => $orderStatus->getIdOrderStatus(),
                'descriptionStatus' => $orderStatus->getDescriptionStatus()
            ]);
        } catch (Exception $e) {
            $response->setStatusCode(404);
            $response->setBody(['error' => $e->getMessage()]);
        }

        $response->send();
    }

    // Método para actualizar un estado de pedido
    public function update($id)
    {
        $response = new Response();
        $data = json_decode(file_get_contents('php://input'), true);
        $id = intval($id[0]);

        try {
            $orderStatus = OrderStatus::getById($id);
            $descriptionStatus = $data['description_status'] ?? $orderStatus->getDescriptionStatus();

            if (!$descriptionStatus) {
                throw new Exception("La descripción del estado del pedido es necesaria");
            }

            $orderStatus->setDescriptionStatus($descriptionStatus);
            $orderStatus->update();

            $response->setStatusCode(200);
            $response->setBody(['id' => $orderStatus->getIdOrderStatus(), 'message' => 'Estado del pedido actualizado exitosamente']);
        } catch (Exception $e) {
            $response->setStatusCode(400);
            $response->setBody(['error' => $e->getMessage()]);
        }

        $response->send();
    }

    // Método para eliminar un estado de pedido
    public function delete($id)
    {
        $response = new Response();
        $id = intval($id[0]);

        try {
            if (!is_numeric($id)) {
                throw new Exception("ID de estado del pedido inválido");
            }

            OrderStatus::getById($id);
            OrderStatus::delete($id);
            $response->setStatusCode(200);
            $response->setBody(['message' => 'Estado del pedido eliminado exitosamente']);
        } catch (Exception $e) {
            $response->setStatusCode(400);
            $response->setBody(['error' => $e->getMessage()]);
        }

        $response->send();
    }
}
