<?php
require_once '../auth/session_check.php';
require_once '../config/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed. Use POST']);
    exit;
}

// قراءة بيانات JSON من جسم الطلب
$input = json_decode(file_get_contents('php://input'), true);

if (!$input || empty($input['task_id'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing task_id']);
    exit;
}

$task_id = (int)$input['task_id'];
$employee_id = (int)$_SESSION['employee_id'];

try {
    // التحقق من أن المهمة تخص هذا الموظف
    $stmt = $pdo->prepare("SELECT * FROM task WHERE id = :task_id AND employee_id = :employee_id");
    $stmt->execute([
        'task_id' => $task_id,
        'employee_id' => $employee_id
    ]);

    $task = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$task) {
        http_response_code(403); // غير مخوّل
        echo json_encode(['error' => 'Task not found or access denied']);
        exit;
    }

    // تحديد finish_date كتاريخ اليوم
    $finish_date = date('Y-m-d');

    $update = $pdo->prepare("UPDATE task SET finish_date = :finish_date WHERE id = :task_id");
    $update->execute([
        'finish_date' => $finish_date,
        'task_id' => $task_id
    ]);

    http_response_code(200);
    echo json_encode(['message' => 'Task marked as completed', 'finish_date' => $finish_date]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}