<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Status Cucian - Utama Laundry</title>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet" />
<link rel="stylesheet" href="{{ asset('css/style.css') }}" />
<link rel="stylesheet" href="{{ asset('css/status.css') }}" />
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

<section class="status-wrapper">
    <div class="status-header">
        <h1 class="status-title">Status Cucian</h1>
        <p class="status-subtitle">Lacak progres pencucian laundry kamu secara real-time</p>
    </div>

    <!-- Search Bar -->
    <div class="search-section">
        <form id="searchForm" class="search-form">
            <input type="text" 
                   id="orderSnInput" 
                   name="order_sn" 
                   placeholder="Masukkan ID Pesanan (contoh: UL-20251120-000001)" 
                   value="{{ request('order_sn') }}"
                   class="search-input">
            <button type="submit" class="search-btn">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                </svg>
                Cek Status
            </button>
        </form>
    </div>

    <!-- Result Container -->
    <div id="resultContainer" class="result-container">
        @if(isset($order))
            @if($order)
                <!-- Order Details Card -->
                <div class="order-details-card">
                    <div class="details-header">
                        <h3>Detail Pesanan</h3>
                        <span class="order-id">{{ $order->order_sn }}</span>
                    </div>

                    <div class="details-grid">
                        <div class="detail-item">
                            <span class="detail-label">Nama Pelanggan</span>
                            <span class="detail-value">{{ $order->name }}</span>
                        </div>

                        <div class="detail-item">
                            <span class="detail-label">Layanan</span>
                            <span class="detail-value">{{ $order->service_name }}</span>
                        </div>

                        <div class="detail-item">
                            <span class="detail-label">Paket</span>
                            <span class="detail-value">{{ $order->paket }}</span>
                        </div>

                        @if($order->service_type === 'kiloan')
                        <div class="detail-item">
                            <span class="detail-label">Estimasi Berat</span>
                            <span class="detail-value">{{ $order->estimasi_berat }} kg</span>
                        </div>
                        @else
                        <div class="detail-item">
                            <span class="detail-label">Jumlah Item</span>
                            <span class="detail-value">{{ $order->jumlah_item }} pcs</span>
                        </div>
                        @endif

                        <div class="detail-item">
                            <span class="detail-label">Tanggal Pemesanan</span>
                            <span class="detail-value">{{ $order->created_at->format('d M Y, H:i') }} WIB</span>
                        </div>

                        <div class="detail-item">
                            <span class="detail-label">Estimasi Selesai</span>
                            <span class="detail-value">
                                @php
                                    $estimasiSelesai = $order->paket === 'Express' 
                                        ? $order->created_at->addDay() 
                                        : $order->created_at->addDays(3);
                                @endphp
                                {{ $estimasiSelesai->format('d M Y, H:i') }} WIB
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Progress Tracking -->
                <div class="tracking-card">
                    <h3 class="tracking-title">Progres Pengerjaan</h3>

                    <div class="timeline">
                        <!-- Step 1: Pesanan Diterima -->
                        <div class="timeline-step {{ $order->status_cucian === 'baru' || in_array($order->status_cucian, ['dalam_antrean', 'proses_pengerjaan', 'siap_diambil', 'selesai']) ? 'completed' : '' }}">
                            <div class="step-marker">
                                <span class="step-number">1</span>
                                @if($order->status_cucian === 'baru' || in_array($order->status_cucian, ['dalam_antrean', 'proses_pengerjaan', 'siap_diambil', 'selesai']))
                                <svg class="check-icon" width="24" height="24" fill="white" viewBox="0 0 16 16">
                                    <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                                </svg>
                                @endif
                            </div>
                            <div class="step-content">
                                <h4>Pesanan Diterima</h4>
                                <p>Pesanan laundry kamu telah diterima dan tercatat di sistem</p>
                                @if($order->status_cucian === 'baru')
                                <span class="step-time">{{ $order->created_at->format('d M Y, H:i') }} WIB</span>
                                @endif
                            </div>
                        </div>

                        <!-- Step 2: Dalam Antrean -->
                        <div class="timeline-step {{ in_array($order->status_cucian, ['dalam_antrean', 'proses_pengerjaan', 'siap_diambil', 'selesai']) ? 'completed' : '' }} {{ $order->status_cucian === 'dalam_antrean' ? 'active' : '' }}">
                            <div class="step-marker">
                                <span class="step-number">2</span>
                                @if(in_array($order->status_cucian, ['dalam_antrean', 'proses_pengerjaan', 'siap_diambil', 'selesai']))
                                <svg class="check-icon" width="24" height="24" fill="white" viewBox="0 0 16 16">
                                    <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                                </svg>
                                @endif
                            </div>
                            <div class="step-content">
                                <h4>Dalam Antrean</h4>
                                <p>Cucian kamu sedang dalam antrean untuk diproses</p>
                            </div>
                        </div>

                        <!-- Step 3: Proses Pengerjaan -->
                        <div class="timeline-step {{ in_array($order->status_cucian, ['proses_pengerjaan', 'siap_diambil', 'selesai']) ? 'completed' : '' }} {{ $order->status_cucian === 'proses_pengerjaan' ? 'active' : '' }}">
                            <div class="step-marker">
                                <span class="step-number">3</span>
                                @if(in_array($order->status_cucian, ['proses_pengerjaan', 'siap_diambil', 'selesai']))
                                <svg class="check-icon" width="24" height="24" fill="white" viewBox="0 0 16 16">
                                    <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                                </svg>
                                @endif
                            </div>
                            <div class="step-content">
                                <h4>Proses Pengerjaan</h4>
                                <p>Cucian kamu sedang dalam proses pencucian</p>
                            </div>
                        </div>

                        <!-- Step 4: Siap Diambil -->
                        <div class="timeline-step {{ in_array($order->status_cucian, ['siap_diambil', 'selesai']) ? 'completed' : '' }} {{ $order->status_cucian === 'siap_diambil' ? 'active' : '' }}">
                            <div class="step-marker">
                                <span class="step-number">4</span>
                                @if(in_array($order->status_cucian, ['siap_diambil', 'selesai']))
                                <svg class="check-icon" width="24" height="24" fill="white" viewBox="0 0 16 16">
                                    <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                                </svg>
                                @endif
                            </div>
                            <div class="step-content">
                                <h4>Siap Diambil</h4>
                                <p>Cucian kamu sudah selesai dan siap untuk diambil</p>
                            </div>
                        </div>

                        <!-- Step 5: Selesai -->
                        <div class="timeline-step {{ $order->status_cucian === 'selesai' ? 'completed' : '' }} {{ $order->status_cucian === 'selesai' ? 'active' : '' }}">
                            <div class="step-marker">
                                <span class="step-number">5</span>
                                @if($order->status_cucian === 'selesai')
                                <svg class="check-icon" width="24" height="24" fill="white" viewBox="0 0 16 16">
                                    <path d="M13.854 3.646a.5.5 0 0 1 0 .708l-7 7a.5.5 0 0 1-.708 0l-3.5-3.5a.5.5 0 1 1 .708-.708L6.5 10.293l6.646-6.647a.5.5 0 0 1 .708 0z"/>
                                </svg>
                                @endif
                            </div>
                            <div class="step-content">
                                <h4>Selesai</h4>
                                <p>Transaksi selesai, terima kasih telah menggunakan layanan kami</p>
                            </div>
                        </div>
                    </div>
                </div>

            @else
                <!-- Order Not Found -->
                <div class="empty-state">
                    <svg width="100" height="100" fill="#ccc" viewBox="0 0 16 16">
                        <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z"/>
                        <path d="M7.002 11a1 1 0 1 1 2 0 1 1 0 0 1-2 0zM7.1 4.995a.905.905 0 1 1 1.8 0l-.35 3.507a.552.552 0 0 1-1.1 0L7.1 4.995z"/>
                    </svg>
                    <h3>Pesanan Tidak Ditemukan</h3>
                    <p>ID pesanan yang kamu masukkan tidak ditemukan atau mungkin sudah dibatalkan.</p>
                    <p class="note">Pastikan ID pesanan benar dan coba lagi.</p>
                </div>
            @endif
        @else
            <!-- Initial State -->
            <div class="empty-state">
                <svg width="120" height="120" fill="#e0e0e0" viewBox="0 0 16 16">
                    <path d="M11 6.5a.5.5 0 0 1 .5-.5h1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-1a.5.5 0 0 1-.5-.5v-1z"/>
                    <path d="M2.5 0A2.5 2.5 0 0 0 0 2.5v11A2.5 2.5 0 0 0 2.5 16h6.086a1.5 1.5 0 0 0 1.06-.44l4.915-4.914A1.5 1.5 0 0 0 15 9.586V2.5A2.5 2.5 0 0 0 12.5 0h-10zM2 2.5a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 .5.5v7.086a.5.5 0 0 1-.146.353l-4.915 4.915A.5.5 0 0 1 7.586 15H2.5a.5.5 0 0 1-.5-.5v-12z"/>
                </svg>
                <h3>Cek Status Cucian Kamu</h3>
                <p>Masukkan ID pesanan di kolom pencarian untuk melihat progres laundry kamu</p>
                <p class="note">ID pesanan dapat ditemukan di halaman riwayat atau halaman pembayaran</p>
            </div>
        @endif
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

<div id="toast" class="toast"></div>

<script src="{{ asset('js/main.js') }}"></script>
<script src="{{ asset('js/status.js') }}"></script>

</body>
</html>