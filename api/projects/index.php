<?php
require_once '../auth/session_check.php';
require_once '../config/db.php';

header('Content-Type: application/json');

try {
    $year = isset($_GET['year']) && is_numeric($_GET['year']) ? intval($_GET['year']) : null;

    $sql = "SELECT 
                p.id, 
                p.title, 
                p.description, 
                p.begin_date, 
                p.end_date, 
                p.price, 
                p.notes, 
                p.is_finish, 
                p.is_canceled, 
                p.employee_manager_id, 
                p.client_id,
                e.name AS manager_name,
                c.name AS client_name
            FROM project p
            LEFT JOIN employee e ON p.employee_manager_id = e.id
            INNER JOIN client c ON p.client_id = c.id
            ORDER BY is_finish, is_canceled";

    $params = [];

    if ($year) {
        $sql .= " WHERE YEAR(p.begin_date) = :year";
        $params['year'] = $year;
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);

    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);

    http_response_code(200);
    echo json_encode(['projects' => $projects]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}