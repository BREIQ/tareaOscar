<?php

namespace App\Controllers;

use App\Models\Student;
use App\Models\Attendance;
use Exception;

class AdminController
{
    /**
     * Validar contraseña de administrador
     */
    public function validatePassword(string $password): bool
    {
        return $password === ADMIN_PASSWORD;
    }
    
    // ===== GESTIÓN DE ALUMNOS =====
    
    /**
     * Obtener todos los alumnos
     */
    public function getStudents(): array
    {
        try {
            $studentModel = new Student();
            return [
                'success' => true,
                'data' => $studentModel->findAll()
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Crear un nuevo alumno
     */
    public function createStudent(string $name, string $student_number, string $password): array
    {
        try {
            $studentModel = new Student();
            $student = $studentModel->create($name, $student_number, $password);
            
            return [
                'success' => true,
                'message' => 'Alumno creado exitosamente',
                'data' => $student
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Actualizar un alumno
     */
    public function updateStudent(string $student_number, array $data): array
    {
        try {
            $studentModel = new Student();
            
            // Verificar que el alumno existe
            $student = $studentModel->findByNumber($student_number);
            if (!$student) {
                return [
                    'success' => false,
                    'message' => 'Alumno no encontrado'
                ];
            }
            
            if ($studentModel->update($student_number, $data)) {
                $updatedStudent = $studentModel->findByNumber($student_number);
                return [
                    'success' => true,
                    'message' => 'Alumno actualizado exitosamente',
                    'data' => $updatedStudent
                ];
            }
            
            return [
                'success' => false,
                'message' => 'No se realizaron cambios'
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Eliminar un alumno
     */
    public function deleteStudent(string $student_number): array
    {
        try {
            $studentModel = new Student();
            
            if ($studentModel->delete($student_number)) {
                return [
                    'success' => true,
                    'message' => 'Alumno eliminado exitosamente'
                ];
            }
            
            return [
                'success' => false,
                'message' => 'Alumno no encontrado'
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    // ===== CONSULTA DE ASISTENCIAS =====
    
    /**
     * Obtener asistencias por día
     */
    public function getAttendanceByDay(string $date): array
    {
        try {
            $attendanceModel = new Attendance();
            $studentModel = new Student();
            
            $records = $attendanceModel->getByDay($date);
            
            // Enriquecer con nombres de estudiantes
            foreach ($records as &$record) {
                $student = $studentModel->findByNumber($record['student_number']);
                $record['student_name'] = $student ? $student['name'] : 'Desconocido';
            }
            
            return [
                'success' => true,
                'data' => $records,
                'filter' => 'Día: ' . $date
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Obtener asistencias por mes
     */
    public function getAttendanceByMonth(int $year, int $month): array
    {
        try {
            $attendanceModel = new Attendance();
            $studentModel = new Student();
            
            $records = $attendanceModel->getByMonth($year, $month);
            
            // Enriquecer con nombres de estudiantes
            foreach ($records as &$record) {
                $student = $studentModel->findByNumber($record['student_number']);
                $record['student_name'] = $student ? $student['name'] : 'Desconocido';
            }
            
            return [
                'success' => true,
                'data' => $records,
                'filter' => 'Mes: ' . sprintf('%04d-%02d', $year, $month)
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Obtener asistencias por año
     */
    public function getAttendanceByYear(int $year): array
    {
        try {
            $attendanceModel = new Attendance();
            $studentModel = new Student();
            
            $records = $attendanceModel->getByYear($year);
            
            // Enriquecer con nombres de estudiantes
            foreach ($records as &$record) {
                $student = $studentModel->findByNumber($record['student_number']);
                $record['student_name'] = $student ? $student['name'] : 'Desconocido';
            }
            
            return [
                'success' => true,
                'data' => $records,
                'filter' => 'Año: ' . $year
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
    
    /**
     * Obtener todas las asistencias
     */
    public function getAllAttendance(): array
    {
        try {
            $attendanceModel = new Attendance();
            $studentModel = new Student();
            
            $records = $attendanceModel->getAll();
            
            // Enriquecer con nombres de estudiantes
            foreach ($records as &$record) {
                $student = $studentModel->findByNumber($record['student_number']);
                $record['student_name'] = $student ? $student['name'] : 'Desconocido';
            }
            
            return [
                'success' => true,
                'data' => $records
            ];
            
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}
