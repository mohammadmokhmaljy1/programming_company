<aside class="sider bg-blur box-shadow r24 f-bet f-col" id="sider">
    <div class="f-bet w-100 p8">
        <h1>إدراك</h1>
        <i class="fa-solid fa-bars-staggered txt-secondary pointer" id="btn-sider"></i>
    </div>

    <nav class="w-100">
        <?php
        if($_SESSION['employee_permission'] == 'admin') {
            echo
            '<a href="./"><i class="fa-solid fa-home"></i> <span>الرئيسية</span></a>
            <a href="tasks"><i class="fa-solid fa-list-check"></i> <span>المهام</span></a>
            <a href="projects"><i class="fa-solid fa-diagram-project"></i> <span>المشاريع</span></a>
            <a href="reciepts"><i class="fa-solid fa-file-invoice-dollar"></i> <span>الإيصالات</span></a>
            <a href="logs"><i class="fa-solid fa-font-awesome"></i> <span>العمليات</span></a>
            <a href="reports"><i class="fa-solid fa-file-circle-check"></i> <span>التقارير</span></a>
            <a href="clients"><i class="fa-solid fa-users"></i> <span>العملاء</span></a>
            <a href="payments"><i class="fa-solid fa-money-check-dollar"></i> <span>المدفوعات</span></a>
            <a href="employees"><i class="fa-solid fa-user-tie"></i> <span>الموظفين</span></a>';
        }
        if($_SESSION['employee_permission'] == "manager") {
            echo '
            <a href="tasks"><i class="fa-solid fa-list-check"></i> <span>المهام</span></a>
            <a href="projects"><i class="fa-solid fa-diagram-project"></i> <span>المشاريع</span></a>
            <a href="logs"><i class="fa-solid fa-font-awesome"></i> <span>العمليات</span></a>
            <a href="reports"><i class="fa-solid fa-file-circle-check"></i> <span>التقارير</span></a>
            <a href="clients"><i class="fa-solid fa-users"></i> <span>العملاء</span></a>
            <a href="employees"><i class="fa-solid fa-user-tie"></i> <span>الموظفين</span></a>
            ';
        }
        if($_SESSION['employee_permission'] == "accounter") {
            echo '
            <a href="./"><i class="fa-solid fa-home"></i> <span>الرئيسية</span></a>
            <a href="reciepts"><i class="fa-solid fa-file-invoice-dollar"></i> <span>الإيصالات</span></a>
            <a href="reports"><i class="fa-solid fa-file-circle-check"></i> <span>التقارير</span></a>
            <a href="clients"><i class="fa-solid fa-users"></i> <span>العملاء</span></a>
            <a href="payments"><i class="fa-solid fa-money-check-dollar"></i> <span>المدفوعات</span></a>
            '; 
        }
        if($_SESSION['employee_permission'] == "staff") {
            echo '
            <a href="tasks"><i class="fa-solid fa-list-check"></i> <span>المهام</span></a>
            <a href="projects"><i class="fa-solid fa-diagram-project"></i> <span>المشاريع</span></a>
            <a href="reports"><i class="fa-solid fa-file-circle-check"></i> <span>التقارير</span></a>
            ';
        }
        
        // echo $_SESSION['employee_permission'];
        ?>
    </nav>

    <div class="f-cen g8">
        <div class="logout p8 pointer"><i class="fa-solid fa-sign-out"></i> <span>تسجيل خروج</span></div>
        <a href="../" class="p8 pointer">En</a>
    </div>
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