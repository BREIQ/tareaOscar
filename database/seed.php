<?php

/**
 * Archivo de ejemplo para insertar datos de prueba en MongoDB
 * Ejecutar desde la línea de comandos: php database/seed.php
 */

require_once __DIR__ . '/../config/config.php';

use App\Database\Connection;
use MongoDB\BSON\ObjectId;

try {
    $db = Connection::getDatabase();
    
    // Limpiar colecciones existentes (opcional)
    echo "Limpiando colecciones existentes...\n";
    $db->students->deleteMany([]);
    $db->attendance->deleteMany([]);
    
    // Insertar estudiantes de ejemplo
    echo "Insertando estudiantes de ejemplo...\n";
    
    $students = [
        [
            'student_number' => '01',
            'password' => '1111',
            'name' => 'Carlos García'
        ],
        [
            'student_number' => '02',
            'password' => '2222',
            'name' => 'María López'
        ],
        [
            'student_number' => '03',
            'password' => '3333',
            'name' => 'Roberto Martínez'
        ],
        [
            'student_number' => '04',
            'password' => '4444',
            'name' => 'Ana Rodríguez'
        ],
        [
            'student_number' => '05',
            'password' => '1234',
            'name' => 'Juan Pérez'
        ]
    ];
    
    foreach ($students as $student) {
        $result = $db->students->insertOne($student);
        echo "✓ Alumno insertado: {$student['name']} (Número: {$student['student_number']})\n";
    }
    
    echo "\n¡Base de datos inicializada correctamente!\n";
    echo "\nAlumnos de prueba disponibles:\n";
    echo "- 01 / 1111 (Carlos García)\n";
    echo "- 02 / 2222 (María López)\n";
    echo "- 03 / 3333 (Roberto Martínez)\n";
    echo "- 04 / 4444 (Ana Rodríguez)\n";
    echo "- 05 / 1234 (Juan Pérez)\n";
    echo "\nContraseña de administrador: Oscar9234\n";
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
