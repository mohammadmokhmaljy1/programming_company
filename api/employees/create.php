<?php
require_once '../auth/session_check.php';
require '../config/db.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method Not Allowed. Use POST']);
    exit;
}

// تحقق من الحقول المطلوبة في $_POST
$requiredFields = ['name', 'hiredate', 'salary', 'permission', 'phone', 'skill', 'email', 'password'];
foreach ($requiredFields as $field) {
    if (empty($_POST[$field])) {
        http_response_code(400);
        echo json_encode(['error' => "Missing required field: $field"]);
        exit;
    }
}

// الحصول على البيانات من $_POST
$name = trim($_POST['name']);
$hiredate = $_POST['hiredate'];
$salary = $_POST['salary'];
$permission = $_POST['permission'];
$phone = trim($_POST['phone']);
$skill = trim($_POST['skill']);
$email = trim($_POST['email']);
$password = $_POST['password'];

// التحقق من صلاحية قيمة permission
$validPermissions = ['admin', 'manager', 'staff'];
if (!in_array($permission, $validPermissions)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid permission value']);
    exit;
}

// التحقق من صيغة التاريخ
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $hiredate)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid hiredate format. Use YYYY-MM-DD']);
    exit;
}

// التحقق من الراتب
if (!is_numeric($salary) || $salary < 0) {
    http_response_code(400);
    echo json_encode(['error' => 'Salary must be a positive number']);
    exit;
}

// معالجة رفع الملف (CV)
if (isset($_FILES['cv_file']) && $_FILES['cv_file']['error'] === UPLOAD_ERR_OK) {
    $file_name = $_FILES['cv_file']['name'];
    $file_tmp_name = $_FILES['cv_file']['tmp_name'];
    $file_size = $_FILES['cv_file']['size'];
    $file_type = $_FILES['cv_file']['type'];

    // التحقق من نوع الملف
    $allowed_types = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
    if (!in_array($file_type, $allowed_types)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid file type. Only PDF and DOC/DOCX files are allowed.']);
        exit;
    }

    // التحقق من حجم الملف
    $max_file_size = 5 * 1024 * 1024; // 5MB
    if ($file_size > $max_file_size) {
        http_response_code(400);
        echo json_encode(['error' => 'File size exceeds the maximum limit of 5MB.']);
        exit;
    }

    // تنظيف اسم الملف
    $file_name = preg_replace("/[^a-zA-Z0-9._-]/", "", $file_name);
    $file_name = uniqid() . '_' . $file_name; // إضافة بادئة فريدة

    // مسار حفظ الملف
    $upload_dir = 'uploads/';
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);
    }

    $file_path = $upload_dir . $file_name;

    // نقل الملف للمجلد النهائي
    if (!move_uploaded_file($file_tmp_name, $file_path)) {
        http_response_code(500);
        echo json_encode(['error' => 'Failed to upload CV file.']);
        exit;
    }

    $cv_file_path = $file_path;
} else {
    $cv_file_path = ''; // لا يوجد ملف مرفق
}

try {
    // التحقق من وجود رقم الهاتف مسبقًا
    $stmt = $pdo->prepare("SELECT id FROM employee WHERE phone = :phone");
    $stmt->execute(['phone' => $phone]);
    if ($stmt->fetch()) {
        http_response_code(response_code: 409);
        echo json_encode(['error' => 'Phone number already exists']);
        exit;
    }

    // تشفير كلمة السر
    $password_hashed = password_hash($password, PASSWORD_BCRYPT);

    // إدخال بيانات الموظف
    $insertStmt = $pdo->prepare("INSERT INTO employee (name, hiredate, salary, permission, phone, skill, email, password_hash, cv_file) VALUES (:name, :hiredate, :salary, :permission, :phone, :skill, :email, :password_hash, :cv_file)");
    $insertStmt->execute([
        'name' => $name,
        'hiredate' => $hiredate,
        'salary' => $salary,
        'permission' => $permission,
        'phone' => $phone,
        'skill' => $skill,
        'email' => $email,
        'password_hash' => $password_hashed,
        'cv_file' => $cv_file_path
    ]);

    // جلب بيانات الموظف الجديد
    $newId = $pdo->lastInsertId();
    $stmt = $pdo->prepare("SELECT id, name, hiredate, salary, permission, phone, skill, last_login FROM employee WHERE id = :id");
    $stmt->execute(['id' => $newId]);
    $newEmployee = $stmt->fetch(PDO::FETCH_ASSOC);

    include_once "../logs/new_log.php";
    insertLog( 'create new employee: ' . $name);

    http_response_code(201);
    echo json_encode(['message' => 'Employee created successfully', 'employee' => $newEmployee]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Server error: ' . $e->getMessage()]);
}