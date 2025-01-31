@extends('layout.app')

@section('contents')
<div class="container mt-5">
    <h1>Daftar Pengguna</h1>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <a href="{{ route('Pengguna.create') }}" class="btn btn-primary mb-3">Tambah Data</a>

    <table class="table mt-4">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama User</th>
                <th>Username</th>
                <th>Role</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($users as $index => $user)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $user->nama_user }}</td>
                    <td>{{ $user->username }}</td>
                    <td>{{ ucfirst($user->role) }}</td>
                    <td>
                        <a href="{{ route('Pengguna.edit', $user->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <button type="button" class="btn btn-danger btn-sm" onclick="openDeleteModal('{{ $user->nama_user }}', {{ $user->id }})">Delete</button>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">Tidak ada data pengguna.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<!-- Modal Konfirmasi Hapus -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Penghapusan</h5>
                <button type="button" class="close" data-bs-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Untuk menghapus data pengguna, ketikkan <strong id="user-name"></strong> di kotak di bawah ini:</p>
                <input type="text" id="confirmation-input" class="form-control" placeholder="Ketikkan nama pengguna">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" id="cancel-delete-btn" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" id="confirm-delete-btn" disabled>Hapus</button>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    let userIdToDelete = null;

    function openDeleteModal(userName, userId) {
        // Tampilkan modal konfirmasi
        document.getElementById('user-name').innerText = userName;
        userIdToDelete = userId;
        $('#deleteModal').modal('show');
    }

    // Fungsi untuk memeriksa apakah input cocok dengan nama pengguna
    document.getElementById('confirmation-input').addEventListener('input', function() {
        const inputValue = this.value;
        const userName = document.getElementById('user-name').innerText;

        // Aktifkan tombol hapus jika input cocok dengan nama pengguna
        document.getElementById('confirm-delete-btn').disabled = inputValue !== userName;
    });

    // Fungsi untuk mengonfirmasi dan menghapus pengguna
    document.getElementById('confirm-delete-btn').addEventListener('click', function() {
        if (userIdToDelete !== null) {
            // Kirimkan form penghapusan
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('Pengguna.destroy', '') }}/' + userIdToDelete;

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';
            form.appendChild(csrfToken);

            const method = document.createElement('input');
            method.type = 'hidden';
            method.name = '_method';
            method.value = 'DELETE';
            form.appendChild(method);

            document.body.appendChild(form);
            form.submit();
        }
    });

    // Fungsi untuk menutup modal ketika tombol batal atau tombol X diklik
    document.querySelectorAll('[data-bs-dismiss="modal"]').forEach(button => {
        button.addEventListener('click', function() {
            // Tutup modal secara eksplisit
            $('#deleteModal').modal('hide');
        });
    });
</script>
@endsection
