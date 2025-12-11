<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Detail Pesanan - Admin Utama Laundry</title>
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
            <div>
                <a href="{{ route('admin.order.management') }}" class="btn-back">← Kembali</a>
                <h1>Detail Pesanan</h1>
                <p class="subtitle">ID: {{ $order->order_sn }}</p>
            </div>
        </div>

        <div class="detail-grid">
            <!-- LEFT: Order Info -->
            <div class="detail-left">
                <!-- Customer Info -->
                <div class="info-card">
                    <h3>Informasi Pelanggan</h3>
                    <div class="info-row">
                        <span>Nama:</span>
                        <strong>{{ $order->user->name }}</strong>
                    </div>
                    <div class="info-row">
                        <span>Email:</span>
                        <strong>{{ $order->user->email }}</strong>
                    </div>
                    <div class="info-row">
                        <span>No. HP:</span>
                        <strong>{{ $order->phone }}</strong>
                    </div>
                </div>

                <!-- Order Details -->
                <div class="info-card">
                    <h3>Detail Pesanan</h3>
                    <div class="info-row">
                        <span>Layanan:</span>
                        <strong>{{ $order->service_name }}</strong>
                    </div>
                    <div class="info-row">
                        <span>Paket:</span>
                        <strong>{{ $order->paket }}</strong>
                    </div>
                    @if($order->service_type === 'kiloan')
                    <div class="info-row">
                        <span>Estimasi Berat:</span>
                        <strong>{{ $order->estimasi_berat }} kg</strong>
                    </div>
                    @if($order->jumlah_pakaian_dalam > 0)
                    <div class="info-row">
                        <span>Pakaian Dalam:</span>
                        <strong>{{ $order->jumlah_pakaian_dalam }} item</strong>
                    </div>
                    @endif
                    @else
                    <div class="info-row">
                        <span>Jumlah Item:</span>
                        <strong>{{ $order->jumlah_item }} pcs</strong>
                    </div>
                    @endif
                    <div class="info-row">
                        <span>Metode Pembayaran:</span>
                        <strong>
                            @if($order->payment_method === 'qris_pra') QRIS Pra-Bayar
                            @elseif($order->payment_method === 'qris_pasca') QRIS Pasca-Bayar
                            @elseif($order->payment_method === 'cash_pra') Tunai Pra-Bayar
                            @else Tunai Pasca-Bayar
                            @endif
                        </strong>
                    </div>
                    <div class="info-row">
                        <span>Tanggal Order:</span>
                        <strong>{{ $order->created_at->format('d M Y, H:i') }} WIB</strong>
                    </div>
                    <div class="info-row total-row">
                        <span>Total Harga:</span>
                        <strong class="price">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</strong>
                    </div>
                </div>

                <!-- Status Update -->
                <div class="info-card">
                    <h3>Update Status Cucian</h3>
                    <div class="status-update-form">
                        <select id="statusCucianSelect" class="status-select">
                            <option value="baru" {{ $order->status_cucian === 'baru' ? 'selected' : '' }}>Baru</option>
                            <option value="dalam_antrean" {{ $order->status_cucian === 'dalam_antrean' ? 'selected' : '' }}>Dalam Antrean</option>
                            <option value="proses_pengerjaan" {{ $order->status_cucian === 'proses_pengerjaan' ? 'selected' : '' }}>Proses Pengerjaan</option>
                            <option value="siap_diambil" {{ $order->status_cucian === 'siap_diambil' ? 'selected' : '' }}>Siap Diambil</option>
                            <option value="selesai" {{ $order->status_cucian === 'selesai' ? 'selected' : '' }}>Selesai</option>
                        </select>
                        <button onclick="updateStatus()" class="btn-update">Update Status</button>
                    </div>
                </div>
            </div>

            <!-- RIGHT: Payment Verification -->
            <div class="detail-right">
                @if($order->payment_method === 'qris_pra' && $order->paymentProof)
                <div class="verification-card">
                    <h3>Verifikasi Pembayaran</h3>
                    
                    <div class="proof-status status-{{ $order->paymentProof->status }}">
                        @if($order->paymentProof->status === 'pending')
                            <span>⏳ Menunggu Verifikasi</span>
                        @elseif($order->paymentProof->status === 'verified')
                            <span>✓ Terverifikasi</span>
                        @else
                            <span>✕ Ditolak</span>
                        @endif
                    </div>

                    <div class="proof-image-container">
                        @if(Str::endsWith($order->paymentProof->file_path, '.pdf'))
                            <div class="pdf-preview">
                                <svg width="60" height="60" fill="currentColor" viewBox="0 0 16 16">
                                    <path d="M14 4.5V14a2 2 0 0 1-2 2h-1v-1h1a1 1 0 0 0 1-1V4.5h-2A1.5 1.5 0 0 1 9.5 3V1H4a1 1 0 0 0-1 1v9H2V2a2 2 0 0 1 2-2h5.5L14 4.5z"/>
                                </svg>
                                <p>File PDF</p>
                                <a href="{{ asset('storage/' . $order->paymentProof->file_path) }}" target="_blank" class="btn-view">Lihat PDF</a>
                            </div>
                        @else
                            <img src="{{ asset('storage/' . $order->paymentProof->file_path) }}" alt="Bukti Pembayaran" class="proof-image">
                        @endif
                    </div>

                    <div class="proof-info">
                        <small>Diupload: {{ $order->paymentProof->uploaded_at->format('d M Y, H:i') }} WIB</small>
                    </div>

                    @if($order->paymentProof->status === 'pending')
                    <div class="verification-actions">
                        <button onclick="verifyPayment('approve')" class="btn-approve">✓ Setujui</button>
                        <button onclick="verifyPayment('reject')" class="btn-reject">✕ Tolak</button>
                    </div>
                    @endif

                    @if($order->paymentProof->status === 'rejected' && $order->paymentProof->rejection_reason)
                    <div class="rejection-reason">
                        <strong>Alasan Penolakan:</strong>
                        <p>{{ $order->paymentProof->rejection_reason }}</p>
                    </div>
                    @endif
                </div>
                @else
                <div class="info-card">
                    <h3>Status Pembayaran</h3>
                    <div class="payment-status-big status-{{ $order->payment_status }}">
                        @if($order->payment_status === 'paid')
                            ✓ Lunas
                        @elseif($order->payment_status === 'waiting_verification')
                            ⏳ Menunggu Verifikasi
                        @else
                            ○ Belum Bayar
                        @endif
                    </div>
                    <p class="payment-note">
                        @if($order->payment_method === 'qris_pra')
                            User belum mengupload bukti pembayaran
                        @else
                            Pembayaran dilakukan di outlet
                        @endif
                    </p>

                    @if($order->payment_status !== 'paid')
                    <div class="complete-transaction-section">
                        <button onclick="completeTransaction()" class="btn-complete-transaction">
                            ✓ Selesaikan Transaksi
                        </button>
                        <small class="complete-note">Pastikan pembayaran customer sudah diterima</small>
                    </div>
                    @endif
                </div>
                @endif
            </div>
        </div>
    </main>
</div>

<!-- Modal Reject Reason -->
<div id="rejectModal" class="modal">
    <div class="modal-box">
        <h3>Tolak Pembayaran</h3>
        <p>Masukkan alasan penolakan:</p>
        <textarea id="rejectionReason" rows="4" placeholder="Contoh: Bukti pembayaran tidak jelas / Nominal tidak sesuai"></textarea>
        <div class="modal-actions">
            <button onclick="closeRejectModal()" class="btn-cancel">Batal</button>
            <button onclick="confirmReject()" class="btn-confirm">Tolak Pembayaran</button>
        </div>
    </div>
</div>

<div id="toast" class="toast"></div>

<script src="{{ asset('js/admin.js') }}"></script>
<script>
// Define order serial number
const orderSn = "{{ $order->order_sn }}";

// Update Status Cucian
function updateStatus() {
    const status = document.getElementById('statusCucianSelect').value;
    
    fetch(`/admin/order/update-status/${orderSn}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ status_cucian: status })
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            showToast('success', result.message);
        } else {
            showToast('error', result.message);
        }
    })
    .catch(error => {
        console.error('Update status error:', error);
        showToast('error', 'Gagal mengupdate status');
    });
}

// Complete Transaction (Selesaikan Transaksi)
function completeTransaction() {
    console.log('completeTransaction() dipanggil');
    console.log('orderSn:', orderSn);
    
    const url = `/admin/payment/complete-transaction/${orderSn}`;
    console.log('Mengirim request ke:', url);

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(result => {
        console.log('Response data:', result);
        if (result.success) {
            showToast('success', result.message);
            setTimeout(() => {
                console.log('Reloading halaman...');
                location.reload();
            }, 1500);
        } else {
            showToast('error', result.message || 'Gagal menyelesaikan transaksi');
        }
    })
    .catch(error => {
        console.error('Fetch error:', error);
        showToast('error', 'Gagal menyelesaikan transaksi: ' + error.message);
    });
}

// Verify Payment (for QRIS Pra)
function verifyPayment(action) {
    if (action === 'reject') {
        document.getElementById('rejectModal').style.display = 'flex';
        return;
    }

    // Approve langsung
    fetch(`/admin/payment/verify/${orderSn}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ action: 'approve' })
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            showToast('success', result.message);
            setTimeout(() => location.reload(), 1500);
        } else {
            showToast('error', result.message);
        }
    })
    .catch(error => {
        console.error('Verify payment error:', error);
        showToast('error', 'Gagal memverifikasi pembayaran');
    });
}

// Confirm Reject Payment
function confirmReject() {
    const reason = document.getElementById('rejectionReason').value.trim();
    
    if (!reason) {
        showToast('error', 'Alasan penolakan harus diisi!');
        return;
    }

    fetch(`/admin/payment/verify/${orderSn}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ 
            action: 'reject',
            rejection_reason: reason
        })
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            showToast('success', result.message);
            closeRejectModal();
            setTimeout(() => location.reload(), 1500);
        } else {
            showToast('error', result.message);
        }
    })
    .catch(error => {
        console.error('Reject payment error:', error);
        showToast('error', 'Gagal menolak pembayaran');
    });
}

// Close Reject Modal
function closeRejectModal() {
    document.getElementById('rejectModal').style.display = 'none';
    document.getElementById('rejectionReason').value = '';
}

// Show Toast Notification
function showToast(type, message) {
    const toast = document.getElementById('toast');
    if (!toast) {
        console.error('Toast element tidak ditemukan!');
        alert(message); // Fallback ke alert
        return;
    }
    toast.className = `toast ${type} show`;
    toast.textContent = message;
    setTimeout(() => toast.classList.remove('show'), 3000);
}

// Log when page loads
console.log('Order detail page loaded');
console.log('Order SN:', orderSn);
console.log('CSRF Token:', document.querySelector('meta[name="csrf-token"]')?.content);
</script>

</body>
</html>