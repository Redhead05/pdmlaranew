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
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-4">
                    <div>
                        <h4 class="mb-1">Ticket Support</h4>
                        <p class="text-muted mb-0 fs-14">Laporkan kendala Anda. Admin akan menangani tiket yang Anda buat.</p>
                    </div>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createTicketModal">
                        <i class="material-symbols-outlined align-middle" style="font-size:18px">add</i> Buat Tiket
                    </button>
                </div>

                @if($tickets->isEmpty())
                    <div class="alert alert-info mb-0">Belum ada tiket.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead>
                                <tr><th>No</th><th>Judul</th><th>Status</th><th>Admin</th><th>Dibuat</th><th>Aksi</th></tr>
                            </thead>
                            <tbody>
                            @foreach($tickets as $i => $t)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td class="fw-semibold">{{ $t->judul }}</td>
                                    <td>
                                        @if($t->status === 'open') <span class="badge bg-warning text-dark">Open</span>
                                        @elseif($t->status === 'in_progress') <span class="badge bg-primary">Diproses</span>
                                        @else <span class="badge bg-success">Selesai</span> @endif
                                    </td>
                                    <td>{{ $t->admin?->name ?? '-' }}</td>
                                    <td>{{ $t->created_at->translatedFormat('d M Y H:i') }}</td>
                                    <td>
                                        <a href="{{ route('asesor.ticket.show', $t) }}" class="btn btn-sm btn-outline-primary">
                                            <span class="material-symbols-outlined align-middle" style="font-size:16px">visibility</span> Detail
                                        </a>
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

{{-- Modal buat tiket --}}
<div class="modal fade" id="createTicketModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('asesor.ticket.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Buat Ticket Support</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fs-14">Judul</label>
                        <input type="text" name="judul" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fs-14">Deskripsi</label>
                        <textarea name="deskripsi" class="form-control" rows="4" required></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fs-14">Lampiran Gambar (opsional, maks 2 MB)</label>
                        <input type="file" name="lampiran" accept="image/*" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary">Kirim</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
