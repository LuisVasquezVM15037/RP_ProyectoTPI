<?php

session_start();
if (isset($_SESSION[""]) && $_SESSION[""]   == "") {
    header("Location: index.php");
} 

define('BASE_URL', 'http://localhost/Web/'); //Esta url se ajusta según la configuración del servidor

// Cargar la configuración de PayPal (asegurar que las constantes PAYPAL_* estén disponibles)
require_once __DIR__ . '/config/paypal.php';

// Cargar las clases principales
require_once 'core/App.php'; //Cargar la clase App
require_once 'core/Controller.php'; //Cargar la clase Controller

// Inicializar la aplicación
$app = new App(); //Crear una instancia de la aplicación
?>