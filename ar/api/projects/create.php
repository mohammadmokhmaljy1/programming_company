<?php
require_once '../auth/session_check.php';
require '../config/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

// تحقق من الحقول المطلوبة
$required_fields = ['title', 'begin_date', 'price', 'client_id'];

foreach ($required_fields as $field) {
    if (!isset($data[$field]) || empty($data[$field])) {
        http_response_code(400);
        echo json_encode(['error' => "Missing or invalid field: $field"]);
        exit;
    }
}

// تحقق أن السعر رقم موجب
if (!is_numeric($data['price']) || $data['price'] < 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Price must be a non-negative number']);
    exit;
}

// employee_manager_id قد يكون NULL أو رقم صحيح
$employee_manager_id = isset($data['employee_manager_id']) && is_numeric($data['employee_manager_id']) ? $data['employee_manager_id'] : null;

try {
    $stmt = $pdo->prepare("INSERT INTO project 
        (title, description, begin_date, price, notes, is_finish, is_canceled, employee_manager_id, client_id) 
        VALUES (:title, :description, :begin_date, :price, :notes, 0, 0, :employee_manager_id, :client_id)");

    $stmt->execute([
        'title' => $data['title'],
        'description' => $data['description'] ?? null,
        'begin_date' => $data['begin_date'],
        'price' => $data['price'],
        'notes' => $data['notes'] ?? null,
        'employee_manager_id' => $employee_manager_id,
        'client_id' => $data['client_id']
    ]);

    $newProjectId = $pdo->lastInsertId();

    include_once "../logs/new_log.php";
    insertLog('create new project: ' . $data['title']);

    http_response_code(201);
    echo json_encode(['message' => 'Project created successfully', 'project_id' => $newProjectId]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}