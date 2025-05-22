<h3>
    <?php
        switch($page_name)
        {
            case "index.php": echo "Home"; break;
            case "login.php": echo "Login"; break;
            case "clients.php": echo "Clients"; break;
            case "employees.php": echo "Employees"; break;
            case "logs.php": echo "Logs"; break;
            case "payments.php": echo "Payments"; break;
            case "projects.php": echo "Projects"; break;
            case "reciepts.php": echo "Reciepts"; break;
            case "reports.php": echo "Your Reports"; break;
            case "tasks.php": echo "Your Tasks"; break;
            case "projectTasks.php": echo "Tasks of a project"; break;
        }
    ?>
</h3>

<?php
    session_start();
    if(!isset($_SESSION['employee_id']))
        header("location: login");
?>