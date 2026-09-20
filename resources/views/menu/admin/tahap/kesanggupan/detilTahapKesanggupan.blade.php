@extends('app.layout')
@section('title', 'Kesanggupan & Pasangan Asesor — ' . $tahap->tahap)

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @include('partial.table-ux')
    <style>
        .ksg-stat { min-width: 132px; }
        .ksg-stat .ksg-value { font-size: 1.05rem; font-weight: 600; line-height: 1.1; }
        .ksg-stat .ksg-label { font-size: .75rem; opacity: .75; }
        .ksg-actions .btn { white-space: nowrap; }
        #pairs-table_wrapper .btn-group-sm > .btn,
        #pairs-table_wrapper .btn-sm { padding: .12rem .4rem; }
        .nav-tabs .nav-link { font-size: .875rem; }
    </style>
@endpush

@section('content')
@php
    $uploadUrl = route('admin.tahap.pairing.upload', ['tahap' => $tahap->slug]);
    $downloadUrl = route('admin.tahap.pairing.download', ['tahap' => $tahap->slug]);
@endphp
<div class="container-fluid">
    <div class="main-content d-flex flex-column">

        {{-- ============================ HEADER ============================ --}}
        <div class="card bg-white border-0 rounded-3 mb-4">
            <div class="card-body p-4">
                <nav aria-label="breadcrumb" class="mb-3">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.tahap.index') }}">Visitasi</a></li>
                        <li class="breadcrumb-item active">Kesanggupan &amp; Pasangan Asesor — {{ $tahap->tahap }}</li>
                    </ol>
                </nav>

                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                    <div>
                        <h4 class="mb-1">Kesanggupan &amp; Pasangan Asesor</h4>
                        <p class="text-muted mb-0 fs-14">
                            {{ $tahap->tahap }} &middot; SK: {{ $tahap->surat_keputusan }}
                        </p>
                        <div class="d-flex flex-wrap gap-2 mt-2">
                            <span class="badge bg-light text-dark border">Periode: {{ optional($tahap->start_date)->translatedFormat('d M Y') ?? '-' }} — {{ optional($tahap->end_date)->translatedFormat('d M Y') ?? '-' }}</span>
                            <span class="badge bg-{{ $mode === 'final' ? 'success' : ($mode === 'draft' ? 'warning text-dark' : 'secondary') }}">Pasangan: {{ $modeLabel }}</span>
                            @if($locked)
                                <span class="badge bg-danger">
                                    <i class="material-symbols-outlined align-middle" style="font-size:16px">lock</i>
                                    Data terkunci — {{ optional($tahap->pairing_locked_at)->translatedFormat('d M Y H:i') }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <div class="d-flex flex-wrap gap-2 ksg-actions">
                        <a href="{{ route('admin.tahap.generation.index', ['tahap' => $tahap->slug]) }}" class="btn btn-outline-secondary">
                            <i class="material-symbols-outlined align-middle" style="font-size:18px">hub</i>
                            Pairing Lembaga
                        </a>

                        @if($locked)
                            <form method="POST" action="{{ route('admin.tahap.generation.unlock', ['tahap' => $tahap->slug]) }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger">
                                    <i class="material-symbols-outlined align-middle" style="font-size:18px">lock_open</i>
                                    Buka Kunci
                                </button>
                            </form>
                        @else
                            <form method="POST" action="{{ route('admin.tahap.pairing.generate', ['tahap' => $tahap->slug]) }}"
                                  data-turbo-action="replace"
                                  onsubmit="return konfirmasiGenerate();">
                                @csrf
                                <input type="hidden" name="reset_pairing" value="1">
                                <button type="submit" class="btn btn-success" {{ $canGenerate ? '' : 'disabled' }}
                                        title="{{ $canGenerate ? 'Pasangkan asesor otomatis sesuai kriteria kesanggupan, gender, dan kab/kota' : 'Generate tersedia setelah masa tahap berakhir' }}">
                                    <i class="material-symbols-outlined align-middle" style="font-size:18px">bolt</i>
                                    Generate Pasangan Asesor
                                </button>
                            </form>
                            <form method="POST" action="{{ route('admin.tahap.generation.lock', ['tahap' => $tahap->slug]) }}"
                                  onsubmit="return confirm('Kunci data pasangan? Setelah dikunci, data tidak bisa diubah sampai dibuka kembali.');">
                                @csrf
                                <button type="submit" class="btn btn-outline-warning">
                                    <i class="material-symbols-outlined align-middle" style="font-size:18px">lock</i>
                                    Kunci Data
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                @unless($canGenerate)
                    <div class="alert alert-light border mt-3 mb-0 py-2 fs-13">
                        <i class="material-symbols-outlined align-middle" style="font-size:16px">info</i>
                        Generate pasangan asesor hanya dapat dijalankan setelah masa tahap berakhir
                        (end date: {{ optional($tahap->end_date)->translatedFormat('d M Y H:i') ?? 'belum ditentukan' }}).
                    </div>
                @endunless

                {{-- Statistik ringkas --}}
                <div class="d-flex flex-wrap gap-2 mt-3">
                    <div class="ksg-stat badge bg-light text-dark border text-start px-3 py-2">
                        <div class="ksg-value">{{ number_format($stats['bersedia']) }}</div>
                        <div class="ksg-label">Bersedia</div>
                    </div>
                    <div class="ksg-stat badge bg-light text-dark border text-start px-3 py-2">
                        <div class="ksg-value">{{ number_format($stats['tidak_bisa']) }}</div>
                        <div class="ksg-label">Tidak Bisa</div>
                    </div>
                    <div class="ksg-stat badge bg-light text-dark border text-start px-3 py-2">
                        <div class="ksg-value">{{ number_format($stats['belum_mengisi']) }}</div>
                        <div class="ksg-label">Belum Mengisi</div>
                    </div>
                    <div class="ksg-stat badge bg-light text-dark border text-start px-3 py-2">
                        <div class="ksg-value">{{ number_format($stats['tim']) }}</div>
                        <div class="ksg-label">Tim / Pasangan</div>
                    </div>
                    <div class="ksg-stat badge {{ $stats['belum_terpasang'] > 0 ? 'bg-warning text-dark' : 'bg-light text-dark border' }} text-start px-3 py-2">
                        <div class="ksg-value">{{ number_format($stats['belum_terpasang']) }}</div>
                        <div class="ksg-label">Asesor Bersedia Belum Terpasang</div>
                    </div>
                    <div class="ksg-stat badge bg-light text-dark border text-start px-3 py-2">
                        <div class="ksg-value">{{ number_format($stats['lembaga_terpasang']) }} / {{ number_format($stats['lembaga_tahap']) }}</div>
                        <div class="ksg-label">Lembaga Terpasang</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ====================== PASANGAN ASESOR ====================== --}}
        <div class="card bg-white border-0 rounded-3 mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-3">
                    <div>
                        <h5 class="fw-semibold mb-1">Pasangan Asesor (Tim)</h5>
                        <p class="text-muted fs-13 mb-0">
                            Hasil pembagian tim asesor untuk tahap ini. Ubah pasangan secara manual
                            (@if(!$locked) klik ikon <span class="material-symbols-outlined align-middle" style="font-size:14px">edit</span> di samping NIA @else data terkunci @endif)
                            atau melalui unduh &rarr; edit &rarr; upload Excel.
                        </p>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ $downloadUrl }}" class="btn btn-sm btn-outline-success">
                            <i class="material-symbols-outlined align-middle" style="font-size:16px">download</i>
                            Download Excel
                        </a>
                    </div>
                </div>

                @unless($locked)
                    <div class="card border mb-3">
                        <div class="card-body py-3">
                            <div class="row g-2 align-items-center">
                                <div class="col-lg-5">
                                    <strong class="fs-14">Upload Pasangan Asesor (Excel / CSV)</strong>
                                    <div class="text-muted fs-13">
                                        Unduh <em>Download Excel</em>, ubah kolom <code>NIA Asesor A</code> / <code>NIA Asesor B</code>
                                        (kosongkan bila slot ingin dikosongkan), lalu upload kembali file yang sama.
                                    </div>
                                </div>
                                <div class="col-lg-7">
                                    <form method="POST" action="{{ $uploadUrl }}" enctype="multipart/form-data"
                                          class="d-flex gap-2 flex-wrap"
                                          data-turbo-action="replace"
                                          onsubmit="return confirm('Terapkan pasangan asesor dari file ini? Pasangan pada file akan menggantikan data saat ini.');">
                                        @csrf
                                        <input type="file" name="file" accept=".csv,.txt,.xlsx" class="form-control form-control-sm" style="max-width:340px" required>
                                        <button type="submit" class="btn btn-sm btn-primary text-nowrap">Upload &amp; Terapkan</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                @endunless

                @if(!$hasPairs)
                    <div class="alert alert-light border mb-0">
                        Belum ada pasangan asesor pada tahap ini.
                        @if($canGenerate && !$locked)
                            Klik <strong>Generate Pasangan Asesor</strong> untuk membentuk tim secara otomatis.
                        @endif
                    </div>
                @else
                    <div class="table-responsive">
                        <table id="pairs-table" class="display table table-sm align-middle" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="width:48px">No</th>
                                    <th>Tim</th>
                                    <th>NIA A</th>
                                    <th>Nama Asesor A</th>
                                    <th>Kab/Kota A</th>
                                    <th>Kes.</th>
                                    <th>NIA B</th>
                                    <th>Nama Asesor B</th>
                                    <th>Kab/Kota B</th>
                                    <th>Kes.</th>
                                    <th>Lembaga</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        {{-- ================= ASESOR BERSEDIA BELUM TERPASANG ================= --}}
        <div class="card bg-white border-0 rounded-3 mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                    <div>
                        <h5 class="fw-semibold mb-1">Asesor Bersedia Belum Terpasang</h5>
                        <p class="text-muted fs-13 mb-0">Asesor yang menyatakan bersedia tetapi belum masuk tim mana pun.</p>
                    </div>
                    <span class="badge bg-warning text-dark px-3 py-2">{{ number_format($stats['belum_terpasang']) }} asesor</span>
                </div>

                <div class="table-responsive">
                    <table id="unmatched-table" class="display table table-sm align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th style="width:48px">No</th>
                                <th>NIA</th>
                                <th>Nama</th>
                                <th>Email</th>
                                <th>Kab/Kota</th>
                                <th>Gender</th>
                                <th>Kesanggupan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>

        {{-- ==================== DATA KESANGGUPAN ASESOR ==================== --}}
        <div class="card bg-white border-0 rounded-3 mb-4">
            <div class="card-body p-4">
                <h5 class="fw-semibold mb-1">Data Kesanggupan Asesor</h5>
                <p class="text-muted fs-13 mb-3">Rekap isian form kesanggupan pada tahap ini.</p>

                <ul class="nav nav-tabs" id="kesanggupanTabs" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="tab-bisa" data-bs-toggle="tab" data-bs-target="#pane-bisa"
                                type="button" role="tab" aria-controls="pane-bisa" aria-selected="true">
                            Bersedia ({{ number_format($stats['bersedia']) }})
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab-tidak" data-bs-toggle="tab" data-bs-target="#pane-tidak"
                                type="button" role="tab" aria-controls="pane-tidak" aria-selected="false">
                            Tidak Bisa ({{ number_format($stats['tidak_bisa']) }})
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="tab-belum" data-bs-toggle="tab" data-bs-target="#pane-belum"
                                type="button" role="tab" aria-controls="pane-belum" aria-selected="false">
                            Belum Mengisi ({{ number_format($stats['belum_mengisi']) }})
                        </button>
                    </li>
                </ul>

                <div class="tab-content pt-3" id="kesanggupanTabsContent">
                    <div class="tab-pane fade show active" id="pane-bisa" role="tabpanel" aria-labelledby="tab-bisa" tabindex="0">
                        <div class="table-responsive">
                            <table id="table-bisa" class="display table table-sm align-middle" style="width:100%">
                                <thead>
                                    <tr>
                                        <th style="width:48px">No</th>
                                        <th>NIA</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Kab/Kota</th>
                                        <th>Gender</th>
                                        <th>Tipe Asesor</th>
                                        <th>Kesanggupan</th>
                                        <th>Tim</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="pane-tidak" role="tabpanel" aria-labelledby="tab-tidak" tabindex="0">
                        <div class="table-responsive">
                            <table id="table-tidak" class="display table table-sm align-middle" style="width:100%">
                                <thead>
                                    <tr>
                                        <th style="width:48px">No</th>
                                        <th>NIA</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Kab/Kota</th>
                                        <th>Gender</th>
                                        <th>Tipe Asesor</th>
                                        <th>Alasan</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>

                    <div class="tab-pane fade" id="pane-belum" role="tabpanel" aria-labelledby="tab-belum" tabindex="0">
                        <div class="table-responsive">
                            <table id="table-belum" class="display table table-sm align-middle" style="width:100%">
                                <thead>
                                    <tr>
                                        <th style="width:48px">No</th>
                                        <th>NIA</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Kab/Kota</th>
                                        <th>Gender</th>
                                        <th>Tipe Asesor</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
</div>

{{-- ======================= MODAL: PILIH ASESOR ======================= --}}
<div class="modal fade" id="asesor-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="asesor-modal-title">Pilih Asesor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted fs-13 mb-2" id="asesor-modal-hint">
                    Hanya asesor yang menyatakan <strong>bersedia</strong> pada tahap ini.
                    Memilih asesor yang sudah punya tim akan menukar posisinya dengan asesor yang tergeser.
                </p>
                <div class="table-responsive">
                    <table id="asesor-table" class="display table table-sm align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th>NIA</th>
                                <th>Nama</th>
                                <th>Kab/Kota</th>
                                <th>Kes.</th>
                                <th>Tim</th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ==================== MODAL: PASANGKAN KE TIM ==================== --}}
<div class="modal fade" id="team-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pasangkan Asesor ke Tim</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted fs-13 mb-2" id="team-modal-hint"></p>
                @if(count($teamOptions) === 0)
                    <div class="alert alert-warning py-2 mb-0 fs-13">
                        Belum ada tim pada tahap ini. Jalankan <strong>Generate Pasangan Asesor</strong> terlebih dahulu.
                    </div>
                @else
                    <label class="form-label fs-14">Pilih tim tujuan</label>
                    <select class="form-select form-select-sm" id="team-modal-select">
                        <option value="">— pilih tim —</option>
                        @foreach($teamOptions as $opt)
                            <option value="{{ $opt['id'] }}">{{ $opt['code'] }} — {{ $opt['info'] }}</option>
                        @endforeach
                    </select>
                @endif
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-sm btn-primary" id="team-modal-submit" {{ count($teamOptions) === 0 ? 'disabled' : '' }}>Pasangkan</button>
            </div>
        </div>
    </div>
</div>

{{-- Toast container --}}
<div class="toast-container position-fixed top-0 end-0 p-3" id="toast-container" style="z-index:1080"></div>
@endsection

@push('scripts')
    <script>
        const LOCKED = @json($locked);
        const HAS_LEMBAGA_PAIRING = {{ (int) $stats['lembaga_terpasang'] > 0 ? 'true' : 'false' }};

        function konfirmasiGenerate() {
            if (HAS_LEMBAGA_PAIRING) {
                return confirm('Tahap ini sudah memiliki pasangan tim ↔ lembaga.\n\nGenerate ulang akan MENGHAPUS seluruh pemetaan lembaga tersebut sehingga admin harus menjalankan Auto-Match lagi.\n\nLanjutkan?');
            }

            return confirm('Bentuk ulang pasangan asesor sesuai kriteria kesanggupan, gender, dan kab/kota?');
        }

        $(function () {
            const csrf = '{{ csrf_token() }}';
            const pairsUrl = "{{ route('admin.tahap.pairing.data', ['tahap' => $tahap->slug]) }}";
            const unmatchedUrl = "{{ route('admin.tahap.pairing.unmatched', ['tahap' => $tahap->slug]) }}";
            const asesorOptionsUrl = "{{ route('admin.tahap.pairing.asesor-options', ['tahap' => $tahap->slug]) }}";
            const setSlotUrl = "{{ route('admin.tahap.pairing.set-slot', ['tahap' => $tahap->slug]) }}";
            const addMemberUrl = "{{ route('admin.tahap.pairing.add-member', ['tahap' => $tahap->slug]) }}";
            const removeMemberUrl = "{{ route('admin.tahap.pairing.remove-member', ['tahap' => $tahap->slug]) }}";
            const bisaUrl = "{{ route('admin.tahap.kesanggupan.bisa', ['tahap' => $tahap->slug]) }}";
            const tidakUrl = "{{ route('admin.tahap.kesanggupan.tidak-bisa', ['tahap' => $tahap->slug]) }}";
            const belumUrl = "{{ route('admin.tahap.kesanggupan.belum-mengisi', ['tahap' => $tahap->slug]) }}";

            // ---------------------- Helper ----------------------
            function esc(s) {
                return String(s ?? '').replace(/[&<>"']/g, c => ({
                    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
                }[c]));
            }

            function showToast(title, message, type) {
                if (!message) return;
                type = type || 'success';
                const box = document.getElementById('toast-container');
                if (!box) return;
                const el = document.createElement('div');
                el.className = 'toast align-items-center text-bg-' + type + ' border-0 show';
                el.innerHTML = '<div class="d-flex">'
                    + '<div class="toast-body"><strong>' + esc(title) + '</strong>'
                    + '<div class="fs-14">' + message + '</div></div>'
                    + '<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>'
                    + '</div>';
                box.appendChild(el);
                new bootstrap.Toast(el, { delay: 8000 }).show();
                el.addEventListener('hidden.bs.toast', () => el.remove());
            }

            const flash = {
                success: @json(session('success')),
                error: @json(session('error')),
                info: @json(session('info')),
            };
            if (flash.success) showToast('Sukses', flash.success, 'success');
            else if (flash.error) showToast('Gagal', flash.error, 'danger');
            else if (flash.info) showToast('Info', flash.info, 'info');

            function numbering(col) {
                return function (settings) {
                    this.api().column(col, { search: 'applied', order: 'applied' }).nodes()
                        .each(function (cell, i) { cell.innerHTML = settings._iDisplayStart + i + 1; });
                };
            }

            function emptyCell(data, type) {
                if (type !== 'display') return data;
                return (data === null || data === undefined || data === '') ? '<span class="text-muted">—</span>' : esc(data);
            }

            function niaEditCell(slot) {
                return function (data, type, row) {
                    if (type !== 'display') return data;
                    const text = data ? esc(data) : '<span class="text-muted">—</span>';
                    if (LOCKED) return text;
                    return '<div class="d-flex align-items-center gap-1">' + text
                        + '<button type="button" class="btn btn-link btn-sm p-0 btn-pilih-asesor"'
                        + ' data-team="' + row.team_id + '" data-slot="' + slot + '"'
                        + ' title="Ubah asesor ' + slot.toUpperCase() + '"><span class="material-symbols-outlined align-middle" style="font-size:16px">edit</span></button></div>';
                };
            }

            function ajaxPost(url, payload, onDone) {
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: Object.assign({ _token: csrf }, payload),
                    dataType: 'json',
                }).done(function (res) {
                    onDone(null, res);
                }).fail(function (xhr) {
                    const res = xhr.responseJSON || {};
                    onDone(res.message || 'Terjadi kesalahan.', null);
                });
            }

            // ------------------ DataTable: Pasangan Asesor ------------------
            let pairsTable = null;
            if ($.fn && $.fn.DataTable && $('#pairs-table').length) {
                pairsTable = $('#pairs-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: { url: pairsUrl, type: 'GET' },
                    pageLength: 10,
                    order: [[1, 'asc']],
                    columns: [
                        { data: null, render: function () { return ''; }, orderable: false, searchable: false },
                        { data: 'code', render: function (data, type, row) {
                            if (type !== 'display') return data;
                            const badge = row.anggota > 2
                                ? ' <span class="badge bg-info text-dark" title="' + esc(row.anggota_title) + '">' + row.anggota + ' asesor</span>'
                                : '';
                            return esc(data) + badge;
                        } },
                        { data: 'nia_a', render: niaEditCell('a') },
                        { data: 'nama_a', render: emptyCell },
                        { data: 'kota_a', render: emptyCell },
                        { data: 'kes_a', render: emptyCell },
                        { data: 'nia_b', render: niaEditCell('b') },
                        { data: 'nama_b', render: emptyCell },
                        { data: 'kota_b', render: emptyCell },
                        { data: 'kes_b', render: emptyCell },
                        { data: 'lembaga' },
                        { data: 'action', orderable: false, searchable: false },
                    ],
                    language: { emptyTable: 'Belum ada pasangan asesor.' },
                    drawCallback: numbering(0),
                });
            }

            // ------------------ DataTable: Belum Terpasang ------------------
            let unmatchedTable = null;
            if ($.fn && $.fn.DataTable && $('#unmatched-table').length) {
                unmatchedTable = $('#unmatched-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: { url: unmatchedUrl, type: 'GET' },
                    pageLength: 10,
                    order: [[2, 'asc']],
                    columns: [
                        { data: null, render: function () { return ''; }, orderable: false, searchable: false },
                        { data: 'nia', render: emptyCell },
                        { data: 'name' },
                        { data: 'email', render: emptyCell },
                        { data: 'kota', render: emptyCell },
                        { data: 'gender', render: emptyCell },
                        { data: 'kesanggupan', render: emptyCell },
                        { data: 'action', orderable: false, searchable: false },
                    ],
                    language: { emptyTable: 'Semua asesor bersedia sudah terpasang pada tim.' },
                    drawCallback: numbering(0),
                });
            }

            // ------------------ DataTables: Kesanggupan ------------------
            function initSimpleTable(selector, url, cols, emptyText) {
                if (!$.fn || !$.fn.DataTable || !$(selector).length) return null;

                return $(selector).DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: { url: url, type: 'GET' },
                    pageLength: 10,
                    order: [[1, 'asc']],
                    columns: cols,
                    language: { emptyTable: emptyText },
                    drawCallback: numbering(0),
                });
            }

            // Nomor urut: selalu dibuat baru agar tidak dibagi antar tabel.
            function noCol() {
                return { data: null, render: function () { return ''; }, orderable: false, searchable: false };
            }

            initSimpleTable('#table-bisa', bisaUrl, [
                noCol(),
                { data: 'nia', render: emptyCell },
                { data: 'name' },
                { data: 'email', render: emptyCell },
                { data: 'kota', render: emptyCell },
                { data: 'gender', render: emptyCell },
                { data: 'tipe', render: emptyCell },
                { data: 'kesanggupan', render: emptyCell },
                { data: 'tim', render: emptyCell },
            ], 'Belum ada asesor yang bersedia.');

            let tidakInited = false;
            let belumInited = false;

            $('#tab-tidak').on('shown.bs.tab', function () {
                if (tidakInited) return;
                tidakInited = true;
                initSimpleTable('#table-tidak', tidakUrl, [
                    noCol(),
                    { data: 'nia', render: emptyCell },
                    { data: 'name' },
                    { data: 'email', render: emptyCell },
                    { data: 'kota', render: emptyCell },
                    { data: 'gender', render: emptyCell },
                    { data: 'tipe', render: emptyCell },
                    { data: 'alasan', render: emptyCell },
                ], 'Tidak ada asesor yang menolak.');
            });

            $('#tab-belum').on('shown.bs.tab', function () {
                if (belumInited) return;
                belumInited = true;
                initSimpleTable('#table-belum', belumUrl, [
                    noCol(),
                    { data: 'nia', render: emptyCell },
                    { data: 'name' },
                    { data: 'email', render: emptyCell },
                    { data: 'kota', render: emptyCell },
                    { data: 'gender', render: emptyCell },
                    { data: 'tipe', render: emptyCell },
                ], 'Semua asesor sudah mengisi kesanggupan.');
            });

            // --------------- Modal: pilih / ubah asesor ---------------
            let asesorTable = null;
            let asesorCache = null;
            let ubahCtx = null;

            function openAsesorModal(teamId, slot) {
                ubahCtx = { team_id: teamId, slot: slot };
                $('#asesor-modal-title').text('Ubah Asesor ' + slot.toUpperCase() + ' — Tim ini saja');

                if (asesorTable) {
                    asesorTable.destroy();
                    asesorTable = null;
                }

                const init = function (data) {
                    if (asesorTable) return;
                    asesorTable = $('#asesor-table').DataTable({
                        data: data,
                        pageLength: 10,
                        order: [[1, 'asc']],
                        columns: [
                            { data: 'nia', render: emptyCell },
                            { data: 'name' },
                            { data: 'kota', render: emptyCell },
                            { data: 'kesanggupan', render: emptyCell },
                            { data: 'tim', render: emptyCell },
                            { data: null, orderable: false, searchable: false,
                              render: function (d, type) {
                                  return type === 'display'
                                      ? '<button type="button" class="btn btn-sm btn-primary btn-pilih-asesor-row">Pilih</button>'
                                      : '';
                              } },
                        ],
                        language: { emptyTable: 'Tidak ada asesor bersedia pada tahap ini.' },
                    });
                    new bootstrap.Modal(document.getElementById('asesor-modal')).show();
                };

                if (asesorCache) {
                    init(asesorCache);
                    return;
                }

                $.getJSON(asesorOptionsUrl, function (res) {
                    asesorCache = res.data || [];
                    init(asesorCache);
                }).fail(function () {
                    showToast('Gagal', 'Tidak dapat memuat daftar asesor.', 'danger');
                });
            }

            // Namespace + off() agar handler tidak menumpuk saat Turbo re-render halaman.
            $(document).off('click.ksg', '.btn-pilih-asesor').on('click.ksg', '.btn-pilih-asesor', function () {
                openAsesorModal($(this).data('team'), $(this).data('slot'));
            });

            $(document).off('click.ksg', '.btn-isi-slot').on('click.ksg', '.btn-isi-slot', function () {
                openAsesorModal($(this).data('team'), $(this).data('slot'));
            });

            $(document).off('click.ksg', '.btn-pilih-asesor-row').on('click.ksg', '.btn-pilih-asesor-row', function () {
                if (!asesorTable || !ubahCtx) return;
                const row = asesorTable.row($(this).closest('tr')).data();
                if (!row) return;

                const btn = $(this).prop('disabled', true);
                ajaxPost(setSlotUrl, {
                    team_id: ubahCtx.team_id,
                    slot: ubahCtx.slot,
                    user_id: row.id,
                }, function (err, res) {
                    btn.prop('disabled', false);
                    if (err) {
                        showToast('Gagal', esc(err), 'danger');
                        return;
                    }
                    showToast('Berhasil', esc(res.message), 'success');
                    const modal = bootstrap.Modal.getInstance(document.getElementById('asesor-modal'));
                    if (modal) modal.hide();
                    asesorCache = null;
                    if (pairsTable) pairsTable.ajax.reload(null, false);
                    if (unmatchedTable) unmatchedTable.ajax.reload(null, false);
                });
            });

            // --------------- Keluarkan asesor dari tim ---------------
            $(document).off('click.ksg', '.btn-keluarkan').on('click.ksg', '.btn-keluarkan', function () {
                const teamId = $(this).data('team');
                const userId = $(this).data('user');
                if (!confirm('Keluarkan asesor ini dari tim? Asesor akan masuk daftar "Belum Terpasang".')) return;
                ajaxPost(removeMemberUrl, { team_id: teamId, user_id: userId }, function (err, res) {
                    if (err) { showToast('Gagal', esc(err), 'danger'); return; }
                    showToast('Berhasil', esc(res.message), 'success');
                    asesorCache = null;
                    if (pairsTable) pairsTable.ajax.reload(null, false);
                    if (unmatchedTable) unmatchedTable.ajax.reload(null, false);
                });
            });

            // --------------- Pasangkan asesor ke tim ---------------
            let pasangkanUser = null;

            $(document).off('click.ksg', '.btn-pasangkan').on('click.ksg', '.btn-pasangkan', function () {
                pasangkanUser = { id: $(this).data('user'), name: $(this).closest('tr').find('td').eq(2).text() };
                $('#team-modal-hint').html('Pasangkan <strong>' + esc(pasangkanUser.name) + '</strong> ke tim tujuan.');
                $('#team-modal-select').val('');
                new bootstrap.Modal(document.getElementById('team-modal')).show();
            });

            $('#team-modal-submit').on('click', function () {
                const teamId = $('#team-modal-select').val();
                if (!teamId || !pasangkanUser) {
                    showToast('Pilih Tim', 'Silakan pilih tim tujuan terlebih dahulu.', 'warning');
                    return;
                }
                const btn = $(this).prop('disabled', true);
                ajaxPost(addMemberUrl, { team_id: teamId, user_id: pasangkanUser.id }, function (err, res) {
                    btn.prop('disabled', false);
                    if (err) { showToast('Gagal', esc(err), 'danger'); return; }
                    showToast('Berhasil', esc(res.message), 'success');
                    const modal = bootstrap.Modal.getInstance(document.getElementById('team-modal'));
                    if (modal) modal.hide();
                    asesorCache = null;
                    if (pairsTable) pairsTable.ajax.reload(null, false);
                    if (unmatchedTable) unmatchedTable.ajax.reload(null, false);
                });
            });
        });
    </script>
@endpush
