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
                    <input type="search" name="search" id="search" autofocus tabindex="1" placeholder="search...">
                    <button type="submit"><i class="fa-solid fa-search"></i></button>
                </div>
                <?php include_once "includes/profile.php"; ?>
            </header>

            <button type="button" id="open-add-model" class="btn" style="margin-top: 20px;">Add New Employee</button>

            <div class="table">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Name</th>
                            <th>E-mail</th>
                            <th>Hiredate</th>
                            <th>Salary</th>
                            <th>Position</th>
                            <th>Phone</th>
                            <th>CV File</th>
                            <th>Skill</th>
                            <th>Last Login</th>
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

    <div id="deleteModal" class="modal hidden">
        <div class="modal-content">
            <p>Are you sure you want to delete this employee?</p>
            <div class="modal-actions">
                <button id="confirmDelete" class="btn danger">Yes, Delete</button>
                <button id="cancelDelete" class="btn">Cancel</button>
            </div>
        </div>
    </div>

    <div id="editModal" class="modal hidden">
        <div class="modal-content">
            <h3>Update Employee Info</h3>
            <br>
            <form id="editForm" class="f-cen f-col g24">
                <input type="hidden" name="id" id="edit-id">

                <div class="input-box">
                    <input type="text" name="name" id="edit-name" required>
                    <label for="edit-name">Name:</label>
                </div>

                <div class="input-box">
                    <input type="email" name="email" id="edit-email" required>
                    <label for="edit-email">Email:</label>
                </div>

                <div class="input-box">
                    <input type="date" name="hiredate" id="edit-hiredate" required>
                    <label for="edit-hiredate">Hire Date:</label>
                </div>

                <div class="input-box">
                    <input type="number" name="salary" id="edit-salary" required>
                    <label for="edit-salary">Salary:</label>
                </div>

                <div class="input-box">
                    <select name="permission" id="edit-permission" required>
                        <option hidden>-- Select Permission --</option>
                        <option value="admin">Admin</option>
                        <option value="manager">Manager</option>
                        <option value="staff">Staff</option>
                    </select>
                    <label for="edit-permission">Permission:</label>
                </div>

                <div class="input-box">
                    <input type="tel" name="phone" id="edit-phone" required>
                    <label for="edit-phone">Phone:</label>
                </div>

                <div class="input-box">
                    <input type="text" name="skill" id="edit-skill" required>
                    <label for="edit-skill">Skill:</label>
                </div>

                <div class="modal-actions w-100 f-bet">
                    <button type="submit" class="btn primary">Save</button>
                    <button type="button" id="cancelEdit" class="btn danger">Cancel</button>
                </div>
            </form>
        </div>
    </div>


    <?php include_once "includes/messageModal.php"; ?>

    <div id="addEmployeeModal" class="modal hidden">
        <div class="modal-content">
            <h2>Add New Employee</h2>
            <br>
            <form id="addForm" style="width: 540px;" class="f-evn g16" enctype="multipart/form-data">
                <div class="input-box w-100">
                    <input type="text" name="name" id="add-name" required tabindex="40">
                    <label for="add-name">Name:</label>
                </div>
                <div class="input-box">
                    <input type="date" name="hiredate" id="add-hiredate" required tabindex="41">
                    <label for="add-hiredate">Hire Date:</label>
                </div>
                <div class="input-box">
                    <input type="number" name="salary" id="add-salary" required tabindex="42">
                    <label for="add-salary">Salary:</label>
                </div>
                <div class="input-box">
                    <select name="permission" id="add-permission" required tabindex="43">
                        <option value="" hidden>-- Select Permission --</option>
                        <option value="admin">Admin</option>
                        <option value="manager">Manager</option>
                        <option value="staff">Staff</option>
                    </select>
                    <!-- <label for="add-permission">Permission:</label> -->
                </div>
                <div class="input-box">
                    <input type="tel" name="phone" id="add-phone" required tabindex="44">
                    <label for="add-phone">Phone:</label>
                </div>
                <div class="input-box">
                    <input type="text" name="skill" id="add-skill" required tabindex="45">
                    <label for="add-skill">Skill:</label>
                </div>
                <div class="input-box">
                    <input type="email" name="email" id="add-email" required tabindex="46">
                    <label for="add-email">Email:</label>
                </div>
                <div class="input-box">
                    <input type="password" name="password" id="add-password" required tabindex="47">
                    <label for="add-password">Password:</label>
                </div>
                <div class="input-box">
                    <input type="file" name="cv_file" id="add-cv_file" accept=".pdf, .doc, .docx" tabindex="48">
                    <label for="add-cv_file">CV File:</label>
                </div>
                <div class="modal-actions w-100">
                    <button type="submit" tabindex="49" class="btn primary">Add Employee</button>
                    <button type="button" id="cancelAdd" class="btn danger">Cancel</button>
                </div>
            </form>
        </div>
    </div>



    <script src="scripts/layout.js"></script>
    <script src="scripts/messageModel.js"></script>
    <script src="scripts/employeesServices.js"></script>
</body>

</html>