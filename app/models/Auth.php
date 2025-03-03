<?php
require_once __DIR__ . '/Session.php';
require_once __DIR__ . '/User.php';

class Auth {
    public $session;
    public $user;

    public function __construct() {
        $this->session = new Session();
        $this->user = new User();
    }

    public function login($email, $password) {
        $user = $this->user->authenticate($email, $password);
        
        if ($user) {
            $this->session->set('user_id', $user['user_id']);
            $this->session->set('user_role', $user['role']);
            $this->session->set('user_name', $user['first_name'] . ' ' . $user['last_name']);
            return true;
        }
        
        return false;
    }

    public function logout() {
        $this->session->destroy();
        header('Location: /e-learning/login.php');
        exit();
    }

    public function requireAuth() {
        if (!$this->session->isLoggedIn()) {
            header('Location: /e-learning/login.php');
            exit();
        }
    }

    public function requireRole($roles) {
        $this->requireAuth();
        
        $userRole = $this->session->getUserRole();
        if (!in_array($userRole, (array)$roles)) {
            header('Location: /e-learning/unauthorized.php');
            exit();
        }
    }

    public function getCurrentUser() {
        if ($this->session->isLoggedIn()) {
            return $this->user->getById($this->session->getUserId());
        }
        return null;
    }
}
