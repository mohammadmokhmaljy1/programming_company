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
                    <input type="search" name="search" id="task-search" placeholder="بحث...">
                    <button type="submit"><i class="fa-solid fa-search"></i></button>
                </div>
                <?php include_once "includes/profile.php"; ?>
            </header>

            <button type="button" id="open-add-task" class="btn" style="margin-top: 20px;">إضافة مهمة جديدة</button>

            <div class="table">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>الموظف</th>
                            <th>المشروع</th>
                            <th>تاريخ البدء</th>
                            <th>تاريخ الإنهاء</th>
                            <th>النوع</th>
                            <th>مستوى الأهمية</th>
                            <th>الوصف</th>
                            <th>إنهاء</th>
                            <th>تعديل</th>
                            <th>حذف</th>
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
            <p>هل أنت متأكد من إنهاء هذه المهمة؟</p>
            <div class="modal-actions">
                <button id="confirmTaskFinish" class="btn">نعم إنهاء</button>
                <button id="cancelTaskFinish" class="btn">رجوع</button>
            </div>
        </div>
    </div>

    <div id="deleteTaskModal" class="modal hidden">
        <div class="modal-content">
            <p>هل أنت متأكد من حذف هذه المهمة؟</p>
            <div class="modal-actions">
                <button id="confirmTaskDelete" class="btn danger">حذف</button>
                <button id="cancelTaskDelete" class="btn">رجوع</button>
            </div>
        </div>
    </div>

    <!-- Edit Task Modal -->
    <div id="editTaskModal" class="modal hidden">
        <div class="modal-content">
            <h3>تعديل بيانات المهمة</h3>
            <br>
            <form id="editTaskForm" class="f-cen f-col g24">
                <input type="hidden" id="edit-task-id" name="id">

                <div class="input-box">
                    <select name="employee_id" id="edit-employee-id" required>
                        <option hidden>-- اختر موظف --</option>
                        <!-- Fill dynamically -->
                    </select>
                    <label for="edit-employee-id">الموظف:</label>
                </div>

                <div class="input-box">
                    <input type="date" name="start_date" id="edit-start-date" required>
                    <label for="edit-start-date">تاريخ البدء:</label>
                </div>

                <div class="input-box">
                    <input type="text" name="type" id="edit-type" required>
                    <label for="edit-type">النوع:</label>
                </div>

                <div class="input-box">
                    <select name="level" id="edit-level" required>
                        <option hidden>-- اختر المستوى --</option>
                        <option value="low">منخفض</option>
                        <option value="medium">عادي</option>
                        <option value="high">مستعجل</option>
                    </select>
                    <label for="edit-level">مستوى الأهمية:</label>
                </div>

                <div class="input-box">
                    <select name="project_id" id="edit-project-id" required>
                        <option hidden>-- اختر المشروع --</option>
                        <!-- Fill dynamically -->
                    </select>
                    <label for="edit-project-id">المشروع:</label>
                </div>

                <div class="input-box">
                    <textarea name="notes" id="edit-notes" rows="3"></textarea>
                    <label for="edit-notes">الوصف:</label>
                </div>

                <div class="modal-actions w-100 f-bet">
                    <button type="submit" class="btn primary">حفظ</button>
                    <button type="button" id="cancelTaskEdit" class="btn danger">رجوع</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Add Task Modal -->
    <div id="addTaskModal" class="modal hidden">
        <div class="modal-content">
            <h2>إضافة مهمة جديدة</h2>
            <br>
            <form id="addTaskForm" class="f-evn g24" style="width: 560px;">
                <div class="input-box">
                    <select name="employee_id" id="add-employee-id" required>
                        <option hidden>-- اختر موظف --</option>
                        <!-- Fill dynamically -->
                    </select>
                    <label for="add-employee-id">الموظف:</label>
                </div>

                <div class="input-box">
                    <select name="project_id" id="add-project-id" required>
                        <option hidden>-- اختيار مشروع --</option>
                        <!-- Fill dynamically -->
                    </select>
                    <label for="add-project-id">المشروع:</label>
                </div>

                <div class="input-box">
                    <input type="date" name="start_date" id="add-start-date" required>
                    <label for="add-start-date">تاريخ البدء:</label>
                </div>

                <div class="input-box">
                    <input type="date" name="finish_date" id="add-finish-date" required disabled>
                    <label for="add-finish-date">تاريخ الإنهاء:</label>
                </div>

                <div class="input-box">
                    <input type="text" name="type" id="add-type" required>
                    <label for="add-type">النوع:</label>
                </div>

                <div class="input-box">
                    <select name="level" id="add-level" required>
                        <option hidden>-- اختر المستوى --</option>
                        <option value="low">منخفض</option>
                        <option value="medium">عادي</option>
                        <option value="high">مستعجل</option>
                    </select>
                    <label for="add-level">مستوى الأهمية:</label>
                </div>

                <div class="input-box w-100">
                    <textarea name="notes" id="add-notes" rows="3"></textarea>
                    <label for="add-notes">الملاحظات أو الوصف:</label>
                </div>

                <div class="modal-actions w-100">
                    <button type="submit" class="btn primary">إضافة المهمة</button>
                    <button type="button" id="cancelTaskAdd" class="btn">رجوع</button>
                </div>
            </form>
        </div>
    </div>

    <?php include_once "includes/messageModal.php"; ?>

    <script src="../scripts/layout.js"></script>
    <script src="../scripts/messageModel.js"></script>
    <script src="../scripts/tasksServices.js"></script>
</body>

</html>