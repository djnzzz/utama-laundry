// ===== ADMIN MAIN SCRIPT =====

document.addEventListener("DOMContentLoaded", () => {
    // Set active nav item based on current URL
    const currentPath = window.location.pathname;
    const navItems = document.querySelectorAll(".nav-item");

    navItems.forEach((item) => {
        const href = item.getAttribute("href");
        if (href && currentPath.includes(href) && href !== "/") {
            item.classList.add("active");
        }
    });

    // Smooth scroll animations
    const cards = document.querySelectorAll(".stat-card, .action-card");
    cards.forEach((card, index) => {
        card.style.animation = `fadeInUp 0.5s ease ${index * 0.1}s both`;
    });

    // Sidebar toggle for mobile
    const sidebar = document.querySelector(".sidebar");
    const mainContent = document.querySelector(".main-content");

    // Create mobile toggle button
    if (window.innerWidth <= 768) {
        const toggleBtn = document.createElement("button");
        toggleBtn.className = "mobile-toggle";
        toggleBtn.innerHTML = "☰";
        toggleBtn.style.cssText = `
            position: fixed;
            top: 20px;
            left: 20px;
            z-index: 1000;
            background: #006eff;
            color: white;
            border: none;
            border-radius: 8px;
            padding: 10px 15px;
            font-size: 20px;
            cursor: pointer;
            display: none;
        `;

        if (window.innerWidth <= 768) {
            toggleBtn.style.display = "block";
        }

        document.body.appendChild(toggleBtn);

        toggleBtn.addEventListener("click", () => {
            sidebar.classList.toggle("mobile-open");
        });
    }

    // Close sidebar when clicking outside on mobile
    document.addEventListener("click", (e) => {
        if (window.innerWidth <= 768) {
            if (
                !sidebar.contains(e.target) &&
                !e.target.classList.contains("mobile-toggle")
            ) {
                sidebar.classList.remove("mobile-open");
            }
        }
    });
});

// Animation keyframes
const style = document.createElement("style");
style.textContent = `
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

@media (max-width: 768px) {
    .sidebar {
        transform: translateX(-100%);
        transition: transform 0.3s ease;
        z-index: 999;
    }
    
    .sidebar.mobile-open {
        transform: translateX(0);
    }
    
    .main-content {
        margin-left: 0 !important;
        max-width: 100% !important;
    }
}
`;
document.head.appendChild(style);
