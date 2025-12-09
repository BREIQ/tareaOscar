<?php

namespace App\Controllers;

use App\Models\Student;
use App\Models\Attendance;
use Exception;

class AttendanceController
{
    /**
     * Validar PIN y registrar asistencia
     */
    public function register(string $pin): array
    {
        try {
            // Validar formato del PIN (6 dígitos)
            if (!preg_match('/^\d{6}$/', $pin)) {
                return [
                    'success' => false,
                    'message' => 'PIN inválido. Debe tener 6 dígitos.'
                ];
            }
            
            // Extraer número de alumno (primeros 2 dígitos) y contraseña (últimos 4)
            $student_number = substr($pin, 0, 2);
            $password = substr($pin, 2, 4);
            
            // Buscar estudiante
            $studentModel = new Student();
            $student = $studentModel->findByNumberAndPassword($student_number, $password);
            
            if (!$student) {
                return [
                    'success' => false,
                    'message' => 'PIN incorrecto o usuario no encontrado'
                ];
            }
            
            // Registrar asistencia
            $attendanceModel = new Attendance();
            $attendance = $attendanceModel->register($student_number);
            
            $hora = date('H:i:s', strtotime($attendance['timestamp']));
            
            return [
                'success' => true,
                'message' => 'Asistencia registrada: ' . $student['name'] . ' – ' . $hora,
                'student_name' => $student['name'],
                'time' => $hora
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error al registrar asistencia: ' . $e->getMessage()
            ];
        }
    }
}
