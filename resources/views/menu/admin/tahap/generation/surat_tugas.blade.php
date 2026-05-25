@extends('app.layout')
@section('title', 'Surat Tugas — ' . $tahap->tahap)

@push('styles')
<style>
    .surat-wrap {
        max-width: 980px;
        margin: 0 auto;
    }
    .surat-card {
        border: 1px solid #d9e2f2;
        border-radius: 18px;
        background: #fff;
        box-shadow: 0 12px 32px rgba(15, 23, 42, .06);
    }
    .surat-kop {
        border-bottom: 3px solid #1f3c88;
        padding-bottom: 20px;
        margin-bottom: 28px;
    }
    .surat-logo {
        width: 72px;
        height: 72px;
        border-radius: 50%;
        object-fit: cover;
        border: 1px solid #dbe3f1;
        padding: 6px;
        background: #fff;
    }
    .surat-title {
        letter-spacing: .16em;
        font-size: 1.05rem;
    }
    .surat-number {
        color: #475569;
        font-size: .95rem;
    }
    .surat-body-text {
        text-align: justify;
        line-height: 1.9;
        color: #334155;
    }
    .surat-table thead th {
        background: #eef4ff;
        color: #1e293b;
        font-weight: 700;
        text-transform: uppercase;
        font-size: .85rem;
        letter-spacing: .04em;
    }
    .surat-table td,
    .surat-table th {
        padding: .9rem .8rem;
        vertical-align: middle;
    }
    .surat-footer-note {
        border-left: 4px solid #1f3c88;
        background: #f8fbff;
        padding: 16px 18px;
        border-radius: 12px;
        color: #475569;
    }
    @media print {
        body {
            background: #fff !important;
        }
        .header-area,
        .theme-settings-btn,
        .main-sidebar,
        .btn,
        .offcanvas,
        .footer-area {
            display: none !important;
        }
        .main-content-container,
        .container-fluid,
        .main-content,
        .surat-wrap {
            margin: 0 !important;
            padding: 0 !important;
            max-width: 100% !important;
        }
        .surat-card {
            box-shadow: none !important;
            border: none !important;
            border-radius: 0 !important;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="main-content d-flex flex-column">
        <div class="surat-wrap">
        <div class="card bg-white border-0 rounded-3 mb-4 surat-card">
            <div class="card-body p-4 p-md-5">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3 mb-4 no-print">
                    <div>
                        <h4 class="mb-1 text-uppercase">Surat Tugas Resmi Asesor</h4>
                        <p class="text-muted mb-0">Tahap: {{ $tahap->tahap }} | Run #{{ $run->id }}</p>
                    </div>
                    <div class="text-end">
                        <div class="small text-muted">Tanggal Cetak</div>
                        <div class="fw-semibold">{{ now()->translatedFormat('d F Y') }}</div>
                    </div>
                </div>

                <div class="border rounded-4 p-4 p-md-5 mb-4 bg-white">
                    <div class="surat-kop d-flex align-items-center gap-3 flex-wrap">
                        <img src="{{ asset('assets/logotab.png') }}" alt="Logo" class="surat-logo">
                        <div class="flex-grow-1">
                            <div class="fw-bold text-uppercase" style="font-size:1.1rem; color:#0f172a;">Panitia / Administrator Visitasi</div>
                            <div class="text-muted">Sistem Penugasan Asesor Tahap {{ $tahap->tahap }}</div>
                            <div class="surat-number mt-2">Nomor: ST/{{ str_pad((string) $run->id, 3, '0', STR_PAD_LEFT) }}/{{ $tahap->id }}/{{ now()->format('Y') }}</div>
                        </div>
                        <div class="text-md-end small text-muted">
                            <div>Tanggal Surat</div>
                            <div class="fw-semibold text-dark">{{ now()->translatedFormat('d F Y') }}</div>
                        </div>
                    </div>

                    <div class="text-center mb-4">
                        <div class="fw-bold surat-title">SURAT TUGAS</div>
                        <div class="text-muted mt-2">Penugasan asesor berdasarkan hasil finalisasi pairing untuk {{ $tahap->tahap }}</div>
                    </div>

                    <p class="mb-3 surat-body-text">
                        Berdasarkan hasil finalisasi pairing asesor dan lembaga pada tahap <strong>{{ $tahap->tahap }}</strong>,
                        dengan ini ditetapkan penugasan resmi asesor sebagaimana daftar berikut untuk menjadi dasar pelaksanaan tugas lapangan.
                        @if($isPersonal && $recipientName)
                            Surat tugas ini ditampilkan untuk <strong>{{ $recipientName }}</strong>.
                        @endif
                    </p>

                    <div class="table-responsive">
                        <table class="table table-bordered align-middle surat-table">
                            <thead>
                                <tr>
                                    <th style="width: 80px;">No</th>
                                    <th>Kode Tim</th>
                                    <th>Nama A</th>
                                    <th>Nama B</th>
                                    <th>Nomer Telpon</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rows as $index => $row)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <div class="fw-semibold">{{ $row['team_code'] }}</div>
                                            <div class="small text-muted">{{ $row['lembaga_name'] }} ({{ $row['npsn'] }})</div>
                                        </td>
                                        <td>{{ $row['name_a'] }}</td>
                                        <td>{{ $row['name_b'] }}</td>
                                        <td>{{ $row['phone'] }}</td>
                                    </tr>
                                    @if(!empty($row['notes']))
                                        <tr>
                                            <td></td>
                                            <td colspan="4"><span class="fw-semibold">Catatan:</span> {{ $row['notes'] }}</td>
                                        </tr>
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted">Tidak ada data surat tugas.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="row mt-5 pt-3">
                        <div class="col-md-7">
                            <div class="surat-footer-note">
                                Surat tugas ini dibuat secara sistem, mengacu pada hasil finalisasi pairing dan berlaku sebagai dasar penugasan asesor pada tahap tersebut.
                            </div>
                        </div>
                        <div class="col-md-5 text-md-center mt-4 mt-md-0">
                            <div>{{ now()->translatedFormat('d F Y') }}</div>
                            <div class="mb-5">Administrator / Penanggung Jawab</div>
                            <div class="fw-semibold text-uppercase">{{ auth()->user()->name ?? 'Admin' }}</div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between flex-wrap gap-2 no-print">
                    <a href="{{ auth()->user()->hasRole('admin') ? route('admin.tahap.generation.index', ['tahap' => $tahap->slug]) : route('asesor.dashboard') }}" class="btn btn-outline-secondary">
                        Kembali
                    </a>
                    <button type="button" class="btn btn-primary" onclick="window.print()">Cetak Surat Tugas</button>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>
@endsection

