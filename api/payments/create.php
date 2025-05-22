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

// التحقق من الحقول المطلوبة لإنشاء دفعة
$requiredFields = ['client_id', 'project_id', 'amount', 'payment_date', 'payment_method'];
foreach ($requiredFields as $field) {
    if (!isset($input[$field]) || (empty($input[$field]) && $input[$field] !== 0 && $input[$field] !== '0')) {
        http_response_code(400);
        echo json_encode(['error' => "Missing or empty required field: $field"]);
        exit;
    }
}

// تنظيف وتعيين المتغيرات
$client_id = (int) $input['client_id'];
$project_id = (int) $input['project_id'];
$amount = (float) $input['amount']; // يجب أن يكون رقماً عشرياً
$payment_date = $input['payment_date'];
$payment_method = trim($input['payment_method']);
$notes = isset($input['notes']) ? trim($input['notes']) : null;

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

    // إدخال الدفعة الجديدة
    $insert = $pdo->prepare("
        INSERT INTO payments (client_id, project_id, amount, payment_date, payment_method, notes)
        VALUES (:client_id, :project_id, :amount, :payment_date, :payment_method, :notes)
    ");

    $insert->execute([
        'client_id' => $client_id,
        'project_id' => $project_id,
        'amount' => $amount,
        'payment_date' => $payment_date,
        'payment_method' => $payment_method,
        'notes' => $notes
    ]);

    $newId = $pdo->lastInsertId();

    // جلب تفاصيل الدفعة الجديدة (مع اسم العميل وعنوان المشروع)
    $stmt = $pdo->prepare("
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
        WHERE p.id = :id
    ");
    $stmt->execute(['id' => $newId]);
    $payment = $stmt->fetch(PDO::FETCH_ASSOC);

    include_once "../logs/new_log.php";
    insertLog('Create new payment for client ID: ' . $client_id . ' for project ID: ' . $project_id . ' with amount: ' . $amount);

    http_response_code(201); // 201 Created
    echo json_encode(['message' => 'Payment created successfully', 'payment' => $payment]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}