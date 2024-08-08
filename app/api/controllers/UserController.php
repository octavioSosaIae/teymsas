<?php

require_once dirname(__DIR__) . '/models/User.php';
require_once dirname(__DIR__) . '../../core/Response.php';

class UserController
{
    // Método para crear un nuevo usuario
    public function create(){
        $response = new Response();

        // Obtener los datos del cuerpo de la solicitud
        $completeName = $_POST['complete_name'] ?? null;
        $email = $_POST['email'] ?? null;
        $password = $_POST['password'] ?? null;
        $phone = $_POST['phone'] ?? null;
        $role = $_POST['role'] ?? null;

        try {
            // Validar datos
            if (!$completeName || !$email || !$password || !$phone || !$role) {
                throw new Exception("Todos los campos son necesarios");
            }

            // Llamar al método estático create de la clase User
            $user = User::create($completeName, $email, $password, $phone, $role);

            // Responder con el usuario creado
            $response->setStatusCode(201);
            $response->setBody(['id' => $user->getId(), 'message' => 'Usuario creado exitosamente']);
        } catch (Exception $e) {
            // Responder con un error
            $response->setStatusCode(400); // Código de estado para solicitud incorrecta
            $response->setBody(['error' => $e->getMessage()]);
        }

        // Enviar la respuesta
        $response->send();
    }

    // Método para obtener un usuario por ID
    public function getById($id){
        $response = new Response();

        try {
            $id = intval($id[0]);
            // Validar el ID
            if (!is_numeric($id)) {
                throw new Exception("ID de usuario inválido");
            }

            // Obtener el usuario por ID
            $user = User::getById($id);

            // Responder con los datos del usuario
            $response->setStatusCode(200);
            $response->setBody([
                'id' => $user->getId(),
                'complete_name' => $user->getCompleteName(),
                'email' => $user->getEmail(),
                'phone' => $user->getPhone(),
                'role' => $user->getRole(),
                'created_at' => $user->getCreatedAt()
            ]);
        } catch (Exception $e) {
            // Responder con un error
            $response->setStatusCode(404); // Código de estado para no encontrado
            $response->setBody(['error' => $e->getMessage()]);
        }

        // Enviar la respuesta
        $response->send();
    }

    // Método para actualizar un usuario sin cambiar la contraseña
    public function updateWithoutPassword($id){
        $response = new Response();
        $data = json_decode(file_get_contents('php://input'), true);
        $id = intval($id[0]);
        $user = new User();
        $user = $user->getById($id);

        // Obtener los datos del cuerpo de la solicitud
        $id = $data['id'] ?? $user->getId() ?? null;
        $completeName = $data['complete_name'] ?? $user->getCompleteName() ?? null;
        $email = $data['email'] ?? $user->getEmail() ?? null;
        $phone = $data['phone'] ?? $user->getPhone() ?? null;
        $role = $data['role'] ?? $user->getRole() ?? null;

        try {
            // Validar datos
            if (!$id || !$completeName || !$email || !$phone || !$role) {
                throw new Exception("Todos los campos son necesarios");
            }

            // Obtener el usuario y actualizar sus datos
            $user->setCompleteName($completeName);
            $user->setEmail($email);
            $user->setPhone($phone);
            $user->setRole($role);

            $user->updateWithoutPassword();

            // Responder con éxito
            $response->setStatusCode(200);
            $response->setBody(['message' => 'Usuario actualizado exitosamente']);
        } catch (Exception $e) {
            // Responder con un error
            $response->setStatusCode(400); // Código de estado para solicitud incorrecta
            $response->setBody(['error' => $e->getMessage()]);
        }

        // Enviar la respuesta
        $response->send();
    }

    // Método para actualizar la contraseña del usuario
    public function updatePassword($id){

        $response = new Response();
        $data = json_decode(file_get_contents('php://input'), true);

        $id = intval($id[0]);

        // Obtener los datos del cuerpo de la solicitud
        $currentPassword = $data['current_password'] ?? null;
        $newPassword = $data['new_password'] ?? null;


        try {

            // Obtener el usuario
            $user = User::getById($id);

            // Validar datos
            if (!$id || !$currentPassword || !$newPassword) {
                throw new Exception("Todos los campos son necesarios");
            }

            // actualizar la contraseña
            $user->updatePassword($currentPassword, $newPassword);

            // Responder con éxito
            $response->setStatusCode(200);
            $response->setBody(['message' => 'Contraseña actualizada exitosamente']);
        } catch (Exception $e) {
            // Responder con un error
            $response->setStatusCode(400); // Código de estado para solicitud incorrecta
            $response->setBody(['error' => $e->getMessage()]);
        }

        // Enviar la respuesta
        $response->send();
    }

    // Método para obtener todos los usuarios
    public function getAll(){
        $response = new Response();

        try {
            // Obtener todos los usuarios
            $users = User::getAll();

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

    // Método para eliminar un usuario
    public function delete($id){
        $response = new Response();
        $id = intval($id[0]);

        try {

            User::getById($id);

            // Validar el ID
            if (!is_numeric($id)) {
                throw new Exception("ID de usuario inválido");
            }

            // Eliminar el usuario por ID
            User::delete($id);

            // Responder con éxito
            $response->setStatusCode(200);
            $response->setBody(['message' => 'Usuario eliminado exitosamente']);
        } catch (Exception $e) {
            // Responder con un error
            $response->setStatusCode(404); // Código de estado para no encontrado
            $response->setBody(['error' => $e->getMessage()]);
        }

        // Enviar la respuesta
        $response->send();
    }
}
