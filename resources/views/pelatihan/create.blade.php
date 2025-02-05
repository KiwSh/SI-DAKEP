@extends('layout.app')

@section('contents')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Tambah Pelatihan Baru</h3>
                </div>
                <div class="card-body">
                    @if($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('pelatihan.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group">
                            <label>Nama Pelatihan</label>
                            <input type="text" name="nama_pelatihan" class="form-control" required value="{{ old('nama_pelatihan') }}">
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal Mulai</label>
                                    <input type="date" name="tanggal_mulai" class="form-control" required value="{{ old('tanggal_mulai') }}">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Tanggal Selesai</label>
                                    <input type="date" name="tanggal_selesai" class="form-control" required value="{{ old('tanggal_selesai') }}">
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label>Lokasi</label>
                            <input type="text" name="lokasi" class="form-control" required value="{{ old('lokasi') }}">
                        </div>

                        <div class="form-group">
                            <label>Jenis Penyelenggara</label>
                            <select name="penyelenggara" class="form-control" required>
                                <option value="">Pilih Jenis Penyelenggara</option>
                                <option value="dinas" {{ old('penyelenggara') == 'dinas' ? 'selected' : '' }}>Dinas</option>
                                <option value="non dinas" {{ old('penyelenggara') == 'non dinas' ? 'selected' : '' }}>Non Dinas</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Nama Penyelenggara</label>
                            <input type="text" name="nama_penyelenggara" class="form-control" required value="{{ old('nama_penyelenggara') }}">
                        </div>

                        <div class="form-group">
                            <label>Kontak Penyelenggara</label>
                            <input type="text" name="kontak_penyelenggara" class="form-control" required value="{{ old('kontak_penyelenggara') }}">
                        </div>

                        <div class="form-group">
                            <label>Nomor Surat</label>
                            <input type="text" name="nomor_surat" class="form-control" required value="{{ old('nomor_surat') }}">
                        </div>

                        <div class="form-group">
                            <label>Upload Sertifikat Pelatihan</label>
                            <input type="file" name="sertifikat" class="form-control-file" required accept=".pdf,.jpg,.png">
                        </div>

                        <input type="hidden" name="pegawai_id" value="{{Auth::id()}}">

                        <div class="form-group mt-4">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                            <a href="{{ route('pelatihan.index') }}" class="btn btn-secondary">Kembali</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
