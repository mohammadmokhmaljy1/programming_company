<?php
require_once '../auth/session_check.php';
require_once '../config/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed. Use POST']);
    exit;
}

// قراءة JSON من جسم الطلب
$input = json_decode(file_get_contents('php://input'), true);

if (!$input || empty($input['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing or invalid report ID']);
    exit;
}

$report_id = (int)$input['id'];

try {
    // التأكد من وجود التقرير
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM report WHERE id = ?");
    $stmt->execute([$report_id]);

    if ($stmt->fetchColumn() == 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Report not found']);
        exit;
    }

    // حذف التقرير
    $delete = $pdo->prepare("DELETE FROM report WHERE id = ?");
    $delete->execute([$report_id]);

    include_once "../logs/new_log.php";
    insertLog("Deleted report with ID: $report_id");

    http_response_code(200);
    echo json_encode(['message' => 'Report deleted successfully']);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}