const btnSider = document.getElementById("btn-sider");
const sider = document.getElementById("sider");

btnSider.addEventListener("click", () => {
    sider.classList.toggle("wide");
});

// sider.addEventListener("mouseenter", () => {
//     sider.classList.add("wide");
// });

// sider.addEventListener("mouseleave", () => {
//     sider.classList.remove("wide");
// });