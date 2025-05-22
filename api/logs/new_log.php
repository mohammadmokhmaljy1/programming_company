<?php
function insertLog($action) {
    require '../config/db.php';

    if (!isset($_SESSION['employee_id'])) {
        throw new Exception("Employee ID not found in session.");
    }

    $employeeId = $_SESSION['employee_id'];

    $stmt = $pdo->prepare("INSERT INTO logs (employee_id, action, action_time) VALUES (:employee_id, :action, NOW())");
    $stmt->execute([
        ':employee_id' => $employeeId,
        ':action' => $action
    ]);
}