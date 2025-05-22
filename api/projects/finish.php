<?php
require_once '../auth/session_check.php';
require '../config/db.php';

header('Content-Type: application/json');

// السماح فقط بطلبات POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

// التحقق من وجود معرف المشروع
if (!isset($data['id']) || !is_numeric($data['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid or missing project ID']);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE project SET is_finish = 1 WHERE id = :id");
    $stmt->execute(['id' => $data['id']]);

    if ($stmt->rowCount() > 0) {
        include_once "../logs/new_log.php";
        insertLog('finish project: ' . $data['id']);
        http_response_code(200);
        echo json_encode(['message' => 'Project marked as finished']);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Project not found or already finished']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}
