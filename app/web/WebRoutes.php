<?php

// WebRoutes.php
$router = Router::getInstanceRouter();

$router->get('/', function() {
    echo "Home";
});

$router->get('/contact', function() {
    echo "Contact";
});

$router->get('/about', function() {
    echo "About";
});

$router->get('/store', function() {
    echo "Store";
});
