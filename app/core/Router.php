<?php

class Router
{
    // Instancia única de la clase Router (uso de patrón Singleton)
    private static $instanceRouter;
    //Array con listado de rutas
    public $routes = [];

    // Método estático para obtener la instancia única de la clase Router
    public static function getInstanceRouter()
    {
        // Verificar si la instancia no existe
       if (!self::$instanceRouter) {
            // Crear una nueva instancia de Router
            self::$instanceRouter = new Router();
        }

        //Retornar la instancia
        return self::$instanceRouter;
    }

    private function add($method, $uri, $controller)
    {
        $this->routes[] = [
            'method' => $method,
            'uri' => $uri,
            'controller' => $controller
        ];
    }
    public function get($uri, $controller)
    {
        $this->add('GET', $uri, $controller);
    }

    public function post($uri, $controller)
    {
        $this->add('POST', $uri, $controller);

    }

    public function put($uri, $controller)
    {
        $this->add('PUT', $uri, $controller);
    }

    public function delete($uri, $controller)
    {
        $this->add('DELETE', $uri, $controller);
    }

    public function patch($uri, $controller)
    {
        $this->add('PATCH', $uri, $controller);
    }

    public function dispatchRoute($uriRoute, $requestMethod)
    {
        $routeFound = false;
        foreach ($this->routes as $route) {


            if (strpos($route['uri'], '{id}') !== false) {
                $route['uri'] = str_replace('{id}', '([0-9]+)', $route['uri']);
            }

            if ((preg_match("#^" . $route['uri'] . "$#", $uriRoute, $matches)) && $requestMethod == $route['method']) {

                $params = array_slice($matches, 1);

                // Si el controlador es un array con clase y método
                if (is_array($route['controller'])) {
                    $controllerClass = $route['controller'][0];
                    $controllerMethod = $route['controller'][1];
                    $controllerInstance = new $controllerClass();

                    $routeFound = true;
                    return call_user_func_array([$controllerInstance, $controllerMethod], $params);
                }

                // Si es un controlador ejecutable, llama a la función
                if (is_callable($route['controller'])) {
                    $routeFound = true;
                    return call_user_func($route['controller'], $params);
                } else {
                    $routeFound = true;
                    return require $route['controller'];
                }
            }
        }
        if (!$routeFound) {
            $this->abort();
        }

    }

    protected function abort($code = 404)
    {
        http_response_code($code);

        echo json_encode("error $code");

        die();
    }

    // public function printRoutes()
    // {
    //     foreach ($this->routes as $route) {
    //         echo "Method: " . $route['method'] . ", URI: " . $route['uri'] . ", Controller: " . (is_callable($route['controller']) ? 'Closure' : $route['controller']) . "<br>";
    //     }
    // }

    // Método para prevenir la clonación de la instancia del objeto $instanceRouter
    public function __clone()
    {
        // Vacío para prevenir la clonación
    }

    // Método para prevenir la deserialización de la instancia del objeto $instanceRouter
    public function __wakeup()
    {
        // Vacío para prevenir la deserialización
    }
}