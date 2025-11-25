<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Order Laundry - Utama Laundry</title>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet" />
<link rel="stylesheet" href="{{ asset('css/style.css') }}" />
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

<section class="order-wrapper">
    <!-- Tampilkan Error -->
    @if($errors->any())
    <div class="alert alert-error" style="background: #ffe6e6; border: 2px solid #d72638; padding: 15px; border-radius: 10px; margin-bottom: 20px;">
        <strong style="color: #d72638;">❌ Error:</strong>
        <ul style="margin: 10px 0 0 20px; color: #333;">
            @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-error" style="background: #ffe6e6; border: 2px solid #d72638; padding: 15px; border-radius: 10px; margin-bottom: 20px;">
        <strong style="color: #d72638;">❌ Error:</strong>
        <span style="color: #333;">{{ session('error') }}</span>
    </div>
    @endif



    <h2 class="order-title">Form Pemesanan Laundry</h2>
    <p class="order-subtitle">Mohon Lengkapi data berikut untuk membuat pesanan laundry</p>

    <form id="orderForm" action="{{ route('order.store') }}" method="POST">
        @csrf

        <!-- Grid Input -->
        <div class="form-grid">
            <div>
                <label>Nama Lengkap</label>
                <input type="text" name="name" value="{{ auth()->user()->name }}" readonly>
            </div>

            <div>
                <label>No. HP/Telp</label>
                <input type="text" name="phone" value="{{ auth()->user()->phone }}" readonly>
            </div>

            <div>
            <label>Pilih Paket</label>

            <div class="dropdown-wrapper">
              <div class="dropdown-header">
                <span class="dropdown-label">-- Pilih Paket --</span>
                <span class="arrow"></span>
              </div>

              <div class="dropdown-body">
                <div class="dropdown-item" data-value="Reguler">Reguler (3 hari)</div>
                <div class="dropdown-item" data-value="Express">Express (1 hari)</div>
              </div>

              <input type="hidden" name="paket" id="paketInput">
            </div>

        </div>

            <div>
    <label>Pilih Layanan</label>

    <div class="dropdown-wrapper">
        <div class="dropdown-header">
          <span class="dropdown-label" id="labelLayanan">--Pilih Layanan--</span>
          <span class="arrow"></span>
        </div>

        <div class="dropdown-body">
            <div class="dropdown-item" data-value="pakaian_ck_setrika">Pakaian (Cuci Kering Setrika)</div>
            <div class="dropdown-item" data-value="pakaian_cuci_kering">Pakaian (Cuci Kering)</div>
            <div class="dropdown-item" data-value="pakaian_setrika">Pakaian (Setrika)</div>
            <div class="dropdown-item" data-value="laundry_selimut">Laundry Selimut</div>
            <div class="dropdown-item" data-value="laundry_sprei">Laundry Sprei</div>
            <div class="dropdown-item" data-value="laundry_boneka">Laundry Boneka</div>
        </div>

        <!-- input hidden untuk backend -->
        <input type="hidden" name="service_code" id="layananInput">
    </div>
</div>
        </div>


        <!-- Jika layanan pakaian -->
        <div id="formPakaian" class="hidden dynamic-box">
            <label>Ada Pakaian Dalam?</label>
            <div class="radio-toggle">
              <input type="radio" id="ya" name="pakaian_dalam" value="Ya">
              <label for="ya" class="toggle-btn">Ya</label>

              <input type="radio" id="tidak" name="pakaian_dalam" value="Tidak">
              <label for="tidak" class="toggle-btn">Tidak</label>
            </div>


            <div id="jumlahPakaianDalam" class="hidden">
                <label>Jumlah Pakaian Dalam: (item)</label>
                <input type="number" name="jumlah_pakaian_dalam" min="0">
            </div>

            <label>Pilih Estimasi Berat Cucian</label>
            <div class="dropdown-wrapper">
    <div class="dropdown-header">
      <span class="dropdown-label" id="labelBerat">--Pilih Estimasi Berat Cucian--</span>
      <span class="arrow"></span>
    </div>

    <div class="dropdown-body">
        <div class="dropdown-item" data-value="<=3kg">≤ 3 kg (1 ember pakaian harian)</div>
        <div class="dropdown-item" data-value="4-6kg">4–6 kg (pakaian seminggu)</div>
        <div class="dropdown-item" data-value=">=7kg">7-10 kg (pakaian sebulan)</div>
    </div>

    <!-- input hidden untuk backend -->
    <input type="hidden" name="estimasi_berat" id="beratInput">
</div>
        </div>


        <!-- Jika layanan selimut/sprei/boneka -->
        <div id="formItem" class="hidden dynamic-box">
            <label>Masukkan jumlah item: <br>(Khusus Laundry Selimut/Sprei/Boneka)</label>
            <input type="number" name="jumlah_item" min="0">
        </div>

      <!-- ===== DETAIL PESANAN & TOTAL HARGA ===== -->
<div class="total-box">
  <h3 class="detail-title">Detail Pesanan:</h3>
  <div id="detailHargaList" class="detail-list"></div>

  <div class="total-line">
    <span class="total-label">Total Harga:</span>
    <span id="totalHargaText" class="total-value">Rp 0</span>
  </div>
</div>
        <!-- hidden untuk dikirim ke backend -->
    <input type="hidden" name="total_harga" id="totalHargaInput" value="0">

        <h3 class="detail-title">Pilih Metode Pembayaran</h3>

<div class="dropdown-wrapper payment-method">
  <div class="dropdown-header">
      <span class="dropdown-label">--Pilih Metode Pembayaran--</span>
      <span class="arrow"></span>
  </div>

  <div class="dropdown-body">
      <div class="dropdown-item" data-value="qris_pra">
          <img src="{{ asset('assets/icon/qris.png') }}" class="dropdown-icon">
          QRIS Pra-Bayar
      </div>

      <div class="dropdown-item" data-value="qris_pasca">
          <img src="{{ asset('assets/icon/qris.png') }}" class="dropdown-icon">
          QRIS Pasca-Bayar
      </div>

      <div class="dropdown-item" data-value="cash_pra">
          <img src="{{ asset('assets/icon/cash.png') }}" class="dropdown-icon">
          Tunai Pra-Bayar
      </div>

      <div class="dropdown-item" data-value="cash_pasca">
          <img src="{{ asset('assets/icon/cash.png') }}" class="dropdown-icon">
          Tunai Pasca-Bayar
      </div>
  </div>

  <input type="hidden" name="payment_method" id="paymentMethodInput">
</div>


        <button type="submit" class="order-submit" id="submitBtn" disabled>Konfirmasi Order</button>
    </form>
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
            <li><a href="#">Kebijakan Privasi</a></li>
            <li><a href="#">Syarat & Ketentuan</a></li>
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

<div id="toast" class="toast"></div>
<script src="{{ asset('js/main.js') }}"></script>
<script src="{{ asset('js/order.js') }}"></script>

</body>
</html>
