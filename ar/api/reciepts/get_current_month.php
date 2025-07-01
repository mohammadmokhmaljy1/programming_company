<?php
require_once '../auth/session_check.php';
require_once '../config/db.php';

header('Content-Type: application/json');

try {
    // جلب المقبوضات مع اسم العميل وعنوان المشروع
    $stmt = $pdo->query("
        SELECT
         `receipt`.`id`,
         `receipt`.`receipt_no`, 
         `receipt`.`employee_id`,
         `employee`.`name` AS 'employee_name',
         `receipt`.`receipt_date`, 
         `receipt`.`amount`, 
         `receipt`.`receipt_note` 
        FROM `receipt` INNER JOIN `employee` ON (`employee`.`id` = `receipt`.`employee_id`)
        WHERE month(`receipt_date`) = month(now())
        AND year(`receipt_date`) = year(now())
    ");
    
    $reciepts = $stmt->fetchAll(PDO::FETCH_ASSOC);

    http_response_code(200);
    echo json_encode(['reciepts' => $reciepts]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}