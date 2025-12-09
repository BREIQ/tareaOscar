<?php

require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../src/Autoloader.php';

// Función para responder JSON
function jsonResponse($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode($data, JSON_UNESCAPED_UNICODE);
    exit;
}

// Obtener método y ruta
$method = $_SERVER['REQUEST_METHOD'];
$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$path = str_replace('/api', '', $path);

// API para registrar asistencia
if ($method === 'POST' && $path === '/attendance/register') {
    $data = json_decode(file_get_contents('php://input'), true);
    $pin = $data['pin'] ?? '';
    
    $controller = new \App\Controllers\AttendanceController();
    $result = $controller->register($pin);
    
    jsonResponse($result, $result['success'] ? 200 : 400);
}

// API para validar contraseña de admin
if ($method === 'POST' && $path === '/admin/validate') {
    $data = json_decode(file_get_contents('php://input'), true);
    $password = $data['password'] ?? '';
    
    $controller = new \App\Controllers\AdminController();
    $isValid = $controller->validatePassword($password);
    
    if ($isValid) {
        $_SESSION['admin'] = true;
        jsonResponse([
            'success' => true,
            'message' => 'Acceso permitido'
        ]);
    } else {
        jsonResponse([
            'success' => false,
            'message' => 'Contraseña incorrecta'
        ], 401);
    }
}

// API para obtener alumnos
if ($method === 'GET' && $path === '/admin/students') {
    $controller = new \App\Controllers\AdminController();
    $result = $controller->getStudents();
    jsonResponse($result);
}

// API para crear alumno
if ($method === 'POST' && $path === '/admin/students') {
    $data = json_decode(file_get_contents('php://input'), true);
    
    $name = $data['name'] ?? '';
    $student_number = $data['student_number'] ?? '';
    $password = $data['password'] ?? '';
    
    $controller = new \App\Controllers\AdminController();
    $result = $controller->createStudent($name, $student_number, $password);
    
    jsonResponse($result, $result['success'] ? 201 : 400);
}

// API para actualizar alumno
if ($method === 'PUT' && preg_match('/^\/admin\/students\/(\d{2})$/', $path, $matches)) {
    $student_number = $matches[1];
    $data = json_decode(file_get_contents('php://input'), true);
    
    $controller = new \App\Controllers\AdminController();
    $result = $controller->updateStudent($student_number, $data);
    
    jsonResponse($result, $result['success'] ? 200 : 400);
}

// API para eliminar alumno
if ($method === 'DELETE' && preg_match('/^\/admin\/students\/(\d{2})$/', $path, $matches)) {
    $student_number = $matches[1];
    
    $controller = new \App\Controllers\AdminController();
    $result = $controller->deleteStudent($student_number);
    
    jsonResponse($result, $result['success'] ? 200 : 404);
}

// API para obtener asistencias con filtros
if ($method === 'GET' && $path === '/admin/attendance') {
    $type = $_GET['type'] ?? 'all';
    
    $controller = new \App\Controllers\AdminController();
    
    if ($type === 'day') {
        $date = $_GET['date'] ?? date('Y-m-d');
        $result = $controller->getAttendanceByDay($date);
    } elseif ($type === 'month') {
        $month = $_GET['month'] ?? date('m');
        $year = $_GET['year'] ?? date('Y');
        $result = $controller->getAttendanceByMonth($year, $month);
    } elseif ($type === 'year') {
        $year = $_GET['year'] ?? date('Y');
        $result = $controller->getAttendanceByYear($year);
    } else {
        $result = $controller->getAllAttendance();
    }
    
    jsonResponse($result);
}

// Si no coincide con ninguna ruta, retornar 404
jsonResponse([
    'success' => false,
    'message' => 'Endpoint no encontrado'
], 404);
