let allTasks = [];
let allEmployees = [];
let allProjects = [];

document.addEventListener("DOMContentLoaded", () => {
    fetchTasks();

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
        });

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
        });

    document.getElementById("open-add-task").addEventListener("click", () => {
        document.getElementById("addTaskModal").classList.remove("hidden");
        document.getElementById("addTaskForm").reset();
    });

    document.getElementById("cancelTaskAdd").addEventListener("click", () => {
        document.getElementById("addTaskModal").classList.add("hidden");
        document.getElementById("addTaskForm").reset();
    });

    document.getElementById("cancelTaskEdit").addEventListener("click", () => {
        document.getElementById("editTaskModal").classList.add("hidden");
        document.getElementById("editTaskForm").reset();
    });

    document.getElementById("cancelTaskFinish").addEventListener("click", () => {
        document.getElementById("finishTaskModal").classList.add("hidden");
        currentTaskIdToFinish = null;
    });

    document.getElementById("cancelTaskDelete").addEventListener("click", () => {
        document.getElementById("deleteTaskModal").classList.add("hidden");
        currentTaskIdToDelete = null;
    });

    document.getElementById("addTaskForm").addEventListener("submit", handleAddTask);
    document.getElementById("editTaskForm").addEventListener("submit", handleEditTask);

    document.getElementById("task-search").addEventListener("input", handleSearch);
});


function fetchTasks() {
    return fetch("api/tasks/get_by_employee")
        .then(res => {
            if (!res.ok) throw new Error("Network response was not ok for tasks");
            return res.json();
        })
        .then(data => {
            if (data.error) {
                showMessage(data.error, "error");
                console.error("API Error:", data.error);
            } else {
                allTasks = data.tasks || [];
                populateTasksTable(allTasks);
            }
        })
        .catch(err => {
            console.error("Fetch tasks error:", err);
            showMessage("Failed to load tasks.", "error");
        });
}

function populateSelectOptions(selectElement, data, valueKey, textKey) {
    selectElement.innerHTML = selectElement.querySelector('option[hidden]')?.outerHTML || '<option hidden>-- Select --</option>';

    data.forEach(item => {
        const option = document.createElement("option");
        option.value = item[valueKey];
        option.textContent = item[textKey];
        selectElement.appendChild(option);
    });
}


function populateTasksTable(tasks) {
    const tbody = document.querySelector(".table tbody");
    tbody.innerHTML = "";

    if (tasks.length === 0) {
        tbody.innerHTML = '<tr><td colspan="11" style="text-align: center;">No tasks found.</td></tr>';
        return;
    }

    tasks.forEach((task, index) => {
        const row = document.createElement("tr");

        row.innerHTML = `
            <td>${index + 1}</td>
            <td>${task.employee_name || 'N/A'}</td>
            <td>${task.project_title || 'N/A'}</td>
            <td>${task.start_date}</td>
            <td>${task.finish_date || '<span style="color: grey;">Not Finished</span>'}</td>
            <td>${task.type}</td>
            <td>${task.level}</td>
            <td class="pointer" title="${task.notes || 'No Description'}"><u>Description</u> <i class="fa-solid fa-hand-pointer"></i></td>
            <td>
                ${!task.finish_date
                        ? `<button class="btn finish-task-btn" data-id="${task.id}" title="Mark as Finished"><i class="fa-solid fa-check"></i></button>`
                        : ''}
            </td>
            <td>
                ${!task.finish_date
                        ? `<button class="btn update-btn" data-id="${task.id}"><i class="fa-solid fa-edit"></i></button>`
                        : ''}
            </td>
            <td>
                <button class="btn danger delete-btn" data-id="${task.id}"><i class="fa-solid fa-trash"></i></button>
            </td>
        `;

        tbody.appendChild(row);
    });

    attachTaskActionListeners();
}

function attachTaskActionListeners() {
    document.querySelectorAll(".finish-task-btn").forEach(btn => {
        btn.onclick = () => {
            currentTaskIdToFinish = btn.dataset.id;
            document.getElementById("finishTaskModal").classList.remove("hidden");
        };
    });

    document.querySelectorAll(".update-btn").forEach(btn => {
        btn.onclick = () => {
            const taskId = btn.dataset.id;
            const task = allTasks.find(t => t.id == taskId);
            if (!task) return;

            document.getElementById("edit-task-id").value = task.id;
            document.getElementById("edit-employee-id").value = task.employee_id;
            document.getElementById("edit-start-date").value = task.start_date;
            document.getElementById("edit-type").value = task.type;
            document.getElementById("edit-level").value = task.level;
            document.getElementById("edit-project-id").value = task.project_id;
            document.getElementById("edit-notes").value = task.notes || '';

            document.getElementById("editTaskModal").classList.remove("hidden");
        };
    });

    document.querySelectorAll(".delete-btn").forEach(btn => {
        btn.onclick = () => {
            currentTaskIdToDelete = btn.dataset.id;
            document.getElementById("deleteTaskModal").classList.remove("hidden");
        };
    });
}

const addTaskForm = document.getElementById("addTaskForm");
function handleAddTask(e) {
    e.preventDefault();

    const formData = new FormData(addTaskForm);
    const taskData = Object.fromEntries(formData.entries());

    if (taskData.finish_date === '') {
        taskData.finish_date = null;
    }

    fetch("api/tasks/create.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(taskData)
    })
        .then(res => res.json())
        .then(data => {
            if (data.error) {
                showMessage(data.error, "error");
            } else {
                showMessage(data.message || "Task added successfully!", "success");
                document.getElementById("addTaskModal").classList.add("hidden");
                addTaskForm.reset();
                fetchTasks();
            }
        })
        .catch(err => {
            console.error("Add task error:", err);
            showMessage("An error occurred while adding the task.", "error");
        });
}

// تعديل مهمة
const editTaskForm = document.getElementById("editTaskForm");
function handleEditTask(e) {
    e.preventDefault();

    const formData = new FormData(editTaskForm);
    const taskData = Object.fromEntries(formData.entries());

    // التعامل مع حقل finish_date إذا كان فارغاً
    if (taskData.finish_date === '') {
        taskData.finish_date = null;
    }

    fetch("api/tasks/update.php", { // تأكد من المسار الصحيح لـ API التعديل
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(taskData)
    })
        .then(res => res.json())
        .then(data => {
            if (data.error) {
                showMessage(data.error, "error");
            } else {
                showMessage(data.message || "Task updated successfully!", "success");
                document.getElementById("editTaskModal").classList.add("hidden");
                editTaskForm.reset();
                fetchTasks(); // إعادة جلب المهام لتحديث الجدول
            }
        })
        .catch(err => {
            console.error("Update task error:", err);
            showMessage("An error occurred while updating the task.", "error");
        });
}

// إنهاء مهمة (تغيير الحالة)
const confirmTaskFinishBtn = document.getElementById("confirmTaskFinish");
let currentTaskIdToFinish = null;

confirmTaskFinishBtn.addEventListener("click", () => {
    if (currentTaskIdToFinish) {
        fetch("api/tasks/complete.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                task_id: currentTaskIdToFinish
            })
        })
            .then(res => res.json())
            .then(data => {
                if (data.error) {
                    showMessage(data.error, "error");
                } else {
                    showMessage(data.message || "Task finished successfully!", "success");
                    document.getElementById("finishTaskModal").classList.add("hidden");
                    fetchTasks();
                }
            })
            .catch(err => {
                console.error("Finish task error:", err);
                showMessage("An error occurred while finishing the task.", "error");
            })
            .finally(() => {
                currentTaskIdToFinish = null;
            });
    }
});

// حذف مهمة
const confirmTaskDeleteBtn = document.getElementById("confirmTaskDelete");
let currentTaskIdToDelete = null;

confirmTaskDeleteBtn.addEventListener("click", () => {
    if (currentTaskIdToDelete) {
        fetch("api/tasks/delete.php", { // تأكد من المسار الصحيح لـ API الحذف
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify({
                id: currentTaskIdToDelete
            })
        })
            .then(res => res.json())
            .then(data => {
                if (data.error) {
                    showMessage(data.error, "error");
                } else {
                    showMessage(data.message || "Task deleted successfully!", "success");
                    document.getElementById("deleteTaskModal").classList.add("hidden");
                    fetchTasks(); // إعادة جلب المهام لتحديث الجدول
                }
            })
            .catch(err => {
                console.error("Delete task error:", err);
                showMessage("An error occurred while deleting the task.", "error");
            })
            .finally(() => {
                currentTaskIdToDelete = null;
            });
    }
});


function handleSearch() {
    const searchInput = document.getElementById("task-search");
    const keyword = searchInput.value.toLowerCase().trim();

    const filteredTasks = allTasks.filter(task => {
        return (
            (task.employee_name && task.employee_name.toLowerCase().includes(keyword)) ||
            (task.project_title && task.project_title.toLowerCase().includes(keyword)) ||
            (task.type && task.type.toLowerCase().includes(keyword)) ||
            (task.level && task.level.toLowerCase().includes(keyword)) ||
            (task.notes && task.notes.toLowerCase().includes(keyword))
        );
    });

    populateTasksTable(filteredTasks);
}