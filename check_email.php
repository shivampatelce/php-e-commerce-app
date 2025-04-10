<?php
header('Content-Type: application/json');

require_once './services/DatabaseConnection.php';
require_once './services/User.php';

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['email']) || empty($data['email'])) {
    echo json_encode(['exists' => false, 'error' => 'Email is required']);
    exit;
}

$email = $data['email'];

$user = new User();

$emailExists = $user->check_email_exists($email);

echo json_encode(['exists' => $emailExists]);
