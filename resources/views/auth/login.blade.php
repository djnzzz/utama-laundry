<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login - Utama Laundry</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
  </head>
  <body>
    <div class="auth-wrapper slide-container">
      <!-- VISUAL CARD (Background + Logo digabung biar ikut slide) -->
      <div class="visual-card slide-item">
        <img
          class="bg-image"
          src="{{ asset('assets/img/login-banner.png') }}"
          alt="background"
        />
        <img
          class="logo-overlay"
          src="{{ asset('assets/img/auth-logo.png') }}"
          alt="logo"
        />
      </div>

      <!-- FORM LOGIN -->
      <div class="auth-box slide-item">
        <h2>Login</h2>
        <form method="POST" action="/login">
          @csrf
          <input type="email" name="email" placeholder="Email" required />
          <input type="password" name="password" placeholder="Password" required />
          <button class="auth-btn">Login</button>
        </form>
        <p class="auth-alt">
          Belum punya akun?
          <a class="trigger-slide" data-direction="left" href="/register"
            >Daftar</a
          >
        </p>
      </div>
    </div>

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

    <script src="{{ asset('js/main.js') }}"></script>

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
