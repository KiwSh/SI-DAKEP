@extends('layout.app')

@section('title', 'Edit Pegawai')

@section('contents')
    <h4 class="mb-4">Edit Pegawai</h4>
    <hr />

    <form id="formEditPegawai" method="POST" action="{{ route('pegawai.data.update', $pegawai->id) }}" enctype="multipart/form-data" novalidate>
        @csrf
        @method('PUT')
        <div class="row">
            <!-- Kolom Kiri -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="labels">NIK</label>
                    <input type="text" name="nik" class="form-control" value="{{ $pegawai->nik }}" placeholder="Masukkan NIK" required>
                </div>
                <div class="mb-3">
                    <label class="labels">Nama Pegawai</label>
                    <input type="text" name="nama" class="form-control" value="{{ $pegawai->nama }}" placeholder="Masukkan Nama Pegawai" required>
                </div>
                <div class="mb-3">
                    <label class="labels">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" class="form-control" value="{{ $pegawai->tanggal_lahir }}" required>
                </div>
                <div class="mb-3">
                    <label class="labels">Alamat</label>
                    <input type="text" name="alamat" class="form-control" value="{{ $pegawai->alamat }}" placeholder="Masukkan Alamat" required>
                </div>
                <div class="mb-3">
                    <label class="labels">Jabatan</label>
                    <select name="jabatans_id" class="form-control" required>
                        <option value="">-- Pilih Jabatan --</option>
                        @foreach($jabatans as $jabatan)
                            <option value="{{ $jabatan->id }}" {{ $pegawai->jabatans_id == $jabatan->id ? 'selected' : '' }}>
                                {{ $jabatan->nama_jabatan }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <!-- Kolom Kanan -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="labels">Mulai Kerja</label>
                    <input type="date" name="mulai_kerja" id="mulai_kerja" class="form-control" value="{{ $pegawai->mulai_kerja }}" required>
                </div>
                <div class="mb-3">
                    <label class="labels">Lama Kerja</label>
                    <select name="lama_kerja" id="lama_kerja" class="form-control" required>
                        <option value="">-- Pilih Lama Kerja --</option>
                        @for ($i = 1; $i <= 30; $i++)
                            <option value="{{ $i }}" {{ $pegawai->lama_kerja == $i ? 'selected' : '' }}>
                                {{ $i }} Tahun
                            </option>
                        @endfor
                    </select>
                </div>
                <div class="mb-3">
                    <label class="labels">Selesai Kerja</label>
                    <input type="text" name="selesai_kerja" id="selesai_kerja" class="form-control" value="{{ $pegawai->selesai_kerja }}" readonly>
                </div>
                <div class="mb-3">
                    <label class="labels">Foto</label>
                    <input type="file" name="foto" class="form-control">
                    @if ($pegawai->foto)
                        <small class="text-muted">Foto saat ini: {{ $pegawai->foto }}</small>
                    @endif
                </div>
            </div>
        </div>

        <!-- Tombol Simpan -->
        <div class="mt-4 text-center">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ route('pegawai.data.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
@endsection

<!-- Tambahkan Toastr CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">

<!-- Tambahkan Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

<!-- Script Tambahan -->
@push('scripts')
<script>
    // Fungsi untuk menghitung tahun selesai kerja
    function calculateSelesaiKerja() {
        const mulaiKerja = new Date(document.getElementById('mulai_kerja').value);
        const lamaKerja = parseInt(document.getElementById('lama_kerja').value);

        if (mulaiKerja && lamaKerja) {
            const tahunSelesai = mulaiKerja.getFullYear() + lamaKerja;
            document.getElementById('selesai_kerja').value = tahunSelesai;
        } else {
            document.getElementById('selesai_kerja').value = ''; // Kosongkan jika input tidak valid
        }
    }

    // Event listener untuk menghitung selesai kerja otomatis
    document.getElementById('mulai_kerja').addEventListener('change', calculateSelesaiKerja);
    document.getElementById('lama_kerja').addEventListener('change', calculateSelesaiKerja);

    toastr.options = {
        "closeButton": true,
        "progressBar": true,
        "positionClass": "toast-top-right",
        "timeOut": "3000",
    };

    @if (session('success'))
        toastr.success('{{ session('success') }}');
    @endif
</script>
@endpush
