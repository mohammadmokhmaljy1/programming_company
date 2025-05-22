<?php
require_once '../auth/session_check.php';
require '../config/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed. Use POST']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!$input || !isset($input['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required parameter: id']);
    exit;
}

$id = intval($input['id']);
if ($id <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid employee ID']);
    exit;
}

$fields = ['name', 'email', 'hiredate', 'salary', 'permission', 'phone', 'skill'];
$updates = [];
$params = ['id' => $id];

foreach ($fields as $field) {
    if (isset($input[$field])) {
        $updates[] = "$field = :$field";
        $params[$field] = $input[$field];
    }
}

if (empty($updates)) {
    http_response_code(400);
    echo json_encode(['error' => 'No valid fields to update']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM employee WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $employee = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$employee) {
        http_response_code(404);
        echo json_encode(['error' => 'Employee not found']);
        exit;
    }

    if (isset($input['phone']) && $input['phone'] !== $employee['phone']) {
        $phoneCheck = $pdo->prepare("SELECT id FROM employee WHERE phone = :phone AND id != :id");
        $phoneCheck->execute(['phone' => $input['phone'], 'id' => $id]);
        if ($phoneCheck->fetch()) {
            http_response_code(409);
            echo json_encode(['error' => 'Phone number already exists for another employee']);
            exit;
        }
    }

    $sql = "UPDATE employee SET " . implode(', ', $updates) . " WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    include_once "../logs/new_log.php";
    insertLog('update data of employee: ' . $input['id']);

    http_response_code(200);
    echo json_encode(['message' => 'Employee updated successfully']);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}