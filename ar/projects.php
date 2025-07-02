<!DOCTYPE html>
<html lang="en">

<head>
    <?php include_once "includes/head.php"; ?>
</head>

<body class="ar">
    <main class="p24">
        <?php include_once "includes/sider.php"; ?>

        <div class="main">
            <header class="f-bet r24 p16 bg-blur box-shadow">
                <?php include_once "includes/headerTitle.php"; ?>
                <?php include_once "includes/profile.php"; ?>
            </header>

            <div class="f-bet" style="margin-top: 20px;">
                <button type="button" id="open-add-model" class="btn">إضافة مشروع جديد</button>
                <select id="filter-year" class="input-box btn">
                    <option value="">كل السنين</option>
                </select>
            </div>

            <div class="table">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>العنوان</th>
                            <th>الوصف</th>
                            <th>تاريخ البداية</th>
                            <th>تاريخ الإنهاء</th>
                            <th>السعر</th>
                            <th>العميل</th>
                            <th>المدير</th>
                            <th>المهام</th>
                            <th>تعديل</th>
                            <th>الحالة || العمليات</th>
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
            <p>هل أنت متأكد من إنهاء هذا المشروع؟</p>
            <div class="modal-actions">
                <button id="confirmFinish" class="btn success">إنهاء</button>
                <button id="cancelFinish" class="btn">رجوع</button>
            </div>
        </div>
    </div>

    <div id="cancelModal" class="modal hidden">
        <div class="modal-content">
            <p>هل أنت متأكد من إلغاء هذا المشروع؟</p>
            <div class="modal-actions">
                <button id="confirmCancel" class="btn danger">نعم إلغاء</button>
                <button id="cancelCancel" class="btn">رجوع</button>
            </div>
        </div>
    </div>

    <div id="editModal" class="modal hidden">
        <div class="modal-content">
            <h3>تعديل بيانات المشروع</h3>
            <br>
            <form id="editForm" style="width: 560px;" class="f-evn g24">
                <input type="hidden" name="id" id="edit-id">

                <div class="input-box">
                    <input type="text" name="title" id="edit-title" required>
                    <label for="edit-title">العنوان:</label>
                </div>

                <div class="input-box">
                    <input type="number" name="price" id="edit-price" step="0.01" required>
                    <label for="edit-price">السعر:</label>
                </div>

                <div class="input-box">
                    <input type="date" name="begin_date" id="edit-begin-date" required>
                    <label for="edit-begin-date">تاريخ البداية:</label>
                </div>

                <div class="input-box">
                    <input type="date" name="end_date" id="edit-end-date" disabled>
                    <label for="edit-end-date">تاريخ الإنهاء:</label>
                </div>

                <div class="input-box">
                    <textarea name="description" id="edit-description" rows="3"></textarea>
                    <label for="edit-description">الوصف:</label>
                </div>

                <div class="input-box">
                    <textarea name="notes" id="edit-notes" rows="3"></textarea>
                    <label for="edit-notes">الملاحظات:</label>
                </div>

                <div class="input-box">
                    <select name="employee_manager_id" id="edit-manager" required>
                        
                    </select>
                    <label for="edit-manager">المدير:</label>
                </div>

                <div class="input-box">
                    <select name="client_id" id="edit-client" required>

                    </select>
                    <label for="edit-client">العميل:</label>
                </div>

                <div class="modal-actions w-100 f-bet">
                    <button type="submit" class="btn primary">حفظ</button>
                    <button type="button" id="cancelEdit" class="btn danger">رجوع</button>
                </div>
            </form>
        </div>
    </div>

    <div id="addProjectModal" class="modal hidden">
        <div class="modal-content" >
            <h2>إضافة مشروع جديد</h2>
            <form id="addForm" style="width: 560px;" class="f-evn g24">
                <div class="input-box">
                    <input type="text" name="title" id="add-title" required>
                    <label for="add-title">العنوان:</label>
                </div>

                <div class="input-box">
                    <input type="number" name="price" id="add-price" step="0.01" required>
                    <label for="add-price">السعر:</label>
                </div>

                <div class="input-box">
                    <input type="date" name="begin_date" id="add-begin-date" required>
                    <label for="add-begin-date">تاريخ البداية:</label>
                </div>

                <div class="input-box">
                    <input type="date" name="end_date" id="add-end-date" disabled>
                    <label for="add-end-date">تاريخ الإنهاء:</label>
                </div>

                <div class="input-box">
                    <textarea name="description" id="add-description" rows="3"></textarea>
                    <label for="add-description">الوصف:</label>
                </div>

                <div class="input-box">
                    <textarea name="notes" id="add-notes" rows="3"></textarea>
                    <label for="add-notes">الملاحظات:</label>
                </div>

                <div class="input-box">
                    <select name="employee_manager_id" id="add-manager" required>
                        <option selected hidden>اختر مدير: </option>
                    </select>
                    <!-- <label for="add-manager">Manager:</label> -->
                </div>

                <div class="input-box">
                    <select name="client_id" id="add-client" required>
                        <option selected hidden>اختر عميل: </option>
                    </select>
                    <!-- <label for="add-client">Client:</label> -->
                </div>

                <div class="modal-actions w-100">
                    <button type="submit" class="btn primary">إضافة المشروع</button>
                    <button type="button" id="cancelAdd" class="btn danger">رجوع</button>
                </div>
            </form>
        </div>
    </div>

    <?php include_once "includes/messageModal.php"; ?>

    <script src="../scripts/layout.js"></script>
    <script src="../scripts/messageModel.js"></script>
    <script src="../scripts/projectsServices.js"></script>
</body>

</html>