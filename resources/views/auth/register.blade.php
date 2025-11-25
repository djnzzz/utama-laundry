<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register - Utama Laundry</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
  </head>
  <body>
    <!-- LOGO (ikut slide) -->
    <div class="auth-wrapper slide-container">
      <!-- FORM REGISTER (duluan jika mau posisi kiri) -->
      <div class="auth-box slide-item">
        <h2>Register</h2>
        <form method="POST" action="/register">
          @csrf
          <input type="text" name="name" placeholder="Nama" required />
          <input type="email" name="email" placeholder="Email" required />
          <input type="password" name="password" placeholder="Password" required />
          <input type="password" name="password_confirmation" placeholder="Konfirmasi Password" required />
          <button class="auth-btn">Daftar</button>
        </form>
        <p class="auth-alt">
          Sudah punya akun?
          <a class="trigger-slide" data-direction="right" href="/login"
            >Login</a
          >
        </p>
      </div>

      <!-- VISUAL CARD -->
      <div class="visual-card slide-item">
        <img
          class="bg-image"
          src="{{ asset('assets/img/register-banner.png') }}"
          alt="background"
        />
        <img
          class="logo-overlay"
          src="{{ asset('assets/img/auth-logo.png') }}"
          alt="logo"
        />
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
