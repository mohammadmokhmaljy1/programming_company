<?php
require_once '../auth/session_check.php';
require '../config/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed. Use POST']);
    exit;
}

// قراءة JSON من جسم الطلب
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid or missing JSON body']);
    exit;
}

// تحقق من الحقول المطلوبة
$requiredFields = ['employee_id', 'start_date', 'type', 'level', 'project_id'];
foreach ($requiredFields as $field) {
    if (empty($input[$field])) {
        http_response_code(400);
        echo json_encode(['error' => "Missing required field: $field"]);
        exit;
    }
}

// جلب البيانات
$employee_id = (int) $input['employee_id'];
$start_date = $input['start_date'];
$type = trim($input['type']);
$level = $input['level'];
$project_id = (int) $input['project_id'];
$notes = isset($input['notes']) ? trim($input['notes']) : null;

// تحقق من تنسيق التاريخ
$date_pattern = '/^\d{4}-\d{2}-\d{2}$/';
if (!preg_match($date_pattern, $start_date)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid date format. Use YYYY-MM-DD']);
    exit;
}

// تحقق من مستوى الأولوية
$validLevels = ['low', 'medium', 'high'];
if (!in_array($level, $validLevels)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid level value']);
    exit;
}

try {
    // تحقق من وجود الموظف والمشروع
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM employee WHERE id = ?");
    $stmt->execute([$employee_id]);
    if ($stmt->fetchColumn() == 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Employee not found']);
        exit;
    }

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM project WHERE id = ?");
    $stmt->execute([$project_id]);
    if ($stmt->fetchColumn() == 0) {
        http_response_code(404);
        echo json_encode(['error' => 'Project not found']);
        exit;
    }

    // إدخال المهمة
    $insert = $pdo->prepare("INSERT INTO task (employee_id, start_date, type, level, project_id, notes)
                             VALUES (:employee_id, :start_date, :type, :level, :project_id, :notes)");

    $insert->execute([
        'employee_id' => $employee_id,
        'start_date' => $start_date,
        'type' => $type,
        'level' => $level,
        'project_id' => $project_id,
        'notes' => $notes
    ]);

    $newId = $pdo->lastInsertId();

    // جلب المهمة الجديدة
    $stmt = $pdo->prepare("
        SELECT t.*, e.name AS employee_name, p.title AS project_title
        FROM task t
        JOIN employee e ON t.employee_id = e.id
        JOIN project p ON t.project_id = p.id
        WHERE t.id = :id
    ");
    $stmt->execute(['id' => $newId]);
    $task = $stmt->fetch(PDO::FETCH_ASSOC);

    include_once "../logs/new_log.php";
    insertLog( 'create new task: ' . $input["type"] . ' on project: ' . $input["project_id"]);

    http_response_code(201);
    echo json_encode(['message' => 'Task created successfully', 'task' => $task]);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}