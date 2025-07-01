<?php
require_once '../auth/session_check.php';
require_once '../config/db.php';

header('Content-Type: application/json');

try {
    $stmt = $pdo->query("SELECT `id`, `name`, `email`, `hiredate`, `salary`, `permission`, `phone`, `cv_file`, `skill`, `password_hash`, date(`last_login`) as 'last_login' FROM `employee`");
    $employees = $stmt->fetchAll(PDO::FETCH_ASSOC);

    http_response_code(200);
    echo json_encode(['employees' => $employees]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}