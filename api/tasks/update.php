<?php

require_once '../auth/session_check.php';
require '../config/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed. Only POST requests are accepted.']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

$required_fields = ['id', 'employee_id', 'start_date', 'type', 'level', 'project_id'];

foreach ($required_fields as $field) {

    if (!isset($data[$field]) || ($field !== 'notes' && $field !== 'finish_date' && empty($data[$field]))) {
        http_response_code(400);
        echo json_encode(['error' => "Missing or empty required field: " . $field]);
        exit;
    }
}

if (!is_numeric($data['id']) || !is_numeric($data['employee_id']) || !is_numeric($data['project_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid numeric fields for ID, employee_id, or project_id.']);
    exit;
}

$finish_date = isset($data['finish_date']) && !empty($data['finish_date']) ? $data['finish_date'] : null;
$notes = isset($data['notes']) && !empty($data['notes']) ? $data['notes'] : null;

try {
    $stmt = $pdo->prepare("UPDATE task SET 
        employee_id = :employee_id,
        start_date = :start_date,
        finish_date = :finish_date,
        notes = :notes,
        type = :type,
        level = :level,
        project_id = :project_id
        WHERE id = :id
    ");

    // تنفيذ الاستعلام
    $stmt->execute([
        'employee_id' => $data['employee_id'],
        'start_date' => $data['start_date'],
        'finish_date' => $finish_date,
        'notes' => $notes,
        'type' => $data['type'],
        'level' => $data['level'],
        'project_id' => $data['project_id'],
        'id' => $data['id']
    ]);

    if ($stmt->rowCount() > 0) {
        include_once "../logs/new_log.php";
        insertLog('update task with ID: ' . $data['id'] . ' for employee: ' . $data['employee_id']);

        http_response_code(200);
        echo json_encode(['message' => 'Task updated successfully.']);
    } else {
        http_response_code(404); // أو 200 إذا كنت تعتبر عدم وجود تغيير نجاحًا جزئيًا
        echo json_encode(['error' => 'Task not found or no changes were made.']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}