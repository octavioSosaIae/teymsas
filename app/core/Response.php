<?php

class Response
{
    private $statusCode;
    private $body;

    public function __construct()
    {
        $this->statusCode = 200; // Default status code
        $this->body = [];
    }

    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
    }

    public function setBody($body)
    {
        $this->body = $body;
    }

    public function send()
    {
        // Establecer el tipo de contenido
        header('Content-Type: application/json');
        
        // Establecer el código de estado HTTP
        http_response_code($this->statusCode);
        
        // Convertir el cuerpo a JSON y enviarlo
        echo json_encode($this->body);
    }
}
