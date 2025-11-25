<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Pembayaran QRIS - Utama Laundry</title>
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
        <h2 class="payment-title">Pembayaran QRIS Pra-Bayar</h2>
        <div class="timer-box" id="timerBox">
            <span class="timer-label">Selesaikan pembayaran dalam:</span>
            <span class="timer-countdown" id="countdown">30:00</span>
        </div>
    </div>

    <div class="payment-grid">
        <!-- LEFT SIDE: Detail Pesanan -->
        <div class="payment-left">
            <div class="order-id-box">
                <label>ID Pesanan</label>
                <div class="id-copy-wrapper">
                    <input type="text" id="orderSn" value="{{ $order->order_sn }}" readonly>
                    <button class="btn-copy" onclick="copyOrderSn()">Salin</button>
                </div>
                <small>Gunakan ID ini untuk tracking progres cucian Anda</small>
            </div>

            <div class="detail-order-box">
                <h3>Detail Pesanan</h3>
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
                <div class="detail-row total-row">
                    <span>Total Pembayaran:</span>
                    <span class="total-price">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
                </div>
            </div>

            <!-- Upload Bukti Pembayaran -->
            <div class="upload-box">
                <h3>Upload Bukti Pembayaran</h3>
                <p class="upload-desc">Upload screenshot bukti transfer Anda</p>
                
                <form id="uploadForm" action="/payment/upload-proof" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="order_sn" value="{{ $order->order_sn }}">
                    
                    <div class="upload-area" id="uploadArea">
                        <img src="{{ asset('assets/icon/upload.png') }}" alt="Upload" class="upload-icon">
                        <p>Klik atau seret file ke sini</p>
                        <small>Format: JPG, PNG, PDF (Max 5MB)</small>
                        <input type="file" id="proofFile" name="payment_proof" accept="image/*,.pdf" hidden>
                    </div>

                    <div class="preview-area hidden" id="previewArea">
                        <img id="previewImage" src="" alt="Preview">
                        <button type="button" class="btn-remove" onclick="removeFile()">Hapus</button>
                    </div>

                    <div class="payment-actions">
                        <button type="button" class="btn-cancel" onclick="cancelOrder()">Batalkan Pesanan</button>
                        <button type="submit" class="btn-pay" id="btnPay" disabled>
                            <span id="btnText">Kirim Bukti Pembayaran</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- RIGHT SIDE: QR Code -->
        <div class="payment-right">
            <div class="qris-box">
                <h3>Scan QRIS untuk Membayar</h3>
                <div class="qris-image">
                    <img src="{{ asset('assets/img/QRIS-Outlet.jpeg') }}" alt="QRIS Code">
                </div>
                <p class="qris-info">Scan kode QR di atas menggunakan aplikasi pembayaran digital Anda</p>
                
                <div class="instruction-box">
                    <h4>Cara Pembayaran:</h4>
                    <ol>
                        <li>Buka aplikasi e-wallet/m-banking Anda</li>
                        <li>Pilih menu scan QRIS</li>
                        <li>Scan kode QR di atas</li>
                        <li>Masukkan nominal sesuai total pembayaran</li>
                        <li>Konfirmasi pembayaran</li>
                        <li>Screenshot bukti pembayaran</li>
                        <li>Upload bukti di form sebelah kiri</li>
                    </ol>
                </div>
            </div>

            <!-- Status Verifikasi -->
            <div class="verification-status hidden" id="verificationStatus">
                <div class="status-icon">
                    <div class="spinner"></div>
                </div>
                <h4>Menunggu Verifikasi Admin</h4>
                <p>Bukti pembayaran Anda sedang diverifikasi oleh admin</p>
            </div>
        </div>
    </div>
</section>

<!-- Modal Konfirmasi Pembatalan -->
<div id="cancelModal" class="modal">
    <div class="modal-box">
        <div class="modal-icon warning">!</div>
        <h3 id="modalTitle">Batalkan Pesanan?</h3>
        <p id="modalText">Pesanan akan dibatalkan dan tidak bisa dikembalikan</p>
        <div class="modal-action">
            <button class="modal-cancel" onclick="closeModal()">Kembali</button>
            <button class="modal-confirm" onclick="confirmCancel()">Ya, Batalkan</button>
        </div>
    </div>
</div>

<!-- Modal Success -->
<div id="successModal" class="modal">
    <div class="modal-box">
        <div class="modal-icon success">✓</div>
        <h3>Pembayaran Berhasil!</h3>
        <p>Pembayaran Anda telah diverifikasi oleh admin</p>
        <button class="btn-primary" onclick="redirectToTracking()">Cek Status Cucian</button>
    </div>
</div>

<!-- Modal Timeout -->
<div id="timeoutModal" class="modal">
    <div class="modal-box">
        <div class="modal-icon error">✕</div>
        <h3>Waktu Pembayaran Habis</h3>
        <p>Pesanan telah dibatalkan karena melebihi batas waktu pembayaran</p>
        <button class="btn-primary" onclick="window.location.href='/order'">Buat Pesanan Baru</button>
    </div>
</div>

<div id="toast" class="toast"></div>

<script src="{{ asset('js/main.js') }}"></script>
<script src="{{ asset('js/payment-qris-pra.js') }}"></script>

</body>
</html>