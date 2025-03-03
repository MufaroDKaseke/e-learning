<?php
require_once __DIR__ . '/app/models/Auth.php';

$auth = new Auth();
$auth->logout(); // This will destroy the session and redirect to login.php
