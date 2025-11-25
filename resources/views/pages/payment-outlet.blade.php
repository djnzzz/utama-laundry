<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Pembayaran di Outlet - Utama Laundry</title>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet" />
<link rel="stylesheet" href="{{ asset('css/style.css') }}" />
<link rel="stylesheet" href="{{ asset('css/payment.css') }}" />
</head>

<body>

<!-- ===== NAVBAR ===== -->
<header class="navbar">
  <a href="/" class="logo"><img src="{{ asset('assets/img/logo.png') }}"></a>
  <nav><ul>
    <li><a href="/">Beranda</a></li>
    <li><a href="/info-layanan">Info Layanan</a></li>
    <li><a href="/order">Order</a></li>
    <li><a href="/status">Status Cucian</a></li>
    <li><a href="/riwayat">Riwayat</a></li>
  </ul></nav>

  @auth
  <a href="/profile" class="profile-icon">
    <img id="navbarProfilePic"
      src="{{ auth()->user()->photo && auth()->user()->photo !== '' ? asset('storage/' . auth()->user()->photo) : asset('assets/icon/user-profile.png') }}"
      width="45" height="45" style="border-radius:50%">
  </a>
  @endauth
</header>

<section class="payment-wrapper">
    <div class="payment-header">
        <h2 class="payment-title">
            @if($order->payment_method === 'qris_pasca')
                Pembayaran QRIS Pasca-Bayar
            @elseif($order->payment_method === 'cash_pra')
                Pembayaran Tunai Pra-Bayar
            @else
                Pembayaran Tunai Pasca-Bayar
            @endif
        </h2>
        <p class="payment-subtitle">
            @if(in_array($order->payment_method, ['qris_pasca', 'cash_pasca']))
                Pembayaran dilakukan setelah cucian selesai di outlet
            @else
                Pembayaran dilakukan saat menyerahkan cucian di outlet
            @endif
        </p>
    </div>

    <div class="outlet-payment-container">
        <!-- ID Pesanan -->
        <div class="order-id-card">
            <div class="success-icon">✓</div>
            <h3>Pesanan Berhasil Dibuat!</h3>
            <p class="order-id-label">ID Pesanan Anda:</p>
            <div class="id-copy-wrapper">
                <input type="text" id="orderSn" value="{{ $order->order_sn }}" readonly>
                <button class="btn-copy" onclick="copyOrderSn()">Salin</button>
            </div>
            <small>Simpan ID ini untuk tracking progres cucian Anda</small>
        </div>

        <!-- Detail Pesanan -->
        <div class="detail-order-box">
            <h3>Detail Pesanan</h3>
            <div class="detail-row">
                <span>Nama Pemesan:</span>
                <span>{{ $order->name }}</span>
            </div>
            <div class="detail-row">
                <span>No. HP/Telp:</span>
                <span>{{ $order->phone }}</span>
            </div>
            <div class="detail-row">
                <span>Layanan:</span>
                <span>{{ $order->service_name }}</span>
            </div>
            <div class="detail-row">
                <span>Paket:</span>
                <span>{{ $order->paket }}</span>
            </div>
            @if($order->service_type === 'kiloan')
            <div class="detail-row">
                <span>Estimasi Berat:</span>
                <span>{{ $order->estimasi_berat }}</span>
            </div>
            @if($order->jumlah_pakaian_dalam > 0)
            <div class="detail-row">
                <span>Pakaian Dalam:</span>
                <span>{{ $order->jumlah_pakaian_dalam }} item</span>
            </div>
            @endif
            @else
            <div class="detail-row">
                <span>Jumlah Item:</span>
                <span>{{ $order->jumlah_item }} pcs</span>
            </div>
            @endif
            <div class="detail-row">
                <span>Metode Pembayaran:</span>
                <span>
                    @if($order->payment_method === 'qris_pasca')
                        QRIS Pasca-Bayar
                    @elseif($order->payment_method === 'cash_pra')
                        Tunai Pra-Bayar
                    @else
                        Tunai Pasca-Bayar
                    @endif
                </span>
            </div>
            <div class="detail-row total-row">
                <span>Total 
                    @if(in_array($order->payment_method, ['qris_pra', 'cash_pra']))
                        yang Harus Dibayar:
                    @else
                        (Estimasi):
                    @endif
                </span>
                <span class="total-price">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
            </div>
            @if(in_array($order->payment_method, ['qris_pasca', 'cash_pasca']))
            <div class="info-note">
                <strong>Catatan:</strong> Total pembayaran dapat berubah sesuai berat aktual cucian saat ditimbang di outlet.
            </div>
            @endif
        </div>

        <!-- Instruksi Pembayaran -->
        <div class="instruction-card">
            <h3>Langkah Selanjutnya:</h3>
            
            @if($order->payment_method === 'cash_pra')
                <ol class="steps-list">
                    <li>Bawa cucian Anda ke <strong>Utama Laundry</strong></li>
                    <li>Tunjukkan <strong>ID Pesanan</strong> kepada staff</li>
                    <li>Serahkan cucian untuk ditimbang</li>
                    <li>Lakukan pembayaran secara <strong>tunai</strong></li>
                    <li>Simpan struk pembayaran</li>
                    <li>Cucian akan diproses sesuai paket yang dipilih</li>
                    <li>Cek progres cucian di menu <a href="/status">Status Cucian</a></li>
                </ol>
            @elseif($order->payment_method === 'qris_pasca')
                <ol class="steps-list">
                    <li>Bawa cucian Anda ke <strong>Utama Laundry</strong></li>
                    <li>Tunjukkan <strong>ID Pesanan</strong> kepada staff</li>
                    <li>Serahkan cucian untuk ditimbang</li>
                    <li>Cucian akan diproses sesuai paket yang dipilih</li>
                    <li>Cek progres cucian di menu <a href="/status">Status Cucian</a></li>
                    <li>Ambil cucian sesuai jadwal selesai</li>
                    <li>Lakukan pembayaran via <strong>QRIS</strong> saat pengambilan</li>
                </ol>
            @else
                <ol class="steps-list">
                    <li>Bawa cucian Anda ke <strong>Utama Laundry</strong></li>
                    <li>Serahkan cucian untuk ditimbang</li>
                    <li>Cucian akan diproses sesuai paket yang dipilih</li>
                    <li>Cek progres cucian di menu <a href="/status">Status Cucian</a></li>
                    <li>Ambil cucian sesuai jadwal selesai</li>
                    <li>Lakukan pembayaran secara <strong>tunai</strong> saat pengambilan</li>
                </ol>
            @endif
        </div>

        <!-- Informasi Outlet -->
        <div class="outlet-info-card">
            <h3>Informasi Outlet</h3>
            <div class="info-row">
                <strong>Alamat:</strong>
                <span>Jl. Kerto Raharjo No.1, Lowokwaru, Ketawanggede, Kota Malang</span>
            </div>
            <div class="info-row">
                <strong>Jam Operasional:</strong>
                <span>Setiap Hari, 07.00 - 20.00 WIB</span>
            </div>
            <div class="info-row">
                <strong>Telepon:</strong>
                <span>+62 812 3456 7890 / +62 898 7654 3210</span>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="outlet-actions">
            <button class="btn-cancel" onclick="cancelOrder()">Batalkan Pesanan</button>
            <a href="/status?order_id={{ $order->id }}" class="btn-primary">Cek Status Cucian</a>
        </div>
    </div>
</section>

<!-- Footer -->
<footer class="footer">
  <div class="footer-container">
    <div class="footer-column">
      <h3>Tentang Utama Laundry</h3>
      <p>
        Utama Laundry adalah jasa layanan laundry yang melayani pencucian
        pakaian, sprei, selimut, dan boneka. Kami menyediakan pilihan paket,
        pemesanan laundry, serta pemantauan status cucian.
      </p>
    </div>

    <div class="footer-column">
      <h3>Link Cepat</h3>
      <ul>
        <li><a href="/info-layanan">Info Layanan</a></li>
        <li><a href="/order">Order</a></li>
        <li><a href="/status">Status Cucian</a></li>
        <li><a href="/riwayat">Riwayat</a></li>
      </ul>
    </div>

    <div class="footer-column">
      <h3>Hubungi Kami</h3>
      <p>Email: utamalaundry@gmail.com</p>
      <p>Telepon: +62 812 3456 7890<br />+62 898 7654 3210</p>
      <p>
        Alamat: Jl. Kerto Raharjo No.1, Lowokwaru, Ketawanggede, Kota Malang
      </p>
      <p>Jam Buka: 07.00 - 20.00</p>
    </div>
  </div>
  <div class="footer-bottom">
    <p>© 2025 Utama Laundry. All Rights Reserved.</p>
  </div>
</footer>

<!-- Modal Konfirmasi Pembatalan -->
<div id="cancelModal" class="modal">
    <div class="modal-box">
        <div class="modal-icon warning">!</div>
        <h3>Batalkan Pesanan?</h3>
        <p>Pesanan akan dibatalkan dan tidak bisa dikembalikan</p>
        <div class="modal-action">
            <button class="modal-cancel" onclick="closeModal()">Kembali</button>
            <button class="modal-confirm" onclick="confirmCancel()">Ya, Batalkan</button>
        </div>
    </div>
</div>

<div id="toast" class="toast"></div>

<script src="{{ asset('js/main.js') }}"></script>
<script>
const orderSn = "{{ $order->order_sn }}";

function copyOrderSn() {
    const orderSnInput = document.getElementById('orderSn');
    orderSnInput.select();
    document.execCommand('copy');
    showToast('success', 'Nomor Pesanan berhasil disalin!');
}

function cancelOrder() {
    document.getElementById('cancelModal').style.display = 'flex';
}

function confirmCancel() {
    fetch('/payment/cancel-order', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({ order_sn: orderSn })
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            showToast('success', 'Pesanan berhasil dibatalkan');
            setTimeout(() => {
                window.location.href = '/order';
            }, 1500);
        } else {
            showToast('error', result.message);
        }
    })
    .catch(error => {
        showToast('error', 'Gagal membatalkan pesanan');
    });
}

function closeModal() {
    document.getElementById('cancelModal').style.display = 'none';
}

function showToast(type, message) {
    const toast = document.getElementById('toast');
    toast.className = `toast ${type} show`;
    toast.textContent = message;
    setTimeout(() => toast.classList.remove('show'), 3000);
}
</script>

</body>
</html>