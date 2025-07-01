<?php
require_once '../auth/session_check.php';
require_once '../config/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed. Use POST']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

$requiredFields = ['id', 'name', 'address', 'email', 'phone', 'first_visit_date'];
foreach ($requiredFields as $field) {
    if (empty($input[$field])) {
        http_response_code(400);
        echo json_encode(['error' => "Missing required field: $field"]);
        exit;
    }
}

$id = intval($input['id']);
$name = trim($input['name']);
$address = trim($input['address']);
$email = trim($input['email']);
$phone = trim($input['phone']);
$firstVisitDate = $input['first_visit_date'];
$companyName = isset($input['company_name']) ? trim($input['company_name']) : null;

try {
    $stmt = $pdo->prepare("SELECT id FROM client WHERE id = :id");
    $stmt->execute(['id' => $id]);
    if (!$stmt->fetch()) {
        http_response_code(404);
        echo json_encode(['error' => 'Client not found']);
        exit;
    }

    $updateStmt = $pdo->prepare("
        UPDATE client SET 
            name = :name,
            address = :address,
            email = :email,
            phone = :phone,
            first_visit_date = :first_visit_date,
            company_name = :company_name
        WHERE id = :id
    ");

    $updateStmt->execute([
        'id' => $id,
        'name' => $name,
        'address' => $address,
        'email' => $email,
        'phone' => $phone,
        'first_visit_date' => $firstVisitDate,
        'company_name' => $companyName
    ]);

    include_once "../logs/new_log.php";
    insertLog(action: `update data of client: $name`);

    echo json_encode(['message' => 'Client updated successfully']);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}