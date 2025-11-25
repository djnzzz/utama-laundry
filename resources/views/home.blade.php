<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Utama Laundry</title>
    <link
      href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
  </head>

  <body>
    <!-- Navbar -->
    <header class="navbar">
      <a href="/" class="logo">
        <img src="{{ asset('assets/img/logo.png') }}" alt="Logo Utama Laundry" />
      </a>
      <nav>
        <ul>
          <li><a href="/">Beranda</a></li>
          <li><a href="/info-layanan">Info Layanan</a></li>
          <li><a href="/order">Order</a></li>
          <li><a href="/status">Status Cucian</a></li>
          <li><a href="/riwayat">Riwayat</a></li>
        </ul>
      </nav>
      <div class="auth-buttons">
        @auth
        <!-- Ikon profil ketika sudah login -->
        <a href="/profile" class="profile-icon">
          <img id="navbarProfilePic"
            src="{{ auth()->user()->photo && auth()->user()->photo !== '' ? asset('storage/' . auth()->user()->photo) : asset('assets/icon/user-profile.png') }}"
            width="45" height="45" style="border-radius:50%">
        </a>
        @else
        <!-- Jika belum login -->
        <a href="/login" class="login">Login</a>
        <a href="/register" class="register">Register</a>
        @endauth
      </div>
    </header>

    <!-- Hero Section -->
    <section class="hero">
      <div class="hero-overlay"></div>
      <img
        src="{{ asset('assets/img/banner_utamalaundry.png') }}"
        alt="Utama Laundry"
        class="hero-bg"
      />
      <div class="hero-content">
        <a href="/order" class="order-btn">Order Sekarang</a>
      </div>
    </section>

    <!-- Services -->
    <section class="services">
      <h2>Kami Melayani</h2>
      <div class="service-grid">
        <div class="card">
          <img
            src="{{ asset('assets/img/cuci-kering-setrika.png') }}"
            alt="Cuci Kering Setrika"
          />
          <h3>Cuci Kering Setrika</h3>
          <p>Pakaian dicuci, dikeringkan, lalu disetrika hingga siap pakai.</p>
          <a href="/info-layanan" class="detail-btn">Lihat Detail</a>
        </div>

        <div class="card">
          <img src="{{ asset('assets/img/cuci-kering.jpg') }}" alt="Cuci Kering" />
          <h3>Cuci Kering</h3>
          <p>Pakaian hanya dicuci dan dikeringkan tanpa setrika.</p>
          <a href="/info-layanan" class="detail-btn">Lihat Detail</a>
        </div>

        <div class="card">
          <img src="{{ asset('assets/img/setrika.jpg') }}" alt="Setrika" />
          <h3>Setrika</h3>
          <p>Untuk pakaian yang sudah bersih namun butuh hasil rapi.</p>
          <a href="/info-layanan" class="detail-btn">Lihat Detail</a>
        </div>

        <div class="card">
          <img src="{{ asset('assets/img/paket-laundry.png') }}" alt="Paket Laundry" />
          <h3>Paket Laundry</h3>
          <p>
            <strong>Reguler</strong> (3 hari selesai)<br /><strong
              >Express</strong
            >
            (1 hari selesai)
          </p>
          <a href="/info-layanan" class="detail-btn">Lihat Detail</a>
        </div>

        <div class="card">
          <img
            src="{{ asset('assets/img/beragam-jenis.png') }}"
            alt="Beragam Jenis Cucian"
          />
          <h3>Beragam Jenis Cucian</h3>
          <p>Kami menerima pakaian, sprei, selimut, dan boneka.</p>
          <a href="/info-layanan" class="detail-btn">Lihat Detail</a>
        </div>
      </div>
    </section>

    <!-- Why Choose Section -->
    <section class="why">
      <div class="why-overlay"></div>
      <img
        src="{{ asset('assets/img/utamalaundry_blank_blue.png') }}"
        alt="Background"
        class="why-bg"
      />
      <div class="why-content">
        <h2>Mengapa Memilih Utama Laundry?</h2>
        <div class="why-grid">
          <div class="why-item">
            <div class="icon-circle">
              <img src="{{ asset('assets/icon/time.png') }}" alt="Cepat & Tepat Waktu" />
            </div>
            <p>Cepat & Tepat Waktu</p>
          </div>
          <div class="why-item">
            <div class="icon-circle">
              <img src="{{ asset('assets/icon/credit-card.png') }}" alt="Metode Bayar" />
            </div>
            <p>Metode Pembayaran Fleksibel</p>
          </div>
          <div class="why-item">
            <div class="icon-circle">
              <img src="{{ asset('assets/icon/service.png') }}" alt="Pelayanan Ramah" />
            </div>
            <p>Pelayanan Ramah</p>
          </div>
          <div class="why-item">
            <div class="icon-circle">
              <img src="{{ asset('assets/icon/clothes.png') }}" alt="Cucian Bersih" />
            </div>
            <p>Cucian Wangi, Bersih, dan Rapi</p>
          </div>
        </div>
      </div>
    </section>

    <!-- CTA Section -->
    @guest
    <section class="cta">
      <p>
      Untuk pengalaman yang lebih baik,
      <a href="/login">Masuk</a> atau
      <a href="/register">Daftar</a> sekarang!
      </p>
    </section>
    @endguest

    @auth
    <section class="cta">
      <p>
      Halo, {{ auth()->user()->name }}! Siap untuk memesan layanan laundry?
      <a href="/order">Order Sekarang</a>!
      </p>
    </section>
    @endauth

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

    <!-- TOAST NOTIFICATION -->
    <div id="toast" class="toast"></div>
      <script>
        function showToast(type, message) {
          const toast = document.getElementById("toast");
          toast.className = "toast " + type;
          toast.textContent = message;
          toast.classList.add("show");

          setTimeout(() => {
            toast.classList.remove("show");
          }, 3000);
      }
      </script>

    @if(session('success'))
      <script>showToast("success", "{{ session('success') }}");</script>
    @endif

    @if(session('error'))
      <script>showToast("error", "{{ session('error') }}");</script>
    @endif

    @if ($errors->any())
      <script>showToast("error", "{{ $errors->first() }}");</script>
    @endif

  </body>
</html>
