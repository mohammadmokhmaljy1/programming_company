<h3>
    <?php
        switch($page_name)
        {
            case "index.php": echo "الصفحة الرئيسية"; break;
            case "login.php": echo "تسجيل الدخول"; break;
            case "clients.php": echo "العملاء"; break;
            case "employees.php": echo "الموظفين"; break;
            case "logs.php": echo "العمليات"; break;
            case "payments.php": echo "المدفوعات"; break;
            case "projects.php": echo "المشاريع"; break;
            case "reciepts.php": echo "الإيصالات"; break;
            case "reports.php": echo "التقارير"; break;
            case "tasks.php": echo "مهامك"; break;
            case "projectTasks.php": echo "مهام أحد المشاريع"; break;
        }
    ?>
</h3>

<?php
    if(!isset($_SESSION['employee_id']))
        header("location: login");
?>