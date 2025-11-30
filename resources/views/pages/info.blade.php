<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Informasi Pelayanan & Harga - Utama Laundry</title>
    <link
      href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="{{ asset('css/style.css') }}" />
  </head>
  <body>
    <!-- Navbar -->
    <header class="navbar">
      <div class="logo">
        <a href="/"
          ><img src="{{ asset('assets/img/logo.png') }}" alt="Logo"
        /></a>
      </div>
      <nav>
        <ul>
          <li><a href="/">Beranda</a></li>
          <li><a href="/info-layanan" class="active">Info Layanan</a></li>
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

    <!-- Main Content -->
    <main class="content">
      <h1>Informasi Pelayanan & Harga</h1>
      <p class="subtext">
        Kami menyediakan layanan laundry kiloan dan non-kiloan (satuan).<br />
        Disini, kamu bisa melihat detail harga dan informasi lengkap layanan
        kami sesuai kebutuhanmu.
      </p>

      <!-- Accordion Section -->
      <div class="accordion">
        <!-- KILOAN -->
        <div class="accordion-item">
          <button class="accordion-header">
            Laundry Kiloan (Layanan Cuci Kering Setrika, Cuci Kering, Setrika)
            <span class="arrow"></span>
          </button>
          <div class="accordion-body">
            <table>
              <thead>
                <tr>
                  <th>Layanan</th>
                  <th>Paket Reguler (3 hari)</th>
                  <th>Paket Express (1 hari)</th>
                </tr>
              </thead>
              <tbody>
                @foreach($kiloServices as $service)
                <tr>
                  <td>{{ $service->name }}</td>
                  <td>Rp {{ number_format($service->price_reguler, 0, ',', '.') }} / kg</td>
                  <td>Rp {{ number_format($service->price_express, 0, ',', '.') }} / kg</td>
                </tr>
                @endforeach
              </tbody>
            </table>

            <div class="note">
              Tambahan charge untuk pakaian dalam: <strong>Rp 5000 / item</strong>
            </div>

            <h3>Pilihan Estimasi Berat Cucian</h3>
            <table>
              <thead>
                <tr>
                  <th>Kategori</th>
                  <th>Berat</th>
                  <th>Contoh</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Ringan</td>
                  <td>≤ 3 kg</td>
                  <td>1 ember pakaian harian</td>
                </tr>
                <tr>
                  <td>Sedang</td>
                  <td>4–6 kg</td>
                  <td>pakaian seminggu</td>
                </tr>
                <tr>
                  <td>Berat</td>
                  <td>7-10 kg</td>
                  <td>pakaian sebulan</td>
                </tr>
              </tbody>
            </table>

            <a href="/order" class="btn-order">Order Sekarang</a>
          </div>
        </div>

        <!-- NON KILOAN -->
        <div class="accordion-item">
          <button class="accordion-header">
            Laundry Non-Kiloan (Laundry Selimut, Sprei, dan Boneka)
            <span class="arrow"></span>
          </button>
          <div class="accordion-body">
            <table>
              <thead>
                <tr>
                  <th>Jenis</th>
                  <th>Paket Reguler (3 hari)</th>
                  <th>Paket Express (1 hari)</th>
                </tr>
              </thead>
              <tbody>
                @foreach($nonKiloServices as $service)
                <tr>
                  <td>{{ str_replace('Laundry ', '', $service->name) }}</td>
                  <td>Rp {{ number_format($service->price_reguler, 0, ',', '.') }} / item</td>
                  <td>Rp {{ number_format($service->price_express, 0, ',', '.') }} / item</td>
                </tr>
                @endforeach
              </tbody>
            </table>

            <a href="/order" class="btn-order">Order Sekarang</a>
          </div>
        </div>

        <!-- METODE PEMBAYARAN -->
        <div class="accordion-item">
          <button class="accordion-header">
            Metode Pembayaran
            <span class="arrow"></span>
          </button>

          <div class="accordion-body">
            <ol>
              <li>
                <strong
                  >QRIS Pra-Bayar (Pembayaran dilakukan Langsung di
                  Website)</strong
                >
                <ul>
                  <li>
                    Pembayaran dilakukan setelah menekan tombol
                    <strong>"Konfirmasi Order"</strong>.
                  </li>
                  <li>
                    Sistem akan menampilkan <strong>kode QR</strong> untuk
                    pembayaran.
                  </li>
                  <li>Unggah bukti pembayaran pada halaman yang sama.</li>
                  <li>
                    Harga yang tertera adalah <strong>harga estimasi</strong>.
                  </li>
                  <li>
                    Jika ada selisih harga, penyesuaian dilakukan di outlet.
                  </li>
                </ul>
              </li>

              <li>
                <strong
                  >QRIS Pasca-Bayar (Pembayaran dilakukan di Outlet)</strong
                >
                <ul>
                  <li>Datang ke outlet untuk menimbang cucian.</li>
                  <li>Harga akhir berdasarkan hasil timbangan sebenarnya.</li>
                  <li>Pembayaran setelah cucian selesai dan siap diambil.</li>
                </ul>
              </li>

              <li>
                <strong
                  >Tunai Pra-Bayar (Pembayaran dilakukan di Outlet)</strong
                >
                <ul>
                  <li>
                    Setelah order, datang ke outlet untuk menimbang cucian.
                  </li>
                  <li>Harga akhir dihitung sesuai timbangan.</li>
                  <li>Pembayaran langsung di outlet setelah ditimbang.</li>
                </ul>
              </li>

              <li>
                <strong
                  >Tunai Pasca-Bayar (Pembayaran dilakukan di Outlet)</strong
                >
                <ul>
                  <li>Harga dihitung setelah penimbangan.</li>
                  <li>
                    Pembayaran dilakukan saat cucian selesai dan siap diambil.
                  </li>
                </ul>
              </li>
            </ol>

            <div class="note">
              <strong>Catatan Penting:</strong><br />
              • QRIS Pra-Bayar bersifat estimasi awal.<br />
              • Non-kiloan (selimut/sprei/boneka) harga tetap sesuai daftar.<br />
              • Transaksi QRIS diverifikasi oleh admin.<br />
              • Status pembayaran dapat dilihat pada menu
              <strong>Riwayat</strong>.
            </div>

            <a href="/order" class="btn-order">Order Sekarang</a>
          </div>
        </div>

        <!-- PANDUAN ORDER -->
        <div class="accordion-item">
          <button class="accordion-header">
            Panduan Singkat untuk Order
            <span class="arrow"></span>
          </button>

          <div class="accordion-body">
            <ol>
              <li>
                Pilih Paket & Jenis Layanan sesuai kebutuhanmu (Reguler /
                Express).
              </li>
              <li>
                Pilih Estimasi Berat Cucian (khusus untuk layanan kiloan).
              </li>
              <li>Pilih metode pembayaran — tunai (pra-bayar, pasca-bayar), QRIS (pra-bayar, pasca-bayar).</li>
              <li>
                Klik <strong>"Konfirmasi Order"</strong> untuk menyelesaikan proses pemesanan.
                Kemudian ikuti instruksi pembayaran sesuai metode yang dipilih.
              </li>
              <li>Datang ke outlet untuk penimbangan cucian, dan ambil cucian ketika selesai sesuai durasi paket.</li>
            </ol>

            <div class="note">
              Status cucian dapat dicek melalui menu
              <strong>Status Cucian</strong> di website.
            </div>

            <a href="/order" class="btn-order">Order Sekarang</a>
          </div>
        </div>
      </div>
    </main>

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
  </body>
</html>