@extends('layout.app')

@section('contents')
<div class="container-fluid">
    <div class="row">
        <!-- Profil Perusahaan -->
        <div class="col-md-12">
            <h3 class="text-primary text-center mb-4">Profil Perusahaan</h3>
            <div class="card shadow">
                <div class="card-header bg-info text-white">
                    <strong>Informasi Perusahaan</strong>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <label>Nama Perusahaan</label>
                            <input type="text" class="form-control" value="{{ $profilPerusahaan->nama ?? 'Belum diisi' }}" readonly>
                        </div>
                        <div class="col-md-4">
                            <label>Alamat</label>
                            <input type="text" class="form-control" value="{{ $profilPerusahaan->alamat ?? 'Belum diisi' }}" readonly>
                        </div>
                        <div class="col-md-4">
                            <label>Bidang</label>
                            <input type="text" class="form-control" value="{{ $profilPerusahaan->bidang ?? 'Belum diisi' }}" readonly>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistik Grafik -->
    <div class="row mt-5">
        <!-- Statistik Pegawai -->
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <strong>Total Pegawai</strong>
                </div>
                <div class="card-body">
                    <canvas id="pegawaiChart" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Statistik Pengguna -->
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-warning text-white">
                    <strong>Jumlah Pengguna</strong>
                </div>
                <div class="card-body">
                    <canvas id="userChart" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="mt-5 text-center">
        <p>&copy; 2024 Data Kepegawaian - Laravel Admin Panel</p>
    </footer>
</div>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Data Statistik Pegawai
    const ctxPegawai = document.getElementById('pegawaiChart').getContext('2d');
    const pegawaiChart = new Chart(ctxPegawai, {
        type: 'bar',
        data: {
            labels: ['Total Pegawai'],
            datasets: [{
                label: 'Pegawai',
                data: [{{ $totalPegawai }}], // Menggunakan data total pegawai dari controller
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Data Statistik Pengguna
    const ctxUser = document.getElementById('userChart').getContext('2d');
    const userChart = new Chart(ctxUser, {
        type: 'pie',
        data: {
            labels: ['Total Pengguna'],
            datasets: [{
                label: 'User',
                data: [{{ $totalPengguna }}], // Menggunakan data total pengguna dari controller
                backgroundColor: ['rgba(255, 206, 86, 0.6)'],
                borderColor: ['rgba(255, 206, 86, 1)'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
        }
    });
</script>
@endsection
