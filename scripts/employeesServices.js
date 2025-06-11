let allEmployees = [];

document.addEventListener("DOMContentLoaded", () => {
    fetch("api/employees/")
        .then(response => {
            if (!response.ok) throw new Error("Network response was not ok");
            return response.json();
        })
        .then(data => {
            populateTable(data.employees);
            allEmployees = data.employees;
        } )
        .catch(error => {
            console.error("Fetch error:", error);
        });

});

const addEmployeeButton = document.getElementById("open-add-model");
const addEmployeeModal = document.getElementById("addEmployeeModal");
const cancelAddBtn = document.getElementById("cancelAdd");
const addForm = document.getElementById("addForm");

// Add event listener to the add new employee button to show the modal
addEmployeeButton.addEventListener("click", () => {
    addEmployeeModal.classList.remove("hidden");
});

// Add event listener to the cancel button to hide the modal
cancelAddBtn.addEventListener("click", () => {
    addEmployeeModal.classList.add("hidden");
});

// Add event listener to the form submit
addForm.addEventListener("submit", (event) => {
    event.preventDefault();

    // Get the form data, including the CV file
    const formData = new FormData(addForm);
    const name = formData.get("name");
    const hiredate = formData.get("hiredate");
    const salary = formData.get("salary");
    const permission = formData.get("permission");
    const phone = formData.get("phone");
    const skill = formData.get("skill");
    const email = formData.get("email");
    const password = formData.get("password");
    const cvFile = formData.get("cv_file");

    
    fetch("api/employees/create", {
        method: "POST",
        body: formData,
    })
        .then((response) => {
            if (!response.ok) {
                throw new Error("Failed to add employee");
            }
            return response.json();
        })
        .then((data) => {
            if (data.error) {
                
                showMessage(data.error, "error");
            } else {
                showMessage(data.message, "success");
                addEmployeeModal.classList.add("hidden");
                addForm.reset();
                return fetch("api/employees/");
            }
        })
        .then(res => res.json())
        .then(data => populateTable(data.employees))
        .catch((error) => {
            console.error("Error:", error);
            showMessage("An error occurred while adding the employee", "error");
            addEmployeeModal.classList.add("hidden");
        });
});



function populateTable(data) {
    const tbody = document.querySelector("tbody");
    tbody.innerHTML = "";

    data.forEach((item, index) => {
        const row = document.createElement("tr");

        row.innerHTML = `
            <td>${index + 1}</td>
            <td>${item.name}</td>
            <td><a class="btn-link" href="mailto:${item.email}" target="_blank">E-mail</a></td>
            <td>${item.hiredate}</td>
            <td>${item.salary}$</td>
            <td>${item.permission}</td>
            <td><a class="btn-link" href="tel:${item.phone}" target="_blank"> Phone </a></td>
            <td>${item.cv_file ? `<a class="btn-link" href="api/employees/${item.cv_file}" target="_blank">View CV</a>` : 'null'}</td>
            <td>${item.skill}</td>
            <td>${item.last_login}</td>
            <td><button class="btn update-btn" data-id="${item.id}"><i class="fa-solid fa-edit"></i></button></td>
            <td><button class="btn delete-btn" data-id="${item.id}"><i class="fa-solid fa-trash"></i></button></td>
        `;

        tbody.appendChild(row);
    });

    attachDeleteListeners();
    attachUpdateListeners(data);
    handleSearch();
}

function attachDeleteListeners() {
    const deleteButtons = document.querySelectorAll(".delete-btn");
    const modal = document.getElementById("deleteModal");
    const confirmBtn = document.getElementById("confirmDelete");
    const cancelBtn = document.getElementById("cancelDelete");

    let employeeIdToDelete = null;

    deleteButtons.forEach(btn => {
        btn.addEventListener("click", () => {
            employeeIdToDelete = btn.dataset.id;
            modal.classList.remove("hidden");
        });
    });

    cancelBtn.addEventListener("click", () => {
        modal.classList.add("hidden");
        employeeIdToDelete = null;
    });

    confirmBtn.addEventListener("click", () => {
        if (!employeeIdToDelete) return;

        fetch("api/employees/delete.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ id: employeeIdToDelete })
        })
            .then(response => {
                if (!response.ok) throw new Error("فشل في الحذف");
                return response.json();
            })
            .then(result => {
                showMessage("تم الحذف بنجاح", "success");
                modal.classList.add("hidden");
                employeeIdToDelete = null;
                return fetch("api/employees/");
            })
            .then(res => res.json())
            .then(data => populateTable(data.employees))
            .catch(error => {
                console.error("Delete error:", error);
                showMessage("حدث خطأ أثناء الحذف", "error");
                modal.classList.add("hidden");
            });
    });
}

function attachUpdateListeners(data) {
    const updateButtons = document.querySelectorAll(".update-btn");
    const modal = document.getElementById("editModal");
    const cancelBtn = document.getElementById("cancelEdit");
    const form = document.getElementById("editForm");

    updateButtons.forEach(btn => {
        btn.addEventListener("click", () => {
            const id = btn.dataset.id;
            const employee = data.find(e => e.id == id);
            if (!employee) return;

            document.getElementById("edit-id").value = employee.id;
            document.getElementById("edit-name").value = employee.name || "";
            document.getElementById("edit-email").value = employee.email || "";
            document.getElementById("edit-hiredate").value = employee.hiredate || "";
            document.getElementById("edit-salary").value = employee.salary || "";
            document.getElementById("edit-permission").value = employee.permission || "";
            document.getElementById("edit-phone").value = employee.phone || "";
            document.getElementById("edit-skill").value = employee.skill || "";

            modal.classList.remove("hidden");
        });
    });

    cancelBtn.addEventListener("click", () => {
        modal.classList.add("hidden");
    });

    form.addEventListener("submit", (e) => {
        e.preventDefault();

        const formData = {
            id: document.getElementById("edit-id").value,
            name: document.getElementById("edit-name").value,
            email: document.getElementById("edit-email").value,
            hiredate: document.getElementById("edit-hiredate").value,
            salary: document.getElementById("edit-salary").value,
            permission: document.getElementById("edit-permission").value,
            phone: document.getElementById("edit-phone").value,
            skill: document.getElementById("edit-skill").value,
        };

        fetch("api/employees/update.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(formData)
        })
        .then(res => {
            if (!res.ok) throw new Error("فشل التحديث");
            return res.json();
        })
        .then(result => {
            showMessage(result.message || "تم التحديث بنجاح", "success");
            modal.classList.add("hidden");
            return fetch("api/employees/");
        })
        .then(res => res.json())
        .then(data => populateTable(data.employees))
        .catch(err => {
            console.error("Update error:", err);
            showMessage("حدث خطأ أثناء التحديث", "error");
        });
    });
}

function handleSearch() {
    const searchInput = document.getElementById("search");
    searchInput.addEventListener("input", () => {
        const keyword = searchInput.value.toLowerCase().trim();

        const filtered = allEmployees.filter(emp => {
            return (
                emp.name.toLowerCase().includes(keyword) ||
                emp.email.toLowerCase().includes(keyword) ||
                emp.phone.toLowerCase().includes(keyword) ||
                emp.permission.toLowerCase().includes(keyword) ||
                (emp.skill && emp.skill.toLowerCase().includes(keyword))
            );
        });

        populateTable(filtered); // عرض النتائج المفلترة
    });
}