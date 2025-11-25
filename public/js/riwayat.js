// ===== REMOVE INDIVIDUAL FILTER =====
function removeFilter(filterName) {
    const form = document.getElementById("filterForm");
    const input = form.querySelector(`input[name="${filterName}"]`);

    if (input) {
        input.value = "";
        form.submit();
    }
}

// ===== DROPDOWN FILTER HANDLER =====
document.addEventListener("DOMContentLoaded", () => {
    const filterDropdowns = document.querySelectorAll(
        ".filter-item .dropdown-wrapper"
    );

    filterDropdowns.forEach((dropdown) => {
        const header = dropdown.querySelector(".dropdown-header");
        const body = dropdown.querySelector(".dropdown-body");
        const label = dropdown.querySelector(".dropdown-label");
        const filterName = dropdown.dataset.filter;
        const hiddenInput = document.querySelector(
            `input[name="${filterName}"]`
        );

        // Toggle dropdown
        header.addEventListener("click", () => {
            const isOpen = header.classList.contains("active");

            // Close all dropdowns
            document
                .querySelectorAll(".filter-item .dropdown-header")
                .forEach((h) => {
                    h.classList.remove("active");
                    h.nextElementSibling.classList.remove("show");
                    h.nextElementSibling.style.maxHeight = null;
                });

            // Open clicked dropdown
            if (!isOpen) {
                header.classList.add("active");
                body.classList.add("show");
                body.style.maxHeight = body.scrollHeight + "px";
            }
        });

        // Select item
        dropdown.querySelectorAll(".dropdown-item").forEach((item) => {
            item.addEventListener("click", () => {
                const value = item.dataset.value;
                label.textContent = item.textContent.trim();
                hiddenInput.value = value;

                // Close dropdown
                header.classList.remove("active");
                body.classList.remove("show");
                body.style.maxHeight = null;

                // Submit form
                document.getElementById("filterForm").submit();
            });
        });
    });

    // Close dropdown when clicking outside
    document.addEventListener("click", (e) => {
        if (!e.target.closest(".filter-item .dropdown-wrapper")) {
            document
                .querySelectorAll(".filter-item .dropdown-header")
                .forEach((h) => {
                    h.classList.remove("active");
                    h.nextElementSibling.classList.remove("show");
                    h.nextElementSibling.style.maxHeight = null;
                });
        }
    });
});

// ===== COPY ORDER SN =====
function copySn(orderSn) {
    const tempInput = document.createElement("input");
    tempInput.value = orderSn;
    document.body.appendChild(tempInput);
    tempInput.select();
    document.execCommand("copy");
    document.body.removeChild(tempInput);

    showToast("success", "Nomor pesanan berhasil disalin!");
}

// ===== DELETE CONFIRMATION =====
let orderToDelete = null;

function confirmDelete(orderSn) {
    orderToDelete = orderSn;
    document.getElementById("deleteModal").style.display = "flex";
}

function closeDeleteModal() {
    document.getElementById("deleteModal").style.display = "none";
    orderToDelete = null;
}

// Confirm delete button handler
document
    .getElementById("confirmDeleteBtn")
    .addEventListener("click", async () => {
        if (!orderToDelete) return;

        const csrfToken = document.querySelector(
            'meta[name="csrf-token"]'
        ).content;

        try {
            const response = await fetch(`/riwayat/${orderToDelete}`, {
                method: "DELETE",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrfToken,
                    Accept: "application/json",
                },
            });

            const result = await response.json();

            if (result.success) {
                showToast("success", "Riwayat transaksi berhasil dihapus");

                const card = document.querySelector(
                    `[data-order-sn="${orderToDelete}"]`
                );
                if (card) {
                    card.style.opacity = "0";
                    card.style.transform = "scale(0.9)";
                    setTimeout(() => {
                        card.remove();

                        const remainingOrders =
                            document.querySelectorAll(".order-card");
                        if (remainingOrders.length === 0) {
                            location.reload();
                        }
                    }, 300);
                }

                closeDeleteModal();
            } else {
                showToast("error", result.message || "Gagal menghapus riwayat");
            }
        } catch (error) {
            console.error("Error:", error);
            showToast("error", "Terjadi kesalahan saat menghapus riwayat");
        }
    });

// ===== TOAST NOTIFICATION =====
function showToast(type, message) {
    const toast = document.getElementById("toast");
    toast.className = `toast ${type} show`;
    toast.textContent = message;

    setTimeout(() => {
        toast.classList.remove("show");
    }, 3000);
}

// ===== CLOSE MODAL ON OUTSIDE CLICK =====
document.getElementById("deleteModal").addEventListener("click", (e) => {
    if (e.target.id === "deleteModal") {
        closeDeleteModal();
    }
});

// ===== SMOOTH SCROLL TO TOP =====
window.addEventListener("load", () => {
    window.scrollTo({ top: 0, behavior: "smooth" });
});

// ===== FILTER AUTO SUBMIT ON CHANGE =====
document.addEventListener("DOMContentLoaded", () => {
    const filterForm = document.getElementById("filterForm");

    if (filterForm) {
        const orderCards = document.querySelectorAll(".order-card");
        orderCards.forEach((card, index) => {
            card.style.animation = `fadeInUp 0.4s ease ${index * 0.1}s both`;
        });
    }
});

// ===== ANIMATION KEYFRAMES =====
const style = document.createElement("style");
style.textContent = `
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.order-card {
    transition: all 0.3s ease;
}
`;
document.head.appendChild(style);
