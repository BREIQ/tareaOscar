<?php

/**
 * Helpers y funciones auxiliares
 */

/**
 * Validar si una cadena es un número
 */
function isNumeric($value, $length = null) {
    if (!preg_match('/^\d+$/', $value)) {
        return false;
    }
    
    if ($length && strlen($value) !== $length) {
        return false;
    }
    
    return true;
}

/**
 * Sanitizar entrada de usuario
 */
function sanitize($input) {
    return trim(htmlspecialchars($input, ENT_QUOTES, 'UTF-8'));
}

/**
 * Validar email
 */
function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * Formatear fecha
 */
function formatDate($date, $format = 'Y-m-d H:i:s') {
    if (is_string($date)) {
        $date = strtotime($date);
    }
    return date($format, $date);
}

/**
 * Obtener la hora actual
 */
function getCurrentDateTime() {
    return date('Y-m-d H:i:s');
}

/**
 * Registrar errores en log
 */
function logError($message, $file = 'error.log') {
    $logPath = __DIR__ . '/../logs/' . $file;
    $timestamp = date('Y-m-d H:i:s');
    $logMessage = "[$timestamp] $message\n";
    
    if (!is_dir(__DIR__ . '/../logs')) {
        mkdir(__DIR__ . '/../logs', 0755, true);
    }
    
    file_put_contents($logPath, $logMessage, FILE_APPEND);
}
