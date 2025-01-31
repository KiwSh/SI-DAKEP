<nav class="navbar navbar-expand navbar-light bg-white topbar mb-4 static-top shadow">
    <div class="container-fluid d-flex justify-content-between align-items-center">
        <!-- Nama Aplikasi -->
        <span class="text-primary" style="font-size: 1.5rem;">Dinas Komunikasi dan Informatika</span>

        <!-- Username dan Role -->
        <div class="d-flex align-items-center">
            @if(auth()->check())
                <div class="d-flex align-items-center">
                    <!-- Dropdown -->
                    <div class="dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center text-primary" href="#" role="button" id="userDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                            <!-- Logo Profil -->
                            <i class="fas fa-user-circle text-primary" style="font-size: 1.5rem; margin-right: 5px;"></i>

                            
                            <!-- Username dan Role -->
                            <span>
                                {{ auth()->user()->username }} - {{ ucfirst(auth()->user()->role) }}
                            </span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                            <li>
                                <span class="dropdown-item-text">Role: {{ ucfirst(auth()->user()->role) }}</span>
                            </li>
                            <li>
                                <a class="dropdown-item" href="{{ route('account.edit') }}">
                                    <i class="fas fa-cog mr-2"></i>Pengaturan Akun
                                </a>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form id="logoutForm" action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button type="button" class="dropdown-item" onclick="confirmLogout()">
                                        <i class="fas fa-sign-out-alt mr-2"></i>Keluar
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            @else
                <span class="text-primary">Guest</span>
            @endif
        </div>
    </div>
</nav>

<!-- Script Logout -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmLogout() {
        Swal.fire({
            title: 'Konfirmasi Logout',
            text: "Apakah Anda yakin ingin logout?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Logout',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('logoutForm').submit();
            }
        });
    }
</script>
