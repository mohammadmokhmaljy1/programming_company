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
    // التأكد أن المشروع غير ملغى أو منتهي
    $checkStmt = $pdo->prepare("SELECT is_finish, is_canceled FROM project WHERE id = :id");
    $checkStmt->execute(['id' => $data['id']]);
    $project = $checkStmt->fetch(PDO::FETCH_ASSOC);

    if (!$project) {
        http_response_code(404);
        echo json_encode(['error' => 'Project not found']);
        exit;
    }

    if ($project['is_canceled']) {
        http_response_code(400);
        echo json_encode(['error' => 'Project is already canceled']);
        exit;
    }

    if ($project['is_finish']) {
        http_response_code(400);
        echo json_encode(['error' => 'Cannot cancel a finished project']);
        exit;
    }

    // تنفيذ عملية الإلغاء
    $stmt = $pdo->prepare("UPDATE project SET is_canceled = 1 WHERE id = :id");
    $stmt->execute(['id' => $data['id']]);

    include_once "../logs/new_log.php";
    insertLog('cancel project: ' . $data['id']);

    http_response_code(200);
    echo json_encode(['message' => 'Project canceled successfully']);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}
