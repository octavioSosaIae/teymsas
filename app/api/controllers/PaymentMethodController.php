<?php

require_once dirname(__DIR__) . '/models/PaymentMethod.php';
require_once dirname(__DIR__) . '../../core/Response.php';

class PaymentMethodController
{
    // Método para crear un nuevo método de pago
    public function create()
    {
        $response = new Response();
        $namePaymentMethod = $_POST['name_payment_method'] ?? null;

        try {
            if (!$namePaymentMethod) {
                throw new Exception("El nombre del método de pago es necesario");
            }

            $paymentMethod = PaymentMethod::create($namePaymentMethod);
            $response->setStatusCode(201);
            $response->setBody(['id' => $paymentMethod->getIdPaymentMethod(), 'message' => 'Método de pago creado exitosamente']);
        } catch (Exception $e) {
            $response->setStatusCode(400);
            $response->setBody(['error' => $e->getMessage()]);
        }

        $response->send();
    }

    // Método para obtener todos los métodos de pago
    public function getAll()
    {
        $response = new Response();

        try {
            $paymentMethods = PaymentMethod::getAll();
            $response->setStatusCode(200);
            $response->setBody($paymentMethods);
        } catch (Exception $e) {
            $response->setStatusCode(500);
            $response->setBody(['error' => $e->getMessage()]);
        }

        $response->send();
    }

    // Método para obtener un método de pago por ID
    public function getById($id)
    {
        $response = new Response();
        $id = intval($id[0]);

        try {
            if (!is_numeric($id)) {
                throw new Exception("ID de método de pago inválido");
            }

            $paymentMethod = PaymentMethod::getById($id);
            $response->setStatusCode(200);
            $response->setBody([
                'idPaymentMethod' => $paymentMethod->getIdPaymentMethod(),
                'namePaymentMethod' => $paymentMethod->getNamePaymentMethod()
            ]);
        } catch (Exception $e) {
            $response->setStatusCode(404);
            $response->setBody(['error' => $e->getMessage()]);
        }

        $response->send();
    }

    // Método para actualizar un método de pago
    public function update($id)
    {
        $response = new Response();
        $data = json_decode(file_get_contents('php://input'), true);
        $id = intval($id[0]);

        try {
            $paymentMethod = PaymentMethod::getById($id);
            $namePaymentMethod = $data['name_payment_method'] ?? $paymentMethod->getNamePaymentMethod();

            if (!$namePaymentMethod) {
                throw new Exception("El nombre del método de pago es necesario");
            }

            $paymentMethod->setNamePaymentMethod($namePaymentMethod);
            $paymentMethod->update();

            $response->setStatusCode(200);
            $response->setBody(['id' => $paymentMethod->getIdPaymentMethod(), 'message' => 'Método de pago actualizado exitosamente']);
        } catch (Exception $e) {
            $response->setStatusCode(400);
            $response->setBody(['error' => $e->getMessage()]);
        }

        $response->send();
    }

    // Método para eliminar un método de pago
    public function delete($id)
    {
        $response = new Response();
        $id = intval($id[0]);

        try {
            if (!is_numeric($id)) {
                throw new Exception("ID de método de pago inválido");
            }

            PaymentMethod::getById($id);
            PaymentMethod::delete($id);
            $response->setStatusCode(200);
            $response->setBody(['message' => 'Método de pago eliminado exitosamente']);
        } catch (Exception $e) {
            $response->setStatusCode(400);
            $response->setBody(['error' => $e->getMessage()]);
        }

        $response->send();
    }
}
?>
