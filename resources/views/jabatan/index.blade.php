@extends('layout.app')

@section('contents')
<div class="container">
    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5>Tambah Jabatan</h5>
        </div>
        <div class="card-body">
            <form id="formTambahJabatan" action="{{ route('jabatan.store') }}" method="POST" onsubmit="return validateForm();">
                @csrf
                <div class="mb-3">
                    <label for="nama_jabatan" class="form-label">Nama Jabatan</label>
                    <input 
                        type="text" 
                        class="form-control @error('nama_jabatan') is-invalid @enderror" 
                        id="nama_jabatan" 
                        name="nama_jabatan" 
                        placeholder="Nama Jabatan" 
                        value="{{ old('nama_jabatan') }}"
                    >
                    @error('nama_jabatan')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                <button type="submit" class="btn btn-success">Simpan Jabatan</button>
                <button type="button" class="btn btn-secondary" id="btnBatal">Batal</button>
            </form>
        </div>
    </div>

    <div class="mt-4">
        <h5>Daftar Jabatan</h5>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Jabatan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($jabatans as $j)
                <tr>
                    <td>{{ $loop->iteration }}</td> <!-- Nomor urut -->
                    <td>{{ $j->nama_jabatan }}</td>
                    <td>
                        <form action="{{ route('jabatan.destroy', $j->id) }}" method="POST" class="formDeleteJabatan">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btnHapusJabatan">Hapus</button>
                        </form>
                    </td>
                </tr>
                @endforeach
            </tbody>            
        </table>
        <div class="mt-3">
            {{$jabatans->links()}}
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    // Validasi form tambah jabatan
    function validateForm() {
        const namaJabatan = document.getElementById('nama_jabatan').value.trim();
        
        if (namaJabatan === '') {
            Swal.fire({
                icon: 'error',
                title: 'Gagal!',
                text: 'Nama Jabatan tidak boleh kosong.',
                confirmButtonText: 'OK'
            });
            return false;
        }
        
        return true; // Lolos validasi
    }

    // Tombol batal untuk reset form
    document.getElementById('btnBatal').addEventListener('click', function() {
        const form = document.getElementById('formTambahJabatan');
        form.reset();

        const inputs = form.querySelectorAll('.is-invalid');
        inputs.forEach(input => {
            input.classList.remove('is-invalid');
        });
    });

    // Validasi tombol hapus dengan SweetAlert2
    document.querySelectorAll('.btnHapusJabatan').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            const form = this.closest('.formDeleteJabatan');

            Swal.fire({
                title: 'Yakin ingin menghapus?',
                text: 'Data yang sudah dihapus tidak dapat dikembalikan!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });
    });
</script>
@endsection
