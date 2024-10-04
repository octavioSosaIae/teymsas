<?php

require_once dirname(__DIR__) . '../../core/Database.php';

class Product
{

    private $idProduct;
    private $description;
    private $details;
    private $price;
    private $thumbnail;
    private $stock;
    private $measures;
    private $idCategory;
    private $userId;


    public function __construct($idProduct = null, $description = null, $details = null, $price = null, $thumbnail = null, $stock = null, $measures = null, $idCategory = null,$userId = null)
    {
        $this->idProduct = $idProduct;
        $this->description = $description;
        $this->details = $description;
        $this->price = $price;
        $this->thumbnail = $thumbnail;
        $this->stock = $stock;
        $this->measures = $measures;
        $this->idCategory = $idCategory;
        $this->userId = $userId;
    }


    public static function create($description, $details, $price, $thumbnail, $stock, $measures, $idCategory, $userId)
    {
        $mysqli = Database::getInstanceDB();
        $stmt = $mysqli->prepare("INSERT INTO products (description_product, details_product, price_product, thumbnail_product, stock_product, measures_product, id_Category) VALUES (?,?,?,?,?,?,?)");
        $stmt->bind_param("ssisisi", $description, $details, $price, $thumbnail, $stock, $measures, $idCategory);
        if ($stmt->execute()) {
            return new self($stmt->insert_id, $description, $details, $price, $thumbnail, $stock, $measures, $idCategory);
        } else {
            throw new Exception("Error al crear el producto: " . $stmt->error);
        }
    }


    public static function getById($idProduct)
    {
        $mysqli = Database::getInstanceDB();
        $stmt = $mysqli->prepare("SELECT * FROM products WHERE id_product = ?");
        $stmt->bind_param("i", $idProduct);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            if ($row = $result->fetch_assoc()) {
                return new self($row['id_product'], $row['description_product']);
            } else {
                throw new Exception("Producto no encontrado");
            }
        } else {
            throw new Exception("Error al obtener el producto: " . $stmt->error);
        }
    }


    public static function getAll()
    {
        $mysqli = Database::getInstanceDB();
        $stmt = $mysqli->prepare("SELECT * FROM products");
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $products = [];
            while ($row = $result->fetch_assoc()) {
                $products[] = [

                    'id' => $row['id_product'],
                    'description' => $row['description_product'],
                    'details' => $row['details_product'],
                    'price' => $row['price_product'],
                    'thumbnail' => $row['thumbnail_product'],
                    'stock' => $row['stock_product'],
                    'measures' => $row['measures_product'],
                    'idCategory' => $row['id_category'],
                    'updatedAtProduct' => $row['updated_at_product'],
                    'updatedByProduct' => $row['updated_by_product']
                ];
            }
            return $products;
        } else {
            throw new Exception("Error al obtener los productos: " . $stmt->error);
        }
    }

    public function update()
    {
        $mysqli = Database::getInstanceDB();
        $stmt = $mysqli->prepare("UPDATE products SET description_product = ?, details_product = ?, price_product = ?, thumbnail_product = ?, stock_product = ?, measures_product = ?, idCategory_product = ? WHERE id_product = ?");
        $stmt->bind_param("ssisisii", $this->description, $this->details, $this->price, $this->thumbnail, $this->stock, $this->measures, $this->idCategory, $this->idProduct );
        if (!$stmt->execute()) {
            throw new Exception("Error al actualizar el producto: " . $stmt->error);
        }
    }


    public static function delete($idProduct)
    {
        $mysqli = Database::getInstanceDB();
        $stmt = $mysqli->prepare("DELETE FROM products WHERE id_product = ?");
        $stmt->bind_param("i", $idProduct);
        if (!$stmt->execute()) {
            throw new Exception("Error al eliminar el producto: " . $stmt->error);
        }
     }


        // Getters y Setters
        public function getIdProduct() {
            return $this->idProduct;
        }
    
        public function getDescription() {
            return $this->description;
        }

        public function getDetails() {
            return $this->details;
        }

        public function getPrice() {
            return $this->price;
        }

        public function getThumbnail() {
            return $this->thumbnail;
        }

        public function getStock() {
            return $this->stock;
        }

        public function getMeasures() {
            return $this->measures;
        }

    
        public function setDescription($description) {
            $this->description = $description;
        }
        
        public function setDetails($details) {
            $this->details = $details;
        }

        public function setPrice($price) {
            $this->price = $price;
        }

        public function setThumbnail($thumbnail) {
            $this->thumbnail = $thumbnail;
        }

        public function setStock($stock) {
            $this->stock = $stock;
        }

        public function setMeasures($measures) {
            $this->measures = $measures;

        }
    }