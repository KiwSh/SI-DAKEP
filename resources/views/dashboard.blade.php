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
    <div class="row mt-4">
        <!-- Statistik Pegawai (Pie Chart 1) -->
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <strong>Total Pegawai per Jabatan</strong>
                </div>
                <div class="card-body" style="height: 220px;">
                    <canvas id="pegawaiChart" height="180"></canvas>
                </div>
            </div>
        </div>

        <!-- Statistik Pelatihan (Pie Chart 2) -->
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <strong>Persentase Pelatihan</strong>
                </div>
                <div class="card-body" style="height: 220px;">
                    <canvas id="pelatihanPenyelenggaraChart" height="180"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Bar Chart Row -->
    <div class="row mt-3">
        <!-- Statistik Pelatihan per Bulan (Bar Chart) -->
        <div class="col-md-12">
            <div class="card shadow">
                <div class="card-header bg-warning text-white">
                    <strong>Total Pelatihan per Bulan</strong>
                </div>
                <div class="card-body" style="height: 200px;">
                    <canvas id="pelatihanChart" height="150"></canvas>
                </div>
                <div class="card-footer text-center text-muted">
                    <small>Tahun {{ date('Y') }}</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="mt-4 text-center">
        <p>&copy; {{ date('Y') }} Data Kepegawaian - Laravel Admin Panel</p>
    </footer>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var ctxPegawai = document.getElementById('pegawaiChart').getContext('2d');
var jabatanData = @json($pegawaiPerJabatan);

// Calculate total employees
var totalPegawai = jabatanData.reduce((sum, item) => sum + item.total, 0);

var labels = jabatanData.map(item => {
    var percentage = ((item.total / totalPegawai) * 100).toFixed(1);
    return `${item.nama_jabatan} (${percentage}%)`;
});
var data = jabatanData.map(item => item.total);
var colors = labels.map(() => {
    var r = Math.floor(Math.random() * 255);
    var g = Math.floor(Math.random() * 255);
    var b = Math.floor(Math.random() * 255);
    return `rgba(${r}, ${g}, ${b}, 0.7)`;
});

new Chart(ctxPegawai, {
    type: 'pie',
    data: {
        labels: labels,
        datasets: [{
            data: data,
            backgroundColor: colors,
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    font: {
                        size: 11
                    }
                }
            },
            title: {
                display: true,
                text: 'Distribusi Pegawai per Jabatan',
                font: {
                    size: 12
                }
            }
        }
    }
});

    // Pie Chart 2 - Persentase Pelatihan
    var ctxPelatihanPenyelenggara = document.getElementById('pelatihanPenyelenggaraChart').getContext('2d');
    new Chart(ctxPelatihanPenyelenggara, {
        type: 'pie',
        data: {
            labels: ['Dinas ({{ $persenDinas }}%)', 'Non-Dinas ({{ $persenNonDinas }}%)'],
            datasets: [{
                data: [{{ $persenDinas }}, {{ $persenNonDinas }}],
                backgroundColor: ['rgba(40, 167, 69, 0.7)', 'rgba(220, 53, 69, 0.7)'],
                borderColor: ['rgba(40, 167, 69, 1)', 'rgba(220, 53, 69, 1)'],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        font: {
                            size: 11
                        }
                    }
                }
            }
        }
    });

    // Bar Chart - Pelatihan per Bulan
    var ctxPelatihan = document.getElementById('pelatihanChart').getContext('2d');
    new Chart(ctxPelatihan, {
        type: 'bar',
        data: {
            labels: @json($bulanArray),
            datasets: [{
                label: 'Total Pelatihan',
                data: @json($dataPelatihan),
                backgroundColor: 'rgba(255, 193, 7, 0.7)',
                borderColor: 'rgba(255, 193, 7, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    labels: {
                        font: {
                            size: 11
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    stepSize: 1,
                    ticks: {
                        font: {
                            size: 11
                        }
                    }
                },
                x: {
                    ticks: {
                        font: {
                            size: 11
                        }
                    }
                }
            }
        }
    });
});
</script>
@endsection