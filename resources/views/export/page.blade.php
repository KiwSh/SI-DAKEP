@extends('layout.app')

@section('title', 'Export Data Pegawai')

@section('contents')
    <h4 class="mb-4">Export Data Pegawai</h4>
    <hr />

    <!-- Form Filter -->
    <form method="GET" action="{{ route('pegawai.export') }}" class="mb-4">
        @csrf
        <div class="row">
            <!-- Filter berdasarkan Jabatan -->
            <div class="col-md-4">
                <label class="labels">Jabatan</label>
                <select name="jabatan" class="form-control">
                    <option value="">-- Pilih Jabatan --</option>
                    @foreach ($jabatans as $jabatan)
                        <option value="{{ $jabatan->id }}"
                            {{ request()->input('jabatan') == $jabatan->id ? 'selected' : '' }}>
                            {{ $jabatan->nama_jabatan }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Filter berdasarkan Tanggal Mulai Kerja -->
            <div class="col-md-4">
                <label class="labels">Mulai Kerja</label>
                <input type="date" name="mulai_kerja" class="form-control" value="{{ request()->input('mulai_kerja') }}">
            </div>
        </div>

        <div class="row">
            <!-- Filter berdasarkan Tanggal Selesai Kerja -->
            <div class="col-md-4">
                <label class="labels">Selesai Kerja</label>
                <input type="date" name="selesai_kerja" class="form-control"
                    value="{{ request()->input('selesai_kerja') }}">
            </div>

            <!-- Tombol Export -->
            <div class="col-md-4">
                <label>&nbsp;</label>
                <button type="submit" class="btn btn-primary form-control">Export Data</button>
            </div>
        </div>
    </form>

    <!-- Tabel Data Pegawai yang Terfilter -->
    <h4 class="mt-4">Data Pegawai</h4>
    <table class="table table-bordered">
        <thead>
            <tr>
                <th>NIK</th>
                <th>Foto</th>
                <th>Nama Pegawai</th>
                <th>Jabatan</th>
                <th>Mulai Kerja</th>
                <th>Lama Kerja</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($pegawais as $pegawai)
                <tr>
                    <td>{{ $pegawai->nik }}</td>
                    <td><img src="{{Storage::url($pegawai->foto)}}" alt="Foto Pegawai" width="70" height="50"></td>
                    <td>{{ $pegawai->nama }}</td>
                    <td>{{ $pegawai->nama_jabatan ?? 'Tidak Ada' }}</td>
                    <td>{{ $pegawai->mulai_kerja }}</td>
                    <td>{{ $pegawai->lama_kerja }} Tahun</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
