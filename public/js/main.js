document.addEventListener("DOMContentLoaded", () => {
    const triggers = document.querySelectorAll(".trigger-slide");
    const wrapper = document.querySelector(".slide-container");
    const visual = document.querySelector(".visual-card");
    const form = document.querySelector(".auth-box");

    triggers.forEach((btn) => {
        btn.addEventListener("click", (e) => {
            e.preventDefault();
            const target = btn.getAttribute("href");
            const dir = btn.dataset.direction;

            // Jika ke REGISTER (slide kiri)
            if (dir === "left") {
                wrapper.classList.add("swap-to-register");
                visual.style.setProperty("--endX", "120%");
                form.style.setProperty("--endX", "-120%");
            }

            // Jika ke LOGIN (slide kanan)
            if (dir === "right") {
                wrapper.classList.add("swap-to-login");
                visual.style.setProperty("--endX", "-120%");
                form.style.setProperty("--endX", "120%");
            }

            setTimeout(() => {
                window.location.href = target;
            }, 1000);
        });
    });
});

document.addEventListener("DOMContentLoaded", function () {
    const headers = document.querySelectorAll(".accordion-header");

    headers.forEach((header) => {
        header.addEventListener("click", () => {
            const body = header.nextElementSibling;
            const openBody = document.querySelector(".accordion-body.show");

            // === COLLAPSE jika diklik lagi ===
            if (body.classList.contains("show")) {
                const currentHeight = body.scrollHeight + "px"; // 1. simpan tinggi sekarang
                body.style.maxHeight = currentHeight; // 2. set dulu biar animasi punya titik awal

                requestAnimationFrame(() => {
                    // 3. tunggu 1 frame
                    requestAnimationFrame(() => {
                        // 4. tunggu 1 frame lagi (penting!)
                        body.style.maxHeight = "0px"; // 5. baru collapse
                    });
                });

                body.classList.remove("show");
                header.classList.remove("active");
                return;
            }

            // === Tutup accordion lain jika ada yang open ===
            if (openBody && openBody !== body) {
                const openHeight = openBody.scrollHeight + "px";
                openBody.style.maxHeight = openHeight;

                requestAnimationFrame(() => {
                    requestAnimationFrame(() => {
                        openBody.style.maxHeight = "0px";
                    });
                });

                openBody.classList.remove("show");
                openBody.previousElementSibling.classList.remove("active");
            }

            // === EXPAND yang diklik ===
            body.classList.add("show");
            header.classList.add("active");
            body.style.maxHeight = body.scrollHeight + "px";
        });
    });
});

// =============== PROFILE PHOTO PREVIEW ==================

// SWITCH TAB
document.querySelectorAll(".tab").forEach((tab) => {
    tab.addEventListener("click", function () {
        document.querySelector(".tab.active").classList.remove("active");
        this.classList.add("active");

        document
            .querySelector(".tab-content.active")
            .classList.remove("active");
        document
            .getElementById("tab-" + this.dataset.tab)
            .classList.add("active");
    });
});

// PREVIEW FOTO PROFIL
const input = document.getElementById("profileImageInput");
const img = document.getElementById("profilePic");

if (input) {
    input.addEventListener("change", () => {
        img.src = URL.createObjectURL(input.files[0]);
        showToast("success", "Foto profil berhasil diperbarui");
    });
}

function previewProfile(event) {
    const file = event.target.files[0];
    if (!file) return;

    // Preview hanya di halaman profile
    document.getElementById("profilePic").src = URL.createObjectURL(file);

    // Simpan ke input hidden untuk dikirim saat submit
    const dt = new DataTransfer();
    dt.items.add(file);
    document.getElementById("hiddenPhotoInput").files = dt.files;
}

let modalCallback = null;

function showConfirmModal(title, text, confirmCallback) {
    document.getElementById("modalTitle").innerText = title;
    document.getElementById("modalText").innerText = text;
    document.getElementById("confirmModal").style.display = "flex";

    modalCallback = confirmCallback;
}

function closeModal() {
    document.getElementById("confirmModal").style.display = "none";
}

document.getElementById("modalConfirmBtn").addEventListener("click", () => {
    if (modalCallback) modalCallback();
    closeModal();
});

// Toast
function showToast(type, message) {
    const toast = document.getElementById("toast");
    toast.className = "toast " + type + " show";
    toast.textContent = message;
    setTimeout(() => toast.classList.remove("show"), 3000);
}

// Preview + auto update navbar
function previewProfile(event) {
    const file = event.target.files[0];
    if (!file) return;

    document.getElementById("profilePic").src = URL.createObjectURL(file);
    document.getElementById("navbarProfilePic").src = URL.createObjectURL(file);

    // pass file ke input hidden di form
    const dt = new DataTransfer();
    dt.items.add(file);
    document.getElementById("hiddenPhotoInput").files = dt.files;
}

// ===== TAB SWITCH HANDLER =====
document.querySelectorAll(".tab").forEach((tab) => {
    tab.addEventListener("click", function () {
        // Hapus active di semua tab & content
        document
            .querySelectorAll(".tab")
            .forEach((t) => t.classList.remove("active"));
        document
            .querySelectorAll(".tab-content")
            .forEach((c) => c.classList.remove("active"));

        // Tambah active ke yang diklik
        this.classList.add("active");
        document
            .getElementById("tab-" + this.dataset.tab)
            .classList.add("active");
    });
});

// PASSWORD VALIDATION
function validatePassword() {
    const current = document.querySelector('[name="current_password"]').value;
    const newpass = document.querySelector('[name="new_password"]').value;
    const confirm = document.querySelector(
        '[name="new_password_confirmation"]'
    ).value;

    // Jika semua kosong, berarti user tidak update password (boleh lanjut)
    if (!current && !newpass && !confirm) return true;

    if (!current) {
        showToast("error", "Masukkan password saat ini!");
        return false;
    }

    if (newpass.length < 8) {
        showToast("error", "Password baru minimal 8 karakter!");
        return false;
    }

    if (newpass !== confirm) {
        showToast("error", "Konfirmasi password tidak cocok!");
        return false;
    }

    showToast("success", "Password telah diperbarui");
    return true;
}

// Submit form utama
function submitActiveForm() {
    const tabDetail = document.getElementById("tab-detail");
    const tabPassword = document.getElementById("tab-password");

    if (tabDetail.classList.contains("active")) {
        document.getElementById("profileForm").submit();
    } else if (tabPassword.classList.contains("active")) {
        document.getElementById("passwordForm").submit();
    }
}

// --- MODAL ---
function openModal(title, text, onConfirm) {
    document.getElementById("modalTitle").textContent = title;
    document.getElementById("modalText").textContent = text;
    document.getElementById("modalConfirmBtn").onclick = onConfirm;
    document.getElementById("confirmModal").style.display = "flex";
}

function closeModal() {
    document.getElementById("confirmModal").style.display = "none";
}

function logoutConfirm() {
    openModal(
        "Konfirmasi Logout",
        "Kamu yakin ingin logout dari akun ini?",
        () => (window.location.href = "/logout")
    );
}

function deleteConfirm() {
    openModal("Hapus Akun", "Akun akan dihapus permanen. Kamu yakin?", () =>
        document.getElementById("deleteForm").submit()
    );
}
