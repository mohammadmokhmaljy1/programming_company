<?php

require_once '../auth/session_check.php';
require '../config/db.php';

header('Content-Type: application/json');

$employee_id = (int)$_SESSION['employee_id'];
$employee_permission = $_SESSION['employee_permission'];

try {
    $sql = "
        SELECT
            report.id,
            report.employee_id,
            report.date,
            report.title,
            report.description,
            report.project_id,
            employee.name AS employee_name,
            project.title AS project_title
        FROM
            report
        JOIN employee ON report.employee_id = employee.id
        LEFT JOIN project ON report.project_id = project.id
        WHERE 1 = 1
    ";

    $params = [];

    switch ($_SESSION['employee_permission']) {
        case 'staff':
            $sql .= " AND report.employee_id = :employee_id ";
            $params['employee_id'] = $_SESSION['employee_id'];
            break;

        case 'manager':
            $sql .= " AND (project.employee_manager_id = :employee_id OR report.employee_id = :employee_id) ";
            $params['employee_id'] = $_SESSION['employee_id'];
            break;

        case 'admin':
            $sql .= " AND (report.date >= DATE_SUB(CURDATE(), INTERVAL 3 MONTH) OR report.employee_id = :employee_id) ";
            $params['employee_id'] = $_SESSION['employee_id'];
            break;

        default:
            $sql .= " AND 1 = 0 ";
            break;
    }

    $sql .= " ORDER BY report.date DESC ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $reports = $stmt->fetchAll(PDO::FETCH_ASSOC);

    http_response_code(200);
    echo json_encode(['reports' => $reports]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}