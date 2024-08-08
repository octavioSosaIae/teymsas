CREATE DATABASE ecommerce;
USE ecommerce;

-- Tabla de Departamentos
CREATE TABLE departments (
    id_departament INT PRIMARY KEY AUTO_INCREMENT,
    name_departament VARCHAR(255) NOT NULL
);

-- Tabla de Ciudades
CREATE TABLE cities (
    id_city INT PRIMARY KEY AUTO_INCREMENT,
    name_city VARCHAR(30) NOT NULL,
    id_departament INT NOT NULL,
    CONSTRAINT fk_id_departament_city FOREIGN KEY (id_departament) REFERENCES departments(id_departament)
);

-- Tabla de Usuarios
CREATE TABLE users (
    id_user INT PRIMARY KEY AUTO_INCREMENT,
    complete_name_user VARCHAR(100) NOT NULL,
    email_user VARCHAR(255) NOT NULL UNIQUE,
    password_user VARCHAR(255) NOT NULL,
    phone_user VARCHAR(50) NOT NULL,
    role_user CHAR(1) NOT NULL,
    created_at_user DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT chk_role_user CHECK (role_user IN ('A', 'C'))
);

-- Tabla de Clientes
CREATE TABLE customers (
    id_user_customer INT PRIMARY KEY,
    document_customer VARCHAR(15) NOT NULL,
    address_customer VARCHAR(255) NOT NULL,
    business_name_customer VARCHAR(255),
    rut_customer VARCHAR(50),
    id_city INT NOT NULL,
    CONSTRAINT fk_id_user FOREIGN KEY (id_user_customer) REFERENCES users(id_user),
    CONSTRAINT fk_id_city_customer FOREIGN KEY (id_city) REFERENCES cities(id_city)
);

-- Tabla de Métodos de Pago
CREATE TABLE payment_methods (
    id_payment_method INT PRIMARY KEY AUTO_INCREMENT,
    name_payment_method VARCHAR(50) NOT NULL
);

-- Tabla de Estados de Pedidos
CREATE TABLE order_status (
    id_order_status INT PRIMARY KEY AUTO_INCREMENT,
    description_status VARCHAR(50) NOT NULL
);

-- Tabla de Órdenes de Clientes
CREATE TABLE customer_orders (
    id_customer_order INT PRIMARY KEY AUTO_INCREMENT,
    id_customer INT NOT NULL,
    date_order DATE NOT NULL,
    total_order DECIMAL(10, 2) NOT NULL,
    id_payment_method INT NOT NULL,
    id_order_status INT NOT NULL,
    updated_at_order DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    updated_by_order INT NOT NULL,
    CONSTRAINT fk_id_customer_customer_order FOREIGN KEY (id_customer) REFERENCES customers(id_user_customer),
    CONSTRAINT fk_id_payment_method_customer_order FOREIGN KEY (id_payment_method) REFERENCES payment_methods(id_payment_method),
    CONSTRAINT fk_id_order_stauts_customer_order FOREIGN KEY (id_order_status) REFERENCES order_status(id_order_status),
	CONSTRAINT fk_updated_by_customer_order FOREIGN KEY (updated_by_order) REFERENCES users(id_user)
);

-- Tabla de Entregas
CREATE TABLE deliveries (
    id_delivery INT PRIMARY KEY AUTO_INCREMENT,
    id_customer_order INT NOT NULL,
    address_delivery VARCHAR(255) NOT NULL,
    date_delivery DATE NOT NULL,
    CONSTRAINT fk_id_customer_order_delivery FOREIGN KEY (id_customer_order) REFERENCES customer_orders(id_customer_order)
);

-- Tabla de Categorías
CREATE TABLE categories (
    id_category INT PRIMARY KEY AUTO_INCREMENT,
    description_category VARCHAR(255) NOT NULL
);

-- Tabla de Productos
CREATE TABLE products (
    id_product INT PRIMARY KEY AUTO_INCREMENT,
    description_product VARCHAR(100) NOT NULL,
    details_product TEXT,
    price_product DECIMAL(10, 2) NOT NULL,
    thumbnail_product VARCHAR(255),
    stock_product INT DEFAULT 0,
    measures_product VARCHAR(255),
	id_category INT NOT NULL,
    updated_at_product DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    updated_by_product INT NOT NULL,
    CONSTRAINT fk_id_category_product FOREIGN KEY (id_category) REFERENCES categories(id_category),
	CONSTRAINT fk_updated_by_product FOREIGN KEY (updated_by_product) REFERENCES users(id_user)
);

-- Tabla Imágenes de Productos
CREATE TABLE images_product (
	id_image_product INT PRIMARY KEY AUTO_INCREMENT,
    id_product INT NOT NULL,
    description_image_product VARCHAR(100) NOT NULL,
    url_image_product VARCHAR(255) NOT NULL,
    CONSTRAINT fk_id_product_id_image_product FOREIGN KEY (id_product) REFERENCES products(id_product)
    );
    

-- Tabla de Productos en Órdenes de Clientes
CREATE TABLE order_products_customers (
    id_order_product_customer INT PRIMARY KEY AUTO_INCREMENT,
    id_customer_order INT NOT NULL,
    id_product INT NOT NULL,
    quantity_order_product_customer INT NOT NULL,
    unit_price_order_product_customer DECIMAL(10, 2) NOT NULL,
    total_order_product_customer DECIMAL(10, 2) NOT NULL,
    CONSTRAINT fk_id_customer_order_order_product_customer FOREIGN KEY (id_customer_order) REFERENCES customer_orders(id_customer_order),
    CONSTRAINT fk_id_product_order_product_customer FOREIGN KEY (id_product) REFERENCES products(id_product)
);

-- Tabla de Proveedores
CREATE TABLE providers (
    id_provider INT PRIMARY KEY AUTO_INCREMENT,
    name_provider VARCHAR(100) NOT NULL
);

-- Tabla de Órdenes de Compra
CREATE TABLE purchase_orders (
    id_purchase_order INT PRIMARY KEY AUTO_INCREMENT,
    id_provider INT NOT NULL,
    date_purchase_order DATE NOT NULL,
    total_purchase_order DECIMAL(10, 2) NOT NULL,
    id_payment_method INT NOT NULL,
    CONSTRAINT fk_id_provider_purchase_order FOREIGN KEY (id_provider) REFERENCES providers(id_provider),
	CONSTRAINT fk_id_payment_method_purchase_order FOREIGN KEY (id_payment_method) REFERENCES payment_methods(id_payment_method)
);

-- Tabla de Productos en Órdenes de Compra
CREATE TABLE order_products_purchases (
    id_order_product_purchase INT PRIMARY KEY AUTO_INCREMENT,
    id_purchase_order INT NOT NULL,
    id_product INT NOT NULL,
    quantity_order_product_purchase INT NOT NULL,
    unit_price_order_product_purchase DECIMAL(10, 2) NOT NULL,
    total_order_product_purchase DECIMAL(10, 2) NOT NULL,
    CONSTRAINT fk_id_purchase_order_order_product_purchase FOREIGN KEY (id_purchase_order) REFERENCES purchase_orders(id_purchase_order),
    CONSTRAINT fk_id_product_order_product_purchase FOREIGN KEY (id_product) REFERENCES products(id_product)
);