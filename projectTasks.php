<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once "includes/head.php"; ?>
</head>

<body>
    <main class="p24">
        <?php include_once "includes/sider.php"; ?>

        <div class="main">
            <header class="f-bet r24 p16 bg-blur box-shadow">
                <?php include_once "includes/headerTitle.php"; ?>

                <?php include_once "includes/profile.php"; ?>
            </header>

            <div class="table">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Employee</th>
                            <th>Project</th>
                            <th>Start Date</th>
                            <th>Finish Date</th>
                            <th>Type</th>
                            <th>Level</th>
                            <th>Description</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        include_once "api/config/db.php";

                        if ($pdo && isset($_GET["p"]) && is_numeric($_GET["p"])) {
                            $project_id = (int) $_GET["p"];

                            try {
                                $stmt = $pdo->prepare("
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
                                    FROM task
                                    JOIN employee ON task.employee_id = employee.id
                                    LEFT JOIN project ON task.project_id = project.id
                                    WHERE task.project_id = :project_id
                                    ORDER BY task.start_date DESC
                                ");
                                $stmt->execute(['project_id' => $project_id]);

                                $index = 1;
                                while ($task = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                    echo "<tr>";
                                    echo "<td>{$index}</td>";
                                    echo "<td>" . htmlspecialchars($task['employee_name']) . "</td>";
                                    echo "<td>" . htmlspecialchars($task['project_title'] ?? 'N/A') . "</td>";
                                    echo "<td>" . htmlspecialchars($task['start_date']) . "</td>";
                                    echo "<td>" . ($task['finish_date'] ? htmlspecialchars($task['finish_date']) : '<span style=\"color: grey;\">Not Finished</span>') . "</td>";
                                    echo "<td>" . htmlspecialchars($task['type']) . "</td>";
                                    echo "<td>" . htmlspecialchars($task['level']) . "</td>";
                                    echo "<td title='" . htmlspecialchars($task['notes'] ?? 'No notes') . "'><u>Description</u></td>";
                                    echo "</tr>";
                                    $index++;
                                }

                                if ($index === 1) {
                                    echo "<tr><td colspan='8'>No tasks found for this project.</td></tr>";
                                }
                            } catch (PDOException $e) {
                                echo "<tr><td colspan='8'>Error: " . htmlspecialchars($e->getMessage()) . "</td></tr>";
                            }
                        } else {
                            echo "<tr><td colspan='8'>Invalid or missing project ID.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <script src="scripts/layout.js"></script>
</body>

</html>