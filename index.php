<?php

//Deshabilitar la visualización de errores
ini_set('display_errors', '0');
ini_set('display_startup_errors', '0');

// Habilitar el registro de errores
ini_set('log_errors', '1');
ini_set('error_log', 'logs/php_errors.log');

require_once 'app/core/Router.php';

$requestUri = $_SERVER['REDIRECT_URL'];

$requestMethod = $_SERVER['REQUEST_METHOD'];

$base_url = '/teymsas';
$uriRoute = str_replace($base_url, '', $requestUri);



$uriRoute = $uriRoute == "" ? "/" : strtolower($uriRoute);

if ($uriRoute == "" || $uri="/")

preg_match_all('/\/[^\/]+/', $uriRoute, $segmentsMatches);


if ($segmentsMatches[0][0] == "/api") {
        require_once 'app/api/ApiRoutes.php';
} else {
        require_once 'app/web/WebRoutes.php';
}

// Obtener la instancia del router
$router = Router::getInstanceRouter();

// Imprime las rutas definidas
//$router->printRoutes(); 


$router->dispatchRoute($uriRoute, $requestMethod);