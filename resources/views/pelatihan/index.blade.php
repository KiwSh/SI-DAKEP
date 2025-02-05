@extends('layout.app')

@section('contents')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Data Pelatihan Saya</h3>
                    <div class="card-tools">
                        <a href="{{ route('pelatihan.create') }}" class="btn btn-primary btn-sm">
                            <i class="fas fa-plus"></i> Tambah Pelatihan
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Nama Pelatihan</th>
                                <th class="text-center">Tanggal</th>
                                <th class="text-center">Lokasi</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Catatan Admin</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($pelatihan as $index => $item)
                            {{-- {{dd($pelatihan)}} --}}
                            <tr>
                                <td class="text-center">{{ $index + 1 }}</td>
                                <td class="text-center">{{ $item->nama_pelatihan }}</td>
                                <td class="text-center">{{ $item->tanggal_mulai }} - {{ $item->tanggal_selesai }}</td>
                                <td class="text-center">{{ $item->lokasi }}</td>
                                @if($item->status === 'approved')
                                <td class="bg-success text-white text-center rounded">{!! $item->status !!}</td>
                                @elseif($item->status === 'rejected')
                                    <td class="bg-danger text-white text-center rounded">{!! $item->status !!}</td>
                                @elseif($item->status === 'pending')
                                    <td class="bg-warning text-white text-center rounded">{!! $item->status !!}</td>
                                @endif
                                <td class="text-center">{{ $item->catatan_admin ?? '-'}}</td>
                                
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
@endsection