@extends('layout.app')

@section('contents')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Verifikasi Pelatihan</h3>
                </div>
                <div class="card-body">
                    <!-- Notifikasi SweetAlert2 -->
                    @if(session('success'))
                        <script>
                            Swal.fire({
                                title: 'Berhasil!',
                                text: "{{ session('success') }}",
                                icon: 'success',
                                confirmButtonText: 'OK'
                            });
                        </script>
                    @endif

                    <!-- Notifikasi Error Validasi -->
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Pegawai</th>
                                <th>Nama Pelatihan</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pelatihan as $index => $item)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $item->pegawai->username }}</td>
                                <td>{{ $item->nama_pelatihan }}</td>
                                <td>{{ $item->tanggal_mulai }} - {{ $item->tanggal_selesai }}</td>
                                <td class="text-center rounded 
                                    @if($item->status === 'approved') bg-success text-white
                                    @elseif($item->status === 'rejected') bg-danger text-white
                                    @elseif($item->status === 'pending') bg-warning text-dark
                                    @endif">
                                    {!! $item->status !!}
                                </td>
                                <td>
                                    <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#detailModal{{ $item->id }}">
                                        Lihat Detail
                                    </button>
                                    @if($item->status !== 'approved')
                                    <button type="button" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#verifyModal{{ $item->id }}">
                                        Verifikasi
                                    </button>
                                    @else
                                    Approved By {{ optional($item->VerifiedBy)->username }}
                                    @endif
                                </td>
                            </tr>

                            <!-- Modal Detail Pelatihan -->
                            <div class="modal fade" id="detailModal{{ $item->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Detail Pelatihan</h5>
                                            <button type="button" class="close" data-dismiss="modal">
                                                <span>&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p><strong>Pegawai:</strong> {{ $item->pegawai->username }}</p>
                                            <p><strong>Nama Pelatihan:</strong> {{ $item->nama_pelatihan }}</p>
                                            <p><strong>Tanggal:</strong> {{ $item->tanggal_mulai }} - {{ $item->tanggal_selesai }}</p>
                                            <p><strong>Lokasi:</strong> {{ $item->lokasi }}</p>
                                            <p><strong>Jenis Penyelenggara:</strong> {{ ucfirst($item->penyelenggara) }}</p> <!-- Modified this -->
                                            <p><strong>Nama Penyelenggara:</strong> {{ $item->nama_penyelenggara }}</p>     <!-- Added this -->
                                            <p><strong>Kontak Penyelenggara:</strong> {{ $item->kontak_penyelenggara }}</p>
                                            <p><strong>Nomor Surat:</strong> {{ $item->nomor_surat }}</p>

                                            <!-- Menampilkan Sertifikat jika tersedia -->
                                            @if($item->sertifikat)
                                                <p><strong>Sertifikat:</strong></p>
                                                <a href="{{ asset('storage/' . $item->sertifikat) }}" target="_blank" class="btn btn-info btn-sm">
                                                    Lihat Sertifikat
                                                </a>
                                            @else
                                                <p><strong>Sertifikat:</strong> <span class="text-danger">Belum ada sertifikat</span></p>
                                            @endif
                                        </div>
                                        
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Verifikasi -->
                            <div class="modal fade" id="verifyModal{{ $item->id }}" tabindex="-1">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <form action="{{ route('admin.pelatihan.verify', $item->id) }}" method="POST">
                                            @csrf
                                            <div class="modal-header">
                                                <h5 class="modal-title">Verifikasi Pelatihan</h5>
                                                <button type="button" class="close" data-dismiss="modal">
                                                    <span>&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="form-group">
                                                    <label>Status</label>
                                                    <select name="status" class="form-control @error('status') is-invalid @enderror" required>
                                                        <option value="approved">Setujui</option>
                                                        <option value="rejected">Tolak</option>
                                                    </select>
                                                    @error('status')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                                <div class="form-group">
                                                    <label>Catatan</label>
                                                    <textarea name="catatan_admin" class="form-control @error('catatan_admin') is-invalid @enderror" rows="3"></textarea>
                                                    @error('catatan_admin')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                                <button type="submit" class="btn btn-primary">Simpan</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@endsection
