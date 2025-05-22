<aside class="sider bg-blur box-shadow r24 f-bet f-col" id="sider">
    <div class="f-bet w-100 p8">
        <h1><span>SAV</span>VY</h1>
        <i class="fa-solid fa-bars-staggered txt-secondary pointer" id="btn-sider"></i>
    </div>

    <nav class="w-100">
        <a href="./"><i class="fa-solid fa-home"></i> <span>Home</span></a>
        <a href="tasks"><i class="fa-solid fa-list-check"></i> <span>Tasks</span></a>
        <a href="projects"><i class="fa-solid fa-diagram-project"></i> <span>Projects</span></a>
        <a href="reciepts"><i class="fa-solid fa-file-invoice-dollar"></i> <span>Reciepts</span></a>
        <a href="logs"><i class="fa-solid fa-font-awesome"></i> <span>Logs</span></a>
        <a href="reports"><i class="fa-solid fa-file-circle-check"></i> <span>Reports</span></a>
        <a href="clients"><i class="fa-solid fa-users"></i> <span>Clients</span></a>
        <a href="payments"><i class="fa-solid fa-money-check-dollar"></i> <span>Payments</span></a>
        <a href="employees"><i class="fa-solid fa-user-tie"></i> <span>Employees</span></a>
    </nav>

    <div class="logout w-100 p8 pointer"><i class="fa-solid fa-sign-out"></i> <span>log out</span></div>
</aside>

<!-- كود إرسال طلب لتسجيل الخروج: -->
<script>
    document.querySelector(".logout").addEventListener("click", () => {
        fetch("api/auth/logout.php", {
                method: "POST"
            })
            .then(res => {
                if (!res.ok) throw new Error("Logout failed");
                return res.json();
            })
            .then(data => {
                window.location.href = "login";
            })
            .catch(err => {
                console.error("Logout error:", err);
                showMessage("حدث خطأ أثناء تسجيل الخروج", "error");
            });
    });
</script>