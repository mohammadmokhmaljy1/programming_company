<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['employee_id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized: Please login first']);
    exit;
}