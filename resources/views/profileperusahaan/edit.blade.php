@extends('layout.app')

@section('contents')
<div class="container">
    <h3 class="text-primary text-center mb-4">Kelola Profil Perusahaan</h3>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <div class="card shadow">
        <div class="card-header bg-info text-white">
            <strong>Informasi Perusahaan</strong>
        </div>
        <div class="card-body">
            <form action="{{ route('profile-perusahaan.update') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="nama_dinas" class="form-label">Nama Dinas</label>
                    <input 
                        type="text" 
                        class="form-control @error('nama_dinas') is-invalid @enderror" 
                        id="nama" 
                        name="nama" 
                        value="{{ old('nama', $ProfilePerusahaan->nama_dinas ?? '') }}"
                        required
                    >
                    @error('nama')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="alamat" class="form-label">Alamat</label>
                    <input 
                        type="text" 
                        class="form-control @error('alamat') is-invalid @enderror" 
                        id="alamat" 
                        name="alamat" 
                        value="{{ old('alamat', $ProfilePerusahaan->alamat ?? '') }}"
                        required
                    >
                    @error('alamat')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="bidang" class="form-label">Bidang</label>
                    <input 
                        type="text" 
                        class="form-control @error('bidang') is-invalid @enderror" 
                        id="bidang" 
                        name="bidang" 
                        value="{{ old('bidang', $ProfilePerusahaan->bidang ?? '') }}"
                        required
                    >
                    @error('bidang')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>

                <button type="submit" class="btn btn-success">Simpan Perubahan</button>
            </form>
        </div>
    </div>
</div>
@endsection
