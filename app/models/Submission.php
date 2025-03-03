<?php
require_once __DIR__ . '/../config/Database.php';

class Submission {
    public $db;
    public $table = "submissions";

    public function __construct() {
        $this->db = new Database();
    }

    public function submit($data) {
        $assignment_id = $this->db->escape($data['assignment_id']);
        $student_id = $this->db->escape($data['student_id']);
        $file_path = $this->db->escape($data['file_path']);

        $query = "INSERT INTO {$this->table} 
                 (assignment_id, student_id, file_path, status) 
                 VALUES ($assignment_id, $student_id, '$file_path', 'submitted')";
        
        return $this->db->query($query);
    }

    public function grade($submission_id, $grade_data) {
        $submission_id = $this->db->escape($submission_id);
        $grade = $this->db->escape($grade_data['grade_value']);
        $feedback = $this->db->escape($grade_data['feedback']);
        $grader = $this->db->escape($grade_data['graded_by']);

        // Update submission status
        $this->db->query("UPDATE {$this->table} SET status = 'graded' WHERE submission_id = {$submission_id}");

        // Insert grade
        $query = "INSERT INTO grades (submission_id, grade_value, feedback, graded_by) 
                 VALUES ($submission_id, $grade, '$feedback', $grader)";
        
        return $this->db->query($query);
    }

    public function getSubmissionsByStudent($student_id) {
        $student_id = $this->db->escape($student_id);
        $query = "SELECT s.*, a.title as assignment_title, c.title as course_title, 
                        g.grade_value, g.feedback, g.graded_at 
                 FROM {$this->table} s 
                 JOIN assignments a ON s.assignment_id = a.assignment_id 
                 JOIN courses c ON a.course_id = c.course_id 
                 LEFT JOIN grades g ON s.submission_id = g.submission_id 
                 WHERE s.student_id = {$student_id} 
                 ORDER BY s.submission_date DESC";
        
        return $this->db->query($query);
    }
}
