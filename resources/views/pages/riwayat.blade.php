<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>Riwayat Pesanan - Utama Laundry</title>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet" />
<link rel="stylesheet" href="{{ asset('css/style.css') }}" />
<link rel="stylesheet" href="{{ asset('css/riwayat.css') }}" />
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

<section class="riwayat-wrapper">
    <div class="riwayat-header">
        <h1 class="riwayat-title">Riwayat Pesanan</h1>
        <p class="riwayat-subtitle">Lihat semua transaksi laundry yang pernah kamu lakukan</p>
    </div>

    <!-- Filter Section -->
<div class="filter-section">
    <form id="filterForm" method="GET" action="{{ route('riwayat.index') }}">
        <div class="filter-grid">
            <!-- Status Pembayaran Dropdown -->
            <div class="filter-item">
                <label>Status Pembayaran</label>
                <div class="dropdown-wrapper" data-filter="payment_status">
                    <div class="dropdown-header">
                        <span class="dropdown-label">
                            @if(request('payment_status') === 'paid')
                                Lunas
                            @elseif(request('payment_status') === 'waiting_verification')
                                Menunggu Verifikasi
                            @elseif(request('payment_status') === 'pending')
                                Belum Bayar
                            @elseif(request('payment_status') === 'cancelled')
                                Dibatalkan
                            @elseif(request('payment_status') === 'timeout')
                                Timeout
                            @else
                                Semua Status
                            @endif
                        </span>
                        <span class="arrow"></span>
                    </div>
                    <div class="dropdown-body">
                        <div class="dropdown-item" data-value="">Semua Status</div>
                        <div class="dropdown-item" data-value="paid">Lunas</div>
                        <div class="dropdown-item" data-value="waiting_verification">Menunggu Verifikasi</div>
                        <div class="dropdown-item" data-value="pending">Belum Bayar</div>
                        <div class="dropdown-item" data-value="cancelled">Dibatalkan</div>
                        <div class="dropdown-item" data-value="timeout">Timeout</div>
                    </div>
                </div>
                <input type="hidden" name="payment_status" value="{{ request('payment_status') }}">
            </div>

            <!-- Metode Pembayaran Dropdown -->
            <div class="filter-item">
                <label>Metode Pembayaran</label>
                <div class="dropdown-wrapper" data-filter="payment_method">
                    <div class="dropdown-header">
                        <span class="dropdown-label">
                            @if(request('payment_method') === 'qris_pra')
                                QRIS Pra-Bayar
                            @elseif(request('payment_method') === 'qris_pasca')
                                QRIS Pasca-Bayar
                            @elseif(request('payment_method') === 'cash_pra')
                                Tunai Pra-Bayar
                            @elseif(request('payment_method') === 'cash_pasca')
                                Tunai Pasca-Bayar
                            @else
                                Semua Metode
                            @endif
                        </span>
                        <span class="arrow"></span>
                    </div>
                    <div class="dropdown-body">
                        <div class="dropdown-item" data-value="">Semua Metode</div>
                        <div class="dropdown-item" data-value="qris_pra">QRIS Pra-Bayar</div>
                        <div class="dropdown-item" data-value="qris_pasca">QRIS Pasca-Bayar</div>
                        <div class="dropdown-item" data-value="cash_pra">Tunai Pra-Bayar</div>
                        <div class="dropdown-item" data-value="cash_pasca">Tunai Pasca-Bayar</div>
                    </div>
                </div>
                <input type="hidden" name="payment_method" value="{{ request('payment_method') }}">
            </div>

            <!-- Dari Tanggal -->
            <div class="filter-item">
                <label>Dari Tanggal</label>
                <input type="date" name="date_from" value="{{ request('date_from') }}" onchange="document.getElementById('filterForm').submit()">
            </div>

            <!-- Sampai Tanggal -->
            <div class="filter-item">
                <label>Sampai Tanggal</label>
                <input type="date" name="date_to" value="{{ request('date_to') }}" onchange="document.getElementById('filterForm').submit()">
            </div>
        </div>

        <!-- Active Filters Display -->
        @if(request()->hasAny(['payment_status', 'payment_method', 'date_from', 'date_to']))
        <div class="active-filters">
            <span class="filter-label">Filter Aktif:</span>
            <div class="filter-tags">
                @if(request('payment_status'))
                <span class="filter-tag">
                    <strong>Status:</strong> 
                    @if(request('payment_status') === 'paid') Lunas
                    @elseif(request('payment_status') === 'waiting_verification') Menunggu Verifikasi
                    @elseif(request('payment_status') === 'pending') Belum Bayar
                    @elseif(request('payment_status') === 'cancelled') Dibatalkan
                    @elseif(request('payment_status') === 'timeout') Timeout
                    @endif
                    <button type="button" onclick="removeFilter('payment_status')" class="remove-tag">×</button>
                </span>
                @endif

                @if(request('payment_method'))
                <span class="filter-tag">
                    <strong>Metode:</strong> 
                    @if(request('payment_method') === 'qris_pra') QRIS Pra-Bayar
                    @elseif(request('payment_method') === 'qris_pasca') QRIS Pasca-Bayar
                    @elseif(request('payment_method') === 'cash_pra') Tunai Pra-Bayar
                    @elseif(request('payment_method') === 'cash_pasca') Tunai Pasca-Bayar
                    @endif
                    <button type="button" onclick="removeFilter('payment_method')" class="remove-tag">×</button>
                </span>
                @endif

                @if(request('date_from'))
                <span class="filter-tag">
                    <strong>Dari:</strong> {{ \Carbon\Carbon::parse(request('date_from'))->format('d M Y') }}
                    <button type="button" onclick="removeFilter('date_from')" class="remove-tag">×</button>
                </span>
                @endif

                @if(request('date_to'))
                <span class="filter-tag">
                    <strong>Sampai:</strong> {{ \Carbon\Carbon::parse(request('date_to'))->format('d M Y') }}
                    <button type="button" onclick="removeFilter('date_to')" class="remove-tag">×</button>
                </span>
                @endif
            </div>
            
            <button type="button" class="btn-reset" onclick="window.location.href=`{{route('riwayat.index')}}`">
              Reset Filter
            </button>
        </div>
        @endif
    </form>
</div>

    <!-- Orders Grid -->
    <div class="orders-container">
        @forelse($orders as $order)
        <div class="order-card" data-order-sn="{{ $order->order_sn }}">
            <!-- Header Card -->
            <div class="order-card-header">
                <div class="order-sn-section">
                    <span class="label-small">ID Pesanan</span>
                    <div class="sn-copy-wrapper">
                        <input type="text" class="sn-input" value="{{ $order->order_sn }}" readonly>
                        <button class="btn-copy-small" onclick="copySn('{{ $order->order_sn }}')">
                            <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                                <path d="M4 1.5H3a2 2 0 0 0-2 2V14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V3.5a2 2 0 0 0-2-2h-1v1h1a1 1 0 0 1 1 1V14a1 1 0 0 1-1 1H3a1 1 0 0 1-1-1V3.5a1 1 0 0 1 1-1h1v-1z"/>
                                <path d="M9.5 1a.5.5 0 0 1 .5.5v1a.5.5 0 0 1-.5.5h-3a.5.5 0 0 1-.5-.5v-1a.5.5 0 0 1 .5-.5h3zm-3-1A1.5 1.5 0 0 0 5 1.5v1A1.5 1.5 0 0 0 6.5 4h3A1.5 1.5 0 0 0 11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3z"/>
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="status-payment-wrapper">
                    <span class="status-badge status-{{ $order->payment_status }}">
                        @if($order->payment_status === 'paid')
                            ✓ Lunas
                        @elseif($order->payment_status === 'waiting_verification')
                            ⏳ Menunggu Verifikasi
                        @elseif($order->payment_status === 'cancelled')
                            ✕ Dibatalkan
                        @elseif($order->payment_status === 'timeout')
                            ⏰ Timeout
                        @else
                            ○ Belum Bayar
                        @endif
                    </span>
                </div>
            </div>

            <!-- Body Card -->
            <div class="order-card-body">
                <div class="order-info-grid">
                    <div class="info-item">
                        <span class="info-label">Tanggal Order</span>
                        <span class="info-value">{{ $order->created_at->format('d M Y, H:i') }} WIB</span>
                    </div>

                    <div class="info-item">
                        <span class="info-label">Layanan</span>
                        <span class="info-value">{{ $order->service_name }}</span>
                    </div>

                    <div class="info-item">
                        <span class="info-label">Paket</span>
                        <span class="info-value">{{ $order->paket }}</span>
                    </div>

                    @if($order->service_type === 'kiloan')
                    <div class="info-item">
                        <span class="info-label">Estimasi Berat</span>
                        <span class="info-value">{{ $order->estimasi_berat }} kg</span>
                    </div>
                    @else
                    <div class="info-item">
                        <span class="info-label">Jumlah Item</span>
                        <span class="info-value">{{ $order->jumlah_item }} pcs</span>
                    </div>
                    @endif

                    <div class="info-item">
                        <span class="info-label">Metode Pembayaran</span>
                        <span class="info-value">
                            @if($order->payment_method === 'qris_pra')
                                QRIS Pra-Bayar
                            @elseif($order->payment_method === 'qris_pasca')
                                QRIS Pasca-Bayar
                            @elseif($order->payment_method === 'cash_pra')
                                Tunai Pra-Bayar
                            @else
                                Tunai Pasca-Bayar
                            @endif
                        </span>
                    </div>

                    <div class="info-item total-price-item">
                        <span class="info-label">Total Harga</span>
                        <span class="info-value price-highlight">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</span>
                    </div>
                </div>

                <!-- Catatan Khusus -->
                @if($order->payment_method === 'qris_pra' && $order->payment_status !== 'cancelled')
                <div class="note-box note-info">
                    <strong>ℹ️ Catatan:</strong> Harga final akan ditentukan setelah penimbangan di outlet. Apabila terdapat perbedaan, penyesuaian dapat kamu lakukan langsung di outlet.
                </div>
                @elseif(in_array($order->payment_method, ['qris_pasca', 'cash_pra', 'cash_pasca']) && $order->payment_status === 'pending')
                <div class="note-box note-info">
                    <strong>ℹ️ Catatan:</strong> Kamu bisa track progress cucianmu lewat menu status cucian. Status pembayaranmu akan berubah jadi lunas saat kamu sudah melakukan pembayaran di outlet.
                </div>
                @endif
            </div>

            <!-- Footer Card -->
            <div class="order-card-footer">
                <a href="{{ route('order.payment', $order->order_sn) }}" class="btn-detail">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M8 4.754a3.246 3.246 0 1 0 0 6.492 3.246 3.246 0 0 0 0-6.492zM5.754 8a2.246 2.246 0 1 1 4.492 0 2.246 2.246 0 0 1-4.492 0z"/>
                        <path d="M9.796 1.343c-.527-1.79-3.065-1.79-3.592 0l-.094.319a.873.873 0 0 1-1.255.52l-.292-.16c-1.64-.892-3.433.902-2.54 2.541l.159.292a.873.873 0 0 1-.52 1.255l-.319.094c-1.79.527-1.79 3.065 0 3.592l.319.094a.873.873 0 0 1 .52 1.255l-.16.292c-.892 1.64.901 3.434 2.541 2.54l.292-.159a.873.873 0 0 1 1.255.52l.094.319c.527 1.79 3.065 1.79 3.592 0l.094-.319a.873.873 0 0 1 1.255-.52l.292.16c1.64.893 3.434-.902 2.54-2.541l-.159-.292a.873.873 0 0 1 .52-1.255l.319-.094c1.79-.527 1.79-3.065 0-3.592l-.319-.094a.873.873 0 0 1-.52-1.255l.16-.292c.893-1.64-.902-3.433-2.541-2.54l-.292.159a.873.873 0 0 1-1.255-.52l-.094-.319z"/>
                    </svg>
                    Lihat Detail
                </a>

                @if(in_array($order->payment_status, ['cancelled', 'timeout', 'paid']))
                <button class="btn-delete" onclick="confirmDelete('{{ $order->order_sn }}')">
                    <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                        <path d="M5.5 5.5A.5.5 0 0 1 6 6v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm2.5 0a.5.5 0 0 1 .5.5v6a.5.5 0 0 1-1 0V6a.5.5 0 0 1 .5-.5zm3 .5a.5.5 0 0 0-1 0v6a.5.5 0 0 0 1 0V6z"/>
                        <path fill-rule="evenodd" d="M14.5 3a1 1 0 0 1-1 1H13v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V4h-.5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1H6a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1h3.5a1 1 0 0 1 1 1v1zM4.118 4 4 4.059V13a1 1 0 0 0 1 1h6a1 1 0 0 0 1-1V4.059L11.882 4H4.118zM2.5 3V2h11v1h-11z"/>
                    </svg>
                    Hapus
                </button>
                @endif
            </div>
        </div>
        @empty
        <div class="empty-state">
            <img src="{{ asset('assets/img/empty-order.svg') }}" alt="Tidak ada riwayat" style="width: 200px; opacity: 0.5; margin-bottom: 20px;">
            <h3>Belum Ada Riwayat Pesanan</h3>
            <p>Kamu belum pernah melakukan pesanan. Yuk mulai order laundry sekarang!</p>
            <a href="{{ route('order.create') }}" class="btn-primary">Buat Pesanan Baru</a>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($orders->hasPages())
    <div class="pagination-wrapper">
        {{ $orders->links() }}
    </div>
    @endif
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

<!-- Modal Konfirmasi Hapus -->
<div id="deleteModal" class="modal">
    <div class="modal-box">
        <div class="modal-icon error">!</div>
        <h3>Hapus Riwayat Transaksi?</h3>
        <p>Kamu yakin ingin menghapus data transaksi ini? Data yang sudah dihapus tidak dapat dikembalikan lagi.</p>
        <div class="modal-action">
            <button class="modal-cancel" onclick="closeDeleteModal()">Batal</button>
            <button class="modal-confirm" id="confirmDeleteBtn">Ya, Hapus</button>
        </div>
    </div>
</div>

<div id="toast" class="toast"></div>

<script src="{{ asset('js/main.js') }}"></script>
<script src="{{ asset('js/riwayat.js') }}"></script>

</body>
</html>