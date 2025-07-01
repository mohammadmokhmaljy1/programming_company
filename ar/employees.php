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
                <div class="search-box">
                    <input type="search" name="search" id="search" autofocus tabindex="1" placeholder="بحث...">
                    <button type="submit"><i class="fa-solid fa-search"></i></button>
                </div>
                <?php include_once "includes/profile.php"; ?>
            </header>

            <button type="button" id="open-add-model" class="btn" style="margin-top: 20px;">إضافة موظف جديد</button>

            <div class="table">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>الاسم</th>
                            <th>البريد</th>
                            <th>تاريخ التوظيف</th>
                            <th>الراتب</th>
                            <th>الصلاحيات</th>
                            <th>الهاتف</th>
                            <th>ملف الـCV</th>
                            <th>الخبرة والمهارة</th>
                            <th>آخر تسجيل دخول</th>
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

    <div id="deleteModal" class="modal hidden">
        <div class="modal-content">
            <p>هل أنت متأكد أن تريد حذف الموظف؟</p>
            <div class="modal-actions">
                <button id="confirmDelete" class="btn danger">حذف</button>
                <button id="cancelDelete" class="btn">تراجع</button>
            </div>
        </div>
    </div>

    <div id="editModal" class="modal hidden">
        <div class="modal-content">
            <h3>تعديل معلومات الموظف</h3>
            <br>
            <form id="editForm" class="f-cen f-col g24">
                <input type="hidden" name="id" id="edit-id">

                <div class="input-box">
                    <input type="text" name="name" id="edit-name" required>
                    <label for="edit-name">الاسم:</label>
                </div>

                <div class="input-box">
                    <input type="email" name="email" id="edit-email" required>
                    <label for="edit-email">البريد:</label>
                </div>

                <div class="input-box">
                    <input type="date" name="hiredate" id="edit-hiredate" required>
                    <label for="edit-hiredate">تاريخ التوظيف:</label>
                </div>

                <div class="input-box">
                    <input type="number" name="salary" id="edit-salary" required>
                    <label for="edit-salary">الراتب:</label>
                </div>

                <div class="input-box">
                    <select name="permission" id="edit-permission" required>
                        <option hidden>-- اختيار الصلاحية --</option>
                        <option value="admin">مدير عام</option>
                        <option value="manager">مدير تقني</option>
                        <option value="staff">موظف</option>
                        <option value="accounter">محاسب</option>
                    </select>
                    <label for="edit-permission">الصلاحية:</label>
                </div>

                <div class="input-box">
                    <input type="tel" name="phone" id="edit-phone" required>
                    <label for="edit-phone">الهاتف:</label>
                </div>

                <div class="input-box">
                    <input type="text" name="skill" id="edit-skill" required>
                    <label for="edit-skill">الخبرة:</label>
                </div>

                <div class="modal-actions w-100 f-bet">
                    <button type="submit" class="btn primary">حفظ</button>
                    <button type="button" id="cancelEdit" class="btn danger">رجوع</button>
                </div>
            </form>
        </div>
    </div>


    <?php include_once "includes/messageModal.php"; ?>

    <div id="addEmployeeModal" class="modal hidden">
        <div class="modal-content">
            <h2>إضافة موظف جديد</h2>
            <br>
            <form id="addForm" style="width: 540px;" class="f-evn g16" enctype="multipart/form-data">
                <div class="input-box w-100">
                    <input type="text" name="name" id="add-name" required tabindex="40">
                    <label for="add-name">اسم الموظف:</label>
                </div>
                <div class="input-box">
                    <input type="date" name="hiredate" id="add-hiredate" required tabindex="41">
                    <label for="add-hiredate">تاريخ التوظيف:</label>
                </div>
                <div class="input-box">
                    <input type="number" name="salary" id="add-salary" required tabindex="42">
                    <label for="add-salary">الراتب:</label>
                </div>
                <div class="input-box">
                    <select name="permission" id="add-permission" required tabindex="43">
                        <option value="" hidden>-- تحديد الصلاحية --</option>
                        <option value="admin">مدير عام</option>
                        <option value="manager">مدير تقني</option>
                        <option value="staff">موظف</option>
                        <option value="accounter">محاسب</option>
                    </select>
                    <!-- <label for="add-permission">Permission:</label> -->
                </div>
                <div class="input-box">
                    <input type="tel" name="phone" id="add-phone" required tabindex="44">
                    <label for="add-phone">الهاتف:</label>
                </div>
                <div class="input-box">
                    <input type="text" name="skill" id="add-skill" required tabindex="45">
                    <label for="add-skill">الخبرة:</label>
                </div>
                <div class="input-box">
                    <input type="email" name="email" id="add-email" required tabindex="46">
                    <label for="add-email">البريد:</label>
                </div>
                <div class="input-box">
                    <input type="password" name="password" id="add-password" required tabindex="47">
                    <label for="add-password">كلمة المرور:</label>
                </div>
                <div class="input-box">
                    <input type="file" name="cv_file" id="add-cv_file" accept=".pdf, .doc, .docx" tabindex="48">
                    <label for="add-cv_file">ملف الـCV:</label>
                </div>
                <div class="modal-actions w-100">
                    <button type="submit" tabindex="49" class="btn primary">إضافة</button>
                    <button type="button" id="cancelAdd" class="btn danger">رجوع</button>
                </div>
            </form>
        </div>
    </div>

    <script src="../scripts/layout.js"></script>
    <script src="../scripts/messageModel.js"></script>
    <script src="../scripts/employeesServices.js"></script>
</body>

</html>