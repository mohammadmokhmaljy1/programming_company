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

            <button type="button" id="open-add-model" class="btn" style="margin-top: 20px;">إضافة عميل جديد</button>

            <div class="table">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>الاسم</th>
                            <th>الشركة</th>
                            <th>العنوان</th>
                            <th>البريد</th>
                            <th>الهاتف</th>
                            <th>أول زيارة</th>
                            <th>التعديل</th>
                            <th>الحذف</th>
                        </tr>
                    </thead>

                    <tbody>
                        
                    </tbody>
                </table>
            </div>
        </div>
    </main>

    <!-- Modal الحذف -->
    <div id="deleteModal" class="modal hidden">
        <div class="modal-content">
            <p>هل أنت متأكد أنك تريد حذف هذا العميل؟</p>
            <div class="modal-actions">
                <button id="confirmDelete" class="btn danger">حذف</button>
                <button id="cancelDelete" class="btn">تراجع</button>
            </div>
        </div>
    </div>

    <!-- Modal التعديل -->
    <div id="editModal" class="modal hidden">
        <div class="modal-content">
            <h3>تعديل معلومات العميل</h3>
            <br>
            <form id="editForm" class="f-cen f-col g24">
                <input type="hidden" name="id" id="edit-id">

                <div class="input-box">
                    <input type="text" name="name" id="edit-name" required>
                    <label for="edit-name">الاسم:</label>
                </div>

                <div class="input-box">
                    <textarea name="address" id="edit-address" rows="3" required></textarea>
                    <label for="edit-address">العنوان:</label>
                </div>

                <div class="input-box">
                    <input type="email" name="email" id="edit-email" required>
                    <label for="edit-email">البريد:</label>
                </div>

                <div class="input-box">
                    <input type="tel" name="phone" id="edit-phone" required>
                    <label for="edit-phone">الهاتف:</label>
                </div>

                <div class="input-box">
                    <input type="date" name="first_visit_date" id="edit-first-visit-date" required>
                    <label for="edit-first-visit-date">تاريخ أول زيارة:</label>
                </div>

                <div class="input-box">
                    <input type="text" name="company_name" id="edit-company-name" required>
                    <label for="edit-company-name">اسم الشركة:</label>
                </div>

                <div class="modal-actions w-100 f-bet">
                    <button type="submit" class="btn primary">حفظ</button>
                    <button type="button" id="cancelEdit" class="btn danger">رجوع</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal الإضافة -->
    <div id="addClientModal" class="modal hidden">
        <div class="modal-content">
            <h2>إضافة عميل جديد</h2>
            <br>
            <form id="addForm" class="f-cen f-col g24">
                <div class="input-box">
                    <input type="text" name="name" id="add-name" required tabindex="40">
                    <label for="add-name">الاسم:</label>
                </div>

                <div class="input-box w-100">
                    <textarea name="address" id="add-address" rows="3" required tabindex="41"></textarea>
                    <label for="add-address">العنوان:</label>
                </div>

                <div class="input-box">
                    <input type="email" name="email" id="add-email" required tabindex="42">
                    <label for="add-email">البريد:</label>
                </div>

                <div class="input-box">
                    <input type="tel" name="phone" id="add-phone" required tabindex="43">
                    <label for="add-phone">الهاتف:</label>
                </div>

                <div class="input-box">
                    <input type="date" name="first_visit_date" id="add-first-visit-date" required tabindex="44">
                    <label for="add-first-visit-date">تاريخ أول زيارة:</label>
                </div>

                <div class="input-box">
                    <input type="text" name="company_name" id="add-company-name" required tabindex="45">
                    <label for="add-company-name">اسم الشركة:</label>
                </div>

                <div class="modal-actions w-100">
                    <button type="submit" tabindex="46" class="btn primary">إضافة العميل</button>
                    <button type="button" id="cancelAdd" class="btn danger">رجوع</button>
                </div>
            </form>
        </div>
    </div>

    <?php include_once "includes/messageModal.php"; ?>

    <script src="../scripts/layout.js"></script>
    <script src="../scripts/messageModel.js"></script>
    <script src="../scripts/clientsServices.js"></script>
</body>

</html>