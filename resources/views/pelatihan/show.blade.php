@extends('layout.app')

@section('contents')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Detail Pelatihan</h3>
                    <div class="card-tools">
                        <a href="{{ route('pelatihan.index') }}" class="btn btn-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <table class="table table-bordered">
                                <tr>
                                    <th width="200">Nama Pelatihan</th>
                                    <td>{{ $pelatihan->nama_pelatihan }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Mulai</th>
                                    <td>{{ date('d/m/Y', strtotime($pelatihan->tanggal_mulai)) }}</td>
                                </tr>
                                <tr>
                                    <th>Tanggal Selesai</th>
                                    <td>{{ date('d/m/Y', strtotime($pelatihan->tanggal_selesai)) }}</td>
                                </tr>
                                <tr>
                                    <th>Lokasi</th>
                                    <td>{{ $pelatihan->lokasi }}</td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    @if($pelatihan->status === 'approved')
                                <td class="bg-success text-white text-center rounded">{!! $pelatihan->status !!}</td>
                                @elseif($pelatihan->status === 'rejected')
                                    <td class="bg-danger text-white text-center rounded">{!! $pelatihan->status !!}</td>
                                @elseif($pelatihan->status === 'pending')
                                    <td class="bg-warning text-white text-center rounded">{!! $pelatihan->status !!}</td>
                                @endif
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
                                    <th>Diverifikasi Oleh</th>
                                    <td>{{ $pelatihan->VerifiedBy->username ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <th>Catatan Admin</th>
                                    <td>{{ $pelatihan->catatan_admin ?? '-' }}</td>
                                </tr>
                            </table>
                             <!-- Tambahkan section baru untuk sertifikat -->
                            <div class="row mt-4">
                                <div class="col-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h4 class="card-title">Sertifikat Pelatihan</h4>
                                        </div>
                                        <div class="card-body">
                                            @if($pelatihan->sertifikat)
                                                @if(in_array(pathinfo($pelatihan->sertifikat, PATHINFO_EXTENSION), ['pdf']))
                                                    <embed src="{{ asset('storage/' . $pelatihan->sertifikat) }}" 
                                                        type="application/pdf" 
                                                        width="100%" 
                                                        height="600px" />
                                                @else
                                                    <img src="{{ asset('storage/' . $pelatihan->sertifikat) }}" 
                                                        class="img-fluid" 
                                                        alt="Sertifikat Pelatihan">
                                                @endif
                                            @else
                                                <p class="text-center text-muted">Belum ada sertifikat yang diunggah</p>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection