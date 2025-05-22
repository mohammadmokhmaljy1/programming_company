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
                <div class="search-box">
                    <input type="search" name="search" id="task-search" placeholder="search...">
                    <button type="submit"><i class="fa-solid fa-search"></i></button>
                </div>
                <?php include_once "includes/profile.php"; ?>
            </header>

            <button type="button" id="open-add-task" class="btn" style="margin-top: 20px;">Add New Task</button>

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
                            <th>Finish</th>
                            <th>Update</th>
                            <th>Delete</th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <div id="finishTaskModal" class="modal hidden">
        <div class="modal-content">
            <p>Are you sure you finish this task?</p>
            <div class="modal-actions">
                <button id="confirmTaskFinish" class="btn">Yes, Finish</button>
                <button id="cancelTaskFinish" class="btn">Back</button>
            </div>
        </div>
    </div>

    <div id="deleteTaskModal" class="modal hidden">
        <div class="modal-content">
            <p>Are you sure you want to delete this task?</p>
            <div class="modal-actions">
                <button id="confirmTaskDelete" class="btn danger">Yes, Delete</button>
                <button id="cancelTaskDelete" class="btn">Cancel</button>
            </div>
        </div>
    </div>

    <!-- Edit Task Modal -->
    <div id="editTaskModal" class="modal hidden">
        <div class="modal-content">
            <h3>Update Task</h3>
            <br>
            <form id="editTaskForm" class="f-cen f-col g24">
                <input type="hidden" id="edit-task-id" name="id">

                <div class="input-box">
                    <select name="employee_id" id="edit-employee-id" required>
                        <option hidden>-- Select Employee --</option>
                        <!-- Fill dynamically -->
                    </select>
                    <label for="edit-employee-id">Employee:</label>
                </div>

                <div class="input-box">
                    <input type="date" name="start_date" id="edit-start-date" required>
                    <label for="edit-start-date">Start Date:</label>
                </div>

                <div class="input-box">
                    <input type="text" name="type" id="edit-type" required>
                    <label for="edit-type">Type:</label>
                </div>

                <div class="input-box">
                    <select name="level" id="edit-level" required>
                        <option hidden>-- Select Level --</option>
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                    </select>
                    <label for="edit-level">Level:</label>
                </div>

                <div class="input-box">
                    <select name="project_id" id="edit-project-id" required>
                        <option hidden>-- Select Project --</option>
                        <!-- Fill dynamically -->
                    </select>
                    <label for="edit-project-id">Project:</label>
                </div>

                <div class="input-box">
                    <textarea name="notes" id="edit-notes" rows="3"></textarea>
                    <label for="edit-notes">Notes:</label>
                </div>

                <div class="modal-actions w-100 f-bet">
                    <button type="submit" class="btn primary">Save</button>
                    <button type="button" id="cancelTaskEdit" class="btn danger">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Task Modal -->
    <div id="addTaskModal" class="modal hidden">
        <div class="modal-content">
            <h2>Add New Task</h2>
            <br>
            <form id="addTaskForm" class="f-evn g24" style="width: 560px;">
                <div class="input-box">
                    <select name="employee_id" id="add-employee-id" required>
                        <option hidden>-- Select Employee --</option>
                        <!-- Fill dynamically -->
                    </select>
                    <label for="add-employee-id">Employee:</label>
                </div>

                <div class="input-box">
                    <select name="project_id" id="add-project-id" required>
                        <option hidden>-- Select Project --</option>
                        <!-- Fill dynamically -->
                    </select>
                    <label for="add-project-id">Project:</label>
                </div>

                <div class="input-box">
                    <input type="date" name="start_date" id="add-start-date" required>
                    <label for="add-start-date">Start Date:</label>
                </div>

                <div class="input-box">
                    <input type="date" name="finish_date" id="add-finish-date" required disabled>
                    <label for="add-finish-date">Finish Date:</label>
                </div>

                <div class="input-box">
                    <input type="text" name="type" id="add-type" required>
                    <label for="add-type">Type:</label>
                </div>

                <div class="input-box">
                    <select name="level" id="add-level" required>
                        <option hidden>-- Select Level --</option>
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                    </select>
                    <label for="add-level">Level:</label>
                </div>

                <div class="input-box w-100">
                    <textarea name="notes" id="add-notes" rows="3"></textarea>
                    <label for="add-notes">Notes:</label>
                </div>

                <div class="modal-actions w-100">
                    <button type="submit" class="btn primary">Add Task</button>
                    <button type="button" id="cancelTaskAdd" class="btn">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <?php include_once "includes/messageModal.php"; ?>

    <script src="scripts/layout.js"></script>
    <script src="scripts/messageModel.js"></script>
    <script src="scripts/tasksServices.js"></script>
</body>

</html>