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
    echo json_encode(['error' => 'Invalid or missing payment ID.']);
    exit;
}

$payment_id = (int) $input['id'];

try {
    // التحقق أولاً من وجود الدفعة قبل محاولة حذفها
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM payments WHERE id = ?");
    $stmt->execute([$payment_id]);
    if ($stmt->fetchColumn() == 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Payment not found.']);
        exit;
    }

    // حذف الدفعة
    $stmt = $pdo->prepare("DELETE FROM payments WHERE id = ?");
    $stmt->execute([$payment_id]);

    include_once "../logs/new_log.php";
    insertLog('Deleted payment with ID: ' . $payment_id);

    http_response_code(200);
    echo json_encode(['message' => 'Payment deleted successfully.']);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}