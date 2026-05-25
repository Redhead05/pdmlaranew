@extends('app.layout')
@section('title', 'Hasil Generate — ' . $tahap->tahap)

@php use Illuminate\Support\Facades\Storage; @endphp

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        [data-theme="dark"] .card,
        [data-theme="dark"] .table,
        [data-theme="dark"] .modal-content { background-color: #1e2535 !important; color: #e0e6f0 !important; }
        [data-theme="dark"] .text-muted { color: #9aaccc !important; }
        [data-theme="dark"] .badge.bg-light { background-color: #2d3748 !important; color: #e0e6f0 !important; }
        .status-badge { font-size: .8rem; }
    </style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="main-content d-flex flex-column">

        {{-- Page Header --}}
        <div class="card bg-white border-0 rounded-3 mb-4">
            <div class="card-body p-4">

                {{-- Breadcrumb --}}
                <nav aria-label="breadcrumb" class="mb-3">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.tahap.index') }}">Visitasi</a></li>
                        <li class="breadcrumb-item active">Hasil Generate — {{ $tahap->tahap }}</li>
                    </ol>
                </nav>

                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1">Hasil Generate Pairing</h4>
                        <p class="text-muted mb-0 fs-14">
                            {{ $tahap->tahap }} &nbsp;|&nbsp; SK: {{ $tahap->surat_keputusan ?? '-' }}
                        </p>
                    </div>

                    {{-- Tombol Generate Baru --}}
                    @php
                        $hasLembaga   = $tahap->lembagas()->exists();
                        $hasFinalTeams = \App\Models\Team::where('tahap_id', $tahap->id)->whereNotNull('finalized_at')->exists();
                    @endphp
                    @if($hasLembaga && $hasFinalTeams)
                    <form method="POST" action="{{ route('admin.tahap.generate', ['tahap' => $tahap->slug]) }}"
                          onsubmit="return confirm('Jalankan generate pairing baru untuk tahap ini?');">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            <i class="material-symbols-outlined align-middle" style="font-size:18px">bolt</i>
                            Generate Baru
                        </button>
                    </form>
                    @endif
                </div>

                {{-- Flash messages --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
                        <i class="material-symbols-outlined align-middle me-1" style="font-size:18px">check_circle</i>
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
                        <i class="material-symbols-outlined align-middle me-1" style="font-size:18px">error</i>
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

            </div>
        </div>

        {{-- Runs List --}}
        @forelse($runs as $run)
        <div class="card border-0 rounded-3 mb-4 shadow-sm">
            <div class="card-header d-flex justify-content-between align-items-center py-3">
                <div class="d-flex align-items-center gap-3">
                    <span class="fw-semibold">Run #{{ $run->id }}</span>
                    @php
                        $badgeClass = match($run->status) {
                            'done'    => 'success',
                            'running' => 'warning',
                            default   => 'secondary',
                        };
                        $hasExcel        = Storage::exists("team_generations/{$run->id}/result.xlsx");
                        $hasEditedExcel  = Storage::exists("team_generations/{$run->id}/result_edited.xlsx");
                    @endphp
                    <span class="badge bg-{{ $badgeClass }} status-badge text-uppercase">{{ $run->status }}</span>
                    @if($hasEditedExcel)
                        <span class="badge bg-info text-white status-badge">Ada Revisi</span>
                    @endif
                </div>
                <div class="text-muted fs-13">
                    <i class="material-symbols-outlined align-middle" style="font-size:16px">schedule</i>
                    {{ $run->finalized_at ? $run->finalized_at->format('d-m-Y H:i') : $run->created_at->format('d-m-Y H:i') }}
                    &nbsp;|&nbsp;
                    <i class="material-symbols-outlined align-middle" style="font-size:16px">person</i>
                    {{ $run->generatedBy->name ?? '-' }}
                </div>
            </div>

            <div class="card-body p-4">
                <div class="row g-3">

                    {{-- Download Card --}}
                    <div class="col-md-6">
                        <div class="border rounded-3 p-3 h-100">
                            <h6 class="fw-semibold mb-3">
                                <i class="material-symbols-outlined align-middle me-1" style="font-size:18px">download</i>
                                Download Excel
                            </h6>
                            @if($hasExcel || $hasEditedExcel)
                                <a href="{{ route('admin.tahap.generation.download', ['tahap' => $tahap->slug, 'run' => $run->id]) }}"
                                   class="btn btn-outline-primary btn-sm">
                                    <i class="material-symbols-outlined align-middle" style="font-size:16px">download</i>
                                    {{ $hasEditedExcel ? 'Download Excel (Revisi)' : 'Download Excel' }}
                                </a>
                                <p class="text-muted mt-2 mb-0 fs-13">
                                    File berisi 2 sheet: <strong>Pasangan</strong> &amp; <strong>Tidak Berpasangan</strong>.
                                    Edit sesuai kebutuhan lalu upload kembali.
                                </p>
                            @else
                                <p class="text-muted mb-0 fs-13">File belum tersedia.</p>
                            @endif
                        </div>
                    </div>

                    {{-- Upload Card --}}
                    <div class="col-md-6">
                        <div class="border rounded-3 p-3 h-100">
                            <h6 class="fw-semibold mb-3">
                                <i class="material-symbols-outlined align-middle me-1" style="font-size:18px">upload</i>
                                Upload Revisi Excel
                            </h6>
                            <form action="{{ route('admin.tahap.generation.upload', ['tahap' => $tahap->slug, 'run' => $run->id]) }}"
                                  method="POST" enctype="multipart/form-data">
                                @csrf
                                <div class="d-flex gap-2 align-items-center">
                                    <input type="file" name="excel" accept=".xlsx,.xls"
                                           class="form-control form-control-sm @error('excel') is-invalid @enderror"
                                           required>
                                    <button type="submit" class="btn btn-primary btn-sm text-nowrap">
                                        Upload
                                    </button>
                                </div>
                                @error('excel')
                                    <div class="text-danger fs-13 mt-1">{{ $message }}</div>
                                @enderror
                                <p class="text-muted mt-2 mb-0 fs-13">Format: .xlsx / .xls</p>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Delete run --}}
                <div class="mt-3 d-flex justify-content-end">
                    <form action="{{ route('admin.tahap.generation.destroy', ['tahap' => $tahap->slug, 'run' => $run->id]) }}"
                          method="POST"
                          onsubmit="return confirm('Hapus riwayat generate #{{ $run->id }}? File akan dihapus permanen.');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger btn-sm">
                            <i class="material-symbols-outlined align-middle" style="font-size:16px">delete</i>
                            Hapus Run Ini
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @empty
        <div class="card border-0 rounded-3 mb-4">
            <div class="card-body text-center py-5">
                <i class="material-symbols-outlined text-muted" style="font-size:48px">bolt</i>
                <p class="text-muted mt-2">Belum ada riwayat generate untuk tahap ini.</p>
                @if($hasLembaga && $hasFinalTeams)
                <form method="POST" action="{{ route('admin.tahap.generate', ['tahap' => $tahap->slug]) }}">
                    @csrf
                    <button type="submit" class="btn btn-success">
                        <i class="material-symbols-outlined align-middle" style="font-size:18px">bolt</i>
                        Generate Sekarang
                    </button>
                </form>
                @else
                    <p class="text-muted fs-13">Pastikan lembaga dan tim asesor sudah ditambahkan dan difinalisasi.</p>
                @endif
            </div>
        </div>
        @endforelse

    </div>
</div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Auto-dismiss alerts after 5s
        document.querySelectorAll('.alert').forEach(el => {
            setTimeout(() => {
                const bsAlert = bootstrap.Alert.getOrCreateInstance(el);
                bsAlert.close();
            }, 5000);
        });
    </script>
@endpush


