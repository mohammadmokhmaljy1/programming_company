document.addEventListener("DOMContentLoaded", () => {
    fetch("api/logs/")
        .then(response => {
            if (!response.ok) throw new Error("Network response was not ok");
            return response.json();
        })
        .then(data => {
            populateTable(data.logs);
        } )
        .catch(error => {
            console.error("Fetch error:", error);
        });
});

function populateTable(data) {
    const tbody = document.querySelector("tbody");
    tbody.innerHTML = "";

    data.forEach((item, index) => {
        const row = document.createElement("tr");

        row.innerHTML = `
            <td>${index + 1}</td>
            <td>${item.employee_name}</td>
            <td>${item.action}</td>
            <td>${item.action_time}</td>
        `;

        tbody.appendChild(row);
    });
}