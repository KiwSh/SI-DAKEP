@extends('layout.app')

@section('contents')

<div class="container mt-5">
    <!-- Header -->
    <div class="bg-primary text-white text-center p-2 mb-4">
        <h4>DINAS KOMUNIKASI DAN INFORMATIKA</h4>
    </div>

    <!-- Profil Perusahaan -->
    <div class="card">
        <div class="card-header bg-info text-white">
            <strong>Profil Perusahaan</strong>
        </div>
        <div class="card-body p-0">
            <table class="table">
                <thead>
                    <tr>
                        <th>Nama Perusahaan</th>
                        <th>Alamat</th>
                        <th>Bidang</th>
                        <th>Kelola</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>{{ $profilPerusahaan ? $profilPerusahaan->nama : '-' }}</td>
                        <td>{{ $profilPerusahaan ? $profilPerusahaan->alamat : '-' }}</td>
                        <td>{{ $profilPerusahaan ? $profilPerusahaan->bidang : '-' }}</td>
                        <td>
                            <a href="{{ route('profile-perusahaan.edit') }}" class="btn btn-success">
                                <i class="fa fa-wrench"></i> Edit
                            </a>
                        </td>
                        
                    </tr>
                </tbody>
            </table>
            
        </div>
    </div>

    <!-- Modal Edit Profil -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit Profil Perusahaan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('profile-perusahaan.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="nama">Nama Perusahaan</label>
                        <input type="text" name="nama" class="form-control" value="{{ $profilPerusahaan->nama ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <input type="text" name="alamat" class="form-control" value="{{ $profilPerusahaan->alamat ?? '' }}">
                    </div>
                    <div class="form-group">
                        <label for="bidang">Bidang</label>
                        <input type="text" name="bidang" class="form-control" value="{{ $profilPerusahaan->bidang ?? '' }}">
                    </div>
                    <button type="submit" class="btn btn-success">Simpan</button>
                </form>           
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>

@endsection