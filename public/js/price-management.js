// ===== PRICE MANAGEMENT SCRIPT =====

// Store original values untuk cancel functionality
const originalValues = {};

// Event delegation untuk handle button clicks
document.addEventListener("DOMContentLoaded", () => {
    document.addEventListener("click", (e) => {
        const button = e.target.closest("button[data-action]");
        if (!button) return;

        const action = button.dataset.action;
        const serviceId = button.dataset.serviceId;

        switch (action) {
            case "edit":
                editPrice(serviceId);
                break;
            case "save":
                savePrice(serviceId);
                break;
            case "cancel":
                cancelEdit(serviceId);
                break;
        }
    });
});

// Edit Mode
function editPrice(serviceId) {
    const row = document.querySelector(`tr[data-service-id="${serviceId}"]`);
    const priceDisplays = row.querySelectorAll(".price-display");
    const priceInputs = row.querySelectorAll(".price-input");
    const btnEdit = row.querySelector(".btn-edit");
    const btnSave = row.querySelector(".btn-save");
    const btnCancel = row.querySelector(".btn-cancel");

    // Store original values
    originalValues[serviceId] = {
        price_reguler: priceInputs[0].value,
        price_express: priceInputs[1].value,
    };

    // Hide display, show inputs
    priceDisplays.forEach((display) => (display.style.display = "none"));
    priceInputs.forEach((input) => (input.style.display = "block"));

    // Toggle buttons
    btnEdit.style.display = "none";
    btnSave.style.display = "inline-flex";
    btnCancel.style.display = "inline-flex";
}

// Save Price
async function savePrice(serviceId) {
    const row = document.querySelector(`tr[data-service-id="${serviceId}"]`);
    const priceInputs = row.querySelectorAll(".price-input");

    const priceReguler = priceInputs[0].value;
    const priceExpress = priceInputs[1].value;

    // Validation
    if (
        !priceReguler ||
        !priceExpress ||
        priceReguler < 0 ||
        priceExpress < 0
    ) {
        showToast("error", "Harga tidak boleh kosong atau negatif!");
        return;
    }

    // Disable buttons saat proses
    const btnSave = row.querySelector(".btn-save");
    const btnCancel = row.querySelector(".btn-cancel");
    btnSave.disabled = true;
    btnCancel.disabled = true;
    btnSave.textContent = "Menyimpan...";

    try {
        const response = await fetch(`/admin/price/update/${serviceId}`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector(
                    'meta[name="csrf-token"]'
                ).content,
                Accept: "application/json",
            },
            body: JSON.stringify({
                price_reguler: parseInt(priceReguler),
                price_express: parseInt(priceExpress),
            }),
        });

        const result = await response.json();

        if (result.success) {
            // Update display values
            const priceDisplays = row.querySelectorAll(".price-display");
            priceDisplays[0].textContent = `Rp ${parseInt(
                priceReguler
            ).toLocaleString("id-ID")}`;
            priceDisplays[1].textContent = `Rp ${parseInt(
                priceExpress
            ).toLocaleString("id-ID")}`;

            // Reset UI
            exitEditMode(serviceId);

            showToast("success", "Harga berhasil diperbarui!");

            // Delete stored original values
            delete originalValues[serviceId];
        } else {
            showToast("error", result.message || "Gagal memperbarui harga");
        }
    } catch (error) {
        console.error("Error:", error);
        showToast("error", "Terjadi kesalahan saat memperbarui harga");
    } finally {
        // Re-enable buttons
        btnSave.disabled = false;
        btnCancel.disabled = false;
        btnSave.innerHTML = `
            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
            </svg>
            Simpan
        `;
    }
}

// Cancel Edit
function cancelEdit(serviceId) {
    const row = document.querySelector(`tr[data-service-id="${serviceId}"]`);
    const priceInputs = row.querySelectorAll(".price-input");

    // Restore original values
    if (originalValues[serviceId]) {
        priceInputs[0].value = originalValues[serviceId].price_reguler;
        priceInputs[1].value = originalValues[serviceId].price_express;
        delete originalValues[serviceId];
    }

    exitEditMode(serviceId);
}

// Exit Edit Mode
function exitEditMode(serviceId) {
    const row = document.querySelector(`tr[data-service-id="${serviceId}"]`);
    const priceDisplays = row.querySelectorAll(".price-display");
    const priceInputs = row.querySelectorAll(".price-input");
    const btnEdit = row.querySelector(".btn-edit");
    const btnSave = row.querySelector(".btn-save");
    const btnCancel = row.querySelector(".btn-cancel");

    // Show display, hide inputs
    priceDisplays.forEach((display) => (display.style.display = "inline"));
    priceInputs.forEach((input) => (input.style.display = "none"));

    // Toggle buttons
    btnEdit.style.display = "inline-flex";
    btnSave.style.display = "none";
    btnCancel.style.display = "none";
}

// Toast Notification
function showToast(type, message) {
    const toast = document.getElementById("toast");
    toast.className = `toast ${type} show`;
    toast.textContent = message;

    setTimeout(() => {
        toast.classList.remove("show");
    }, 3000);
}

// Keyboard shortcuts
document.addEventListener("keydown", (e) => {
    // ESC to cancel all edits
    if (e.key === "Escape") {
        Object.keys(originalValues).forEach((serviceId) => {
            cancelEdit(serviceId);
        });
    }
});
