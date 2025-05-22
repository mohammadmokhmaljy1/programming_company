<?php
require_once '../auth/session_check.php';
require_once '../config/db.php';

header('Content-Type: application/json');

try {
    $stmt = $pdo->query("
        SELECT 
            logs.id,
            logs.employee_id,
            employee.name AS employee_name,
            logs.action,
            logs.action_time
        FROM logs
        LEFT JOIN employee ON logs.employee_id = employee.id
        ORDER BY logs.action_time DESC
    ");
    
    $logs = $stmt->fetchAll(PDO::FETCH_ASSOC);

    http_response_code(200);
    echo json_encode(['logs' => $logs]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}
