<?php

namespace App\Database;

use MongoDB\Client;
use MongoDB\Database;
use Exception;

class Connection
{
    private static ?Database $database = null;
    
    /**
     * Obtener conexión a la base de datos MongoDB
     */
    public static function getDatabase(): Database
    {
        if (self::$database === null) {
            try {
                $client = new Client(MONGODB_URI);
                self::$database = $client->selectDatabase(MONGODB_DATABASE);
                
                // Crear índices si no existen
                self::createIndexes();
                
            } catch (Exception $e) {
                throw new Exception('Error conectando a MongoDB: ' . $e->getMessage());
            }
        }
        
        return self::$database;
    }
    
    /**
     * Crear índices en las colecciones
     */
    private static function createIndexes(): void
    {
        try {
            $db = self::$database;
            
            // Índice para búsqueda rápida de estudiantes por número
            $db->students->createIndex(['student_number' => 1]);
            
            // Índice para búsqueda rápida de asistencias por número de alumno
            $db->attendance->createIndex(['student_number' => 1]);
            
            // Índice para búsqueda por fecha
            $db->attendance->createIndex(['timestamp' => 1]);
            
        } catch (Exception $e) {
            // Los índices pueden ya existir, ignorar error
        }
    }
}
