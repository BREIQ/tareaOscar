<?php

namespace App\Models;

use App\Database\Connection;
use MongoDB\BSON\UTCDateTime;
use Exception;
use DateTime;

class Attendance
{
    private $collection;
    
    public function __construct()
    {
        $db = Connection::getDatabase();
        $this->collection = $db->attendance;
    }
    
    /**
     * Registrar asistencia
     */
    public function register(string $student_number): array
    {
        $now = new DateTime();
        $timestamp = new UTCDateTime($now);
        
        $result = $this->collection->insertOne([
            'student_number' => $student_number,
            'timestamp' => $timestamp
        ]);
        
        return [
            '_id' => (string)$result->getInsertedId(),
            'student_number' => $student_number,
            'timestamp' => $now->format('Y-m-d H:i:s')
        ];
    }
    
    /**
     * Obtener asistencias de un estudiante
     */
    public function getByStudentNumber(string $student_number): array
    {
        $records = $this->collection->find(
            ['student_number' => $student_number],
            ['sort' => ['timestamp' => -1]]
        );
        
        $result = [];
        foreach ($records as $record) {
            $data = $record->bsonSerialize();
            $data['timestamp'] = $data['timestamp']->toDateTime()->format('Y-m-d H:i:s');
            $result[] = $data;
        }
        
        return $result;
    }
    
    /**
     * Obtener asistencias por día
     */
    public function getByDay(string $date): array
    {
        $startOfDay = new DateTime($date . ' 00:00:00');
        $endOfDay = new DateTime($date . ' 23:59:59');
        
        $startTimestamp = new UTCDateTime($startOfDay);
        $endTimestamp = new UTCDateTime($endOfDay);
        
        $records = $this->collection->find(
            [
                'timestamp' => [
                    '$gte' => $startTimestamp,
                    '$lte' => $endTimestamp
                ]
            ],
            ['sort' => ['timestamp' => -1]]
        );
        
        return $this->formatRecords($records);
    }
    
    /**
     * Obtener asistencias por mes
     */
    public function getByMonth(int $year, int $month): array
    {
        $startOfMonth = new DateTime("$year-$month-01 00:00:00");
        $endOfMonth = new DateTime($startOfMonth->format('Y-m-t 23:59:59'));
        
        $startTimestamp = new UTCDateTime($startOfMonth);
        $endTimestamp = new UTCDateTime($endOfMonth);
        
        $records = $this->collection->find(
            [
                'timestamp' => [
                    '$gte' => $startTimestamp,
                    '$lte' => $endTimestamp
                ]
            ],
            ['sort' => ['timestamp' => -1]]
        );
        
        return $this->formatRecords($records);
    }
    
    /**
     * Obtener asistencias por año
     */
    public function getByYear(int $year): array
    {
        $startOfYear = new DateTime("$year-01-01 00:00:00");
        $endOfYear = new DateTime("$year-12-31 23:59:59");
        
        $startTimestamp = new UTCDateTime($startOfYear);
        $endTimestamp = new UTCDateTime($endOfYear);
        
        $records = $this->collection->find(
            [
                'timestamp' => [
                    '$gte' => $startTimestamp,
                    '$lte' => $endTimestamp
                ]
            ],
            ['sort' => ['timestamp' => -1]]
        );
        
        return $this->formatRecords($records);
    }
    
    /**
     * Obtener todas las asistencias
     */
    public function getAll(): array
    {
        $records = $this->collection->find([], ['sort' => ['timestamp' => -1]]);
        return $this->formatRecords($records);
    }
    
    /**
     * Formatear registros de asistencia
     */
    private function formatRecords($records): array
    {
        $result = [];
        foreach ($records as $record) {
            $data = $record->bsonSerialize();
            $data['timestamp'] = $data['timestamp']->toDateTime()->format('Y-m-d H:i:s');
            $result[] = $data;
        }
        
        return $result;
    }
}
