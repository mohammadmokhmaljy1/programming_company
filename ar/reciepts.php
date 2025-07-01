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
                <button type="button" id="open-add-reciept" class="btn" style="margin-top: 20px;">إضافة إيصال جديد</button>
            </div>

            <div class="table">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>رقم الإيصال</th>
                            <th>الموظف</th>
                            <th>المبلغ</th>
                            <th>تاريخ التقبيض</th>
                            <th>الملاحظات</th>
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

    <div id="deleteRecieptModal" class="modal hidden">
        <div class="modal-content">
            <p>هل أنت متأكد من حذف هذا الإيصال</p>
            <div class="modal-actions">
                <button id="confirmRecieptDelete" class="btn danger">حذف</button>
                <button id="cancelRecieptDelete" class="btn">تراجع</button>
            </div>
        </div>
    </div>

    <div id="editRecieptModal" class="modal hidden">
        <div class="modal-content">
            <h3>تعديل بيانات الإيصال</h3>
            <br>
            <form id="editRecieptForm" class="f-cen f-col g24">
                <input type="hidden" id="edit-reciept-id" name="id">

                <div class="input-box">
                    <input type="number" name="receipt_no" id="edit-reciept-no" required>
                    <label for="edit-reciept-no">رقم الإيصال:</label>
                </div>

                <div class="input-box">
                    <select name="employee_id" id="edit-employee-id" required>
                        <option hidden>-- اختر موظف --</option>
                        </select>
                    <label for="edit-employee-id">الموظف:</label>
                </div>

                <div class="input-box">
                    <input type="number" step="0.01" name="amount" id="edit-amount" required>
                    <label for="edit-amount">المبلغ:</label>
                </div>

                <div class="input-box">
                    <input type="date" name="receipt_date" id="edit-reciept-date" required>
                    <label for="edit-reciept-date">تاريخ الإيصال:</label>
                </div>

                <div class="input-box w-100">
                    <textarea name="receipt_note" id="edit-notes" rows="3"></textarea>
                    <label for="edit-notes">الملاحظات:</label>
                </div>

                <div class="modal-actions w-100 f-bet">
                    <button type="submit" class="btn primary">حفظ</button>
                    <button type="button" id="cancelRecieptEdit" class="btn danger">رجوع</button>
                </div>
            </form>
        </div>
    </div>

    <div id="addRecieptModal" class="modal hidden">
        <div class="modal-content">
            <h2>إضافة إيصال جديد</h2>
            <br>
            <form id="addRecieptForm" class="f-evn g24" style="width: 560px;">

                <div class="input-box">
                    <input type="number" name="receipt_no" id="add-reciept-no" required>
                    <label for="add-reciept-no">رقم الإيصال:</label>
                </div>

                <div class="input-box">
                    <select name="employee_id" id="add-employee-id" required>
                        <option hidden>-- اختر موظف --</option>
                        </select>
                    <label for="add-employee-id">الموظف:</label>
                </div>

                <div class="input-box">
                    <input type="number" step="1" name="amount" id="add-amount" required>
                    <label for="edit-amount">المبلغ:</label>
                </div>

                <div class="input-box">
                    <input type="date" name="receipt_date" id="add-reciept-date" required>
                    <label for="add-reciept-date">تاريخ الإيصال:</label>
                </div>

                <div class="input-box w-100">
                    <textarea name="receipt_note" id="add-receipt_note" rows="3"></textarea>
                    <label for="add-receipt_note">الملاحظات:</label>
                </div>

                <div class="modal-actions w-100">
                    <button type="submit" class="btn primary">إضافة الإيصال</button>
                    <button type="button" id="cancelRecieptAdd" class="btn">رجوع</button>
                </div>
            </form>
        </div>
    </div>

    <?php include_once "includes/messageModal.php"; ?>

    <script src="../scripts/layout.js"></script>
    <script src="../scripts/messageModel.js"></script>
    <script src="../scripts/recieptsServices.js"></script>

</body>

</html>