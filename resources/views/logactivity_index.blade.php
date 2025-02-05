@extends('layout.app')

@section('contents')
<div class="container mt-5">
    <h4>Riwayat Aktivitas</h4>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>No</th>
                <th>User</th>
                <th>Event</th>
                <th>Sebelum</th>
                <th>Sesudah</th>
                <th>Waktu</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($models as $log)
                <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $log->causer->nama_user ?? 'Sistem' }} - {{ $log->causer->role ?? 'System' }}</td>
                    <td>{{ $log->event }}</td>
                    <td>
                        @if(isset($log->changes['old']))
                            <ul>
                                @foreach($log->changes['old'] as $key => $value)
                                    <li>{{ $key }}: {{ $value }}</li>
                                @endforeach
                            </ul>
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @if(isset($log->changes['attributes']))
                            <ul>
                                @foreach($log->changes['attributes'] as $key => $value)
                                    <li>{{ $key }}: {{ $value }}</li>
                                @endforeach
                            </ul>
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $log->created_at->format('d/m/Y H:i:s') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
    {{ $models->links() }}
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</div>
@endsection