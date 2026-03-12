// Flash auto-dismiss
document.querySelectorAll(".flash").forEach((el) => {
    setTimeout(() => el.remove(), 3000);
});

// Sticky header shadow
const siteHeader = document.getElementById("siteHeader");
if (siteHeader) {
    window.addEventListener(
        "scroll",
        () => {
            siteHeader.classList.toggle("is-scrolled", window.scrollY > 4);
        },
        { passive: true },
    );
}

// Mobile nav toggle
const navToggle = document.getElementById("navToggle");
const mainNav = document.getElementById("mainNav");
if (navToggle && mainNav) {
    navToggle.addEventListener("click", () => {
        const open = mainNav.classList.toggle("is-open");
        navToggle.setAttribute("aria-expanded", String(open));
    });
    document.addEventListener("click", (e) => {
        if (!navToggle.contains(e.target) && !mainNav.contains(e.target)) {
            mainNav.classList.remove("is-open");
            navToggle.setAttribute("aria-expanded", "false");
        }
    });
}

// User avatar dropdown
const userMenuBtn = document.getElementById("userMenuBtn");
const userDropdown = document.getElementById("userDropdown");
if (userMenuBtn && userDropdown) {
    userMenuBtn.addEventListener("click", (e) => {
        e.stopPropagation();
        const open = userDropdown.classList.toggle("is-open");
        userMenuBtn.setAttribute("aria-expanded", String(open));
        userDropdown.setAttribute("aria-hidden", String(!open));
    });
    document.addEventListener("click", (e) => {
        if (!userMenuBtn.contains(e.target)) {
            userDropdown.classList.remove("is-open");
            userMenuBtn.setAttribute("aria-expanded", "false");
            userDropdown.setAttribute("aria-hidden", "true");
        }
    });
}

// Sidebar drawer (mobile)
const sidebar = document.getElementById("sidebar");
const sidebarToggle = document.getElementById("sidebarToggle");
const sidebarClose = document.getElementById("sidebarClose");
const sidebarBackdrop = document.getElementById("sidebarBackdrop");

function openSidebar() {
    if (!sidebar) return;
    sidebar.classList.add("is-open");
    sidebarBackdrop?.classList.add("is-active");
    sidebarToggle?.setAttribute("aria-expanded", "true");
    document.body.style.overflow = "hidden";
}

function closeSidebar() {
    if (!sidebar) return;
    sidebar.classList.remove("is-open");
    sidebarBackdrop?.classList.remove("is-active");
    sidebarToggle?.setAttribute("aria-expanded", "false");
    document.body.style.overflow = "";
}

if (sidebarToggle) sidebarToggle.addEventListener("click", openSidebar);
if (sidebarClose) sidebarClose.addEventListener("click", closeSidebar);
if (sidebarBackdrop) sidebarBackdrop.addEventListener("click", closeSidebar);
document.addEventListener("keydown", (e) => {
    if (e.key === "Escape") closeSidebar();
});
