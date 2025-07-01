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
                <button type="button" id="open-add-payment" class="btn" style="margin-top: 20px;">إضافة دفعة جديدة</button>

                <select id="filter-year" class="input-box btn">
                    <option value="">كل السنين</option>
                </select>
            </div>

            <div class="table">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>العميل</th>
                            <th>المشروع</th>
                            <th>المبلغ</th>
                            <th>تاريخ الدفعة</th>
                            <th>طريقة الدفع</th>
                            <th>ملاحظات</th>
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

    <div id="deletePaymentModal" class="modal hidden">
        <div class="modal-content">
            <p>هل أنت متأكد من أنك تريد حذف الدفعة؟</p>
            <div class="modal-actions">
                <button id="confirmPaymentDelete" class="btn danger">حذف</button>
                <button id="cancelPaymentDelete" class="btn">تراجع</button>
            </div>
        </div>
    </div>

    <div id="editPaymentModal" class="modal hidden">
        <div class="modal-content">
            <h3>تعديل بيانات الدفعة</h3>
            <br>
            <form id="editPaymentForm" class="f-cen f-col g24">
                <input type="hidden" id="edit-payment-id" name="id">

                <div class="input-box">
                    <select name="client_id" id="edit-client-id" required>
                        <option hidden>-- اختيار عميل --</option>
                        </select>
                    <label for="edit-client-id">العميل:</label>
                </div>

                <div class="input-box">
                    <select name="project_id" id="edit-project-id" required>
                        <option hidden>-- اختيار مشروع --</option>
                        </select>
                    <label for="edit-project-id">المشروع:</label>
                </div>

                <div class="input-box">
                    <input type="number" step="0.01" name="amount" id="edit-amount" required>
                    <label for="edit-amount">المبلغ:</label>
                </div>

                <div class="input-box">
                    <input type="date" name="payment_date" id="edit-payment-date" required>
                    <label for="edit-payment-date">تاريخ الدفعة:</label>
                </div>

                <div class="input-box">
                    <select name="payment_method" id="edit-payment-method" required>
                        <option hidden>-- طريقة الدفع --</option>
                        <option value="cash">Cash</option>
                        <option value="credit_card">Credit Card</option>
                        <option value="bank_transfer">Bank Transfer</option>
                    </select>
                    <label for="edit-payment-method">طريقة الدفع:</label>
                </div>

                <div class="input-box w-100">
                    <textarea name="notes" id="edit-notes" rows="3"></textarea>
                    <label for="edit-notes">الملاحظات:</label>
                </div>

                <div class="modal-actions w-100 f-bet">
                    <button type="submit" class="btn primary">حفظ</button>
                    <button type="button" id="cancelPaymentEdit" class="btn danger">رجوع</button>
                </div>
            </form>
        </div>
    </div>

    <div id="addPaymentModal" class="modal hidden">
        <div class="modal-content">
            <h2>إضافة دفعة جديدة</h2>
            <br>
            <form id="addPaymentForm" class="f-evn g24" style="width: 560px;">
                <div class="input-box">
                    <select name="client_id" id="add-client-id" required>
                        <option hidden>-- اختيار عميل --</option>
                        </select>
                    <label for="add-client-id">العميل:</label>
                </div>

                <div class="input-box">
                    <select name="project_id" id="add-project-id" required>
                        <option hidden>-- اختيار مشروع --</option>
                        </select>
                    <label for="add-project-id">المشروع:</label>
                </div>

                <div class="input-box">
                    <input type="number" step="0.01" name="amount" id="add-amount" required>
                    <label for="add-amount">المبلغ:</label>
                </div>

                <div class="input-box">
                    <input type="date" name="payment_date" id="add-payment-date" required>
                    <label for="add-payment-date">تاريخ الدفعة:</label>
                </div>

                <div class="input-box">
                    <select name="payment_method" id="add-payment-method" required>
                        <option hidden>-- اختيار الطريقة --</option>
                        <option value="cash">Cash</option>
                        <option value="credit_card">Credit Card</option>
                        <option value="bank_transfer">Bank Transfer</option>
                    </select>
                    <label for="add-payment-method">طريقة الدفع:</label>
                </div>

                <div class="input-box w-100">
                    <textarea name="notes" id="add-notes" rows="3"></textarea>
                    <label for="add-notes">ملاحظات:</label>
                </div>

                <div class="modal-actions w-100">
                    <button type="submit" class="btn primary">إضافة الدفعة</button>
                    <button type="button" id="cancelPaymentAdd" class="btn">رجوع</button>
                </div>
            </form>
        </div>
    </div>

    <?php include_once "includes/messageModal.php"; ?>

    <script src="../scripts/layout.js"></script>
    <script src="../scripts/messageModel.js"></script>
    <script src="../scripts/paymentsServices.js"></script>
</body>

</html>