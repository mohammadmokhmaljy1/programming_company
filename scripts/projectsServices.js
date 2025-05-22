let allProjects = [];
let allClients = [];
let allManagers = [];

// تحميل المشاريع والعملاء والمديرين عند تحميل الصفحة
document.addEventListener("DOMContentLoaded", () => {
    // جلب المشاريع
    fetch("api/projects/")
        .then(res => {
            if (!res.ok) throw new Error("Network response was not ok for projects");
            return res.json();
        })
        .then(data => {
            allProjects = data.projects || [];
            populateProjectTable(allProjects);
            populateYearFilterOptions(allProjects);
        })
        .catch(err => {
            console.error("Fetch projects error:", err);
            // يمكنك عرض رسالة خطأ للمستخدم هنا
        });

    // جلب العملاء
    fetch("api/clients/")
        .then(res => {
            if (!res.ok) throw new Error("Network response was not ok for clients");
            return res.json();
        })
        .then(data => {
            allClients = data.clients || [];
            populateSelectOptions(document.getElementById("add-client"), allClients, 'id', 'name');
            populateSelectOptions(document.getElementById("edit-client"), allClients, 'id', 'name');
        })
        .catch(err => {
            console.error("Fetch clients error:", err);
        });

    // جلب المديرين (الموظفين)
    fetch("api/employees/")
        .then(res => {
            if (!res.ok) throw new Error("Network response was not ok for employees");
            return res.json();
        })
        .then(data => {
            allManagers = data.employees || []; // افترض أن الـ API يعيد 'employees'
            populateSelectOptions(document.getElementById("add-manager"), allManagers, 'id', 'name');
            populateSelectOptions(document.getElementById("edit-manager"), allManagers, 'id', 'name');
        })
        .catch(err => {
            console.error("Fetch managers error:", err);
        });
});

// دالة مساعدة لتعبئة قوائم الـ select
function populateSelectOptions(selectElement, data, valueKey, textKey) {
    data.forEach(item => {
        const option = document.createElement("option");
        option.value = item[valueKey];
        option.textContent = item[textKey];
        selectElement.appendChild(option);
    });
}

// عرض المشاريع في الجدول
function populateProjectTable(projects) {
    const tbody = document.querySelector("tbody");
    tbody.innerHTML = "";

    projects.forEach((proj, index) => {
        // تحديد حالة "Finished" و "Canceled" للعرض
        const isFinished = proj.is_finish == 1 ? '<i class="fa-solid fa-check text-success"></i>' : '<i class="fa-solid fa-xmark text-danger"></i>';
        const isCanceled = proj.is_canceled == 1 ? '<i class="fa-solid fa-check text-success"></i>' : '<i class="fa-solid fa-xmark text-danger"></i>';

        // الحصول على اسم العميل والمدير من البيانات المجلوبة
        // افترض أن الـ API الخاص بالمشاريع يعيد client_name و manager_name
        const clientName = proj.client_name || 'N/A';
        const managerName = proj.manager_name || 'N/A';

tbody.innerHTML += `
    <tr>
        <td>${index + 1}</td>
        <td>${proj.title}</td>
        <td class="pointer" title="${proj.description}"><u>Description</u> <i class="fa-solid fa-hand-pointer"></i></td> 
        <td>${proj.begin_date}</td>
        <td>${proj.end_date}</td>
        <td>${proj.price}</td>
        <td>${clientName}</td>
        <td>${managerName}</td>
        <td><a href="projectTasks?p=${proj.id}">Tasks</a></td>
        <td><button class="btn update-btn" data-id="${proj.id}"><i class="fa-solid fa-edit"></i></button></td>
        <td>
            ${
                proj.is_canceled == 1 
                    ? "<span class='text-red-600 font-semibold'>Canceled</span>" 
                    : proj.is_finish == 1 
                        ? "<span class='text-green-600 font-semibold'>Finished</span>" 
                        : `
                            <button class="btn cancel-project-btn" data-id="${proj.id}" title="Cancel Project">
                                <i class="fa-solid fa-close"></i>
                            </button>
                            <button class="btn finish-project-btn" data-id="${proj.id}" title="Mark as Finished">
                                <i class="fa-solid fa-check"></i>
                            </button>
                        `
            }
        </td>
    </tr>
`;



    });

    // إعادة ربط المستمعات بعد تحديث الجدول
    attachUpdateListeners();
    attachFinishProjectListeners(); // تم تغيير الاسم لتجنب التضارب
    attachCancelProjectListeners(); // تم تغيير الاسم لتجنب التضارب
}

// تعبئة خيارات فلتر السنة بناءً على سنوات بداية المشاريع
function populateYearFilterOptions(projects) {
    const filterYear = document.getElementById("filter-year");

    const years = new Set();
    projects.forEach(p => {
        if (p.begin_date) {
            years.add(new Date(p.begin_date).getFullYear());
        }
    });

    // احتفظ بخيار "All Years" الموجود مسبقًا
    const existingOptions = filterYear.innerHTML;
    filterYear.innerHTML = existingOptions; // لتجنب مسح "All Years"

    Array.from(years).sort((a, b) => b - a).forEach(year => {
        filterYear.innerHTML += `<option value="${year}">${year}</option>`;
    });
}

// فلترة المشاريع حسب السنة المختارة
document.getElementById("filter-year").addEventListener("change", e => {
    const year = e.target.value;
    if (!year) {
        populateProjectTable(allProjects);
    } else {
        const filtered = allProjects.filter(p => {
            return new Date(p.begin_date).getFullYear() == year;
        });
        populateProjectTable(filtered);
    }
});

// فتح مودال تعديل المشروع وتعبئة البيانات
const editModal = document.getElementById("editModal");
const editForm = document.getElementById("editForm");
const cancelEditBtn = document.getElementById("cancelEdit");

function attachUpdateListeners() {
    document.querySelectorAll(".update-btn").forEach(btn => {
        btn.onclick = () => {
            const id = btn.dataset.id;
            const project = allProjects.find(p => p.id == id);
            if (!project) return;

            // تعبئة بيانات المشروع في النموذج
            editForm.elements["id"].value = project.id;
            editForm.elements["title"].value = project.title;
            editForm.elements["description"].value = project.description || ''; // تعبئة الوصف
            editForm.elements["begin_date"].value = project.begin_date;
            editForm.elements["end_date"].value = project.end_date;
            editForm.elements["price"].value = project.price;
            editForm.elements["notes"].value = project.notes || ''; // تعبئة الملاحظات

            // تعبئة الـ select للعميل والمدير
            // تأكد من أن قيم الـ IDs متطابقة مع قيم الـ option
            editForm.elements["employee_manager_id"].value = project.employee_manager_id;
            editForm.elements["client_id"].value = project.client_id;

            editModal.classList.remove("hidden");
        };
    });
}

cancelEditBtn.addEventListener("click", () => {
    editModal.classList.add("hidden");
    editForm.reset();
});

// إرسال تحديث المشروع
editForm.addEventListener("submit", e => {
    e.preventDefault();

    const formData = {
        id: editForm.elements["id"].value,
        title: editForm.elements["title"].value.trim(),
        description: editForm.elements["description"].value.trim(), // تضمين الوصف
        begin_date: editForm.elements["begin_date"].value,
        end_date: editForm.elements["end_date"].value,
        price: parseFloat(editForm.elements["price"].value),
        notes: editForm.elements["notes"].value.trim(), // تضمين الملاحظات
        employee_manager_id: editForm.elements["employee_manager_id"].value, // تضمين مدير المشروع
        client_id: editForm.elements["client_id"].value // تضمين العميل
    };

    fetch("api/projects/update.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(formData)
    })
        .then(res => res.json())
        .then(data => {
            if (data.error) {
                // Show message using messageModel.js
                showMessage(data.error, "error");
            } else {
                // Show message using messageModel.js
                showMessage(data.message || "Project updated successfully!", "success");
                editModal.classList.add("hidden");
                return fetchProjects();
            }
        })
        .catch(err => {
            console.error("Update error:", err);
            showMessage("An error occurred while updating the project.", "error");
        });
});

// إعادة جلب المشاريع وتحديث الجدول
function fetchProjects() {
    return fetch("api/projects/")
        .then(res => res.json())
        .then(data => {
            allProjects = data.projects || [];
            populateProjectTable(allProjects);
        })
        .catch(err => {
            console.error("Fetch projects error:", err);
            showMessage("Failed to load projects.", "error");
        });
}

// فتح مودال إضافة مشروع جديد
const addProjectModal = document.getElementById("addProjectModal");
const openAddModalBtn = document.getElementById("open-add-model");
const addForm = document.getElementById("addForm");
const cancelAddBtn = document.getElementById("cancelAdd");

openAddModalBtn.addEventListener("click", () => {
    addProjectModal.classList.remove("hidden");
    addForm.reset(); // للتأكد من أن النموذج فارغ عند الفتح
});

cancelAddBtn.addEventListener("click", () => {
    addProjectModal.classList.add("hidden");
    addForm.reset();
});

// إرسال إضافة مشروع جديد
addForm.addEventListener("submit", e => {
    e.preventDefault();

    const formData = {
        title: addForm.elements["title"].value.trim(),
        description: addForm.elements["description"].value.trim(),
        begin_date: addForm.elements["begin_date"].value,
        end_date: addForm.elements["end_date"].value,
        price: parseFloat(addForm.elements["price"].value),
        notes: addForm.elements["notes"].value.trim(),
        employee_manager_id: addForm.elements["employee_manager_id"].value,
        client_id: addForm.elements["client_id"].value
    };

    fetch("api/projects/create", { // افترض أن لديك API لإضافة المشاريع
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(formData)
    })
        .then(res => res.json())
        .then(data => {
            if (data.error) {
                showMessage(data.error, "error");
            } else {
                showMessage(data.message || "Project added successfully!", "success");
                addProjectModal.classList.add("hidden");
                return fetchProjects(); // إعادة جلب المشاريع لتحديث الجدول
            }
        })
        .catch(err => {
            console.error("Add project error:", err);
            showMessage("An error occurred while adding the project.", "error");
        });
});


// إنهاء المشروع (تغيير الحالة) - باستخدام modal التأكيد
const finishModal = document.getElementById("finishModal");
const confirmFinishBtn = document.getElementById("confirmFinish");
const cancelFinishBtn = document.getElementById("cancelFinish");
let currentProjectIdToFinish = null; // لتخزين ID المشروع الذي سيتم إنهائه

function attachFinishProjectListeners() {
    document.querySelectorAll(".finish-project-btn").forEach(btn => {
        btn.onclick = () => {
            currentProjectIdToFinish = btn.dataset.id;
            finishModal.classList.remove("hidden");
        };
    });
}

confirmFinishBtn.addEventListener("click", () => {
    if (currentProjectIdToFinish) {
        fetch("api/projects/finish.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                id: currentProjectIdToFinish
            })
        })
            .then(res => res.json())
            .then(data => {
                if (data.error) {
                    showMessage("Error", data.error, "error");
                } else {
                    showMessage("Success", data.message || "Project finished successfully!", "success");
                    finishModal.classList.add("hidden");
                    fetchProjects();
                }
            })
            .catch(err => {
                console.error("Finish error:", err);
                showMessage("Error", "An error occurred while finishing the project.", "error");
            })
            .finally(() => {
                currentProjectIdToFinish = null; // إعادة تعيين الـ ID
            });
    }
});

cancelFinishBtn.addEventListener("click", () => {
    finishModal.classList.add("hidden");
    currentProjectIdToFinish = null;
});


// إلغاء المشروع (تغيير الحالة) - باستخدام modal التأكيد
const cancelModal = document.getElementById("cancelModal");
const confirmCancelBtn = document.getElementById("confirmCancel");
const cancelCancelBtn = document.getElementById("cancelCancel");
let currentProjectIdToCancel = null; // لتخزين ID المشروع الذي سيتم إلغاؤه

function attachCancelProjectListeners() {
    document.querySelectorAll(".cancel-project-btn").forEach(btn => {
        btn.onclick = () => {
            currentProjectIdToCancel = btn.dataset.id;
            cancelModal.classList.remove("hidden");
        };
    });
}

confirmCancelBtn.addEventListener("click", () => {
    if (currentProjectIdToCancel) {
        fetch("api/projects/cancel.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                id: currentProjectIdToCancel
            })
        })
            .then(res => res.json())
            .then(data => {
                if (data.error) {
                    showMessage(data.error, "error");
                } else {
                    showMessage(data.message || "Project canceled successfully!", "success");
                    cancelModal.classList.add("hidden");
                    fetchProjects();
                }
            })
            .catch(err => {
                console.error("Cancel error:", err);
                showMessage("An error occurred while canceling the project.", "error");
            })
            .finally(() => {
                currentProjectIdToCancel = null; // إعادة تعيين الـ ID
            });
    }
});

cancelCancelBtn.addEventListener("click", () => {
    cancelModal.classList.add("hidden");
    currentProjectIdToCancel = null;
});