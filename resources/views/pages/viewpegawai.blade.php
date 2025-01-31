@extends('layout.app')

@section('contents')
<div class="container mt-4">
    <h4>Detail Pegawai</h4>
    <a href="{{ route('pegawai.data.index') }}" class="btn btn-secondary mb-3">Kembali</a>

    <table class="table table-bordered">
        <tr>
            <th>Foto</th>
            <td>
                @if ($pegawai->foto && Storage::disk('public')->exists($pegawai->foto))
                    <img src="{{ Storage::url($pegawai->foto) }}" alt="Foto Pegawai" width="150">
                @else
                    Tidak Ada Foto
                @endif
            </td>
        </tr>
        <tr>
            <th>NIK</th>
            <td>{{ $pegawai->nik }}</td>
        </tr>
        <tr>
            <th>Nama</th>
            <td>{{ $pegawai->nama }}</td>
        </tr>
        <tr>
            <th>Jabatan</th>
            <td>{{ $pegawai->jabatan ? $pegawai->jabatan->nama_jabatan : 'Tidak Ada Jabatan' }}</td>
        </tr>
        <tr>
            <th>Tanggal Lahir</th>
            <td>{{ $pegawai->tanggal_lahir ? \Carbon\Carbon::parse($pegawai->tanggal_lahir)->format('d-m-Y') : 'Tidak Ada Data' }}</td>
        </tr>
        <tr>
            <th>Alamat</th>
            <td>{{ $pegawai->alamat ?? 'Tidak Ada Data' }}</td>
        </tr>
        <tr>
            <th>Mulai Kerja</th>
            <td>{{ $pegawai->mulai_kerja ? \Carbon\Carbon::parse($pegawai->mulai_kerja)->format('d-m-Y') : 'Tidak Ada Data' }}</td>
        </tr>
        <tr>
            <th>Selesai Kerja</th>
            <td>{{ $pegawai->selesai_kerja ? \Carbon\Carbon::parse($pegawai->selesai_kerja)->format('d-m-Y') : 'Masih Bekerja' }}</td>
        </tr>
        <tr>
            <th>Lama Kerja</th>
            <td>
                @if ($pegawai->mulai_kerja)
                    @php
                        $mulaiKerja = \Carbon\Carbon::parse($pegawai->mulai_kerja);
                        $selesaiKerja = $pegawai->selesai_kerja ? \Carbon\Carbon::parse($pegawai->selesai_kerja) : now();
                        $lamaKerja = $mulaiKerja->diff($selesaiKerja);
                    @endphp
                    {{ $lamaKerja->y }} tahun, {{ $lamaKerja->m }} bulan, {{ $lamaKerja->d }} hari
                @else
                    Tidak Ada Data
                @endif
            </td>
        </tr>
    </table>
</div>
@endsection
