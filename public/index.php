<?php

session_start();

// Cargar configuración
require_once __DIR__ . '/../config/config.php';

// Obtener la ruta solicitada
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = str_replace('/public', '', $path);

// Rutas públicas (no requieren autenticación)
if ($path === '/' || $path === '/index.php') {
    include __DIR__ . '/../views/home.php';
    exit;
}

// Ruta del panel de administrador
if ($path === '/admin' || $path === '/admin/index.php') {
    include __DIR__ . '/../views/admin.php';
    exit;
}

// Si no hay ruta coincidente, mostrar home
include __DIR__ . '/../views/home.php';
