<?php

require_once '../auth/session_check.php';
require_once '../config/db.php';

header('Content-Type: application/json');

$employee_id = (int)$_SESSION['employee_id'];
$employee_permission = $_SESSION['employee_permission'];

try {
    $sql = "
        SELECT
            task.id,
            task.employee_id,
            task.start_date,
            task.finish_date,
            task.notes,
            task.type,
            task.level,
            task.project_id,
            employee.name AS employee_name,
            project.title AS project_title
        FROM
            task
        JOIN employee ON task.employee_id = employee.id
        LEFT JOIN project ON task.project_id = project.id
    ";

    $params = [];

    switch ($employee_permission) {
        case 'staff':
            $sql .= " WHERE task.employee_id = :employee_id ";
            $params['employee_id'] = $employee_id;
            break;
        case 'manager':
            $sql .= " WHERE project.employee_manager_id = :employee_id ";
            $params['employee_id'] = $employee_id;
            break;
        case 'admin':
            $sql .= " WHERE task.finish_date IS NULL AND task.start_date >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH) ";
            break;
        default:
            $sql .= " WHERE 1 = 0 ";
            break;
    }

    $sql .= " ORDER BY task.finish_date IS NOT NULL ASC, task.start_date DESC ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $tasks = $stmt->fetchAll(PDO::FETCH_BOTH);

    http_response_code(200);
    echo json_encode(['tasks' => $tasks]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}