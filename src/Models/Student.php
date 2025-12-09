<?php

namespace App\Models;

use App\Database\Connection;
use MongoDB\BSON\ObjectId;
use Exception;

class Student
{
    private $collection;
    
    public function __construct()
    {
        $db = Connection::getDatabase();
        $this->collection = $db->students;
    }
    
    /**
     * Buscar estudiante por número y contraseña
     */
    public function findByNumberAndPassword(string $student_number, string $password): ?array
    {
        $student = $this->collection->findOne([
            'student_number' => $student_number,
            'password' => $password
        ]);
        
        return $student ? $student->bsonSerialize() : null;
    }
    
    /**
     * Buscar estudiante por número
     */
    public function findByNumber(string $student_number): ?array
    {
        $student = $this->collection->findOne([
            'student_number' => $student_number
        ]);
        
        return $student ? $student->bsonSerialize() : null;
    }
    
    /**
     * Obtener todos los estudiantes
     */
    public function findAll(): array
    {
        $students = $this->collection->find([], ['sort' => ['student_number' => 1]]);
        
        $result = [];
        foreach ($students as $student) {
            $result[] = $student->bsonSerialize();
        }
        
        return $result;
    }
    
    /**
     * Crear un nuevo estudiante
     */
    public function create(string $name, string $student_number, string $password): array
    {
        // Validaciones
        if (!preg_match('/^\d{2}$/', $student_number)) {
            throw new Exception('El número de alumno debe tener 2 dígitos');
        }
        
        if (!preg_match('/^\d{4}$/', $password)) {
            throw new Exception('La contraseña debe tener 4 dígitos');
        }
        
        if (empty(trim($name))) {
            throw new Exception('El nombre es requerido');
        }
        
        // Verificar que no exista el número de alumno
        if ($this->findByNumber($student_number)) {
            throw new Exception('El número de alumno ya existe');
        }
        
        $result = $this->collection->insertOne([
            'name' => $name,
            'student_number' => $student_number,
            'password' => $password
        ]);
        
        return [
            '_id' => (string)$result->getInsertedId(),
            'name' => $name,
            'student_number' => $student_number,
            'password' => $password
        ];
    }
    
    /**
     * Actualizar un estudiante
     */
    public function update(string $student_number, array $data): bool
    {
        $updateData = [];
        
        if (isset($data['name']) && !empty(trim($data['name']))) {
            $updateData['name'] = $data['name'];
        }
        
        if (isset($data['password'])) {
            if (!preg_match('/^\d{4}$/', $data['password'])) {
                throw new Exception('La contraseña debe tener 4 dígitos');
            }
            $updateData['password'] = $data['password'];
        }
        
        if (empty($updateData)) {
            return false;
        }
        
        $result = $this->collection->updateOne(
            ['student_number' => $student_number],
            ['$set' => $updateData]
        );
        
        return $result->getModifiedCount() > 0;
    }
    
    /**
     * Eliminar un estudiante
     */
    public function delete(string $student_number): bool
    {
        $result = $this->collection->deleteOne([
            'student_number' => $student_number
        ]);
        
        return $result->getDeletedCount() > 0;
    }
}
