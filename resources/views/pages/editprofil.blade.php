<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Profil Akun - Utama Laundry</title>

<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700&display=swap" rel="stylesheet" />
<link rel="stylesheet" href="{{ asset('css/style.css') }}" />

<style>
#profileImageInput { display: none; } /* Hilangkan choose file */
</style>

</head>
<body>

<!-- ===== MODAL KONFIRMASI ===== -->
<div id="confirmModal" class="modal">
  <div class="modal-box">
    <div class="modal-icon">!</div>
    <h3 id="modalTitle"></h3>
    <p id="modalText"></p>
    <div class="modal-action">
      <button class="modal-cancel" onclick="closeModal()">Batal</button>
      <button class="modal-confirm" id="modalConfirmBtn">Ya</button>
    </div>
  </div>
</div>

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

<!-- ===== CONTENT ===== -->
<section class="profile-container">
<h2 class="profile-title">Profil Akun</h2>

<div class="profile-wrapper">

  <!-- FOTO PROFIL -->
  <div class="profile-left">
    <img id="profilePic"
      src="{{ auth()->user()->photo && auth()->user()->photo !== '' ? asset('storage/' . auth()->user()->photo) : asset('assets/icon/user-profile.png') }}"
      class="profile-photo" />

    <p class="profile-name">{{ auth()->user()->name }}</p>

    <button class="btn-upload" onclick="document.getElementById('profileImageInput').click()">
      Ubah Foto Profil
    </button>

    <input type="file" id="profileImageInput" name="photo" accept="image/*" onchange="previewProfile(event)" />
  </div>

  <!-- FORM PROFILE -->
  <div class="profile-right">

    <div class="tab-menu">
      <button class="tab active" data-tab="detail">Detail Akun</button>
      <button class="tab" data-tab="password">Password</button>
    </div>

    <!-- FORM UTAMA -->
    <form id="profileForm" action="/profile/update" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <div id="tab-detail" class="tab-content active">
        <label>Nama</label>
        <input type="text" name="name" placeholder="Nama" value="{{ auth()->user()->name }}" required />

        <label>Email</label>
        <input type="email" placeholder="Email" value="{{ auth()->user()->email }}" readonly />

        <label>No. HP/Telp</label>
        <input type="text" name="phone" placeholder="08xxxxxxxxxx" value="{{ auth()->user()->phone }}" />

        <input type="file" id="hiddenPhotoInput" name="photo" hidden>
      </div>
    </form>
    <!-- FORM CHANGE PASSWORD (dipisah biar route-nya benar) -->
    <form id="passwordForm" action="/profile/change-password" method="POST">
      @csrf

      <div id="tab-password" class="tab-content">
        <label>Password Saat Ini</label>
        <input type="password" name="current_password" placeholder="Password saat ini">

        <label>Password Baru</label>
        <input type="password" name="new_password" placeholder="Password baru">

        <label>Konfirmasi Password</label>
        <input type="password" name="new_password_confirmation" placeholder="Konfirmasi password baru">
      </div>

    </form>

  </div>

</div>

<!-- ===== TOMBOL AKSI DI BAWAH (UNIVERSAL) ===== -->
<div class="profile-actions">
  <button type="button" class="btn-save" onclick="submitActiveForm()">Simpan Perubahan</button>
  <button class="btn-logout" onclick="logoutConfirm()">Logout</button>

  <form id="deleteForm" action="/profile/delete" method="POST">
    @csrf @method('DELETE')
  </form>

  <button class="btn-delete" onclick="deleteConfirm()">Hapus Akun</button>
</div>

</section>

<!-- ===== TOAST ===== -->
<div id="toast" class="toast"></div>

<!-- ===== SCRIPT ===== -->
<script src="{{ asset('js/main.js') }}"></script>

@if(session('success')) <script>showToast("success", "{{ session('success') }}");</script> @endif
@if(session('error'))   <script>showToast("error", "{{ session('error') }}");</script> @endif
@if($errors->any())    <script>showToast("error", "{{ $errors->first() }}");</script> @endif

</body>
</html>
