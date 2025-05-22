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

// قراءة البيانات من جسم الطلب
$data = json_decode(file_get_contents("php://input"), true);

// التحقق من وجود الحقول الأساسية والضرورية
$required_fields = ['id', 'title', 'begin_date', 'price', 'employee_manager_id', 'client_id'];

foreach ($required_fields as $field) {
    if (!isset($data[$field]) || ($field !== 'employee_manager_id' && empty($data[$field]))) {
        http_response_code(400);
        echo json_encode(['error' => "Missing or invalid field: $field"]);
        exit;
    }
}

// تحقق من نوع البيانات
if (!is_numeric($data['id']) || !is_numeric($data['price']) || !is_numeric($data['employee_manager_id']) && $data['employee_manager_id'] !== null || !is_numeric($data['client_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid numeric fields']);
    exit;
}

// // قيم افتراضية للحالات إذا لم تُرسل
// $is_finish = isset($data['is_finish']) ? (int)$data['is_finish'] : 0;
// $is_canceled = isset($data['is_canceled']) ? (int)$data['is_canceled'] : 0;

try {
    $stmt = $pdo->prepare("UPDATE project SET 
        title = :title,
        description = :description,
        begin_date = :begin_date,
        price = :price,
        notes = :notes,
        employee_manager_id = :employee_manager_id,
        client_id = :client_id
        WHERE id = :id
    ");

    $stmt->execute([
        'title' => $data['title'],
        'description' => $data['description'] ?? null,
        'begin_date' => $data['begin_date'],
        'price' => $data['price'],
        'notes' => $data['notes'] ?? null,
        'employee_manager_id' => $data['employee_manager_id'],
        'client_id' => $data['client_id'],
        'id' => $data['id']
    ]);

    if ($stmt->rowCount() > 0) {
        include_once "../logs/new_log.php";
        insertLog('update data of project: ' . $data['title']);
        http_response_code(200);
        echo json_encode(['message' => 'Project updated successfully']);
        
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Project not found or no changes made']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}