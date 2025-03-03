<?php
require_once __DIR__ . '/../config/Database.php';

class User {
    private $db;
    protected $table = "users";
    
    public $user_id;
    public $email;
    public $first_name;
    public $last_name;
    public $role;

    public function __construct() {
        $this->db = new Database();
    }

    public function getById($id) {
        $id = $this->db->escape($id);
        $query = "SELECT * FROM {$this->table} WHERE user_id = {$id}";
        $result = $this->db->query($query);
        return mysqli_fetch_assoc($result);
    }

    public function create($data) {
        $email = $this->db->escape($data['email']);
        $password = password_hash($data['password'], PASSWORD_DEFAULT);
        $first_name = $this->db->escape($data['first_name']);
        $last_name = $this->db->escape($data['last_name']);
        $role = $this->db->escape($data['role']);

        $query = "INSERT INTO {$this->table} (email, password_hash, first_name, last_name, role) 
                 VALUES ('$email', '$password', '$first_name', '$last_name', '$role')";
        
        return $this->db->query($query);
    }

    public function authenticate($email, $password) {
        $this->db->connect();
        $email = $this->db->escape($email);
        $query = "SELECT * FROM {$this->table} WHERE email = '$email' LIMIT 1";
        $result = $this->db->query($query);
        
        if ($result && $result->num_rows > 0) {
            $user = mysqli_fetch_assoc($result);
            if (password_verify($password, $user['password_hash'])) {
                return $user;
            }
        }
        return false;
    }
}
