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
                <button type="button" id="open-add-reciept" class="btn" style="margin-top: 20px;">Add New reciept</button>
            </div>

            <div class="table">
                <table>
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Reciept</th>
                            <th>Employee</th>
                            <th>Amount</th>
                            <th>Reciept Date</th>
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

    <div id="deleteRecieptModal" class="modal hidden">
        <div class="modal-content">
            <p>Are you sure you want to delete this receipt?</p>
            <div class="modal-actions">
                <button id="confirmRecieptDelete" class="btn danger">Yes, Delete</button>
                <button id="cancelRecieptDelete" class="btn">Cancel</button>
            </div>
        </div>
    </div>

    <div id="editRecieptModal" class="modal hidden">
        <div class="modal-content">
            <h3>Update Payment</h3>
            <br>
            <form id="editRecieptForm" class="f-cen f-col g24">
                <input type="hidden" id="edit-reciept-id" name="id">

                <div class="input-box">
                    <input type="number" name="receipt_no" id="edit-reciept-no" required>
                    <label for="edit-reciept-no">Reciept No:</label>
                </div>

                <div class="input-box">
                    <select name="employee_id" id="edit-employee-id" required>
                        <option hidden>-- Select Employee --</option>
                        </select>
                    <label for="edit-employee-id">Employee:</label>
                </div>

                <div class="input-box">
                    <input type="number" step="0.01" name="amount" id="edit-amount" required>
                    <label for="edit-amount">Amount:</label>
                </div>

                <div class="input-box">
                    <input type="date" name="receipt_date" id="edit-reciept-date" required>
                    <label for="edit-reciept-date">Reciept Date:</label>
                </div>

                <div class="input-box w-100">
                    <textarea name="receipt_note" id="edit-notes" rows="3"></textarea>
                    <label for="edit-notes">Notes:</label>
                </div>

                <div class="modal-actions w-100 f-bet">
                    <button type="submit" class="btn primary">Save</button>
                    <button type="button" id="cancelRecieptEdit" class="btn danger">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <div id="addRecieptModal" class="modal hidden">
        <div class="modal-content">
            <h2>Add New Reciept</h2>
            <br>
            <form id="addRecieptForm" class="f-evn g24" style="width: 560px;">

                <div class="input-box">
                    <input type="number" name="receipt_no" id="add-reciept-no" required>
                    <label for="add-reciept-no">Reciept No:</label>
                </div>

                <div class="input-box">
                    <select name="employee_id" id="add-employee-id" required>
                        <option hidden>-- Select Employee --</option>
                        </select>
                    <label for="add-employee-id">Employee:</label>
                </div>

                <div class="input-box">
                    <input type="number" step="1" name="amount" id="add-amount" required>
                    <label for="edit-amount">Amount:</label>
                </div>

                <div class="input-box">
                    <input type="date" name="receipt_date" id="add-reciept-date" required>
                    <label for="add-reciept-date">Reciept Date:</label>
                </div>

                <div class="input-box w-100">
                    <textarea name="receipt_note" id="add-receipt_note" rows="3"></textarea>
                    <label for="add-receipt_note">Notes:</label>
                </div>

                <div class="modal-actions w-100">
                    <button type="submit" class="btn primary">Add Reciept</button>
                    <button type="button" id="cancelRecieptAdd" class="btn">Cancel</button>
                </div>
            </form>
        </div>
    </div>

    <?php include_once "includes/messageModal.php"; ?>

    <script src="scripts/layout.js"></script>
    <script src="scripts/messageModel.js"></script>
    <script src="scripts/recieptsServices.js"></script>

</body>

</html>