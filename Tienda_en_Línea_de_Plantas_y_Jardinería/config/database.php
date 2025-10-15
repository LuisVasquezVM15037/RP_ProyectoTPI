<?php
/**
 * Configuración de la base de datos
 * Tienda en Línea de Plantas y Jardinería
 */

return [
    'host' => 'localhost',
    'database' => 'tienda_plantas',
    'username' => 'root',
    'password' => '',
    'charset' => 'utf8mb4',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
];
