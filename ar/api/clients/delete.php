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

if (!$input || !isset($input['id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing required parameter: id']);
    exit;
}

$id = intval($input['id']);

if ($id <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid id']);
    exit;
}

try {
    // Check if the client exists
    $stmt = $pdo->prepare("SELECT id FROM client WHERE id = :id");
    $stmt->execute(['id' => $id]);
    $client = $stmt->fetch();

    if (!$client) {
        http_response_code(404);
        echo json_encode(['error' => 'Client not found']);
        exit;
    }

    // Delete the client
    $deleteStmt = $pdo->prepare("DELETE FROM client WHERE id = :id");
    $deleteStmt->execute(['id' => $id]);

    include_once "../logs/new_log.php";
    insertLog(action: `delete client: $id`);

    http_response_code(200);
    echo json_encode(['message' => 'Client deleted successfully']);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}