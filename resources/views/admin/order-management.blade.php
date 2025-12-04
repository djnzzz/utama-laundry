<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Kelola Pesanan - Admin Utama Laundry</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin-orders.css') }}">
</head>
<body>

<div class="admin-wrapper">
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="sidebar-header">
            <img src="{{ asset('assets/img/logo.png') }}" alt="Logo" class="sidebar-logo">
            <h3>Admin Panel</h3>
        </div>

        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}" class="nav-item">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M8 4a.5.5 0 0 1 .5.5V6a.5.5 0 0 1-1 0V4.5A.5.5 0 0 1 8 4z"/>
                    <path fill-rule="evenodd" d="M0 10a8 8 0 1 1 15.547 2.661c-.442 1.253-1.845 1.602-2.932 1.25C11.309 13.488 9.475 13 8 13c-1.474 0-3.31.488-4.615.911-1.087.352-2.49.003-2.932-1.25A7.988 7.988 0 0 1 0 10z"/>
                </svg>
                Dashboard
            </a>

            <a href="{{ route('admin.price.management') }}" class="nav-item">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M4 10.781c.148 1.667 1.513 2.85 3.591 3.003V15h1.043v-1.216c2.27-.179 3.678-1.438 3.678-3.3 0-1.59-.947-2.51-2.956-3.028l-.722-.187V3.467c1.122.11 1.879.714 2.07 1.616h1.47c-.166-1.6-1.54-2.748-3.54-2.875V1H7.591v1.233c-1.939.23-3.27 1.472-3.27 3.156 0 1.454.966 2.483 2.661 2.917l.61.162v4.031c-1.149-.17-1.94-.8-2.131-1.718H4zm3.391-3.836c-1.043-.263-1.6-.825-1.6-1.616 0-.944.704-1.641 1.8-1.828v3.495l-.2-.05zm1.591 1.872c1.287.323 1.852.859 1.852 1.769 0 1.097-.826 1.828-2.2 1.939V8.73l.348.086z"/>
                </svg>
                Kelola Harga
            </a>

            <a href="{{ route('admin.order.management') }}" class="nav-item active">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M0 1.5A.5.5 0 0 1 .5 1H2a.5.5 0 0 1 .485.379L2.89 3H14.5a.5.5 0 0 1 .491.592l-1.5 8A.5.5 0 0 1 13 12H4a.5.5 0 0 1-.491-.408L2.01 3.607 1.61 2H.5a.5.5 0 0 1-.5-.5zM3.102 4l1.313 7h8.17l1.313-7H3.102zM5 12a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm7 0a2 2 0 1 0 0 4 2 2 0 0 0 0-4zm-7 1a1 1 0 1 1 0 2 1 1 0 0 1 0-2zm7 0a1 1 0 1 1 0 2 1 1 0 0 1 0-2z"/>
                </svg>
                Kelola Pesanan
            </a>

            <!--<a href="/" class="nav-item">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M15 8a.5.5 0 0 0-.5-.5H2.707l3.147-3.146a.5.5 0 1 0-.708-.708l-4 4a.5.5 0 0 0 0 .708l4 4a.5.5 0 0 0 .708-.708L2.707 8.5H14.5A.5.5 0 0 0 15 8z"/>
                </svg>
                Kembali ke Website
            </a>-->

            <a href="/logout" class="nav-item logout-btn">
                <svg width="20" height="20" fill="currentColor" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M10 12.5a.5.5 0 0 1-.5.5h-8a.5.5 0 0 1-.5-.5v-9a.5.5 0 0 1 .5-.5h8a.5.5 0 0 1 .5.5v2a.5.5 0 0 0 1 0v-2A1.5 1.5 0 0 0 9.5 2h-8A1.5 1.5 0 0 0 0 3.5v9A1.5 1.5 0 0 0 1.5 14h8a1.5 1.5 0 0 0 1.5-1.5v-2a.5.5 0 0 0-1 0v2z"/>
                    <path fill-rule="evenodd" d="M15.854 8.354a.5.5 0 0 0 0-.708l-3-3a.5.5 0 0 0-.708.708L14.293 7.5H5.5a.5.5 0 0 0 0 1h8.793l-2.147 2.146a.5.5 0 0 0 .708.708l3-3z"/>
                </svg>
                Logout
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="admin-profile">
                <img src="{{ auth()->user()->photo ? asset('storage/' . auth()->user()->photo) : asset('assets/icon/user-profile.png') }}" alt="Admin">
                <div>
                    <p class="admin-name">{{ auth()->user()->name }}</p>
                    <p class="admin-role">Administrator</p>
                </div>
            </div>
        </div>
    </aside>

    <!-- Main Content -->
    <main class="main-content">
        <div class="content-header">
            <h1>Kelola Pesanan</h1>
            <p class="subtitle">Verifikasi pembayaran dan kelola semua pesanan laundry</p>
        </div>

        <!-- Quick Stats -->
        <div class="quick-stats-grid">
            <div class="quick-stat-card warning">
                <div class="stat-icon">⏳</div>
                <div>
                    <p class="stat-count">{{ $stats['waiting_verification'] }}</p>
                    <p class="stat-label">Menunggu Verifikasi</p>
                </div>
            </div>
            <div class="quick-stat-card pending">
                <div class="stat-icon">○</div>
                <div>
                    <p class="stat-count">{{ $stats['pending'] }}</p>
                    <p class="stat-label">Belum Bayar</p>
                </div>
            </div>
            <div class="quick-stat-card success">
                <div class="stat-icon">✓</div>
                <div>
                    <p class="stat-count">{{ $stats['paid'] }}</p>
                    <p class="stat-label">Sudah Lunas</p>
                </div>
            </div>
        </div>

        <!-- Filter & Search Section -->
        <div class="filter-search-section">
            <form method="GET" action="{{ route('admin.order.management') }}" class="filter-form">
                <div class="search-box">
                    <input type="text" name="search" placeholder="Cari ID pesanan, nama, atau no. HP..." value="{{ request('search') }}">
                    <button type="submit" class="btn-search">
                        <svg width="16" height="16" fill="currentColor" viewBox="0 0 16 16">
                            <path d="M11.742 10.344a6.5 6.5 0 1 0-1.397 1.398h-.001c.03.04.062.078.098.115l3.85 3.85a1 1 0 0 0 1.415-1.414l-3.85-3.85a1.007 1.007 0 0 0-.115-.1zM12 6.5a5.5 5.5 0 1 1-11 0 5.5 5.5 0 0 1 11 0z"/>
                        </svg>
                    </button>
                </div>

                <select name="payment_status" onchange="this.form.submit()" class="filter-select">
                    <option value="">Semua Status Pembayaran</option>
                    <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Belum Bayar</option>
                    <option value="waiting_verification" {{ request('payment_status') === 'waiting_verification' ? 'selected' : '' }}>Menunggu Verifikasi</option>
                    <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Lunas</option>
                </select>

                <select name="status_cucian" onchange="this.form.submit()" class="filter-select">
                    <option value="">Semua Status Cucian</option>
                    <option value="baru" {{ request('status_cucian') === 'baru' ? 'selected' : '' }}>Baru</option>
                    <option value="dalam_antrean" {{ request('status_cucian') === 'dalam_antrean' ? 'selected' : '' }}>Dalam Antrean</option>
                    <option value="proses_pengerjaan" {{ request('status_cucian') === 'proses_pengerjaan' ? 'selected' : '' }}>Proses Pengerjaan</option>
                    <option value="siap_diambil" {{ request('status_cucian') === 'siap_diambil' ? 'selected' : '' }}>Siap Diambil</option>
                    <option value="selesai" {{ request('status_cucian') === 'selesai' ? 'selected' : '' }}>Selesai</option>
                </select>

                @if(request()->hasAny(['search', 'payment_status', 'status_cucian']))
                <a href="{{ route('admin.order.management') }}" class="btn-reset">Reset Filter</a>
                @endif
            </form>
        </div>

        <!-- Orders Table -->
        <div class="orders-table-container">
            <table class="orders-table">
                <thead>
                    <tr>
                        <th>ID Pesanan</th>
                        <th>Pelanggan</th>
                        <th>Layanan</th>
                        <th>Total</th>
                        <th>Pembayaran</th>
                        <th>Status Cucian</th>
                        <th>Tanggal</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td>
                            <span class="order-sn">{{ $order->order_sn }}</span>
                        </td>
                        <td>
                            <div class="customer-info">
                                <strong>{{ $order->name }}</strong>
                                <small>{{ $order->phone }}</small>
                            </div>
                        </td>
                        <td>
                            <span class="service-name">{{ $order->service_name }}</span>
                            <span class="badge-paket">{{ $order->paket }}</span>
                        </td>
                        <td>
                            <strong>Rp {{ number_format($order->total_harga, 0, ',', '.') }}</strong>
                        </td>
                        <td>
                            <span class="payment-badge payment-{{ $order->payment_status }}">
                                @if($order->payment_status === 'waiting_verification')
                                    ⏳ Verifikasi
                                @elseif($order->payment_status === 'paid')
                                    ✓ Lunas
                                @else
                                    ○ Pending
                                @endif
                            </span>
                        </td>
                        <td>
                            <span class="status-badge status-{{ $order->status_cucian }}">
                                @if($order->status_cucian === 'baru') Baru
                                @elseif($order->status_cucian === 'dalam_antrean') Antrean
                                @elseif($order->status_cucian === 'proses_pengerjaan') Proses
                                @elseif($order->status_cucian === 'siap_diambil') Siap Ambil
                                @else Selesai
                                @endif
                            </span>
                        </td>
                        <td>
                            <small>{{ $order->created_at->format('d M Y') }}</small>
                        </td>
                        <td>
                            <a href="{{ route('admin.order.detail', $order->order_sn) }}" class="btn-detail">Detail</a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="empty-state">
                            <p>Tidak ada pesanan yang ditemukan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($orders->hasPages())
        <div class="pagination-wrapper">
            {{ $orders->links() }}
        </div>
        @endif
    </main>
</div>

<div id="toast" class="toast"></div>

<script src="{{ asset('js/admin.js') }}"></script>

</body>
</html>