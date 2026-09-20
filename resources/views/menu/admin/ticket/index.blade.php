@extends('app.layout')
@section('title', 'Ticket Support')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="container-fluid">
    <div class="main-content d-flex flex-column">
        <div class="card bg-white border-0 rounded-3 mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
                    <div>
                        <h4 class="mb-1">Ticket Support</h4>
                        <p class="text-muted mb-0 fs-14">Buka tiket untuk menjadi penanggung jawab, lalu selesaikan kasus.</p>
                    </div>
                </div>

                <ul class="nav nav-tabs mb-3">
                    <li class="nav-item"><a class="nav-link {{ !$status ? 'active' : '' }}" href="{{ route('admin.ticket.index') }}">Semua</a></li>
                    <li class="nav-item"><a class="nav-link {{ $status === 'open' ? 'active' : '' }}" href="{{ route('admin.ticket.index', ['status' => 'open']) }}">Open</a></li>
                    <li class="nav-item"><a class="nav-link {{ $status === 'in_progress' ? 'active' : '' }}" href="{{ route('admin.ticket.index', ['status' => 'in_progress']) }}">Diproses</a></li>
                    <li class="nav-item"><a class="nav-link {{ $status === 'closed' ? 'active' : '' }}" href="{{ route('admin.ticket.index', ['status' => 'closed']) }}">Selesai</a></li>
                </ul>

                @if($tickets->isEmpty())
                    <div class="alert alert-info mb-0">Belum ada tiket.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead>
                                <tr><th>No</th><th>Judul</th><th>Asesor</th><th>Status</th><th>Penanggung Jawab</th><th>Dibuat</th><th>Aksi</th></tr>
                            </thead>
                            <tbody>
                            @foreach($tickets as $i => $t)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td class="fw-semibold">{{ $t->judul }}</td>
                                    <td>{{ $t->asesor?->name ?? '-' }}</td>
                                    <td>
                                        @if($t->status === 'open') <span class="badge bg-warning text-dark">Open</span>
                                        @elseif($t->status === 'in_progress') <span class="badge bg-primary">Diproses</span>
                                        @else <span class="badge bg-success">Selesai</span> @endif
                                    </td>
                                    <td>{{ $t->admin?->name ?? '-' }}</td>
                                    <td>{{ $t->created_at->translatedFormat('d M Y H:i') }}</td>
                                    <td>
                                        <div class="d-flex gap-1 flex-wrap">
                                            <a href="{{ route('admin.ticket.show', $t) }}" class="btn btn-sm btn-outline-primary">Detail</a>
                                            @if($t->status === 'open')
                                                <form action="{{ route('admin.ticket.open', $t) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="btn btn-sm btn-primary">Ambil Tiket</button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
