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

// التحقق من وجود بيانات JSON صالحة
if (!$input) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid or missing JSON body']);
    exit;
}

// التحقق من الحقول المطلوبة لإنشاء فاتورة
$requiredFields = [ 'receipt_no', 'employee_id', 'receipt_date', 'amount', 'receipt_note'];
foreach ($requiredFields as $field) {
    if (!isset($input[$field]) || (empty($input[$field]) && $input[$field] !== 0 && $input[$field] !== '0')) {
        http_response_code(400);
        echo json_encode(['error' => "Missing or empty required field: $field"]);
        exit;
    }
}

// تنظيف وتعيين المتغيرات
$receipt_no = (int) $input['receipt_no'];
$employee_id = (int) $input['employee_id'];
$receipt_date = $input['receipt_date'];
$amount = (float) $input['amount']; // يجب أن يكون رقماً عشرياً
$receipt_note = isset($input['receipt_note']) ? trim($input['receipt_note']) : null;

// التحقق من صحة تنسيق التاريخ
$date_pattern = '/^\d{4}-\d{2}-\d{2}$/';
if (!preg_match($date_pattern, $receipt_date)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid date format for receipt_date. Use YYYY-MM-DD']);
    exit;
}

// التحقق من أن المبلغ غير سالب
if ($amount < 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Amount cannot be negative.']);
    exit;
}

try {
    // التحقق من وجود الموظف
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM employee WHERE id = ?");
    $stmt->execute([$employee_id]);
    if ($stmt->fetchColumn() == 0) {
        http_response_code(404);
        echo json_encode(['error' => 'employee not found']);
        exit;
    }

    // إدخال الفاتورة الجديدة
    $insert = $pdo->prepare("
        INSERT INTO receipt (`receipt_no`, `employee_id`, `receipt_date`, `amount`, `receipt_note`)
        VALUES (:receipt_no, :employee_id, :receipt_date, :amount, :receipt_note)
    ");

    $insert->execute([
        'receipt_no' => $receipt_no,
        'employee_id' => $employee_id,
        'receipt_date' => $receipt_date,
        'amount' => $amount,
        'receipt_note' => $receipt_note
    ]);

    include_once "../logs/new_log.php";
    insertLog('Create new receipt for emloyee ID: ' . $employee_id . ' with amount: ' . $amount);

    http_response_code(201); // 201 Created
    echo json_encode(['message' => 'receipt created successfully']);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}