@extends('layout.app')

@section('title', 'Tambah Pegawai')

@section('contents')
    <form id="formTambahPegawai" method="POST" action="{{ route('pegawai.data.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <!-- Kolom Kiri -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="labels">NIK</label>
                    <input type="text" name="nik" class="form-control" 
                           placeholder="Masukkan NIK" value="{{ old('nik') }}" required>
                    <div class="invalid-feedback">Field is required</div>
                </div>
                <div class="mb-3">
                    <label class="labels">Nama Pegawai</label>
                    <input type="text" name="nama" class="form-control" 
                           placeholder="Masukkan Nama Pegawai" value="{{ old('nama') }}" required>
                    <div class="invalid-feedback">Field is required</div>
                </div>
                <div class="mb-3">
                    <label class="labels">Tanggal Lahir</label>
                    <input type="date" name="tanggal_lahir" class="form-control" 
                           value="{{ old('tanggal_lahir') }}" required>
                    <div class="invalid-feedback">Field is required</div>
                </div>
                <div class="mb-3">
                    <label class="labels">Alamat</label>
                    <input type="text" name="alamat" class="form-control" 
                           placeholder="Masukkan Alamat" value="{{ old('alamat') }}" required>
                    <div class="invalid-feedback">Field is required</div>
                </div>
                <div class="mb-3">
                    <label class="labels">Jabatan</label>
                    <select name="jabatans_id" class="form-control" required>
                        <option value="">-- Pilih Jabatan --</option>
                        @foreach($jabatans as $jabatan)
                            <option value="{{ $jabatan->id }}" {{ old('jabatans_id') == $jabatan->id ? 'selected' : '' }}>{{ $jabatan->nama_jabatan }}</option>
                        @endforeach
                    </select>
                    <div class="invalid-feedback">Field is required</div>
                </div>
            </div>

            <!-- Kolom Kanan -->
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="labels">Mulai Kerja</label>
                    <input type="date" name="mulai_kerja" id="mulai_kerja" class="form-control" 
                           value="{{ old('mulai_kerja') }}" required>
                    <div class="invalid-feedback">Field is required</div>
                </div>
                <div class="mb-3">
                    <label class="labels">Lama Kerja</label>
                    <select name="lama_kerja" id="lama_kerja" class="form-control" required>
                        <option value="">-- Pilih Lama Kerja --</option>
                        @for ($i = 1; $i <= 30; $i++)
                            <option value="{{ $i }}" {{ old('lama_kerja') == $i ? 'selected' : '' }}>{{ $i }} Tahun</option>
                        @endfor
                    </select>
                    <div class="invalid-feedback">Field is required</div>
                </div>
                <div class="mb-3">
                    <label class="labels">Selesai Kerja</label>
                    <input type="text" name="selesai_kerja" id="selesai_kerja" class="form-control" readonly value="{{ old('selesai_kerja') }}">
                </div>
                <div class="mb-3">
                    <label class="labels">Foto</label>
                    <input type="file" name="foto" class="form-control">
                    <div class="invalid-feedback">Field is required</div>
                </div>
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

    // Event listener untuk kalkulasi selesai kerja
    document.getElementById('mulai_kerja').addEventListener('input', calculateSelesaiKerja);
    document.getElementById('lama_kerja').addEventListener('input', calculateSelesaiKerja);
    document.getElementById('mulai_kerja').addEventListener('change', calculateSelesaiKerja);
    document.getElementById('lama_kerja').addEventListener('change', calculateSelesaiKerja);

    // Validasi form menggunakan SweetAlert
    const form = document.getElementById('formTambahPegawai');
    form.addEventListener('submit', function(event) {
        const fields = ['nik', 'nama', 'tanggal_lahir', 'alamat', 'jabatans_id', 'mulai_kerja', 'lama_kerja'];
        let isValid = true;
        let invalidFields = [];

        fields.forEach(field => {
            const input = document.querySelector(`[name=${field}]`);
            if (!input.value.trim()) {
                invalidFields.push(input);
                isValid = false;
            }
        });

        if (!isValid) {
            // Menampilkan SweetAlert jika ada field yang invalid
            Swal.fire({
                icon: 'error',
                title: 'Validasi Gagal',
                text: 'Tolong isi semua field yang diperlukan!',
            });

            // Fokuskan pada field pertama yang invalid
            invalidFields[0].focus();
            event.preventDefault();
        }
    });
});
</script>
@endpush
