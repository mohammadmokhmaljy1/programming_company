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

            <button type="button" id="open-add-report" class="btn" style="margin-top: 20px;">Add New Report</button>

            <div class="table">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Date</th>
                            <th>Employee</th>
                            <th>Project</th>
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

    <div id="deleteReportModal" class="modal hidden">
        <div class="modal-content">
            <p>Are you sure you want to delete this report?</p>
            <div class="modal-actions">
                <button id="confirmReportDelete" class="btn danger">Yes, Delete</button>
                <button id="cancelReportDelete" class="btn">Cancel</button>
            </div>
        </div>
    </div>

    <div id="editReportModal" class="modal hidden">
        <div class="modal-content">
            <h3>Update Report</h3>
            <br>
            <form id="editReportForm" class="f-cen f-col g24">
                <input type="hidden" id="edit-report-id" name="id">

                <div class="input-box">
                    <input type="text" name="title" id="edit-title" required>
                    <label for="edit-title">Title:</label>
                </div>

                <div class="input-box">
                    <input type="date" name="date" id="edit-date" required>
                    <label for="edit-date">Date:</label>
                </div>

                <div class="input-box">
                    <select name="employee_id" id="edit-employee-id" required>
                        <option hidden>-- Select Employee --</option>
                        <!-- Fill dynamically -->
                    </select>
                    <label for="edit-employee-id">Employee:</label>
                </div>

                <div class="input-box">
                    <select name="project_id" id="edit-project-id" required>
                        <option hidden>-- Select Project --</option>
                        <!-- Fill dynamically -->
                    </select>
                    <label for="edit-project-id">Project:</label>
                </div>

                <div class="input-box w-100">
                    <textarea name="description" id="edit-description" rows="3"></textarea>
                    <label for="edit-description">Description:</label>
                </div>

                <div class="modal-actions w-100 f-bet">
                    <button type="submit" class="btn primary">Save</button>
                    <button type="button" id="cancelReportEdit" class="btn danger">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Report Modal -->
    <div id="addReportModal" class="modal hidden">
        <div class="modal-content">
            <h2>Add New Report</h2>
            <br>
            <form id="addReportForm" class="f-evn g24" style="width: 560px;">
                <div class="input-box">
                    <input type="text" name="title" id="add-title" required>
                    <label for="add-title">Title:</label>
                </div>

                <div class="input-box">
                    <input type="date" name="date" id="add-date" required>
                    <label for="add-date">Date:</label>
                </div>

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

                <div class="input-box w-100">
                    <textarea name="description" id="add-description" rows="3"></textarea>
                    <label for="add-description">Description:</label>
                </div>

                <div class="modal-actions w-100">
                    <button type="submit" class="btn primary">Add Report</button>
                    <button type="button" id="cancelReportAdd" class="btn">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <?php include_once "includes/messageModal.php"; ?>

    <script src="scripts/layout.js"></script>
    <script src="scripts/messageModel.js"></script>
    <script src="scripts/reportsServices.js"></script>
</body>

</html>