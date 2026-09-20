@extends('app.layout')
@section('title', 'Surat Tugas — ' . $tahap->tahap)

@push('styles')
<style>
    .surat-wrap {
        max-width: 1080px;
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
    .surat-table {
        width: 100%;
    }
    .surat-table thead th {
        background: #eef4ff;
        color: #1e293b;
        font-weight: 700;
        text-transform: uppercase;
        font-size: .78rem;
        letter-spacing: .02em;
        white-space: normal;
    }
    .surat-table td,
    .surat-table th {
        padding: .5rem .45rem;
        vertical-align: middle;
        font-size: .82rem;
        word-wrap: break-word;
    }
    .surat-footer-note {
        border-left: 4px solid #1f3c88;
        background: #f8fbff;
        padding: 16px 18px;
        border-radius: 12px;
        color: #475569;
    }
    @page {
        size: A4;
        margin: 12mm 10mm;
    }
    @media print {
        html, body {
            width: 100%;
            margin: 0 !important;
            padding: 0 !important;
            background: #fff !important;
        }
        .header-area,
        .theme-settings-btn,
        .main-sidebar,
        .sidebar-area,
        .btn,
        .offcanvas,
        .footer-area,
        .no-print {
            display: none !important;
        }
        .main-content-container,
        .container-fluid,
        .main-content,
        .surat-wrap {
            margin: 0 !important;
            padding: 0 !important;
            max-width: 100% !important;
            width: 100% !important;
        }
        .surat-card {
            box-shadow: none !important;
            border: none !important;
            border-radius: 0 !important;
        }
        .table-responsive {
            overflow: visible !important;
        }
        .surat-table {
            width: 100% !important;
            table-layout: fixed !important;
        }
        .surat-table th,
        .surat-table td {
            word-wrap: break-word;
            white-space: normal;
            font-size: .72rem;
            padding: .4rem .3rem;
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
                        <p class="text-muted mb-0">
                            Tahap: {{ $tahap->tahap }} &middot; SK: {{ $tahap->surat_keputusan }}
                            @if($isPersonal) &middot; Khusus: {{ $recipientName }} @endif
                        </p>
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
                            <div class="surat-number mt-2">Nomor: <strong>{{ $nomorSt }}</strong></div>
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
                                    <th style="width:46px;">No</th>
                                    <th>NPSN</th>
                                    <th>Nama Lembaga</th>
                                    <th>NIA A</th>
                                    <th>Nama A</th>
                                    <th>NIA B</th>
                                    <th>Nama B</th>
                                    <th>Kab/Kota Lembaga</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($rows as $index => $row)
                                    <tr>
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td class="text-nowrap">{{ $row['npsn'] ?? '-' }}</td>
                                        <td>{{ $row['nama_lembaga'] ?? '-' }}</td>
                                        <td class="text-nowrap">{{ $row['nia_a'] ?? '-' }}</td>
                                        <td>{{ $row['nama_a'] ?? '-' }}</td>
                                        <td class="text-nowrap">{{ $row['nia_b'] ?? '-' }}</td>
                                        <td>{{ $row['nama_b'] ?? '-' }}</td>
                                        <td>{{ $row['kabupaten'] ?? '-' }}</td>
                                    </tr>
                                    @if(!empty($row['extra']))
                                        @foreach($row['extra'] as $extra)
                                            <tr>
                                                <td></td>
                                                <td colspan="7" class="text-muted small">
                                                    Anggota tambahan: {{ $extra['nama'] }} (NIA {{ $extra['nia'] }})
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center text-muted">Tidak ada data surat tugas untuk Anda pada tahap ini.</td>
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
                            <div class="mb-2">Administrator / Penanggung Jawab</div>
                            <div class="d-flex justify-content-center mb-2">
                                <div id="surat-qr" style="width:112px;height:112px;"></div>
                            </div>
                            <div class="small text-muted mb-1">Ditandatangani secara digital</div>
                            <div class="fw-semibold text-uppercase">{{ $adminName }}</div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-between flex-wrap gap-2 no-print">
                    <a href="{{ auth()->user()->hasRole('admin') ? route('admin.tahap.generation.index', ['tahap' => $tahap->slug]) : route('asesor.visitasi.index') }}" class="btn btn-outline-secondary">
                        Kembali
                    </a>
                    <button type="button" class="btn btn-primary" onclick="window.print()">
                        <span class="material-symbols-outlined align-middle" style="font-size:18px">print</span>
                        Cetak / Unduh PDF
                    </button>
                </div>
            </div>
        </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    (function () {
        const payload = [
            'DIGITAL SIGNATURE',
            'Nomor: ' + @json($nomorSt),
            'Tahap: ' + @json($tahap->tahap),
            'SK: ' + @json($tahap->surat_keputusan),
            'Admin: ' + @json($adminName),
            'Tanggal: ' + @json(now()->translatedFormat('d F Y')),
        ].join(' | ');

        const el = document.getElementById('surat-qr');
        if (el && typeof QRCode === 'function') {
            new QRCode(el, { text: payload, width: 112, height: 112, correctLevel: QRCode.CorrectLevel.M });
        }
    })();
</script>
<script>
    @if(request()->has('print'))
        window.addEventListener('load', function () {
            window.setTimeout(function () { window.print(); }, 400);
        });
    @endif
</script>
@endpush
