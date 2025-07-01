let allReciept = [];
let allEmployees = [];
let currentRecieptIdToDelete = null;
let currentFilterYear = ''; // متغير لتخزين السنة المختارة للفلترة

document.addEventListener("DOMContentLoaded", () => {
    fetchReciepts(); // جلب جميع المدفوعات عند تحميل الصفحة لأول مرة

    // جلب بيانات العملاء (employees) لملء قوائم الاختيار
    fetch("api/employees/")
        .then(res => {
            if (!res.ok) throw new Error("Network response was not ok for employees");
            return res.json();
        })
        .then(data => {
            allEmployees = data.employees || [];
            populateSelectOptions(document.getElementById("add-employee-id"), allEmployees, 'id', 'name');
            populateSelectOptions(document.getElementById("edit-employee-id"), allEmployees, 'id', 'name');
        })
        .catch(err => {
            console.error("Fetch employees error:", err);
            showMessage("Failed to load employees.", "error");
        });


    // أحداث فتح وإغلاق المودال (Modals)
    document.getElementById("open-add-reciept").addEventListener("click", () => {
        document.getElementById("addRecieptModal").classList.remove("hidden");
        document.getElementById("addRecieptForm").reset();
        // إعادة تعيين قيم قوائم الاختيار إلى الخيار المخفي
        document.getElementById("add-employee-id").value = "";

    });

    document.getElementById("cancelRecieptAdd").addEventListener("click", () => {
        document.getElementById("addRecieptModal").classList.add("hidden");
        document.getElementById("addRecieptForm").reset();
    });

    document.getElementById("cancelRecieptEdit").addEventListener("click", () => {
        document.getElementById("editRecieptModal").classList.add("hidden");
        document.getElementById("editRecieptForm").reset();
    });

    document.getElementById("cancelRecieptDelete").addEventListener("click", () => {
        document.getElementById("deleteRecieptModal").classList.add("hidden");
        currentRecieptIdToDelete = null;
    });

    // أحداث إرسال النماذج
    document.getElementById("addRecieptForm").addEventListener("submit", handleAddReciept);
    document.getElementById("editRecieptForm").addEventListener("submit", handleEditReciept);

    // حدث تأكيد الحذف
    document.getElementById("confirmRecieptDelete").addEventListener("click", () => {
        if (currentRecieptIdToDelete) {
            fetch("api/reciepts/delete.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json"
                    },
                    body: JSON.stringify({ id: currentRecieptIdToDelete })
                })
                .then(res => res.json())
                .then(data => {
                    if (data.error) {
                        showMessage(data.error, "error");
                    } else {
                        showMessage(data.message || "Reciept deleted successfully!", "success");
                        document.getElementById("deleteRecieptModal").classList.add("hidden");
                        fetchReciepts(); // إعادة جلب المدفوعات بعد الحذف
                    }
                })
                .catch(err => {
                    console.error("Delete Reciept error:", err);
                    showMessage("An error occurred while deleting the Reciept.", "error");
                })
                .finally(() => {
                    currentRecieptIdToDelete = null;
                });
        }
    });

    // حدث تغيير الفلتر حسب السنة
    document.getElementById("filter-year").addEventListener("change", (e) => {
        currentFilterYear = e.target.value;
        populateRecieptsTable(getFilteredPayments());
    });
});

// --- دوال جلب وعرض البيانات ---

function fetchReciepts() {
    return fetch("api/reciepts/get_current_month")
        .then(res => {
            if (!res.ok) throw new Error("Network response was not ok for Reciepts");
            return res.json();
        })
        .then(data => {
            if (data.error) {
                showMessage(data.error, "error");
                // console.error("API Error:", data.error);
            } else {
                allReciept = data.reciepts || [];
                // populateYearsFilter(allReciept); // ملء قائمة السنوات
                populateRecieptsTable(allReciept); // عرض المدفوعات بعد الفلترة (إذا كانت هناك سنة محددة)
            }
        })
        .catch(err => {
            console.error("Fetch Reciepts error:", err);
            showMessage("Failed to load Reciepts.", "error");
        });
}

function populateSelectOptions(selectElement, data, valueKey, textKey) {
    // الحفاظ على خيار "Select" المخفي أو إضافته
    selectElement.innerHTML = selectElement.querySelector('option[hidden]') ? selectElement.outerHTML : '<option hidden>-- Select --</option>';

    data.forEach(item => {
        const option = document.createElement("option");
        option.value = item[valueKey];
        option.textContent = item[textKey];
        selectElement.appendChild(option);
    });
}

function populateRecieptsTable(recieptsToDisplay) {
    const tbody = document.querySelector(".table tbody");
    tbody.innerHTML = ""; // مسح المحتوى الحالي للجدول

    if (recieptsToDisplay.length === 0) {
        tbody.innerHTML = '<tr><td colspan="8" style="text-align: center;">No reciepts found.</td></tr>';
        return;
    }

    recieptsToDisplay.forEach((reciept, index) => {
        const row = document.createElement("tr");

        row.innerHTML = `
            <td>${index + 1}</td>
            <td>${reciept.receipt_no}</td>
            <td>${reciept.employee_name || 'N/A'}</td>
            <td>${parseFloat(reciept.amount).toFixed(2)}$</td>
            <td>${reciept.receipt_date}</td>
            <td>${reciept.receipt_note || ''}</td>
            <td>
                <button class="btn edit update-btn" data-id="${reciept.id}"><i class="fa-solid fa-pen-to-square"></i></button>
            </td>
            <td>
                <button class="btn danger delete-btn" data-id="${reciept.id}"><i class="fa-solid fa-trash"></i></button>
            </td>
        `;

        tbody.appendChild(row);
    });

    attachRecieptActionListeners(); // إرفاق المستمعات للأزرار بعد ملء الجدول
}

function attachRecieptActionListeners() {
    document.querySelectorAll(".update-btn").forEach(btn => {
        btn.onclick = () => {
            const recieptId = btn.dataset.id;
            const reciept = allReciept.find(r => r.id == recieptId);
            if (!reciept) return;

            document.getElementById("edit-reciept-id").value = reciept.id;
            document.getElementById("edit-reciept-no").value = reciept.receipt_no;
            document.getElementById("edit-employee-id").value = reciept.employee_id;
            document.getElementById("edit-amount").value = reciept.amount;
            document.getElementById("edit-reciept-date").value = reciept.receipt_date;
            document.getElementById("edit-notes").value = reciept.receipt_note;

            document.getElementById("editRecieptModal").classList.remove("hidden");
        };
    });

    document.querySelectorAll(".delete-btn").forEach(btn => {
        btn.onclick = () => {
            currentRecieptIdToDelete = btn.dataset.id;
            document.getElementById("deleteRecieptModal").classList.remove("hidden");
        };
    });
}

// --- دوال معالجة الإرسال (Add/Edit) ---
const addRecieptForm = document.getElementById("addRecieptForm");

function handleAddReciept(e) {
    e.preventDefault();

    const formData = new FormData(addRecieptForm);
    const RecieptData = Object.fromEntries(formData.entries());
    RecieptData.amount = parseFloat(RecieptData.amount);

    fetch("api/reciepts/create.php", { // تأكد من استخدام المسار الصحيح
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(RecieptData)
        })
        .then(res => res.json())
        .then(data => {
            if (data.error) {
                showMessage(data.error, "error");
            } else {
                showMessage(data.message || "reciept added successfully!", "success");
                document.getElementById("addRecieptModal").classList.add("hidden");
                addRecieptForm.reset();
                fetchReciepts(); // إعادة جلب المدفوعات بعد الإضافة (للتحديث بالفلتر والسنة الجديدة إذا وجدت)
            }
        })
        .catch(err => {
            console.error("Add reciept error:", err);
            showMessage("An error occurred while adding the reciept.", "error");
        });
}

const editRecieptForm = document.getElementById("editRecieptForm");

function handleEditReciept(e) {
    e.preventDefault();

    const formData = new FormData(editRecieptForm);
    const recieptData = Object.fromEntries(formData.entries());
    recieptData.amount = parseFloat(recieptData.amount);

    fetch("api/reciepts/update.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(recieptData)
        })
        .then(res => res.json())
        .then(data => {
            if (data.error) {
                showMessage(data.error, "error");
            } else {
                showMessage(data.message || "reciept updated successfully!", "success");
                document.getElementById("editRecieptModal").classList.add("hidden");
                editRecieptForm.reset();
                fetchReciepts();
            }
        })
        .catch(err => {
            console.error("Update reciept error:", err);
            showMessage("An error occurred while updating the reciept.", "error");
        });
}