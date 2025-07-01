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

            <div class="f-bet" style="margin-top: 20px;">
                <button type="button" id="open-add-model" class="btn">Add New Project</button>
                <select id="filter-year" class="input-box btn">
                    <option value="">All Years</option>
                </select>
            </div>

            <div class="table">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Title</th>
                            <th>Description</th>
                            <th>Begin Date</th>
                            <th>End Date</th>
                            <th>Price</th>
                            <th>Client</th>
                            <th>Manager</th>
                            <th>Tasks</th>
                            <th>Update</th>
                            <th>Status || Actions </th>
                        </tr>
                    </thead>
                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <div id="finishModal" class="modal hidden">
        <div class="modal-content">
            <p>Are you sure you want to finish this project?</p>
            <div class="modal-actions">
                <button id="confirmFinish" class="btn success">Yes, Finish</button>
                <button id="cancelFinish" class="btn">Cancel</button>
            </div>
        </div>
    </div>

    <div id="cancelModal" class="modal hidden">
        <div class="modal-content">
            <p>Are you sure you want to cancel this project?</p>
            <div class="modal-actions">
                <button id="confirmCancel" class="btn danger">Yes, Cancel</button>
                <button id="cancelCancel" class="btn">Don't Cancel</button>
            </div>
        </div>
    </div>

    <div id="editModal" class="modal hidden">
        <div class="modal-content">
            <h3>Update Project</h3>
            <br>
            <form id="editForm" style="width: 560px;" class="f-evn g24">
                <input type="hidden" name="id" id="edit-id">

                <div class="input-box">
                    <input type="text" name="title" id="edit-title" required>
                    <label for="edit-title">Title:</label>
                </div>

                <div class="input-box">
                    <input type="number" name="price" id="edit-price" step="0.01" required>
                    <label for="edit-price">Price:</label>
                </div>

                <div class="input-box">
                    <input type="date" name="begin_date" id="edit-begin-date" required>
                    <label for="edit-begin-date">Begin Date:</label>
                </div>

                <div class="input-box">
                    <input type="date" name="end_date" id="edit-end-date" disabled>
                    <label for="edit-end-date">End Date:</label>
                </div>

                <div class="input-box">
                    <textarea name="description" id="edit-description" rows="3"></textarea>
                    <label for="edit-description">Description:</label>
                </div>

                <div class="input-box">
                    <textarea name="notes" id="edit-notes" rows="3"></textarea>
                    <label for="edit-notes">Notes:</label>
                </div>

                <div class="input-box">
                    <select name="employee_manager_id" id="edit-manager" required>
                        
                    </select>
                    <label for="edit-manager">Manager:</label>
                </div>

                <div class="input-box">
                    <select name="client_id" id="edit-client" required>

                    </select>
                    <label for="edit-client">Client:</label>
                </div>

                <div class="modal-actions w-100 f-bet">
                    <button type="submit" class="btn primary">Save</button>
                    <button type="button" id="cancelEdit" class="btn danger">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <div id="addProjectModal" class="modal hidden">
        <div class="modal-content" >
            <h2>Add New Project</h2>
            <form id="addForm" style="width: 560px;" class="f-evn g24">
                <div class="input-box">
                    <input type="text" name="title" id="add-title" required>
                    <label for="add-title">Title:</label>
                </div>

                <div class="input-box">
                    <input type="number" name="price" id="add-price" step="0.01" required>
                    <label for="add-price">Price:</label>
                </div>

                <div class="input-box">
                    <input type="date" name="begin_date" id="add-begin-date" required>
                    <label for="add-begin-date">Begin Date:</label>
                </div>

                <div class="input-box">
                    <input type="date" name="end_date" id="add-end-date" disabled>
                    <label for="add-end-date">End Date:</label>
                </div>

                <div class="input-box">
                    <textarea name="description" id="add-description" rows="3"></textarea>
                    <label for="add-description">Description:</label>
                </div>

                <div class="input-box">
                    <textarea name="notes" id="add-notes" rows="3"></textarea>
                    <label for="add-notes">Notes:</label>
                </div>

                <div class="input-box">
                    <select name="employee_manager_id" id="add-manager" required>
                        <option selected hidden>select manager: </option>
                    </select>
                    <!-- <label for="add-manager">Manager:</label> -->
                </div>

                <div class="input-box">
                    <select name="client_id" id="add-client" required>
                        <option selected hidden>select client: </option>
                    </select>
                    <!-- <label for="add-client">Client:</label> -->
                </div>

                <div class="modal-actions w-100">
                    <button type="submit" class="btn primary">Add Project</button>
                    <button type="button" id="cancelAdd" class="btn danger">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <?php include_once "includes/messageModal.php"; ?>

    <script src="scripts/layout.js"></script>
    <script src="scripts/messageModel.js"></script>
    <script src="scripts/projectsServices.js"></script>
</body>

</html>