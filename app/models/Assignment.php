<?php
require_once __DIR__ . '/../config/Database.php';

class Assignment {
    private $db;
    protected $table = "assignments";

    public function __construct() {
        $this->db = new Database();
    }

    public function create($data) {
        $course_id = $this->db->escape($data['course_id']);
        $title = $this->db->escape($data['title']);
        $desc = $this->db->escape($data['description']);
        $type = $this->db->escape($data['type']);
        $due_date = $this->db->escape($data['due_date']);
        $points = $this->db->escape($data['total_points']);
        $weight = $this->db->escape($data['weight_percentage']);

        $query = "INSERT INTO {$this->table} 
                 (course_id, title, description, type, due_date, total_points, weight_percentage) 
                 VALUES ($course_id, '$title', '$desc', '$type', '$due_date', $points, $weight)";
        
        return $this->db->query($query);
    }

    public function getPendingForStudent($student_id, $course_id = null) {
        $student_id = $this->db->escape($student_id);
        $query = "SELECT a.*, c.title as course_title 
                 FROM {$this->table} a 
                 JOIN courses c ON a.course_id = c.course_id 
                 JOIN enrollments e ON c.course_id = e.course_id 
                 WHERE e.student_id = {$student_id} 
                 AND a.due_date > NOW() 
                 AND NOT EXISTS (
                     SELECT 1 FROM submissions s 
                     WHERE s.assignment_id = a.assignment_id 
                     AND s.student_id = {$student_id}
                 )";
        
        if ($course_id) {
            $course_id = $this->db->escape($course_id);
            $query .= " AND c.course_id = {$course_id}";
        }
        
        $query .= " ORDER BY a.due_date ASC";
        
        return $this->db->query($query);
    }
}
