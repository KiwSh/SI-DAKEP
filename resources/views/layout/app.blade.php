<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="">
  <meta name="author" content="">
  <title>SB Admin 2 - Dashboard</title>
  <!-- Custom fonts for this template-->
  <link href="{{ asset('admin_assets/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
  <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
  
  <!-- Custom styles for this template-->
  <link href="{{ asset('admin_assets/css/sb-admin-2.min.css') }}" rel="stylesheet">

  <!-- Tambahkan CSS Toastr -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
  
</head>
<body id="page-top">
  <!-- Page Wrapper -->
  <div id="wrapper">
  
    <!-- Sidebar -->
    @include('layout.sidebar')
    <!-- End of Sidebar -->
  
    <!-- Content Wrapper -->
    <div id="content-wrapper" class="d-flex flex-column">
  
      <!-- Main Content -->
      <div id="content">
  
        <!-- Topbar -->
        @include('layout.navbar')
        <!-- End of Topbar -->
  
        <!-- Begin Page Content -->
        <div class="container-fluid">
  
          <!-- Page Heading -->
          <div class="d-sm-flex align-items-center justify-content-between mb-4">
            <h1 class="h3 mb-0 text-gray-800">@yield('title')</h1>
          </div>
  
          @yield('contents')
  
          <!-- Content Row -->
  
        </div>
        <!-- /.container-fluid -->
  
      </div>
      <!-- End of Main Content -->
  
      <!-- Footer -->
      @include('layout.footer')
      <!-- End of Footer -->
  
    </div>
    <!-- End of Content Wrapper -->
  
  </div>
  <!-- End of Page Wrapper -->
  
  <!-- Scroll to Top Button-->
  <a class="scroll-to-top rounded" href="#page-top">
    <i class="fas fa-angle-up"></i>
  </a>
  
  <!-- Bootstrap core JavaScript-->
  <script src="{{ asset('admin_assets/vendor/jquery/jquery.min.js') }}"></script>
  <script src="{{ asset('admin_assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
  <!-- Core plugin JavaScript-->
  <script src="{{ asset('admin_assets/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
  <!-- Custom scripts for all pages-->
  <script src="{{ asset('admin_assets/js/sb-admin-2.min.js') }}"></script>
  <!-- Page level plugins -->
  <script src="{{ asset('admin_assets/vendor/chart.js/Chart.min.js') }}"></script>

  <!-- Tambahkan JS Toastr -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<script>
     toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right", // Posisi kanan atas
        "timeOut": "3000", // 3 detik
        "extendedTimeOut": "1000", // Waktu tambahan jika mouse diarahkan ke notifikasi
    };

    @if (session('success'))
        toastr.success('{{ session('success') }}');
    @endif

    @if (session('error'))
        toastr.error('{{ session('error') }}');
    @endif

    @if (Auth::check())
    let timeout;

    function resetActivityTimer() {
        clearTimeout(timeout);

        // Kirim permintaan AJAX untuk memperbarui sesi
        fetch("{{ route('dashboard') }}", {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            }
        }).catch(() => {
            console.error('Gagal memperbarui sesi.');
        });

        // Mulai ulang timer logout otomatis
        timeout = setTimeout(() => {
            alert('Anda terkena logout otomatis karena tidak ada aktivitas selama 15 menit.');
            document.getElementById('logout-form').submit();
        }, 900000); // 15 menit dalam milidetik
    }

    // Jalankan resetActivityTimer saat ada aktivitas
    window.onload = resetActivityTimer;
    window.onmousemove = resetActivityTimer;
    window.onkeypress = resetActivityTimer;
@endif
</script>

</body>
</html>
