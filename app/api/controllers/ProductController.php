<?php

require_once dirname(__DIR__) . '/models/Product.php';
require_once dirname(__DIR__) . '../../core/Response.php';

class ProductController
{
  public function create(){
        $response = new Response();

         $description = $_POST['description_product'] ?? null;
         $details = $_POST['details_product'] ?? null;
         $price = $_POST['price_product'] ?? null;
         $thumbnail = $_POST['thumbnail_product'] ?? null;
         $stock = $_POST['stock_product'] ?? null;
         $measures = $_POST['measures_product'] ?? null;
         $idCategory = $_POST['id_Category'] ?? null;

        try {
               // Validar datos
               if (!$description || !$details || !$price || !$thumbnail || !$stock ||!$measures || !$idCategory) {
                throw new Exception("Todos los campos son necesarios");
            }

            $product = Product::create($description, $details, $price, $thumbnail, $stock, $measures, $idCategory);

            $response->setStatusCode(201);
            $response->setBody(['id' => $product->getIdProduct(), 'message' => 'Producto creado exitosamente']);
        } catch (Exception $e) {
            $response->setStatusCode(400);
            $response->setBody(['error' => $e->getMessage()]);
        }

        $response->send();
    }

     // Método para obtener un producto por ID
     public function getById($id){
        $response = new Response();

        try {
            $id = intval($id[0]);
            // Validar el ID
            if (!is_numeric($id)) {
                throw new Exception("ID de el producto inválido");
            }

            // Obtener el producto por ID
            $product = Product::getById($id);

            // Responder con los datos del producto
            $response->setStatusCode(200);
            $response->setBody([
                'id' => $product->getIdProduct(),
                'description' => $product->getDescription(),
                'details' => $product->getDetails(),
                'price' => $product->getPrice(),
                'thumbnail' => $product->getThumbnail(),
                'stock' => $product->getStock(),
                'measures' => $product->getMeasures()
            ]);
        } catch (Exception $e) {
            // Responder con un error
            $response->setStatusCode(404); // Código de estado para no encontrado
            $response->setBody(['error' => $e->getMessage()]);
        }

        // Enviar la respuesta
        $response->send();
    }

    public function getAll(){
        $response = new Response();

        try {
            $product = Product::getAll();

            $response->setStatusCode(200);
            $response->setBody($product);
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
            $product = Product::getById($id);
            
            // Obtener los datos del cuerpo de la solicitud
            $description = $data['description'] ?? $product->getDescription();
            $details = $data['details'] ?? $product->getDetails();
            $price = $data['price'] ?? $product->getPrice();
            $thumbnail = $data['thumbnail'] ?? $product->getThumbnail();
            $stock = $data['stock'] ?? $product->getStock();
            $measures = $data['measures'] ?? $product->getMeasures();

            // Validar datos
            if (!$description || !$details || !$price || !$thumbnail || !$stock || !$measures) {
                throw new Exception("Todos los campos son necesarios");
            }

            // Actualizar los datos del producto
            $product->setDescription($description);
            $product->setDetails($details);
            $product->setPrice($price);
            $product->setThumbnail($thumbnail);
            $product->setStock($stock);
            $product->setMeasures($measures);

            // Guardar los cambios
            $product->update();

            // Responder con el producto actualizado
            $response->setStatusCode(200);
            $response->setBody(['id' => $product->getIdProduct(), 'message' => 'Producto actualizado exitosamente']);
        } catch (Exception $e) {
            // Responder con un error
            $response->setStatusCode(400); // Código de estado para solicitud incorrecta
            $response->setBody(['error' => $e->getMessage()]);
        }

        // Enviar la respuesta
        $response->send();
    }


    
    public function delete($id){
        $response = new Response();
        $id = intval($id[0]);

        try {
            if (!is_numeric($id)) {
                throw new Exception("ID de producto inválido");
            }

            Product::getById($id);
            Product::delete($id);

            $response->setStatusCode(200);
            $response->setBody(['message' => 'Producto eliminado exitosamente']);
        } catch (Exception $e) {
            $response->setStatusCode(400);
            $response->setBody(['error' => $e->getMessage()]);
        }

        $response->send();
    }


}
















