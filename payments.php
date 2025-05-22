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
                <button type="button" id="open-add-payment" class="btn" style="margin-top: 20px;">Add New Payment</button>

                <select id="filter-year" class="input-box btn">
                    <option value="">All Years</option>
                </select>
            </div>

            <div class="table">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Client</th>
                            <th>Project</th>
                            <th>Amount</th>
                            <th>Payment Date</th>
                            <th>Method</th>
                            <th>Notes</th>
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

    <div id="deletePaymentModal" class="modal hidden">
        <div class="modal-content">
            <p>Are you sure you want to delete this payment?</p>
            <div class="modal-actions">
                <button id="confirmPaymentDelete" class="btn danger">Yes, Delete</button>
                <button id="cancelPaymentDelete" class="btn">Cancel</button>
            </div>
        </div>
    </div>

    <div id="editPaymentModal" class="modal hidden">
        <div class="modal-content">
            <h3>Update Payment</h3>
            <br>
            <form id="editPaymentForm" class="f-cen f-col g24">
                <input type="hidden" id="edit-payment-id" name="id">

                <div class="input-box">
                    <select name="client_id" id="edit-client-id" required>
                        <option hidden>-- Select Client --</option>
                        </select>
                    <label for="edit-client-id">Client:</label>
                </div>

                <div class="input-box">
                    <select name="project_id" id="edit-project-id" required>
                        <option hidden>-- Select Project --</option>
                        </select>
                    <label for="edit-project-id">Project:</label>
                </div>

                <div class="input-box">
                    <input type="number" step="0.01" name="amount" id="edit-amount" required>
                    <label for="edit-amount">Amount:</label>
                </div>

                <div class="input-box">
                    <input type="date" name="payment_date" id="edit-payment-date" required>
                    <label for="edit-payment-date">Payment Date:</label>
                </div>

                <div class="input-box">
                    <select name="payment_method" id="edit-payment-method" required>
                        <option hidden>-- Select Method --</option>
                        <option value="cash">Cash</option>
                        <option value="credit_card">Credit Card</option>
                        <option value="bank_transfer">Bank Transfer</option>
                    </select>
                    <label for="edit-payment-method">Payment Method:</label>
                </div>

                <div class="input-box w-100">
                    <textarea name="notes" id="edit-notes" rows="3"></textarea>
                    <label for="edit-notes">Notes:</label>
                </div>

                <div class="modal-actions w-100 f-bet">
                    <button type="submit" class="btn primary">Save</button>
                    <button type="button" id="cancelPaymentEdit" class="btn danger">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <div id="addPaymentModal" class="modal hidden">
        <div class="modal-content">
            <h2>Add New Payment</h2>
            <br>
            <form id="addPaymentForm" class="f-evn g24" style="width: 560px;">
                <div class="input-box">
                    <select name="client_id" id="add-client-id" required>
                        <option hidden>-- Select Client --</option>
                        </select>
                    <label for="add-client-id">Client:</label>
                </div>

                <div class="input-box">
                    <select name="project_id" id="add-project-id" required>
                        <option hidden>-- Select Project --</option>
                        </select>
                    <label for="add-project-id">Project:</label>
                </div>

                <div class="input-box">
                    <input type="number" step="0.01" name="amount" id="add-amount" required>
                    <label for="add-amount">Amount:</label>
                </div>

                <div class="input-box">
                    <input type="date" name="payment_date" id="add-payment-date" required>
                    <label for="add-payment-date">Payment Date:</label>
                </div>

                <div class="input-box">
                    <select name="payment_method" id="add-payment-method" required>
                        <option hidden>-- Select Method --</option>
                        <option value="cash">Cash</option>
                        <option value="credit_card">Credit Card</option>
                        <option value="bank_transfer">Bank Transfer</option>
                    </select>
                    <label for="add-payment-method">Payment Method:</label>
                </div>

                <div class="input-box w-100">
                    <textarea name="notes" id="add-notes" rows="3"></textarea>
                    <label for="add-notes">Notes:</label>
                </div>

                <div class="modal-actions w-100">
                    <button type="submit" class="btn primary">Add Payment</button>
                    <button type="button" id="cancelPaymentAdd" class="btn">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <?php include_once "includes/messageModal.php"; ?>

    <script src="scripts/layout.js"></script>
    <script src="scripts/messageModel.js"></script>
    <script src="scripts/paymentsServices.js"></script>
</body>

</html>