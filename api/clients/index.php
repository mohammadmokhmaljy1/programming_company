<?php
require_once '../auth/session_check.php';
require_once '../config/db.php';

header('Content-Type: application/json');

try {
    $stmt = $pdo->query("SELECT `id`, `name`, `address`, `email`, `phone`, `first_visit_date`, `company_name` FROM `client`");
    $clients = $stmt->fetchAll(PDO::FETCH_ASSOC);

    http_response_code(200);
    echo json_encode(['clients' => $clients]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}