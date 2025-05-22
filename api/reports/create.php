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

if (!$input) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid or missing JSON body']);
    exit;
}

$requiredFields = ['title', 'date', 'employee_id', 'project_id'];
foreach ($requiredFields as $field) {
    if (empty($input[$field])) {
        http_response_code(400);
        echo json_encode(['error' => "Missing required field: $field"]);
        exit;
    }
}

$title = trim($input['title']);
$date = $input['date'];
$employee_id = (int) $input['employee_id'];
$project_id = (int) $input['project_id'];
$description = isset($input['description']) ? trim($input['description']) : null;

$date_pattern = '/^\d{4}-\d{2}-\d{2}$/';
if (!preg_match($date_pattern, $date)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid date format. Use YYYY-MM-DD']);
    exit;
}

try {
    // تحقق من وجود الموظف
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM employee WHERE id = ?");
    $stmt->execute([$employee_id]);
    if ($stmt->fetchColumn() == 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Employee not found']);
        exit;
    }

    // تحقق من وجود المشروع
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM project WHERE id = ?");
    $stmt->execute([$project_id]);
    if ($stmt->fetchColumn() == 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Project not found']);
        exit;
    }

    // إدخال التقرير
    $insert = $pdo->prepare("
        INSERT INTO report (title, description, date, employee_id, project_id)
        VALUES (:title, :description, :date, :employee_id, :project_id)
    ");

    $insert->execute([
        'title' => $title,
        'description' => $description,
        'date' => $date,
        'employee_id' => $employee_id,
        'project_id' => $project_id
    ]);

    $newId = $pdo->lastInsertId();

    // جلب التقرير الجديد
    $stmt = $pdo->prepare("
        SELECT r.*, e.name AS employee_name, p.title AS project_title
        FROM report r
        JOIN employee e ON r.employee_id = e.id
        JOIN project p ON r.project_id = p.id
        WHERE r.id = :id
    ");
    $stmt->execute(['id' => $newId]);
    $report = $stmt->fetch(PDO::FETCH_ASSOC);

    // تسجيل في السجل
    include_once "../logs/new_log.php";
    insertLog('Create new report: ' . $title . ' for project: ' . $project_id);

    http_response_code(201);
    echo json_encode(['message' => 'Report created successfully', 'report' => $report]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}