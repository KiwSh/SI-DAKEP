@extends('layout.app')

@section('contents')
@if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
@endif

    <div class="container mt-4">
        <h4>Data Pegawai</h4>

        <!-- Tombol -->
        <a href="{{ route('pegawai.data.create') }}" class="btn btn-primary mb-3">Tambah Pegawai</a>
        <button class="btn btn-secondary mb-3" data-bs-toggle="modal" data-bs-target="#importModal">Import Data</button>

        <br>

        @if (auth()->check() && auth()->user()->role == 'admin')
            <!-- Form Pencarian -->
            <form method="GET" action="{{ route('pegawai.data.index') }}"
                class="d-none d-sm-inline-block form-inline mr-auto ml-md-3 my-2 my-md-0 mw-100 navbar-search mb-4">
                <div class="input-group">
                    <input type="text" name="search" class="form-control bg-light border-0 small"
                        placeholder="Cari Pegawai..." aria-label="Search" aria-describedby="basic-addon2"
                        value="{{ request('search') }}">
                    <div class="input-group-append">
                        <button class="btn btn-primary" type="submit">
                            <i class="fas fa-search fa-sm"></i>
                        </button>
                    </div>
                </div>
            </form>
        @endif

        @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    html: "{{ session('success') }}",
                    confirmButtonText: 'OK'
                });
            });
        </script>
    @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Foto</th>
                    <th>NIK</th>
                    <th>Nama</th>
                    <th>Jabatan</th>
                    <th>Mulai Kerja</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pegawais as $index => $pegawai)
                    <tr> <!-- Tambahkan class text-center -->
                        <td>{{ $pegawais->perPage() * ($pegawais->currentPage() - 1) + $loop->iteration }}</td>
                        <td>
                            <img src="{{ asset('storage/' . $pegawai->foto) }}" alt="Foto Pegawai"
                                 style="width: 80px; height: 80px; object-fit: cover; border-radius: 5px;">
                        </td>
                        <td>{{ $pegawai->nik }}</td>
                        <td>{{ $pegawai->nama }}</td>
                        <td>{{ $pegawai->jabatan ? $pegawai->jabatan->nama_jabatan : 'Tidak Ada Jabatan' }}</td>
                        <td>{{ $pegawai->mulai_kerja }}</td>
                        <td>
                            <a href="{{ route('pegawai.data.edit', $pegawai->id) }}" class="btn btn-sm btn-success">Edit</a>
                            <a href="{{ route('pegawai.data.show', $pegawai->id) }}" class="btn btn-sm btn-info">Lihat Detail</a>
                            @if (auth()->check() && auth()->user()->role == 'admin')
                                <form id="deleteForm{{ $pegawai->id }}" action="{{ route('pegawai.data.destroy', $pegawai->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete({{ $pegawai->id }})">Hapus</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
            
        </table>
        <div class="mt-3">
            {{$pegawais->links()}}
        </div>
    </div>

    <!-- Modal Import Data -->
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Import Data Pegawai</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('import.excel') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="importFile" class="form-label">Pilih File Excel</label>
                        <input class="form-control" type="file" id="importFile" name="file" accept=".xls,.xlsx" required>
                        <small class="text-muted">* Hanya file Excel (.xls, .xlsx) yang diperbolehkan</small>
                    </div>
                    <div class="alert alert-info" role="alert">
                        <strong>Perhatian!</strong>
                        <ul class="mb-0">
                            <li>Pastikan format file sesuai template</li>
                            <li>Kolom harus sama dengan struktur database</li>
                            <li>Ukuran file maksimal 2MB</li>
                        </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('styles')
    <style>
        /* Hapus ikon panah di pagination */
        .pagination .page-item:first-child .page-link,
        .pagination .page-item:last-child .page-link {
            display: none;
        }
    </style>
@endpush

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function confirmDelete(id) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data ini akan dihapus!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('deleteForm' + id).submit();
                }
            });
        }
    </script>
@endsection
