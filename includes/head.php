<!-- meta links -->
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="keywords" content="">
<meta name="author" content="">
<meta charset="UTF-8">

<!-- files links -->
<!-- <script src="https://kit.fontawesome.com/5cd3c2f996.js" crossorigin="anonymous"></script> -->
<link rel="stylesheet" href="fontawesome/css/all.min.css">
<link rel="stylesheet" href="styles/layouts.css">
<link rel="stylesheet" href="styles/tables.css">
<link rel="stylesheet" href="styles/forms.css">
<link rel="stylesheet" href="styles/sider.css">
<link rel="stylesheet" href="styles/main.css">
<link rel="icon" href="images/logo.jpg">

<title>
    <?php
    $page_name = basename($_SERVER["PHP_SELF"]);
    switch($page_name)
    {
        case "index.php": echo "Home - SAVVY"; break;
        case "login.php": echo "Login - SAVVY"; break;
        case "clients.php": echo "Clients Management - SAVVY"; break;
        case "employees.php": echo "Employees Management - SAVVY"; break;
        case "logs.php": echo "Logs Management - SAVVY"; break;
        case "payments.php": echo "Payments Management - SAVVY"; break;
        case "projects.php": echo "Projects Management - SAVVY"; break;
        case "reciepts.php": echo "Reciepts Management - SAVVY"; break;
        case "reports.php": echo "Reports Management - SAVVY"; break;
        case "tasks.php": echo "Tasks Management - SAVVY"; break;
        case "projectTasks.php": echo "Tasks of a project - SAVVY"; break;
    }

    session_start();
    ?>
</title>