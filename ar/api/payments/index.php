<?php
require_once '../auth/session_check.php';
require_once '../config/db.php';

header('Content-Type: application/json');

try {
    // جلب المدفوعات مع اسم العميل وعنوان المشروع
    $stmt = $pdo->query("
        SELECT 
            p.id,
            p.client_id,
            c.name AS client_name,
            p.project_id,
            proj.title AS project_title,
            p.amount,
            p.payment_date,
            p.payment_method,
            p.notes
        FROM payments AS p
        LEFT JOIN client AS c ON p.client_id = c.id
        LEFT JOIN project AS proj ON p.project_id = proj.id
        ORDER BY p.payment_date DESC
    ");
    
    $payments = $stmt->fetchAll(PDO::FETCH_ASSOC);

    http_response_code(200);
    echo json_encode(['payments' => $payments]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}