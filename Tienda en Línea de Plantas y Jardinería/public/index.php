<?php
/**
 * Punto de entrada principal de la aplicación
 * Tienda en Línea de Plantas y Jardinería
 */

// Configuración de errores para desarrollo
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Incluir el autoloader
require_once __DIR__ . '/../vendor/autoload.php';

// Incluir la configuración
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../config/paypal.php';

// Iniciar sesión
session_start();

// Incluir el router principal
require_once __DIR__ . '/../controllers/Router.php';

// Crear instancia del router y manejar la petición
$router = new Router();
$router->handleRequest();
