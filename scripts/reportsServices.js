let allReport = [];
let allEmployees = [];
let allProjects = [];
let currentReportIdToDelete = null;

document.addEventListener("DOMContentLoaded", () => {
    fetchReports();

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

    document.getElementById("open-add-report").addEventListener("click", () => {
        document.getElementById("addReportModal").classList.remove("hidden");
        document.getElementById("addReportForm").reset();
    });

    document.getElementById("cancelReportAdd").addEventListener("click", () => {
        document.getElementById("addReportModal").classList.add("hidden");
        document.getElementById("addReportForm").reset();
    });

    document.getElementById("cancelReportEdit").addEventListener("click", () => {
        document.getElementById("editReportModal").classList.add("hidden");
        document.getElementById("editReportForm").reset();
    });

    document.getElementById("cancelReportDelete").addEventListener("click", () => {
        document.getElementById("deleteReportModal").classList.add("hidden");
        currentReportIdToDelete = null;
    });

    document.getElementById("addReportForm").addEventListener("submit", handleAddReport);
    document.getElementById("editReportForm").addEventListener("submit", handleEditReport);

    document.getElementById("confirmReportDelete").addEventListener("click", () => {
        if (currentReportIdToDelete) {
            fetch("api/reports/delete.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json"
                },
                body: JSON.stringify({ id: currentReportIdToDelete })
            })
                .then(res => res.json())
                .then(data => {
                    if (data.error) {
                        showMessage(data.error, "error");
                    } else {
                        showMessage(data.message || "Report deleted successfully!", "success");
                        document.getElementById("deleteReportModal").classList.add("hidden");
                        fetchReports();
                    }
                })
                .catch(err => {
                    console.error("Delete report error:", err);
                    showMessage("An error occurred while deleting the report.", "error");
                })
                .finally(() => {
                    currentReportIdToDelete = null;
                });
        }
    });
});

function fetchReports() {
    return fetch("api/reports/get_by_employee")
        .then(res => {
            if (!res.ok) throw new Error("Network response was not ok for Reports");
            return res.json();
        })
        .then(data => {
            if (data.error) {
                showMessage(data.error, "error");
                console.error("API Error:", data.error);
            } else {
                allReport = data.reports || [];
                populateReportsTable(allReport);
            }
        })
        .catch(err => {
            console.error("Fetch Reports error:", err);
            showMessage("Failed to load Reports.", "error");
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

function populateReportsTable(reports) {
    const tbody = document.querySelector(".table tbody");
    tbody.innerHTML = "";

    if (reports.length === 0) {
        tbody.innerHTML = '<tr><td colspan="8" style="text-align: center;">No reports found.</td></tr>';
        return;
    }

    reports.forEach((report, index) => {
        const row = document.createElement("tr");

        row.innerHTML = `
            <td>${index + 1}</td>
            <td>${report.title || 'N/A'}</td>
            <td>${report.description}</td>
            <td>${report.date}</td>
            <td>${report.employee_name}</td>
            <td>${report.project_title || 'N/A'}</td>
            <td>
                <button class="btn edit update-btn" data-id="${report.id}"><i class="fa-solid fa-pen-to-square"></i></button>
            </td>
            <td>
                <button class="btn danger delete-btn" data-id="${report.id}"><i class="fa-solid fa-trash"></i></button>
            </td>
        `;

        tbody.appendChild(row);
    });

    attachReportActionListeners();
}

function attachReportActionListeners() {
    document.querySelectorAll(".update-btn").forEach(btn => {
        btn.onclick = () => {
            const reportId = btn.dataset.id;
            const report = allReport.find(r => r.id == reportId);
            if (!report) return;

            document.getElementById("edit-report-id").value = report.id;
            document.getElementById("edit-title").value = report.title;
            document.getElementById("edit-description").value = report.description;
            document.getElementById("edit-date").value = report.date;
            document.getElementById("edit-employee-id").value = report.employee_id;
            document.getElementById("edit-project-id").value = report.project_id;

            document.getElementById("editReportModal").classList.remove("hidden");
        };
    });

    document.querySelectorAll(".delete-btn").forEach(btn => {
        btn.onclick = () => {
            currentReportIdToDelete = btn.dataset.id;
            document.getElementById("deleteReportModal").classList.remove("hidden");
        };
    });
}

const addReportForm = document.getElementById("addReportForm");
function handleAddReport(e) {
    e.preventDefault();

    const formData = new FormData(addReportForm);
    const reportData = Object.fromEntries(formData.entries());

    fetch("api/reports/create", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(reportData)
    })
        .then(res => res.json())
        .then(data => {
            if (data.error) {
                showMessage(data.error, "error");
            } else {
                showMessage(data.message || "Report added successfully!", "success");
                document.getElementById("addReportModal").classList.add("hidden");
                addReportForm.reset();
                fetchReports();
            }
        })
        .catch(err => {
            console.error("Add report error:", err);
            showMessage("An error occurred while adding the report.", "error");
        });
}

const editReportForm = document.getElementById("editReportForm");
function handleEditReport(e) {
    e.preventDefault();

    const formData = new FormData(editReportForm);
    const reportData = Object.fromEntries(formData.entries());

    fetch("api/reports/update.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(reportData)
    })
        .then(res => res.json())
        .then(data => {
            if (data.error) {
                showMessage(data.error, "error");
            } else {
                showMessage(data.message || "Report updated successfully!", "success");
                document.getElementById("editReportModal").classList.add("hidden");
                editReportForm.reset();
                fetchReports();
            }
        })
        .catch(err => {
            console.error("Update report error:", err);
            showMessage("An error occurred while updating the report.", "error");
        });
}