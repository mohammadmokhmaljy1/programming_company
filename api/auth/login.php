<?php
session_start();
// لتكون الصفحة بيانات JSON
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed. Use POST.']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid JSON input']);
    exit;
}

if (empty($input['email']) || empty($input['password'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Email and password are required']);
    exit;
}

$email = trim($input['email']);
$password = $input['password'];

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(response_code: 400);
    echo json_encode(['error' => 'Invalid email format']);
    exit;
}

require '../config/db.php';
include_once "../logs/new_log.php";

try {
    $stmt = $pdo->prepare("SELECT id, name, password_hash, permission, skill FROM employee WHERE email = :email LIMIT 1");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch(PDO::FETCH_BOTH);

    if (!$user) {
        http_response_code(response_code: 401);
        echo json_encode(['error' => 'Invalid email or email not found']);
        exit;
    }

    if (!password_verify($input['password'], $user['password_hash'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Invalid password!']);
        exit;
    }

    $_SESSION['employee_id'] = $user['id'];
    $_SESSION['employee_name'] = $user['name'];
    $_SESSION['employee_permission'] = $user['permission'];
    $_SESSION['employee_skill'] = $user['skill'];
    $_SESSION['logged_in_at'] = time();

    $updateStmt = $pdo->prepare("UPDATE employee SET last_login = NOW() WHERE id = :id");
    $updateStmt->execute(['id' => $user['id']]);

    
    insertLog("login by: " . $user['name']);

    
    http_response_code(200);
    echo json_encode([
        'message' => 'Login successful',
        'employee' => [
            'id' => $user['id'],
            'name' => $user['name'],
            'permission' => $user['permission'],
        ]
    ]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}