<?php

require_once "controllers/UserController.php";
require_once "controllers/CustomerController.php";
require_once "controllers/DepartmentController.php";
require_once "controllers/CityController.php";
require_once "controllers/OrderStatusController.php";
require_once "controllers/PaymentMethodController.php";
require_once "controllers/ProductController.php";



// ApiRoutes.php
$router = Router::getInstanceRouter();

const API_VERSION = '/api/v1';


// Rutas para el controlador de usuarios
$router->post(API_VERSION . '/users', [UserController::class, 'create']);
$router->get(API_VERSION . '/users', [UserController::class, 'getAll']);
$router->get(API_VERSION . '/users/{id}', function ($id) {
    $controller = new UserController();
    $controller->getById($id);
});
$router->patch(API_VERSION . '/users/{id}', function ($id) {
    $controller = new UserController();
    $controller->updateWithoutPassword($id);
});
$router->patch(API_VERSION . '/users/{id}/password', function ($id) {
    $controller = new UserController();
    $controller->updatePassword($id);
});
$router->delete(API_VERSION . '/users/{id}', function ($id) {
    $controller = new UserController();
    $controller->delete($id);
});

// Rutas para el controlador de clientes
$router->post(API_VERSION . '/customers', [CustomerController::class, 'create']);
$router->get(API_VERSION . '/customers', [CustomerController::class, 'getAll']);
$router->get(API_VERSION . '/customers/{id}', function ($id) {
    $controller = new CustomerController();
    $controller->getById($id);
});
$router->put(API_VERSION . '/customers/{id}', function ($id) {
    $controller = new CustomerController();
    $controller->update($id);
});
$router->delete(API_VERSION . '/customers/{id}', function ($id) {
    $controller = new CustomerController();
    $controller->delete($id);
});


// Rutas para el controlador de departamentos
$router->post(API_VERSION . '/departments', [DepartmentController::class, 'create']);
$router->get(API_VERSION . '/departments', [DepartmentController::class, 'getAll']);
$router->get(API_VERSION . '/departments/{id}', function ($id) {
    $controller = new DepartmentController();
    $controller->getById($id);
});
$router->put(API_VERSION . '/departments/{id}', function ($id) {
    $controller = new DepartmentController();
    $controller->update($id);
});
$router->delete(API_VERSION . '/departments/{id}', function ($id) {
    $controller = new DepartmentController();
    $controller->delete($id);
});

// Rutas para el controlador de ciudades
$router->post(API_VERSION . '/cities', [CityController::class, 'create']);
$router->get(API_VERSION . '/cities', [CityController::class, 'getAll']);
$router->get(API_VERSION . '/cities/{id}', function ($id) {
    $controller = new CityController();
    $controller->getById($id);
});
$router->put(API_VERSION . '/cities/{id}', function ($id) {
    $controller = new CityController();
    $controller->update($id);
});
$router->delete(API_VERSION . '/cities/{id}', function ($id) {
    $controller = new CityController();
    $controller->delete($id);
});


// Rutas para el controlador de métodos de pago
$router->post(API_VERSION . '/payment_methods', [PaymentMethodController::class, 'create']);
$router->get(API_VERSION . '/payment_methods', [PaymentMethodController::class, 'getAll']);
$router->get(API_VERSION . '/payment_methods/{id}', function ($id) {
    $controller = new PaymentMethodController();
    $controller->getById($id);
});
$router->put(API_VERSION . '/payment_methods/{id}', function ($id) {
    $controller = new PaymentMethodController();
    $controller->update($id);
});
$router->delete(API_VERSION . '/payment_methods/{id}', function ($id) {
    $controller = new PaymentMethodController();
    $controller->delete($id);
});

// Rutas para el controlador de estados de pedido
$router->post(API_VERSION . '/order_status', [OrderStatusController::class, 'create']);
$router->get(API_VERSION . '/order_status', [OrderStatusController::class, 'getAll']);
$router->get(API_VERSION . '/order_status/{id}', function ($id) {
    $controller = new OrderStatusController();
    $controller->getById($id);
});
$router->put(API_VERSION . '/order_status/{id}', function ($id) {
    $controller = new OrderStatusController();
    $controller->update($id);
});
$router->delete(API_VERSION . '/order_status/{id}', function ($id) {
    $controller = new OrderStatusController();
    $controller->delete($id);
});

// Rutas para el controlador de proveedores
$router->post(API_VERSION . '/providers', [ProviderController::class, 'create']);
$router->get(API_VERSION . '/providers', [ProviderController::class, 'getAll']);
$router->get(API_VERSION . '/providers/{id}', function ($id) {
    $controller = new ProviderController();
    $controller->getById($id);
});
$router->put(API_VERSION . '/providers/{id}', function ($id) {
    $controller = new ProviderController();
    $controller->update($id);
});
$router->delete(API_VERSION . '/providers/{id}', function ($id) {
    $controller = new ProviderController();
    $controller->delete($id);
});

// Rutas para el controlador de categorias
$router->post(API_VERSION . '/categories', [CategoryController::class, 'create']);
$router->get(API_VERSION . '/categories', [CategoryController::class, 'getAll']);
$router->get(API_VERSION . '/categories/{id}', function ($id) {
    $controller = new CategoryController();
    $controller->getById($id);
});
$router->put(API_VERSION . '/categories/{id}', function ($id) {
    $controller = new CategoryController();
    $controller->update($id);
});
$router->delete(API_VERSION . '/categories/{id}', function ($id) {
    $controller = new CategoryController();
    $controller->delete($id);
});

// Rutas para el controlador de productos
$router->post(API_VERSION . '/products', [ProductController::class, 'create']);
$router->get(API_VERSION . '/products', [ProductController::class, 'getAll']);
$router->get(API_VERSION . '/products/{id}', function ($id) {
    $controller = new ProductController();
    $controller->getById($id);
});
$router->put(API_VERSION . '/categories/{id}', function ($id) {
    $controller = new ProductController();
    $controller->update($id);
});
$router->delete(API_VERSION . '/categories/{id}', function ($id) {
    $controller = new ProductController();
    $controller->delete($id);
});