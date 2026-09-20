@extends('app.layout')
@section('title', 'Detail Tiket')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="container-fluid">
    <div class="main-content d-flex flex-column">
        <div class="card bg-white border-0 rounded-3 mb-4">
            <div class="card-body p-4">
                <nav aria-label="breadcrumb" class="mb-3">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('asesor.ticket.index') }}">Ticket Support</a></li>
                        <li class="breadcrumb-item active">{{ $ticket->judul }}</li>
                    </ol>
                </nav>

                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                    <div>
                        <h4 class="mb-1">{{ $ticket->judul }}</h4>
                        <p class="text-muted mb-0 fs-14">
                            Dibuat {{ $ticket->created_at->translatedFormat('d M Y H:i') }}
                            &middot;
                            @if($ticket->status === 'open') <span class="badge bg-warning text-dark">Open</span>
                            @elseif($ticket->status === 'in_progress') <span class="badge bg-primary">Diproses</span>
                            @else <span class="badge bg-success">Selesai</span> @endif
                        </p>
                        @if($ticket->admin)
                            <p class="text-muted mb-0 fs-14">Penanggung jawab: {{ $ticket->admin->name }}</p>
                        @endif
                    </div>
                    <div class="d-flex gap-2">
                        <a href="{{ route('asesor.ticket.index') }}" class="btn btn-outline-secondary">Kembali</a>
                        @if($ticket->status !== 'closed')
                            <form action="{{ route('asesor.ticket.close', $ticket) }}" method="POST" class="js-confirm-form"
                                  data-title="Tutup Tiket" data-message="Tutup tiket ini karena sudah selesai/teratasi?">
                                @csrf
                                <button type="submit" class="btn btn-success">Tutup Tiket</button>
                            </form>
                        @endif
                    </div>
                </div>

                <hr>

                <div class="mb-3">
                    <div class="text-muted fs-14">Deskripsi</div>
                    <p class="mb-0">{{ $ticket->deskripsi }}</p>
                </div>
                @if($ticket->lampiran)
                    <div class="mb-3">
                        <div class="text-muted fs-14 mb-2">Lampiran</div>
                        <a href="{{ asset('storage/'.$ticket->lampiran) }}" target="_blank">
                            <img src="{{ asset('storage/'.$ticket->lampiran) }}" class="img-fluid rounded border" style="max-height:260px">
                        </a>
                    </div>
                @endif

                <h6 class="mt-4 mb-3">Riwayat Percakapan</h6>
                @forelse($ticket->messages as $m)
                    <div class="border rounded-3 p-3 mb-2 {{ $m->user_id === auth()->id() ? 'bg-light' : '' }}">
                        <div class="d-flex justify-content-between">
                            <span class="fw-semibold">{{ $m->user->name }}</span>
                            <span class="fs-12 text-muted">{{ $m->created_at->translatedFormat('d M Y H:i') }}</span>
                        </div>
                        <p class="mb-0 mt-1">{{ $m->pesan }}</p>
                    </div>
                @empty
                    <div class="text-muted fs-14">Belum ada balasan.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@include('partial.confirm-modal')
@endsection
