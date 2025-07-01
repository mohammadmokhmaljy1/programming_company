<?php
require_once '../auth/session_check.php';
require '../config/db.php';

header('Content-Type: application/json');

// التحقق من أن الطلب من نوع POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed. Use POST']);
    exit;
}

// قراءة بيانات JSON المرسلة في جسم الطلب
$input = json_decode(file_get_contents('php://input'), true);

// التحقق من وجود بيانات JSON صالحة ومن وجود حقل 'id'
if (!$input || !isset($input['id']) || empty($input['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid or missing receipt ID.']);
    exit;
}

$receipt_id = (int) $input['id'];

try {
    // التحقق أولاً من وجود الفاتورة قبل محاولة حذفها
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM receipt WHERE id = ?");
    $stmt->execute([$receipt_id]);
    if ($stmt->fetchColumn() == 0) {
        http_response_code(404);
        echo json_encode(['error' => 'receipt not found.']);
        exit;
    }

    // حذف الفاتورة
    $stmt = $pdo->prepare("DELETE FROM receipt WHERE id = ?");
    $stmt->execute([$receipt_id]);

    include_once "../logs/new_log.php";
    insertLog('Deleted receipt with ID: ' . $receipt_id);

    http_response_code(200);
    echo json_encode(['message' => 'receipt deleted successfully.']);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}