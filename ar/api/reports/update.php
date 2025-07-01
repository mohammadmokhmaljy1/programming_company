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

$required_fields = ['id', 'employee_id', 'date', 'title', 'project_id'];

foreach ($required_fields as $field) {
    if (!isset($data[$field]) || empty($data[$field])) {
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

$description = isset($data['description']) && !empty($data['description']) ? $data['description'] : null;

try {
    $stmt = $pdo->prepare("UPDATE report SET 
        employee_id = :employee_id,
        date = :date,
        title = :title,
        description = :description,
        project_id = :project_id
        WHERE id = :id
    ");

    $stmt->execute([
        'employee_id' => $data['employee_id'],
        'date' => $data['date'],
        'title' => $data['title'],
        'description' => $description,
        'project_id' => $data['project_id'],
        'id' => $data['id']
    ]);

    if ($stmt->rowCount() > 0) {
        include_once "../logs/new_log.php";
        insertLog('Updated report with ID: ' . $data['id'] . ' for employee: ' . $data['employee_id']);

        http_response_code(200);
        echo json_encode(['message' => 'Report updated successfully.']);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Report not found or no changes were made.']);
    }
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}