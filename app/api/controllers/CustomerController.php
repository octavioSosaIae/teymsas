<?php

require_once dirname(__DIR__) . '/models/Customer.php';
require_once dirname(__DIR__) . '/models/User.php';
require_once dirname(__DIR__) . '../../core/Response.php';

class CustomerController
{
    // Método para crear un nuevo cliente
    public function create(){
        $response = new Response();

        // Obtener los datos del cuerpo de la solicitud
        $document = $_POST['document'] ?? null;
        $address = $_POST['address'] ?? null;
        $businessName = $_POST['business_name'] ?? null;
        $rut = $_POST['rut'] ?? null;
        $idCity = $_POST['id_city'] ?? null;
        $completeName = $_POST['complete_name'] ?? null;
        $email = $_POST['email'] ?? null;
        $password = $_POST['password'] ?? null;
        $phone = $_POST['phone'] ?? null;

        try {
            // Validar datos
            if (!$document || !$address || !$idCity || !$completeName || !$email || !$password || !$phone) {
                throw new Exception("Todos los campos son necesarios");
            }

            // Crear el usuario
            $user = User::create($completeName, $email, $password, $phone, 'C');
            
            // Crear el cliente
            $customer = Customer::create($user->getId(), $document, $address, $businessName, $rut, $idCity);

            // Responder con el cliente creado
            $response->setStatusCode(201);
            $response->setBody(['id' => $customer->getIdUser(), 'message' => 'Cliente creado exitosamente']);
        } catch (Exception $e) {
            // Responder con un error
            $response->setStatusCode(400); // Código de estado para solicitud incorrecta
            $response->setBody(['error' => $e->getMessage()]);
        }

        // Enviar la respuesta
        $response->send();
    }

    // Método para obtener un cliente por ID
    public function getById($id){
        $response = new Response();

        try {
            $id = intval($id[0]);
            // Validar el ID
            if (!is_numeric($id)) {
                throw new Exception("ID de cliente inválido");
            }

            // Obtener el cliente por ID
            $customer = Customer::getById($id);

            // Responder con los datos del cliente
            $response->setStatusCode(200);
            $response->setBody([
                'idUser' => $customer->getIdUser(),
                'document' => $customer->getDocument(),
                'address' => $customer->getAddress(),
                'businessName' => $customer->getBusinessName(),
                'rut' => $customer->getRut(),
                'idCity' => $customer->getIdCity()
            ]);
        } catch (Exception $e) {
            // Responder con un error
            $response->setStatusCode(404); // Código de estado para no encontrado
            $response->setBody(['error' => $e->getMessage()]);
        }

        // Enviar la respuesta
        $response->send();
    }

        // Método para obtener todos los clientes
        public function getAll(){
            $response = new Response();
    
            try {
                // Obtener todos los usuarios
                $users = Customer::getAll();
    
                // Responder con los datos de los usuarios
                $response->setStatusCode(200);
                $response->setBody($users);
            } catch (Exception $e) {
                // Responder con un error
                $response->setStatusCode(500); // Código de estado para error interno del servidor
                $response->setBody(['error' => $e->getMessage()]);
            }
    
            // Enviar la respuesta
            $response->send();
        }

    // Método para actualizar un cliente
    public function update($id){
        $response = new Response();
        $data = json_decode(file_get_contents('php://input'), true);
        $id = intval($id[0]);
        
        try {
            $customer = Customer::getById($id);
            
            // Obtener los datos del cuerpo de la solicitud
            $document = $data['document'] ?? $customer->getDocument();
            $address = $data['address'] ?? $customer->getAddress();
            $businessName = $data['business_name'] ?? $customer->getBusinessName();
            $rut = $data['rut'] ?? $customer->getRut();
            $idCity = $data['id_city'] ?? $customer->getIdCity();

            // Validar datos
            if (!$document || !$address || !$idCity) {
                throw new Exception("Todos los campos son necesarios");
            }

            // Actualizar los datos del cliente
            $customer->setDocument($document);
            $customer->setAddress($address);
            $customer->setBusinessName($businessName);
            $customer->setRut($rut);
            $customer->setIdCity($idCity);

            // Guardar los cambios
            $customer->update();

            // Responder con el cliente actualizado
            $response->setStatusCode(200);
            $response->setBody(['id' => $customer->getIdUser(), 'message' => 'Cliente actualizado exitosamente']);
        } catch (Exception $e) {
            // Responder con un error
            $response->setStatusCode(400); // Código de estado para solicitud incorrecta
            $response->setBody(['error' => $e->getMessage()]);
        }

        // Enviar la respuesta
        $response->send();
    }

    // Método para eliminar un cliente
    public function delete($id){
        $response = new Response();
        $id = intval($id[0]);

        try {
            // Validar el ID
            if (!is_numeric($id)) {
                throw new Exception("ID de cliente inválido");
            }

            // Obtener el cliente por ID
            Customer::getById($id);

            // Eliminar el cliente
            Customer::delete($id);

            // Eliminar el usuario relacionado
            User::delete($id);

            // Responder con un mensaje de éxito
            $response->setStatusCode(200);
            $response->setBody(['message' => 'Cliente y usuario relacionado eliminados exitosamente']);
        } catch (Exception $e) {
            // Responder con un error
            $response->setStatusCode(400); // Código de estado para solicitud incorrecta
            $response->setBody(['error' => $e->getMessage()]);
        }

        // Enviar la respuesta
        $response->send();
    }
}