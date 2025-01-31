@extends('layout.app')

@section('contents')
<div class="container mt-5">
    <h1>Edit User</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('Pengguna.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')
        
        <div class="mb-3">
            <label for="nama_user" class="form-label">Nama User</label>
            <input type="text" name="nama_user" id="nama_user" class="form-control @error('nama_user') is-invalid @enderror" value="{{ old('nama_user', $user->nama_user) }}" required>
            @error('nama_user')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="username" class="form-label">Username</label>
            <input type="text" name="username" id="username" class="form-control @error('username') is-invalid @enderror" value="{{ old('username', $user->username) }}" required>
            @error('username')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control @error('password') is-invalid @enderror">
            <small class="form-text text-muted">Biarkan kosong jika tidak ingin mengubah password</small>
            @error('password')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="role" class="form-label">Role</label>
            <select name="role" id="role" class="form-control @error('role') is-invalid @enderror" required>
                <option value="">Pilih Role</option>
                <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="user" {{ old('role', $user->role) == 'user' ? 'selected' : '' }}>User</option>
            </select>
            @error('role')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Update User</button>
        <a href="{{ route('Pengguna.index') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection