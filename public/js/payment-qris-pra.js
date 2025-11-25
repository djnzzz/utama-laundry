// ===== TIMER COUNTDOWN =====
let timeLeft = 30 * 60; // 30 menit dalam detik
let timerInterval;
let orderSn = document.getElementById("orderSn").value;

function startTimer() {
    timerInterval = setInterval(() => {
        timeLeft--;
        updateTimerDisplay();

        // Warning saat tersisa 5 menit
        if (timeLeft === 5 * 60) {
            document.getElementById("timerBox").classList.add("warning");
            showToast(
                "warning",
                "Perhatian: Waktu pembayaran tersisa 5 menit!"
            );
        }

        // Timeout
        if (timeLeft <= 0) {
            clearInterval(timerInterval);
            handleTimeout();
        }
    }, 1000);
}

function updateTimerDisplay() {
    const minutes = Math.floor(timeLeft / 60);
    const seconds = timeLeft % 60;
    const display = `${minutes.toString().padStart(2, "0")}:${seconds
        .toString()
        .padStart(2, "0")}`;
    document.getElementById("countdown").textContent = display;
}

function handleTimeout() {
    // Batalkan pesanan otomatis
    fetch("/payment/auto-cancel", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN":
                document.querySelector('input[name="_token"]')?.value || "",
        },
        body: JSON.stringify({ order_sn: orderSn }),
    }).then(() => {
        document.getElementById("timeoutModal").style.display = "flex";
    });
}

// ===== COPY ORDER SN =====
function copyOrderSn() {
    const orderSnInput = document.getElementById("orderSn");
    orderSnInput.select();
    document.execCommand("copy");
    showToast("success", "Nomor Pesanan berhasil disalin!");
}

// ===== UPLOAD FILE HANDLING =====
const uploadArea = document.getElementById("uploadArea");
const previewArea = document.getElementById("previewArea");
const proofFile = document.getElementById("proofFile");
const previewImage = document.getElementById("previewImage");
const btnPay = document.getElementById("btnPay");

// Click to upload
uploadArea.addEventListener("click", () => proofFile.click());

// Drag & drop
uploadArea.addEventListener("dragover", (e) => {
    e.preventDefault();
    uploadArea.style.borderColor = "#006eff";
    uploadArea.style.background = "#f0f7ff";
});

uploadArea.addEventListener("dragleave", () => {
    uploadArea.style.borderColor = "#d3d3d3";
    uploadArea.style.background = "transparent";
});

uploadArea.addEventListener("drop", (e) => {
    e.preventDefault();
    uploadArea.style.borderColor = "#d3d3d3";
    uploadArea.style.background = "transparent";

    const files = e.dataTransfer.files;
    if (files.length > 0) {
        handleFile(files[0]);
    }
});

// File input change
proofFile.addEventListener("change", (e) => {
    if (e.target.files.length > 0) {
        handleFile(e.target.files[0]);
    }
});

function handleFile(file) {
    // Validasi tipe file
    const validTypes = [
        "image/jpeg",
        "image/jpg",
        "image/png",
        "application/pdf",
    ];
    if (!validTypes.includes(file.type)) {
        showToast(
            "error",
            "Format file tidak valid! Gunakan JPG, PNG, atau PDF"
        );
        return;
    }

    // Validasi ukuran (max 5MB)
    if (file.size > 5 * 1024 * 1024) {
        showToast("error", "Ukuran file terlalu besar! Maksimal 5MB");
        return;
    }

    // Preview image
    if (file.type.startsWith("image/")) {
        const reader = new FileReader();
        reader.onload = (e) => {
            previewImage.src = e.target.result;
            uploadArea.classList.add("hidden");
            previewArea.classList.remove("hidden");
            btnPay.disabled = false;
        };
        reader.readAsDataURL(file);
    } else {
        // Jika PDF, tampilkan text saja
        previewImage.src = "";
        uploadArea.classList.add("hidden");
        previewArea.classList.remove("hidden");
        previewArea.innerHTML = `
            <div style="padding: 40px; text-align: center; background: white; border-radius: 12px; border: 2px solid #d7d7d7;">
                <p style="font-weight: 600; margin-bottom: 10px;">📄 ${file.name}</p>
                <p style="color: #666; font-size: 0.9em;">File PDF berhasil dipilih</p>
                <button type="button" class="btn-remove" onclick="removeFile()">Hapus</button>
            </div>
        `;
        btnPay.disabled = false;
    }

    showToast("success", "File berhasil dipilih!");
}

function removeFile() {
    proofFile.value = "";
    uploadArea.classList.remove("hidden");
    previewArea.classList.add("hidden");
    previewImage.src = "";
    btnPay.disabled = true;
    showToast("info", "File dihapus");
}

// ===== FORM SUBMIT =====
document.getElementById("uploadForm").addEventListener("submit", async (e) => {
    e.preventDefault();

    if (!proofFile.files[0]) {
        showToast("error", "Pilih bukti pembayaran terlebih dahulu!");
        return;
    }

    const formData = new FormData(e.target);
    const btnText = document.getElementById("btnText");

    btnPay.disabled = true;
    btnPay.classList.add("loading");
    btnText.textContent = "Mengirim...";

    try {
        const response = await fetch("/payment/upload-proof", {
            method: "POST",
            body: formData,
            headers: {
                "X-CSRF-TOKEN":
                    document.querySelector('meta[name="csrf-token"]')
                        ?.content || "",
            },
        });

        const result = await response.json();

        if (result.success) {
            showToast("success", "Bukti pembayaran berhasil dikirim!");

            // Tampilkan status verifikasi
            document.querySelector(".upload-box").classList.add("hidden");
            document
                .getElementById("verificationStatus")
                .classList.remove("hidden");

            // Mulai polling status verifikasi
            startVerificationPolling();
        } else {
            throw new Error(
                result.message || "Gagal mengirim bukti pembayaran"
            );
        }
    } catch (error) {
        showToast("error", error.message);
        btnPay.disabled = false;
        btnPay.classList.remove("loading");
        btnText.textContent = "Kirim Bukti Pembayaran";
    }
});

// ===== POLLING VERIFIKASI =====
let pollingInterval;

function startVerificationPolling() {
    pollingInterval = setInterval(async () => {
        try {
            const response = await fetch(`/payment/check-status/${orderSn}`);
            const result = await response.json();

            if (result.status === "verified") {
                clearInterval(pollingInterval);
                clearInterval(timerInterval);
                showSuccessModal();
            } else if (result.status === "rejected") {
                clearInterval(pollingInterval);
                showToast(
                    "error",
                    "Pembayaran ditolak oleh admin. Silakan upload ulang bukti yang valid."
                );

                // Reset form
                document
                    .querySelector(".upload-box")
                    .classList.remove("hidden");
                document
                    .getElementById("verificationStatus")
                    .classList.add("hidden");
                removeFile();
            }
        } catch (error) {
            console.error("Error checking status:", error);
        }
    }, 3000);
}

function showSuccessModal() {
    document.getElementById("successModal").style.display = "flex";
}

function redirectToTracking() {
    window.location.href = `/status?order_sn=${orderSn}`;
}

// ===== CANCEL ORDER =====
function cancelOrder() {
    document.getElementById("cancelModal").style.display = "flex";
}

function confirmCancel() {
    const csrfToken = document.querySelector('input[name="_token"]')?.value;

    if (!csrfToken) {
        showToast("error", "CSRF token tidak ditemukan");
        return;
    }

    fetch("/payment/cancel-order", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": csrfToken,
        },
        body: JSON.stringify({ order_sn: orderSn }),
    })
        .then((response) => response.json())
        .then((result) => {
            if (result.success) {
                showToast("success", "Pesanan berhasil dibatalkan");
                setTimeout(() => {
                    window.location.href = "/order";
                }, 1500);
            } else {
                showToast(
                    "error",
                    result.message || "Gagal membatalkan pesanan"
                );
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            showToast("error", "Gagal membatalkan pesanan");
        });
}

function closeModal() {
    document.getElementById("cancelModal").style.display = "none";
}

// ===== TOAST NOTIFICATION =====
function showToast(type, message) {
    const toast = document.getElementById("toast");
    toast.className = `toast ${type} show`;
    toast.textContent = message;
    setTimeout(() => toast.classList.remove("show"), 3000);
}

// ===== INIT =====
document.addEventListener("DOMContentLoaded", () => {
    startTimer();
    checkExistingProof();
});

async function checkExistingProof() {
    try {
        const response = await fetch(`/payment/check-proof/${orderSn}`);
        const result = await response.json();

        if (result.has_proof) {
            document.querySelector(".upload-box").classList.add("hidden");
            document
                .getElementById("verificationStatus")
                .classList.remove("hidden");
            startVerificationPolling();
        }
    } catch (error) {
        console.error("Error checking proof:", error);
    }
}

// Cleanup saat user leave page
window.addEventListener("beforeunload", () => {
    if (timerInterval) clearInterval(timerInterval);
    if (pollingInterval) clearInterval(pollingInterval);
});
