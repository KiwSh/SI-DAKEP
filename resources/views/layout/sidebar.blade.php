<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon rotate-n-15">
        </div>
        <div class="sidebar-brand-text mx-2">SI DATA KEPEGAWAIAN<sup></sup></div>
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('dashboard') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>

    {{-- <li class="nav-item">
    <a class="nav-link" href="{{ route('products') }}">
      <i class="fas fa-fw fa-tachometer-alt"></i>
      <span>Product</span></a>
  </li> --}}

    <li class="nav-item">
        <a class="nav-link" href="{{ route('pegawai.data.index') }}">
            <i class="fas fa-fw fa-users"></i>
            <span>Data Pegawai</span>
        </a>
    </li>

    @if (auth()->check() && auth()->user()->role == 'admin')
    <li class="nav-item">
        <a class="nav-link" href="{{ route('jabatan.index') }}">
            <i class="fas fa-fw fa-briefcase"></i>
            <span>Tambah Jabatan</span>
        </a>
    </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('profile.index') }}">
                <i class="fas fa-fw fa-building"></i>
                <span>Profile Perusahaan</span>
            </a>
        </li>
    @endif


    <li class="nav-item">
        <a class="nav-link" href="{{ route('pegawai.export.page') }}">
            <i class="fas fa-fw fa-file-excel"></i>
            <span>Export Pegawai</span>
        </a>
    </li>

    @if (auth()->check() && auth()->user()->role == 'admin')
    <li class="nav-item">
        <a class="nav-link" href="{{ route('Pengguna.index') }}">
            <i class="fas fa-fw fa-user-plus"></i>
            <span>Tambah Pengguna</span>
        </a>
    </li>
    @endif

    @if (auth()->check() && auth()->user()->role == 'admin')
    <li class="nav-item {{ \Route::is('logactivity*') ? 'active' : ''}}">
        <a class="nav-link" href="{{ route('logactivity.index') }}">
            <i class="fas fa-history"></i>
            <span>History Aktivitas</span>
        </a>
    </li>
@endif

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmLogout() {
            Swal.fire({
                title: 'Konfirmasi Logout',
                text: "Apakah Anda yakin ingin logout?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Logout',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('logoutForm').submit();
                }
            });
        }
    </script>


    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>


</ul>
