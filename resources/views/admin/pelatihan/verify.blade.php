@extends('layout.app')

@section('contents')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Pelatihan</h3>
                    <div class="card-tools">
                        <a href="{{ route('admin.pelatihan.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="200">Nama Pegawai</th>
                                    <td>{{ $pelatihan->pegawai->name }}</td>
                                </tr>
                                <tr>
                                    <th>Nama Pelatihan</th>
                                    <td>{{ $pelatihan->nama_pelatihan }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Mulai</th>
                                    <td>{{ $pelatihan->tanggal_mulai->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Selesai</th>
                                    <td>{{ $pelatihan->tanggal_selesai->format('d/m/Y') }}</td>
                                </tr>
                                <tr>
                                    <th>Lokasi</th>
                                    <td>{{ $pelatihan->lokasi }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="200">Penyelenggara</th>
                                    <td>{{ $pelatihan->penyelenggara }}</td>
                                </tr>
                                <tr>
                                    <th>Kontak Penyelenggara</th>
                                    <td>{{ $pelatihan->kontak_penyelenggara }}</td>
                                </tr>
                                <tr>
                                    <th>Nomor Surat</th>
                                    <td>{{ $pelatihan->nomor_surat }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>{!! $pelatihan->status_badge !!}</td>
                                </tr>
                                <tr>
                                    <th>Catatan Admin</th>
                                    <td>{{ $pelatihan->catatan_admin ?? '-' }}</td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    @if($pelatihan->status == 'pending')
                    <div class="mt-4">
                        <form action="{{ route('admin.pelatihan.verify', $pelatihan->id) }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Status Verifikasi</label>
                                        <select name="status" class="form-control" required>
                                            <option value="approved">Setujui</option>
                                            <option value="rejected">Tolak</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Catatan</label>
                                        <textarea name="catatan_admin" class="form-control" rows="3"></textarea>
                                    </div>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Simpan Verifikasi</button>
                        </form>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection