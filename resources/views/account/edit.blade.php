@extends('layout.app')

@section('contents')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Pengaturan Akun</h4>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('account.update') }}">
                        @csrf
                        @method('PUT')
                        
                        <div class="mb-3">
                            <label class="form-label">Username</label>
                            <input type="text" name="username" class="form-control" 
                                   value="{{ $user->username }}" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Password Saat Ini</label>
                            <input type="password" name="current_password" 
                                   class="form-control" placeholder="Masukkan password saat ini" required>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Password Baru</label>
                            <input type="password" name="new_password" 
                                   class="form-control" placeholder="Masukkan password baru">
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Konfirmasi Password Baru</label>
                            <input type="password" name="new_password_confirmation" 
                                   class="form-control" placeholder="Konfirmasi password baru">
                        </div>
                        
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i> Perbarui Akun
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection