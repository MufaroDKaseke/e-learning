<?php
require_once __DIR__ . '/../config/Database.php';

class Course {
    public $db;
    public $table = "courses";

    public function __construct() {
        $this->db = new Database();
    }

    public function getAll() {
        $query = "SELECT c.*, u.first_name, u.last_name 
                 FROM {$this->table} c 
                 JOIN users u ON c.instructor_id = u.user_id";
        return $this->db->query($query);
    }

    public function create($data) {
        $code = $this->db->escape($data['course_code']);
        $title = $this->db->escape($data['title']);
        $desc = $this->db->escape($data['description']);
        $instructor = $this->db->escape($data['instructor_id']);
        $weeks = $this->db->escape($data['duration_weeks']);
        $hours = $this->db->escape($data['duration_hours']);

        $query = "INSERT INTO {$this->table} 
                 (course_code, title, description, instructor_id, duration_weeks, duration_hours) 
                 VALUES ('$code', '$title', '$desc', $instructor, $weeks, $hours)";
        
        return $this->db->query($query);
    }

    public function getEnrolledStudents($course_id) {
        $course_id = $this->db->escape($course_id);
        $query = "SELECT u.*, e.progress_percentage, e.status 
                 FROM users u 
                 JOIN enrollments e ON u.user_id = e.student_id 
                 WHERE e.course_id = {$course_id}";
        return $this->db->query($query);
    }
}
