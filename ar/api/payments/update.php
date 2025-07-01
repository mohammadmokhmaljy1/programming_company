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

// التحقق من وجود بيانات JSON صالحة ومن وجود حقل 'id' المطلوب للتعديل
if (!$input || !isset($input['id']) || empty($input['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid or missing payment ID for update.']);
    exit;
}

// الحقول المطلوبة للتعديل (كل الحقول تقريباً مطلوبة ما عدا الملاحظات)
// بتقدر تشوف قاعدة البيانت يا حبيب!
$requiredFields = ['client_id', 'project_id', 'amount', 'payment_date', 'payment_method'];
foreach ($requiredFields as $field) {
    // نتحقق من وجود الحقل، وأن قيمته ليست فارغة إلا إذا كانت 0 (للمبالغ مثلاً)
    if (!isset($input[$field]) || (empty($input[$field]) && $input[$field] !== 0 && $input[$field] !== '0')) {
        http_response_code(400);
        echo json_encode(['error' => "Missing or empty required field: $field"]);
        exit;
    }
}

// تنظيف وتعيين المتغيرات
$id = (int) $input['id'];
$client_id = (int) $input['client_id'];
$project_id = (int) $input['project_id'];
$amount = (float) $input['amount'];
$payment_date = $input['payment_date'];
$payment_method = trim($input['payment_method']);
$notes = isset($input['notes']) ? trim($input['notes']) : null; // حقل اختياري

// التحقق من صحة تنسيق التاريخ
$date_pattern = '/^\d{4}-\d{2}-\d{2}$/';
if (!preg_match($date_pattern, $payment_date)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid date format for payment_date. Use YYYY-MM-DD']);
    exit;
}

// التحقق من أن المبلغ غير سالب
if ($amount < 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Amount cannot be negative.']);
    exit;
}

// التحقق من طريقة الدفع (ENUM)
$allowedPaymentMethods = ['cash', 'credit_card', 'bank_transfer'];
if (!in_array($payment_method, $allowedPaymentMethods)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid payment method. Allowed methods are: ' . implode(', ', $allowedPaymentMethods)]);
    exit;
}

try {
    // التحقق أولاً من وجود الدفعة المراد تعديلها
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM payments WHERE id = ?");
    $stmt->execute([$id]);
    if ($stmt->fetchColumn() == 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Payment not found.']);
        exit;
    }

    // التحقق من وجود العميل
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM client WHERE id = ?");
    $stmt->execute([$client_id]);
    if ($stmt->fetchColumn() == 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Client not found']);
        exit;
    }

    // التحقق من وجود المشروع
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM project WHERE id = ?");
    $stmt->execute([$project_id]);
    if ($stmt->fetchColumn() == 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Project not found']);
        exit;
    }

    // تحديث الدفعة في قاعدة البيانات
    $update = $pdo->prepare("
        UPDATE payments
        SET 
            client_id = :client_id,
            project_id = :project_id,
            amount = :amount,
            payment_date = :payment_date,
            payment_method = :payment_method,
            notes = :notes
        WHERE id = :id
    ");

    $update->execute([
        'client_id' => $client_id,
        'project_id' => $project_id,
        'amount' => $amount,
        'payment_date' => $payment_date,
        'payment_method' => $payment_method,
        'notes' => $notes,
        'id' => $id
    ]);

    include_once "../logs/new_log.php";
    insertLog('Updated payment with ID: ' . $id);

    http_response_code(200); // 200 OK
    echo json_encode(['message' => 'Payment updated successfully']);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}