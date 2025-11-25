let hargaLaundry = {};
const beratMap = { "<=3kg": 3, "4-6kg": 6, ">=7kg": 10 };

document.addEventListener("DOMContentLoaded", () => {
    // === Ambil harga dari backend ===
    fetch("/laundry-services")
        .then((res) => res.json())
        .then((data) => {
            data.forEach((item) => {
                hargaLaundry[item.code] = {
                    Reguler: item.price_reguler,
                    Express: item.price_express,
                    type: item.type,
                    name: item.name,
                };
            });
            console.log("Harga laundry berhasil dimuat:", hargaLaundry);
        })
        .catch((err) => console.error("Gagal memuat data harga:", err));

    // === Dropdown handler ===
    document.querySelectorAll(".dropdown-wrapper").forEach((dropdown) => {
        const header = dropdown.querySelector(".dropdown-header");
        const body = dropdown.querySelector(".dropdown-body");
        const label = dropdown.querySelector(".dropdown-label");
        const hiddenInput = dropdown.querySelector("input[type=hidden]");

        header.addEventListener("click", () => {
            const open = header.classList.contains("active");

            // Tutup semua dropdown lainnya
            document
                .querySelectorAll(".dropdown-body.show")
                .forEach((openList) => {
                    openList.classList.remove("show");
                    openList.style.maxHeight = null;
                    openList.previousElementSibling.classList.remove("active");
                });

            // Toggle dropdown yang diklik
            if (!open) {
                header.classList.add("active");
                body.classList.add("show");
                body.style.maxHeight = body.scrollHeight + "px";
            }
        });

        dropdown.querySelectorAll(".dropdown-item").forEach((item) => {
            item.addEventListener("click", () => {
                label.textContent = item.textContent.trim();

                // PERBAIKAN: Jika ini dropdown berat, konversi ke angka
                if (hiddenInput.id === "beratInput") {
                    const beratString = item.dataset.value;
                    const beratAngka = beratMap[beratString] || 0;
                    hiddenInput.value = beratAngka; // Simpan ANGKA, bukan string
                    console.log(
                        `Berat dipilih: ${beratString} → ${beratAngka} kg`
                    );
                } else {
                    hiddenInput.value = item.dataset.value;
                }

                // Hapus class selected dari semua item
                dropdown
                    .querySelectorAll(".dropdown-item")
                    .forEach((i) => i.classList.remove("selected"));
                item.classList.add("selected");

                // Tutup dropdown
                body.classList.remove("show");
                header.classList.remove("active");
                body.style.maxHeight = null;

                // Jika layanan dipilih → tampilkan form dinamis
                if (hiddenInput.id === "layananInput") {
                    handleServiceSelection(item.dataset.value);
                }

                // Hitung ulang total
                hitungTotal();
            });
        });
    });

    // === Form dinamis ===
    const formPakaian = document.getElementById("formPakaian");
    const formItem = document.getElementById("formItem");
    const jumlahPakaianDalam = document.getElementById("jumlahPakaianDalam");

    window.handleServiceSelection = (value) => {
        // Reset form sebelumnya
        formPakaian.classList.add("hidden");
        formItem.classList.add("hidden");

        // Reset input fields
        document.querySelector("input[name='estimasi_berat']").value = "";
        document.querySelector("input[name='jumlah_item']").value = "";
        document.querySelector("input[name='jumlah_pakaian_dalam']").value = "";

        // Uncheck radio pakaian dalam
        document
            .querySelectorAll("input[name='pakaian_dalam']")
            .forEach((radio) => {
                radio.checked = false;
            });
        jumlahPakaianDalam.classList.add("hidden");

        // Reset label dropdown berat
        const labelBerat = document.getElementById("labelBerat");
        if (labelBerat) {
            labelBerat.textContent = "--Pilih Estimasi Berat Cucian--";
        }

        // Tampilkan form yang sesuai
        if (
            [
                "pakaian_ck_setrika",
                "pakaian_cuci_kering",
                "pakaian_setrika",
            ].includes(value)
        ) {
            formPakaian.classList.remove("hidden");
        } else if (
            ["laundry_selimut", "laundry_sprei", "laundry_boneka"].includes(
                value
            )
        ) {
            formItem.classList.remove("hidden");
        }

        // Hitung ulang setelah reset
        hitungTotal();
    };

    // === Radio "Ada Pakaian Dalam?" ===
    document.querySelectorAll("input[name=pakaian_dalam]").forEach((radio) => {
        radio.addEventListener("change", () => {
            if (radio.value === "Ya") {
                jumlahPakaianDalam.classList.remove("hidden");
            } else {
                jumlahPakaianDalam.classList.add("hidden");
                document.querySelector(
                    "input[name='jumlah_pakaian_dalam']"
                ).value = "";
            }
            hitungTotal();
        });
    });

    // === Dropdown pembayaran dengan icon ===
    document
        .querySelectorAll(".payment-method .dropdown-item")
        .forEach((item) => {
            item.addEventListener("click", () => {
                const wrapper = item.closest(".dropdown-wrapper");
                const label = wrapper.querySelector(".dropdown-label");
                const hiddenInput =
                    document.getElementById("paymentMethodInput");

                const icon = item.querySelector("img");
                const text = item.textContent.trim();

                // Update label dengan icon
                if (icon) {
                    const iconClone = icon.cloneNode(true);
                    label.innerHTML = "";
                    label.appendChild(iconClone);
                    label.append(" " + text);
                } else {
                    label.textContent = text;
                }

                hiddenInput.value = item.dataset.value;

                // Validasi ulang
                hitungTotal();
            });
        });

    // === Event listener untuk input number ===
    document.querySelectorAll("input[type='number']").forEach((el) => {
        el.addEventListener("input", hitungTotal);
        el.addEventListener("change", hitungTotal);
    });
});

// === PERHITUNGAN TOTAL ===
function hitungTotal() {
    const serviceCode = document.getElementById("layananInput").value;
    const paket = document.getElementById("paketInput").value;
    const kg = parseInt(document.getElementById("beratInput").value) || 0; // SUDAH ANGKA dari dropdown handler
    const jumlahItem =
        parseInt(document.querySelector("input[name='jumlah_item']").value) ||
        0;
    const pakaianDalam = document.querySelector(
        "input[name='pakaian_dalam']:checked"
    )?.value;
    const jumlahPakaianDalam =
        parseInt(
            document.querySelector("input[name='jumlah_pakaian_dalam']").value
        ) || 0;

    const totalHargaText = document.getElementById("totalHargaText");
    const totalHargaInput = document.getElementById("totalHargaInput");
    const detailBox = document.getElementById("detailHargaList");

    // Jika belum ada layanan atau paket, reset
    if (!serviceCode || !paket) {
        totalHargaText.textContent = "Rp 0";
        detailBox.innerHTML = "";
        totalHargaInput.value = 0;
        validateForm();
        return;
    }

    const data = hargaLaundry[serviceCode];
    if (!data) {
        console.warn("Data layanan tidak ditemukan untuk:", serviceCode);
        validateForm();
        return;
    }

    const harga = data[paket];
    let total = 0;
    let detailHTML = "";

    // === PERHITUNGAN UNTUK LAYANAN KILOAN ===
    if (data.type === "kiloan") {
        const hargaUnderwear = 5000;
        const tambahan =
            pakaianDalam === "Ya" ? jumlahPakaianDalam * hargaUnderwear : 0;

        total = harga * kg + tambahan;

        detailHTML += `<span>Layanan: ${data.name}</span>`;
        detailHTML += `<span>Paket: ${paket}</span>`;
        detailHTML += `<span>Estimasi Berat: ${kg} kg</span>`;
        detailHTML += `<span>Harga per kg: Rp ${harga.toLocaleString(
            "id-ID"
        )}</span>`;

        if (tambahan > 0) {
            detailHTML += `<span>Tambahan pakaian dalam (${jumlahPakaianDalam} item): Rp ${tambahan.toLocaleString(
                "id-ID"
            )}</span>`;
        }
    }
    // === PERHITUNGAN UNTUK LAYANAN NON-KILOAN ===
    else {
        total = harga * jumlahItem;

        detailHTML += `<span>Layanan: ${data.name}</span>`;
        detailHTML += `<span>Paket: ${paket}</span>`;
        detailHTML += `<span>Jumlah item: ${jumlahItem}</span>`;
        detailHTML += `<span>Harga per item: Rp ${harga.toLocaleString(
            "id-ID"
        )}</span>`;
    }

    // Pembulatan ke ratusan terdekat
    total = Math.ceil(total / 100) * 100;

    // Update tampilan
    detailBox.innerHTML = detailHTML;
    totalHargaText.textContent = `Rp ${total.toLocaleString("id-ID")}`;
    totalHargaInput.value = total;

    console.log("Perhitungan:", {
        service: data.name,
        paket: paket,
        berat_kg: kg,
        harga_per_kg: harga,
        total: total,
    });

    // Validasi form
    validateForm();
}

// === VALIDASI FORM ===
function validateForm() {
    const paket = document.getElementById("paketInput").value;
    const serviceCode = document.getElementById("layananInput").value;
    const estimasiBerat = document.getElementById("beratInput").value; // Sudah angka (3, 6, atau 10)
    const jumlahItem = document.querySelector(
        "input[name='jumlah_item']"
    ).value;
    const pakaianDalam = document.querySelector(
        "input[name='pakaian_dalam']:checked"
    );
    const jumlahPakaianDalam = document.querySelector(
        "input[name='jumlah_pakaian_dalam']"
    ).value;
    const paymentMethod = document.getElementById("paymentMethodInput").value;
    const total =
        parseInt(document.getElementById("totalHargaInput").value) || 0;

    let valid = true;
    let errors = [];

    // === CEK ISIAN WAJIB ===
    if (!paket) {
        valid = false;
        errors.push("Paket belum dipilih");
    }

    if (!serviceCode) {
        valid = false;
        errors.push("Layanan belum dipilih");
    }

    if (!paymentMethod) {
        valid = false;
        errors.push("Metode pembayaran belum dipilih");
    }

    // === KHUSUS LAYANAN PAKAIAN ===
    if (serviceCode && serviceCode.includes("pakaian")) {
        if (!pakaianDalam) {
            valid = false;
            errors.push("Pilihan pakaian dalam belum dipilih");
        }

        if (!estimasiBerat || estimasiBerat === "0") {
            valid = false;
            errors.push("Estimasi berat belum dipilih");
        }

        if (
            pakaianDalam?.value === "Ya" &&
            (!jumlahPakaianDalam || jumlahPakaianDalam === "0")
        ) {
            valid = false;
            errors.push("Jumlah pakaian dalam harus diisi");
        }
    }

    // === KHUSUS NON-KILOAN ===
    if (serviceCode && serviceCode.includes("laundry_")) {
        if (!jumlahItem || jumlahItem === "0") {
            valid = false;
            errors.push("Jumlah item harus diisi");
        }
    }

    // === CEK TOTAL ===
    if (total <= 0) {
        valid = false;
        errors.push("Total harga tidak valid");
    }

    // Debug log
    if (!valid) {
        console.log("Form tidak valid:", errors);
    } else {
        console.log("Form valid! Siap submit dengan data:", {
            paket,
            serviceCode,
            estimasiBerat: estimasiBerat + " kg",
            paymentMethod,
            total,
        });
    }

    // Update tampilan tombol
    const submitBtn = document.querySelector(".order-submit");
    submitBtn.disabled = !valid;

    if (valid) {
        submitBtn.style.opacity = "1";
        submitBtn.style.cursor = "pointer";
        submitBtn.classList.remove("disabled");
    } else {
        submitBtn.style.opacity = "0.6";
        submitBtn.style.cursor = "not-allowed";
        submitBtn.classList.add("disabled");
    }
}

// === HANDLE FORM SUBMIT ===
document.querySelector("#orderForm").addEventListener("submit", function (e) {
    // JANGAN pakai e.preventDefault() kecuali untuk validasi

    // Validasi final sebelum submit
    const paket = document.getElementById("paketInput").value;
    const serviceCode = document.getElementById("layananInput").value;
    const paymentMethod = document.getElementById("paymentMethodInput").value;
    const total =
        parseInt(document.getElementById("totalHargaInput").value) || 0;

    if (!paket || !serviceCode || !paymentMethod || total <= 0) {
        e.preventDefault(); // hanya prevent jika validasi gagal
        alert("Mohon lengkapi semua data pemesanan!");
        return false;
    }

    // Jika lolos validasi, tampilkan loading
    const btn = document.querySelector(".order-submit");
    btn.textContent = "Memproses...";
    btn.disabled = true;
    btn.style.opacity = "0.6";

    // Biarkan form submit secara normal (jangan preventDefault di sini)
    return true;
});
