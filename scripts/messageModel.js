function showMessage(message, type = "info") {
    const modal = document.getElementById("messageModal");
    const text = document.getElementById("messageText");
    const closeBtn = document.getElementById("closeMessage");

    // Apply style class based on type: info, success, error
    modal.classList.remove("success", "error", "info");
    modal.classList.add(type);

    text.textContent = message;
    modal.classList.remove("hidden");

    closeBtn.onclick = () => {
        modal.classList.add("hidden");
    };
}