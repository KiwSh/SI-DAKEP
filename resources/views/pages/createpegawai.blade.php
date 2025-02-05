@extends('layout.app')

@section('title', 'Tambah Pegawai')

@section('contents')
{{-- @if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif --}}




<form id="formTambahPegawai" method="POST" action="{{ route('pegawai.data.store') }}" enctype="multipart/form-data" novalidate>
    @csrf
    <div class="row">
        <!-- Kolom Kiri -->
        <div class="col-md-6">
            <div class="mb-3">
                <label class="labels">NIK</label>
                <input type="text" name="nik" id="nik" class="form-control @error('nik') is-invalid @enderror" name="nik"
                placeholder="Masukkan NIK (16 digit)" value="{{ old('nik') }}" > 
                <div class="invalid-feedback" id="nikFeedback">NIK harus 16 digit angka</div>           
            </div>
            @error('nik')
                <div class="invalid-feedback" id="nikFeedback">NIK harus 16 digit angka</div>
            @enderror
            <div class="mb-3">
                <label class="labels">Nama Pegawai</label>
                <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" 
                placeholder="Masukkan Nama Pegawai" value="{{ old('nama') }}" required>
                <div class="invalid-feedback" id="namaFeedback">Nama tidak boleh mengandung simbol</div>
            </div>
            @error('nama')
                <p class="invalid-feedback">{{ $message }}</p>
            @enderror

            <div class="mb-3">
                <label class="labels">Tanggal Lahir</label>
                <input type="date" name="tanggal_lahir" class="form-control @error('tanggal_lahir') is-invalid @enderror" 
                value="{{ old('tanggal_lahir') }}" required>
                <div class="invalid-feedback">Field is required</div>
            </div>
            @error('tanggal_lahir')
                <p class="invalid-feedback">{{ $message }}</p>
            @enderror

            <div class="mb-3">
                <label class="labels">Alamat</label>
                <input type="text" name="alamat" class="form-control @error('alamat') is-invalid @enderror" 
                placeholder="Masukkan Alamat" value="{{ old('alamat') }}" required>
                <div class="invalid-feedback">Field is required</div>
            </div>
            @error('alamat')
                <p class="invalid-feedback">{{ $message }}</p>
            @enderror

            <div class="mb-3">
                <label class="labels">Jabatan</label>
                <select name="jabatans_id" class="form-control @error('jabatans_id') is-invalid @enderror" required>
                    <option value="">-- Pilih Jabatan --</option>
                    @foreach($jabatans as $jabatan)
                        <option value="{{ $jabatan->id }}" {{ old('jabatans_id') == $jabatan->id ? 'selected' : '' }}>{{ $jabatan->nama_jabatan }}</option>
                    @endforeach
                </select>
                <div class="invalid-feedback">Field is required</div>
            </div>
            @error('jabatans_id')
                <p class="invalid-feedback">{{ $message }}</p>
            @enderror
        </div>

        <!-- Kolom Kanan remains the same -->
        <div class="col-md-6">
            <div class="mb-3">
                <label class="labels">Mulai Kerja</label>
                <input type="date" name="mulai_kerja" id="mulai_kerja" class="form-control @error('mulai_kerja') is-invalid @enderror" 
                value="{{ old('mulai_kerja') }}" required>
                <div class="invalid-feedback">Field is required</div>
            </div>
            @error('mulai_kerja')
                <p class="invalid-feedback">{{ $message }}</p>
            @enderror

            <div class="mb-3">
                <label class="labels">Lama Kerja</label>
                <select name="lama_kerja" id="lama_kerja" class="form-control @error('lama_kerja') is-invalid @enderror" required>
                    <option value="">-- Pilih Lama Kerja --</option>
                    @for ($i = 1; $i <= 30; $i++)
                        <option value="{{ $i }}" {{ old('lama_kerja') == $i ? 'selected' : '' }}>{{ $i }} Tahun</option>
                    @endfor
                </select>
                <div class="invalid-feedback">Field is required</div>
            </div>
            @error('lama_kerja')
                <p class="invalid-feedback">{{ $message }}</p>
            @enderror

            <div class="mb-3">
                <label class="labels">Selesai Kerja</label>
                <input type="date" name="selesai_kerja" id="selesai_kerja" class="form-control" readonly value="{{ old('selesai_kerja') }}">
            </div>

            <div class="mb-3">
                <label class="labels">Foto</label>
                <input type="file" name="foto" class="form-control @error('foto') is-invalid @enderror" required accept=".jpg,.png">
                <div class="invalid-feedback">Field is required</div>
            </div>
            @error('foto')
                <p class="invalid-feedback">{{ $message }}</p>
            @enderror
        </div>
    </div>

    <!-- Tombol Simpan -->
    <div class="mt-4 text-center">
        <button type="submit" class="btn btn-primary">Simpan</button>
        <a href="{{ route('pegawai.data.index') }}" class="btn btn-secondary">Batal</a>
    </div>
</form>

@endsection

@push('styles')
<!-- Include SweetAlert CSS -->
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.9/dist/sweetalert2.min.css" rel="stylesheet">
@endpush
@push('scripts')
<!-- Include SweetAlert JS -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.4.9/dist/sweetalert2.min.js"></script>

{{-- @if (session('error'))
<script>
    console.log("Notifikasi error muncul");
    Swal.fire({
        icon: 'error',
        title: 'Gagal Login!',
        text: "{{ session('error_message') }}",
        confirmButtonColor: '#d33'
    });
</script>
@endif --}}

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const nikInput = document.getElementById('nik');
        const namaInput = document.getElementById('nama');
    
        // Validasi NIK (Harus 16 digit angka)
        nikInput.addEventListener('input', function () {
            this.value = this.value.replace(/\D/g, ''); // Hanya angka
            if (this.value.length === 16) {
                this.classList.remove('is-invalid');
            } else {
                this.classList.add('is-invalid');
                Swal.fire({
                    icon: 'warning',
                    title: 'NIK tidak valid!',
                    text: 'NIK harus terdiri dari 16 digit angka.',
                    confirmButtonColor: '#d33'
                });
            }
        });
    
        // Validasi Nama (Hanya huruf, spasi, dan titik)
        namaInput.addEventListener('input', function () {
            const regexNama = /^[a-zA-Z\s.]+$/;
            if (regexNama.test(this.value) || this.value === '') {
                this.classList.remove('is-invalid');
            } else {
                this.classList.add('is-invalid');
                Swal.fire({
                    icon: 'warning',
                    title: 'Nama tidak valid!',
                    text: 'Nama tidak boleh mengandung simbol selain titik dan spasi.',
                    confirmButtonColor: '#d33'
                });
            }
        });
    });
    </script>
    
@endpush
