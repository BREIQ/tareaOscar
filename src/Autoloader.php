<?php

/**
 * Autoloader para clases del namespace App
 * Permite cargar automáticamente las clases sin require/include explícitos
 */

spl_autoload_register(function ($class) {
    // Verificar que la clase pertenece al namespace App
    $prefix = 'App\\';
    $len = strlen($prefix);
    
    if (strncmp($prefix, $class, $len) !== 0) {
        return; // No es de nuestro namespace
    }
    
    // Obtener la ruta relativa a partir del nombre de la clase
    $relative_class = substr($class, $len);
    $file = __DIR__ . '/src/' . str_replace('\\', '/', $relative_class) . '.php';
    
    // Si el archivo existe, incluirlo
    if (file_exists($file)) {
        require $file;
    }
});
