let allPayments = [];
let allClients = [];
let allProjects = [];
let currentPaymentIdToDelete = null;
let currentFilterYear = ''; // متغير لتخزين السنة المختارة للفلترة

document.addEventListener("DOMContentLoaded", () => {
    fetchPayments(); // جلب جميع المدفوعات عند تحميل الصفحة لأول مرة

    // جلب بيانات العملاء (Clients) لملء قوائم الاختيار
    fetch("api/clients/")
        .then(res => {
            if (!res.ok) throw new Error("Network response was not ok for clients");
            return res.json();
        })
        .then(data => {
            allClients = data.clients || [];
            populateSelectOptions(document.getElementById("add-client-id"), allClients, 'id', 'name');
            populateSelectOptions(document.getElementById("edit-client-id"), allClients, 'id', 'name');
        })
        .catch(err => {
            console.error("Fetch clients error:", err);
            showMessage("Failed to load clients.", "error");
        });

    // جلب بيانات المشاريع (Projects) لملء قوائم الاختيار
    fetch("api/projects/")
        .then(res => {
            if (!res.ok) throw new Error("Network response was not ok for projects");
            return res.json();
        })
        .then(data => {
            allProjects = data.projects || [];
            populateSelectOptions(document.getElementById("add-project-id"), allProjects, 'id', 'title');
            populateSelectOptions(document.getElementById("edit-project-id"), allProjects, 'id', 'title');
        })
        .catch(err => {
            console.error("Fetch projects error:", err);
            showMessage("Failed to load projects.", "error");
        });

    // أحداث فتح وإغلاق المودال (Modals)
    document.getElementById("open-add-payment").addEventListener("click", () => {
        document.getElementById("addPaymentModal").classList.remove("hidden");
        document.getElementById("addPaymentForm").reset();
        // إعادة تعيين قيم قوائم الاختيار إلى الخيار المخفي
        document.getElementById("add-client-id").value = "";
        document.getElementById("add-project-id").value = "";
        document.getElementById("add-payment-method").value = "";
    });

    document.getElementById("cancelPaymentAdd").addEventListener("click", () => {
        document.getElementById("addPaymentModal").classList.add("hidden");
        document.getElementById("addPaymentForm").reset();
    });

    document.getElementById("cancelPaymentEdit").addEventListener("click", () => {
        document.getElementById("editPaymentModal").classList.add("hidden");
        document.getElementById("editPaymentForm").reset();
    });

    document.getElementById("cancelPaymentDelete").addEventListener("click", () => {
        document.getElementById("deletePaymentModal").classList.add("hidden");
        currentPaymentIdToDelete = null;
    });

    // أحداث إرسال النماذج
    document.getElementById("addPaymentForm").addEventListener("submit", handleAddPayment);
    document.getElementById("editPaymentForm").addEventListener("submit", handleEditPayment);

    // حدث تأكيد الحذف
    document.getElementById("confirmPaymentDelete").addEventListener("click", () => {
        if (currentPaymentIdToDelete) {
            fetch("api/payments/delete.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ id: currentPaymentIdToDelete })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.error) {
                        showMessage(data.error, "error");
                    } else {
                        showMessage(data.message || "Payment deleted successfully!", "success");
                        document.getElementById("deletePaymentModal").classList.add("hidden");
                        fetchPayments(); // إعادة جلب المدفوعات بعد الحذف
                    }
                })
                .catch(err => {
                    console.error("Delete payment error:", err);
                    showMessage("An error occurred while deleting the payment.", "error");
                })
                .finally(() => {
                    currentPaymentIdToDelete = null;
                });
        }
    });

    // حدث تغيير الفلتر حسب السنة
    document.getElementById("filter-year").addEventListener("change", (e) => {
        currentFilterYear = e.target.value;
        populatePaymentsTable(getFilteredPayments());
    });
});

// --- دوال جلب وعرض البيانات ---

function fetchPayments() {
    return fetch("api/payments/")
        .then(res => {
            if (!res.ok) throw new Error("Network response was not ok for Payments");
            return res.json();
        })
        .then(data => {
            if (data.error) {
                showMessage(data.error, "error");
                console.error("API Error:", data.error);
            } else {
                allPayments = data.payments || [];
                populateYearsFilter(allPayments); // ملء قائمة السنوات
                populatePaymentsTable(getFilteredPayments()); // عرض المدفوعات بعد الفلترة (إذا كانت هناك سنة محددة)
            }
        })
        .catch(err => {
            console.error("Fetch Payments error:", err);
            showMessage("Failed to load payments.", "error");
        });
}

function populateSelectOptions(selectElement, data, valueKey, textKey) {
    // الحفاظ على خيار "Select" المخفي أو إضافته
    selectElement.innerHTML = selectElement.querySelector('option[hidden]')?.outerHTML || '<option hidden>-- Select --</option>';

    data.forEach(item => {
        const option = document.createElement("option");
        option.value = item[valueKey];
        option.textContent = item[textKey];
        selectElement.appendChild(option);
    });
}

function populateYearsFilter(payments) {
    const yearSelect = document.getElementById("filter-year");
    const years = new Set();
    payments.forEach(payment => {
        const year = new Date(payment.payment_date).getFullYear();
        if (year) {
            years.add(year);
        }
    });

    const sortedYears = Array.from(years).sort((a, b) => b - a); // ترتيب تنازلي (الأحدث أولاً)

    // مسح الخيارات الحالية باستثناء "All Years"
    yearSelect.innerHTML = '<option value="">All Years</option>';
    sortedYears.forEach(year => {
        const option = document.createElement("option");
        option.value = year;
        option.textContent = year;
        yearSelect.appendChild(option);
    });

    // إذا كانت هناك سنة مفلترة مسبقًا، حددها في القائمة
    if (currentFilterYear) {
        yearSelect.value = currentFilterYear;
    }
}

function getFilteredPayments() {
    if (!currentFilterYear) {
        return allPayments; // إذا لم تكن هناك سنة محددة، ارجع كل المدفوعات
    }
    return allPayments.filter(payment => {
        const year = new Date(payment.payment_date).getFullYear().toString();
        return year === currentFilterYear;
    });
}


function populatePaymentsTable(paymentsToDisplay) {
    const tbody = document.querySelector(".table tbody");
    tbody.innerHTML = ""; // مسح المحتوى الحالي للجدول

    if (paymentsToDisplay.length === 0) {
        tbody.innerHTML = '<tr><td colspan="9" style="text-align: center;">No payments found.</td></tr>';
        return;
    }

    paymentsToDisplay.forEach((payment, index) => {
        const row = document.createElement("tr");

        row.innerHTML = `
            <td>${index + 1}</td>
            <td>${payment.client_name || 'N/A'}</td>
            <td>${payment.project_title || 'N/A'}</td>
            <td>${parseFloat(payment.amount).toFixed(2)}$</td>
            <td>${payment.payment_date}</td>
            <td>${payment.payment_method}</td>
            <td>${payment.notes || ''}</td>
            <td>
                <button class="btn edit update-btn" data-id="${payment.id}"><i class="fa-solid fa-pen-to-square"></i></button>
            </td>
            <td>
                <button class="btn danger delete-btn" data-id="${payment.id}"><i class="fa-solid fa-trash"></i></button>
            </td>
        `;

        tbody.appendChild(row);
    });

    attachPaymentActionListeners(); // إرفاق المستمعات للأزرار بعد ملء الجدول
}

function attachPaymentActionListeners() {
    document.querySelectorAll(".update-btn").forEach(btn => {
        btn.onclick = () => {
            const paymentId = btn.dataset.id;
            const payment = allPayments.find(p => p.id == paymentId);
            if (!payment) return;

            document.getElementById("edit-payment-id").value = payment.id;
            document.getElementById("edit-client-id").value = payment.client_id;
            document.getElementById("edit-project-id").value = payment.project_id;
            document.getElementById("edit-amount").value = payment.amount;
            document.getElementById("edit-payment-date").value = payment.payment_date;
            document.getElementById("edit-payment-method").value = payment.payment_method;
            document.getElementById("edit-notes").value = payment.notes;

            document.getElementById("editPaymentModal").classList.remove("hidden");
        };
    });

    document.querySelectorAll(".delete-btn").forEach(btn => {
        btn.onclick = () => {
            currentPaymentIdToDelete = btn.dataset.id;
            document.getElementById("deletePaymentModal").classList.remove("hidden");
        };
    });
}

// --- دوال معالجة الإرسال (Add/Edit) ---

const addPaymentForm = document.getElementById("addPaymentForm");
function handleAddPayment(e) {
    e.preventDefault();

    const formData = new FormData(addPaymentForm);
    const paymentData = Object.fromEntries(formData.entries());
    paymentData.amount = parseFloat(paymentData.amount);

    fetch("api/payments/create.php", { // تأكد من استخدام المسار الصحيح
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(paymentData)
    })
        .then(res => res.json())
        .then(data => {
            if (data.error) {
                showMessage(data.error, "error");
            } else {
                showMessage(data.message || "Payment added successfully!", "success");
                document.getElementById("addPaymentModal").classList.add("hidden");
                addPaymentForm.reset();
                fetchPayments(); // إعادة جلب المدفوعات بعد الإضافة (للتحديث بالفلتر والسنة الجديدة إذا وجدت)
            }
        })
        .catch(err => {
            console.error("Add payment error:", err);
            showMessage("An error occurred while adding the payment.", "error");
        });
}

const editPaymentForm = document.getElementById("editPaymentForm");
function handleEditPayment(e) {
    e.preventDefault();

    const formData = new FormData(editPaymentForm);
    const paymentData = Object.fromEntries(formData.entries());
    paymentData.amount = parseFloat(paymentData.amount);

    fetch("api/payments/update.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(paymentData)
    })
        .then(res => res.json())
        .then(data => {
            if (data.error) {
                showMessage(data.error, "error");
            } else {
                showMessage(data.message || "Payment updated successfully!", "success");
                document.getElementById("editPaymentModal").classList.add("hidden");
                editPaymentForm.reset();
                fetchPayments(); // إعادة جلب المدفوعات بعد التعديل (للتحديث بالفلتر)
            }
        })
        .catch(err => {
            console.error("Update payment error:", err);
            showMessage("An error occurred while updating the payment.", "error");
        });
}