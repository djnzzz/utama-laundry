// ===== STATUS CUCIAN PAGE SCRIPT =====

document.addEventListener("DOMContentLoaded", () => {
    const searchForm = document.getElementById("searchForm");
    const orderSnInput = document.getElementById("orderSnInput");

    // Handle search form submit
    if (searchForm) {
        searchForm.addEventListener("submit", (e) => {
            e.preventDefault();

            const orderSn = orderSnInput.value.trim();

            if (!orderSn) {
                showToast("error", "Masukkan ID pesanan terlebih dahulu!");
                return;
            }

            // Redirect dengan query parameter
            window.location.href = `/status?order_sn=${encodeURIComponent(
                orderSn
            )}`;
        });
    }

    // Auto-focus input saat halaman load
    if (orderSnInput && !orderSnInput.value) {
        orderSnInput.focus();
    }

    // Format ID pesanan otomatis saat user mengetik
    if (orderSnInput) {
        orderSnInput.addEventListener("input", (e) => {
            let value = e.target.value.toUpperCase();

            // Auto format: UL-YYYYMMDD-NNNNNN
            // Hapus karakter selain huruf, angka, dan dash
            value = value.replace(/[^A-Z0-9-]/g, "");

            e.target.value = value;
        });
    }

    // Animasi smooth scroll ke result
    const resultContainer = document.getElementById("resultContainer");
    if (
        resultContainer &&
        resultContainer.children.length > 0 &&
        window.location.search.includes("order_sn")
    ) {
        setTimeout(() => {
            resultContainer.scrollIntoView({
                behavior: "smooth",
                block: "center",
            });
        }, 300);
    }
});

// Toast notification function
function showToast(type, message) {
    const toast = document.getElementById("toast");
    toast.className = `toast ${type} show`;
    toast.textContent = message;

    setTimeout(() => {
        toast.classList.remove("show");
    }, 3000);
}

// Keyboard shortcut: Press '/' to focus search
document.addEventListener("keydown", (e) => {
    if (e.key === "/" && !e.ctrlKey && !e.metaKey) {
        e.preventDefault();
        const orderSnInput = document.getElementById("orderSnInput");
        if (orderSnInput) {
            orderSnInput.focus();
            orderSnInput.select();
        }
    }
});
