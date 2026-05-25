@extends('app.layout')
@section('title', 'Notifikasi')

@section('content')
<div class="container-fluid">
    <div class="main-content d-flex flex-column">
        <div class="card bg-white border-0 rounded-3 mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
                    <div>
                        <h4 class="mb-1">Notifikasi</h4>
                        <p class="text-muted mb-0">Daftar notifikasi surat tugas dan pemberitahuan sistem.</p>
                    </div>
                    <span class="badge bg-primary">{{ $notifications->total() }} item</span>
                </div>

                <div class="list-group list-group-flush">
                    @forelse($notifications as $notification)
                        <div class="list-group-item px-0 py-3 border-bottom">
                            <div class="d-flex justify-content-between align-items-start gap-3 flex-wrap">
                                <div>
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <span class="fw-semibold">{{ $notification->data['title'] ?? 'Notifikasi' }}</span>
                                        @if(is_null($notification->read_at))
                                            <span class="badge bg-danger">Baru</span>
                                        @endif
                                    </div>
                                    <div class="text-muted mb-2">{{ $notification->data['message'] ?? '-' }}</div>
                                    <div class="small text-secondary">{{ $notification->created_at->diffForHumans() }}</div>
                                </div>
                                <div class="d-flex gap-2">
                                    <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-primary">Buka</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center text-muted py-5">Belum ada notifikasi.</div>
                    @endforelse
                </div>

                <div class="mt-4">{{ $notifications->links() }}</div>
            </div>
        </div>
    </div>
</div>
@endsection
