<?php
require_once '../auth/session_check.php';
require_once '../config/db.php';

header('Content-Type: application/json');

// التحقق من أن الطلب POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed. Use POST']);
    exit;
}

// قراءة بيانات JSON من الطلب
$input = json_decode(file_get_contents("php://input"), true);

if (!$input) {
    http_response_code(400);
    echo json_encode(['error' => 'null request input!']);
    exit;
}

$name = trim($input['name'] ?? '');
$address = trim($input['address'] ?? '');
$email = trim($input['email'] ?? '');
$phone = trim($input['phone'] ?? '');
$first_visit_date = trim($input['first_visit_date'] ?? '');
$company_name = trim($input['company_name'] ?? null);

if (!$name || !$address || !$email || !$phone || !$first_visit_date) {
    http_response_code(400);
    echo json_encode(['error' => 'All Field are required']);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT id FROM client WHERE email = :email OR phone = :phone");
    $stmt->execute(['email' => $email, 'phone' => $phone]);
    if ($stmt->fetch()) {
        http_response_code(409);
        echo json_encode(['error' => 'Email address or phone already exist!']);
        exit;
    }

    // إدخال البيانات
    $insert = $pdo->prepare("
        INSERT INTO client (name, address, email, phone, first_visit_date, company_name)
        VALUES (:name, :address, :email, :phone, :first_visit_date, :company_name)
    ");

    $insert->execute([
        'name' => $name,
        'address' => $address,
        'email' => $email,
        'phone' => $phone,
        'first_visit_date' => $first_visit_date,
        'company_name' => $company_name,
    ]);


    include_once "../logs/new_log.php";
    insertLog("create new client: " . $name);

    http_response_code(201);
    echo json_encode(['message' => 'Client Added Successfully']);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server Error: ' . $e->getMessage()]);
}
