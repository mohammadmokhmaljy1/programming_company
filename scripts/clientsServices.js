let allClients = [];

// Load clients on page load
document.addEventListener("DOMContentLoaded", () => {
    fetch("api/clients/")
        .then(response => {
            if (!response.ok) throw new Error("Network response was not ok");
            return response.json();
        })
        .then(data => {
            populateClientTable(data.clients);
            allClients = data.clients;
        })
        .catch(error => {
            console.error("Fetch error:", error);
        });
});

const addClientButton = document.getElementById("open-add-model");
const addClientModal = document.getElementById("addClientModal");
const cancelAddBtn = document.getElementById("cancelAdd");
const addForm = document.getElementById("addForm");

addClientButton.addEventListener("click", () => {
    addClientModal.classList.remove("hidden");
});

cancelAddBtn.addEventListener("click", () => {
    addClientModal.classList.add("hidden");
});

addForm.addEventListener("submit", (event) => {
    event.preventDefault();

    const clientData = {
        name: addForm.name.value,
        address: addForm.address.value,
        email: addForm.email.value,
        phone: addForm.phone.value,
        first_visit_date: addForm.first_visit_date.value,
        company_name: addForm.company_name.value
    };

    fetch("api/clients/create.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/json"
        },
        body: JSON.stringify(clientData)
    })
    .then(response => {
        if (!response.ok) throw new Error("Failed to add client");
        return response.json();
    })
    .then(data => {
        if (data.error) {
            showMessage(data.error, "error");
        } else {
            showMessage(data.message, "success");
            addClientModal.classList.add("hidden");
            addForm.reset();
            return fetch("api/clients/");
        }
    })
    .then(res => res.json())
    .then(data => populateClientTable(data.clients))
    .catch(error => {
        console.error("Error:", error);
        showMessage("An error occurred while adding the client", "error");
        addClientModal.classList.add("hidden");
    });
});

function populateClientTable(data) {
    const tbody = document.querySelector("tbody");
    tbody.innerHTML = "";

    data.forEach((client, index) => {
        const row = document.createElement("tr");

        row.innerHTML = `
            <td>${index + 1}</td>
            <td>${client.name}</td>
            <td>${client.company_name}</td>
            <td>${client.address}</td>
            <td><a class="btn-link" href="mailto:${client.email}" target="_blank">Email</a></td>
            <td><a class="btn-link" href="tel:${client.phone}" target="_blank">Phone</a></td>
            <td>${client.first_visit_date}</td>
            <td><button class="btn update-btn" data-id="${client.id}"><i class="fa-solid fa-edit"></i></button></td>
            <td><button class="btn delete-btn" data-id="${client.id}"><i class="fa-solid fa-trash"></i></button></td>
        `;

        tbody.appendChild(row);
    });

    attachClientUpdateListeners(data);
    attachClientDeleteListeners();
}

function attachClientDeleteListeners() {
    const deleteButtons = document.querySelectorAll(".delete-btn");
    const modal = document.getElementById("deleteModal");
    const confirmBtn = document.getElementById("confirmDelete");
    const cancelBtn = document.getElementById("cancelDelete");

    let clientIdToDelete = null;

    deleteButtons.forEach(btn => {
        btn.addEventListener("click", () => {
            clientIdToDelete = btn.dataset.id;
            modal.classList.remove("hidden");
        });
    });

    cancelBtn.addEventListener("click", () => {
        modal.classList.add("hidden");
        clientIdToDelete = null;
    });

    confirmBtn.addEventListener("click", () => {
        if (!clientIdToDelete) return;

        fetch("api/clients/delete", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ id: clientIdToDelete })
        })
            .then(response => response.json())
            .then(result => {
                showMessage("Client deleted successfully", "success");
                modal.classList.add("hidden");
                clientIdToDelete = null;
                return fetch("api/clients/");
            })
            .then(res => res.json())
            .then(data => populateClientTable(data.clients))
            .catch(error => {
                console.error("Delete error:", error);
                showMessage("An error occurred while deleting the client", "error");
                modal.classList.add("hidden");
            });
    });
}

function attachClientUpdateListeners(data) {
    const updateButtons = document.querySelectorAll(".update-btn");
    const modal = document.getElementById("editModal");
    const cancelBtn = document.getElementById("cancelEdit");
    const form = document.getElementById("editForm");

    updateButtons.forEach(btn => {
        btn.addEventListener("click", () => {
            const id = btn.dataset.id;
            const client = data.find(c => c.id == id);
            if (!client) return;

            document.getElementById("edit-id").value = client.id;
            document.getElementById("edit-name").value = client.name;
            document.getElementById("edit-email").value = client.email;
            document.getElementById("edit-address").value = client.address;
            document.getElementById("edit-phone").value = client.phone;
            document.getElementById("edit-first-visit-date").value = client.first_visit_date;
            document.getElementById("edit-company-name").value = client.company_name;

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
            address: document.getElementById("edit-address").value,
            phone: document.getElementById("edit-phone").value,
            first_visit_date: document.getElementById("edit-first-visit-date").value,
            company_name: document.getElementById("edit-company-name").value
        };

        fetch("api/clients/update.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(formData)
        })
        .then(res => res.json())
        .then(result => {
            showMessage("Client updated successfully", "success");
            modal.classList.add("hidden");
            return fetch("api/clients/");
        })
        .then(res => res.json())
        .then(data => populateClientTable(data.clients))
        .catch(err => {
            console.error("Update error:", err);
            showMessage("An error occurred while updating the client", "error");
        });
    });
}