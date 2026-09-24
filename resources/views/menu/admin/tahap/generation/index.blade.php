@extends('app.layout')
@section('title', 'Pairing Tim & Lembaga — ' . $tahap->tahap)

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @include('partial.table-ux')
@endpush

@section('content')
<div class="container-fluid">
    <div class="main-content d-flex flex-column">

        @php $pPlan = session('pairing_upload_plan'); @endphp
        @if($pPlan && (int) ($pPlan['tahap_id'] ?? 0) === $tahap->id)
        <div class="card border border-warning bg-warning-subtle rounded-3 mb-4">
            <div class="card-body">
                <h6 class="fw-semibold mb-1"><i class="material-symbols-outlined align-middle" style="font-size:18px">warning</i> Tinjau sebelum diterapkan</h6>
                <p class="mb-2 fs-14">
                    File ini akan: <strong>+{{ $pPlan['new'] }}</strong> pasangan baru,
                    <strong>±{{ $pPlan['update'] }}</strong> berubah,
                    <strong>−{{ $pPlan['delete'] }}</strong> dihapus.
                    @if(($pPlan['delete'] ?? 0) > 0)
                        <br>Pasangan yang dihapus (tidak ada di file) akan kembali ke <em>Lembaga Tersisa</em> dan tampil di Manual Override.
                        Pastikan file adalah hasil unduhan <strong>terbaru</strong> sebelum melanjutkan.
                    @endif
                </p>
                <div class="d-flex flex-wrap gap-2">
                    <form method="POST" action="{{ route('admin.tahap.generation.upload-confirm', ['tahap' => $tahap->slug]) }}">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-warning text-dark">Lanjutkan &amp; Terapkan</button>
                    </form>
                    <form method="POST" action="{{ route('admin.tahap.generation.upload-cancel', ['tahap' => $tahap->slug]) }}">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-secondary">Batalkan</button>
                    </form>
                    <a href="{{ route('admin.tahap.generation.download', ['tahap' => $tahap->slug]) }}" class="btn btn-sm btn-outline-primary" data-turbo="false">Unduh file terbaru</a>
                </div>
            </div>
        </div>
        @endif

        {{-- Header --}}
        <div class="card bg-white border-0 rounded-3 mb-4">
            <div class="card-body p-4">
                <nav aria-label="breadcrumb" class="mb-3">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.tahap.index') }}">Visitasi</a></li>
                        <li class="breadcrumb-item active">Pairing — {{ $tahap->tahap }}</li>
                    </ol>
                </nav>

                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                    <div>
                        <h4 class="mb-1">Pairing Tim Asesor & Lembaga</h4>
                        <p class="text-muted mb-0 fs-14">{{ $tahap->tahap }}</p>
                        @if($locked)
                            <span class="badge bg-danger mt-2">
                                <i class="material-symbols-outlined align-middle" style="font-size:16px">lock</i>
                                Data Terkunci — {{ optional($tahap->pairing_locked_at)->translatedFormat('d M Y H:i') }}
                            </span>
                        @endif
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        @if($locked)
                            @if($run)
                                <a href="{{ route('admin.tahap.generation.surat-tugas', ['tahap' => $tahap->slug, 'run' => $run->id]) }}" class="btn btn-outline-primary">
                                    <i class="material-symbols-outlined align-middle" style="font-size:18px">description</i>
                                    Lihat Surat Tugas
                                </a>
                            @endif
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#suratTugasModal">
                                <i class="material-symbols-outlined align-middle" style="font-size:18px">send</i>
                                {{ $suratTugasSent ? 'Kirim Ulang Surat Tugas' : 'Kirim Surat Tugas' }}
                            </button>
                            <form method="POST" action="{{ route('admin.tahap.generation.unlock', ['tahap' => $tahap->slug]) }}">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger">
                                    <i class="material-symbols-outlined align-middle" style="font-size:18px">lock_open</i>
                                    Buka Kunci (Cancel)
                                </button>
                            </form>
                        @else
                            @if($stats['teams'] > 0 && $stats['lembagas'] > 0)
                                <form method="POST" action="{{ route('admin.tahap.generate', ['tahap' => $tahap->slug]) }}"
                                      onsubmit="return confirm('Jalankan auto-match ulang? Seluruh pasangan akan direset lalu dipasangkan ulang secara otomatis.');">
                                    @csrf
                                    <button type="submit" class="btn btn-success">
                                        <i class="material-symbols-outlined align-middle" style="font-size:18px">bolt</i>
                                        Jalankan Auto-Match
                                    </button>
                                </form>
                            @endif
                            <a href="{{ route('admin.tahap.generation.download', ['tahap' => $tahap->slug]) }}" class="btn btn-outline-primary" data-turbo="false">
                                <i class="material-symbols-outlined align-middle" style="font-size:18px">download</i>
                                Unduh Hasil Pairing
                            </a>
                            @if($stats['terpasang'] > 0 || $stats['tim_belum_penuh'] > 0)
                                <form method="POST" action="{{ route('admin.tahap.generation.lock', ['tahap' => $tahap->slug]) }}"
                                      onsubmit="return confirm('Kunci data pairing? Setelah dikunci, data tidak dapat diubah lagi sampai dibuka kembali.');">
                                    @csrf
                                    <button type="submit" class="btn btn-warning text-dark">
                                        <i class="material-symbols-outlined align-middle" style="font-size:18px">lock</i>
                                        Kunci Data
                                    </button>
                                </form>
                            @endif
                        @endif
                    </div>
                </div>

                @if(!$locked)
                <div class="card border mt-3 mb-0">
                    <div class="card-body py-3">
                        <div class="row g-2 align-items-center">
                            <div class="col-md-4">
                                <strong class="fs-14">Upload Manual Override</strong>
                                <div class="text-muted fs-13">
                                    Unduh <em>Hasil Pairing</em>, isi kolom <code>npsn</code>/<code>nama_lembaga</code> pada sheet
                                    <em>Manual Override</em> untuk tim yang belum penuh, lalu upload file yang sama.
                                </div>
                            </div>
                            <div class="col-md-8">
                                <form method="POST" action="{{ route('admin.tahap.generation.upload', ['tahap' => $tahap->slug]) }}" enctype="multipart/form-data" class="d-flex gap-2 flex-wrap">
                                    @csrf
                                    <input type="file" name="file" accept=".csv,.txt,.xlsx" class="form-control form-control-sm" style="max-width:340px" required>
                                    <button type="submit" class="btn btn-sm btn-primary text-nowrap">Upload &amp; Pasangkan</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Statistik ringkas --}}
                <div class="d-flex flex-wrap gap-2 mt-3">
                    <span class="badge bg-light text-dark border px-3 py-2">Tim: {{ $stats['teams'] }}</span>
                    <span class="badge bg-light text-dark border px-3 py-2">Lembaga: {{ $stats['lembagas'] }}</span>
                    <span class="badge bg-light text-dark border px-3 py-2">Terpasang: {{ $stats['terpasang'] }}</span>
                    @if($stats['tersisa'] > 0)
                        <a href="{{ route('admin.tahap.generation.tersisa', ['tahap' => $tahap->slug]) }}" class="badge bg-warning text-dark px-3 py-2 text-decoration-none">
                            Lembaga Tersisa: {{ $stats['tersisa'] }} → lihat
                        </a>
                    @else
                        <span class="badge bg-light text-dark border px-3 py-2">Lembaga Tersisa: 0</span>
                    @endif
                    <span class="badge {{ $stats['tim_belum_penuh'] > 0 ? 'bg-info text-dark' : 'bg-light text-dark border' }} px-3 py-2">Tim Belum Penuh: {{ $stats['tim_belum_penuh'] }}</span>
                </div>
            </div>
        </div>

        {{-- Hasil Pairing per NPSN (DataTables server-side) --}}
        <div class="card bg-white border-0 rounded-3 mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                    <h5 class="fw-semibold mb-0">Hasil Pairing per Lembaga (NPSN)</h5>
                    <div class="d-flex gap-2">
                        <button type="button" id="copy-result-btn" class="btn btn-sm btn-outline-secondary">
                            <span class="material-symbols-outlined align-middle" style="font-size:16px">content_copy</span> Copy
                        </button>
                        <a href="{{ route('admin.tahap.generation.download', ['tahap' => $tahap->slug]) }}" class="btn btn-sm btn-outline-success" data-turbo="false">
                            <span class="material-symbols-outlined align-middle" style="font-size:16px">download</span> Excel
                        </a>
                        @if(!$locked)
                        <button type="button" id="batch-cancel-btn" class="btn btn-sm btn-outline-danger" disabled>
                            <span class="material-symbols-outlined align-middle" style="font-size:16px">delete_sweep</span> Cancel Terpilih (<span id="batch-count">0</span>)
                        </button>
                        @endif
                    </div>
                </div>
                @if($stats['terpasang'] === 0)
                    <p class="text-muted mb-0">Belum ada pasangan. Jalankan Auto-Match terlebih dahulu.</p>
                @else
                    <p class="text-muted fs-13 mb-2">Klik ikon <span class="material-symbols-outlined align-middle" style="font-size:14px">edit</span> di samping NIA untuk mengubah asesor pada baris (NPSN) ini saja.</p>
                    <div class="table-responsive">
                        <table id="result-table" class="display table table-sm align-middle" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="width:32px"><input type="checkbox" id="select-all-result" class="form-check-input" title="Pilih semua di halaman ini"></th>
                                    <th>No</th>
                                    <th>Team</th>
                                    <th>NPSN</th>
                                    <th>Nama Lembaga</th>
                                    <th>Kab/Kota</th>
                                    <th>NIA Asesor A</th>
                                    <th>Nama Asesor A</th>
                                    <th>NIA Asesor B</th>
                                    <th>Nama Asesor B</th>
                                    <th>Cancel</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        {{-- Manual Override (DataTables server-side) --}}
        @if(!$locked)
        <div class="card bg-white border-0 rounded-3 mb-4">
            <div class="card-body p-4">
                <h5 class="fw-semibold mb-3">Manual Override — Tim yang Belum Penuh</h5>
                @if($stats['tim_belum_penuh'] === 0 || $stats['tersisa'] === 0)
                    <p class="text-muted mb-0">{{ $stats['tersisa'] === 0 ? 'Tidak ada lembaga tersisa — semua lembaga sudah terpasang.' : 'Semua tim sudah mencapai kuota.' }}</p>
                @else
                    <p class="text-muted fs-13 mb-2">Ketik lalu pilih lembaga dari daftar — lembaga dapat ditambahkan beberapa sekaligus sesuai sisa kuota tim.</p>
                    <div class="table-responsive">
                        <table id="override-table" class="display table table-sm align-middle" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Team</th>
                                    <th>NIA Asesor A</th>
                                    <th>Nama Asesor A</th>
                                    <th>NIA Asesor B</th>
                                    <th>Nama Asesor B</th>
                                    <th>Pilih Lembaga &amp; Assign</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <datalist id="lembaga-datalist"></datalist>
                @endif
            </div>
        </div>
        @else
        <div class="alert alert-secondary d-flex align-items-center gap-2 mb-4">
            <i class="material-symbols-outlined">lock</i>
            Data pairing terkunci. Buka kunci terlebih dahulu untuk melakukan manual override / upload / auto-match.
        </div>
        @endif

    </div>
</div>

{{-- Modal: Ubah Asesor (per NPSN) --}}
<div class="modal fade" id="asesor-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="asesor-modal-title">Ubah Asesor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p class="text-muted fs-13 mb-2" id="asesor-modal-hint">Pilih asesor dari daftar seluruh asesor (termasuk yang belum mengisi kesanggupan).</p>
                <div class="table-responsive">
                    <table id="asesor-table" class="display table table-sm align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th>NIA</th>
                                <th>Nama</th>
                                <th>Tim (tahap ini)</th>
                                <th>Kesanggupan</th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal: Kirim Surat Tugas --}}
@php
    $suggestedSt = $run
        ? 'ST/'.str_pad((string) $run->id, 3, '0', STR_PAD_LEFT).'/'.$tahap->id.'/'.now()->format('Y')
        : '';
@endphp
<div class="modal fade" id="suratTugasModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Kirim Surat Tugas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('admin.tahap.generation.surat-tugas.send', ['tahap' => $tahap->slug]) }}">
                @csrf
                <div class="modal-body">
                    <label class="form-label fs-14">Nomor Surat Tugas</label>
                    <input type="text" name="nomor_st" class="form-control" value="{{ $suratTugasNumber ?? $suggestedSt }}" placeholder="contoh: ST/001/5/2026" required>
                    <div class="form-text">
                        Nomor ST akan tampil pada surat tugas. Notifikasi akan dikirim ke seluruh asesor yang tertugaskan pada tahap ini.
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

{{-- Toast container --}}
<div class="toast-container position-fixed top-0 end-0 p-3" id="toast-container" style="z-index:1080"></div>
@endsection

@push('scripts')
    <script>
        function initGenerationPage() {
            var $sentinel = $('#copy-result-btn');
            if (!$sentinel.length || $sentinel.data('init')) return;
            if (!$.fn || !$.fn.DataTable) return;
            $sentinel.data('init', true);

            function showToast(title, message, type) {
                if (!message) return;
                type = type || 'success';
                const box = document.getElementById('toast-container');
                if (!box) return;
                const el = document.createElement('div');
                el.className = 'toast align-items-center text-bg-' + type + ' border-0 show';
                el.innerHTML = '<div class="d-flex">'
                    + '<div class="toast-body"><strong>' + title + '</strong>'
                    + '<div class="fs-14">' + message + '</div></div>'
                    + '<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>'
                    + '</div>';
                box.appendChild(el);
                const t = new bootstrap.Toast(el, { delay: 8000 });
                t.show();
                el.addEventListener('hidden.bs.toast', () => el.remove());
            }

            const flash = {
                toast: @json(session('toast')),
                success: @json(session('success')),
                error: @json(session('error')),
            };
            if (flash.toast) {
                showToast(flash.toast.title || (flash.toast.type === 'danger' ? 'Gagal' : 'Sukses'), flash.toast.message, flash.toast.type || 'success');
            } else if (flash.success) {
                showToast('Sukses', flash.success, 'success');
            } else if (flash.error) {
                showToast('Gagal', flash.error, 'danger');
            }

            const LOCKED = @json($locked);
            const csrf = '{{ csrf_token() }}';
            const unassignBase = "{{ route('admin.tahap.generation.unassign', ['tahap' => $tahap->slug, 'assignment' => '__ASSIGNMENT__']) }}";
            const optionsUrl = "{{ route('admin.tahap.generation.lembaga-options', ['tahap' => $tahap->slug]) }}";
            const assignUrl = "{{ route('admin.tahap.generation.assign', ['tahap' => $tahap->slug]) }}";
            const resultsUrl = "{{ route('admin.tahap.generation.pairing-results', ['tahap' => $tahap->slug]) }}";
            const copyUrl = "{{ route('admin.tahap.generation.results-copy', ['tahap' => $tahap->slug]) }}";
            const dataUrl = "{{ route('admin.tahap.generation.data', ['tahap' => $tahap->slug]) }}";
            const batchCancelUrl = "{{ route('admin.tahap.generation.batch-cancel', ['tahap' => $tahap->slug]) }}";
            const asesorDataUrl = "{{ route('admin.tahap.generation.asesor-data', ['tahap' => $tahap->slug]) }}";
            const asesorSetUrl = "{{ route('admin.tahap.generation.asesor-set', ['tahap' => $tahap->slug]) }}";

            function esc(s) {
                return String(s ?? '').replace(/[&<>"']/g, c => ({
                    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
                }[c]));
            }

            function debounce(fn, ms) {
                let timer;
                return function () {
                    clearTimeout(timer);
                    timer = setTimeout(() => fn.apply(this, arguments), ms);
                };
            }

            function numberingCol(settings) {
                const api = this.api();
                api.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
                    cell.innerHTML = settings._iDisplayStart + i + 1;
                });
            }

            // ---------- DataTables: Hasil Pairing per NPSN ----------
            function codeCell(data, type) {
                if (type !== 'display') return data;
                return data ? esc(data) : '<span class="text-muted">—</span>';
            }

            function niaCell(data, type, row, slot) {
                if (type !== 'display') return data;
                const text = data ? esc(data) : '<span class="text-muted">—</span>';
                if (LOCKED) return text;
                return '<div class="d-flex align-items-center gap-1">' + text
                    + '<button type="button" class="btn btn-link btn-sm p-0 ubah-btn" data-assignment="' + row.assignment_id + '" data-slot="' + slot + '" title="Ubah asesor ' + slot.toUpperCase() + ' untuk NPSN ini">'
                    + '<span class="material-symbols-outlined align-middle" style="font-size:16px">edit</span></button></div>';
            }

            const selected = new Set();

            function numberingAt(col) {
                return function (settings) {
                    const api = this.api();
                    api.column(col, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
                        cell.innerHTML = settings._iDisplayStart + i + 1;
                    });
                };
            }

            function syncCheckboxes(api) {
                $(api.table().container()).find('input.row-select').each(function () {
                    this.checked = selected.has(Number(this.dataset.id));
                });
                const btn = document.getElementById('batch-cancel-btn');
                if (btn) {
                    btn.disabled = selected.size === 0;
                    const cnt = document.getElementById('batch-count');
                    if (cnt) cnt.textContent = selected.size;
                }
            }

            function batchCountLabel() {
                const btn = document.getElementById('batch-cancel-btn');
                if (btn) btn.disabled = selected.size === 0;
                const cnt = document.getElementById('batch-count');
                if (cnt) cnt.textContent = selected.size;
            }

            if ($.fn && $.fn.DataTable && $('#result-table').length) {
                $('#result-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: { url: resultsUrl, type: 'GET' },
                    pageLength: 10,
                    order: [[2, 'asc']],
                    columns: [
                        { data: null, orderable: false, searchable: false,
                          render: function (data, type, row) {
                              if (type !== 'display') return '';
                              if (LOCKED) return '';
                              return '<input type="checkbox" class="form-check-input row-select" data-id="' + row.assignment_id + '" title="Pilih untuk dibatalkan">';
                          } },
                        { data: null, render: function () { return ''; }, orderable: false, searchable: false },
                        { data: 'code', render: codeCell },
                        { data: 'npsn' },
                        { data: 'name' },
                        { data: 'kabupaten' },
                        { data: 'nia_a', render: function (data, type, row) { return niaCell(data, type, row, 'a'); } },
                        { data: 'nama_a' },
                        { data: 'nia_b', render: function (data, type, row) { return niaCell(data, type, row, 'b'); } },
                        { data: 'nama_b' },
                        { data: 'action', orderable: false, searchable: false },
                    ],
                    language: { emptyTable: 'Belum ada pasangan.' },
                    drawCallback: function (settings) {
                        numberingAt(1).call(this, settings);
                        syncCheckboxes(this.api());
                    },
                });
            }

            // Seleksi multi (checkbox) untuk Cancel berjamaah
            $(document).off('change.gen', '.row-select').on('change.gen', '.row-select', function () {
                const id = Number(this.dataset.id);
                if (this.checked) selected.add(id); else selected.delete(id);
                batchCountLabel();
            });

            $('#select-all-result').on('change', function () {
                // pilih / kosongkan semua baris pada halaman saat ini
                $('#result-table').find('input.row-select').prop('checked', this.checked).each(function () {
                    const id = Number(this.dataset.id);
                    if (this.checked) selected.add(id); else selected.delete(id);
                });
                batchCountLabel();
            });

            $('#batch-cancel-btn').on('click', function () {
                if (!selected.size) {
                    showToast('Cancel', 'Pilih minimal 1 baris lembaga terlebih dahulu.', 'warning');
                    return;
                }
                if (!confirm('Batalkan ' + selected.size + ' pasangan terpilih? Lembaga akan kembali ke daftar Lembaga Tersisa.')) return;
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = batchCancelUrl;
                let html = '<input type="hidden" name="_token" value="' + csrf + '">';
                selected.forEach(function (id) { html += '<input type="hidden" name="assignment_ids[]" value="' + id + '">'; });
                form.innerHTML = html;
                document.body.appendChild(form);
                form.submit();
            });

            // ---------- Modal Ubah Asesor ----------
            let ubahCtx = null;
            let asesorCache = null;
            let asesorTable = null;

            function openUbahModal(assignmentId, slot) {
                if (!assignmentId) return;
                ubahCtx = { assignment_id: assignmentId, slot: slot };
                $('#asesor-modal-title').text('Ubah Asesor ' + (slot === 'a' ? 'A' : 'B') + ' — NPSN ini saja');

                if (asesorTable) {
                    asesorTable.destroy();
                    asesorTable = null;
                }

                const initTable = function (data) {
                    if (asesorTable) return;
                    asesorTable = $('#asesor-table').DataTable({
                        data: data,
                        pageLength: 10,
                        order: [[1, 'asc']],
                        columns: [
                            { data: 'nia' },
                            { data: 'name' },
                            { data: 'team_code', render: function (d, type) { return type === 'display' ? (d ? esc(d) : '<span class="text-muted">—</span>') : d; } },
                            { data: 'kesanggupan' },
                            { data: null, orderable: false, searchable: false,
                              render: function (d2, type, row) {
                                  return type === 'display'
                                      ? '<button type="button" class="btn btn-sm btn-primary pilih-asesor-btn">Pilih</button>'
                                      : '';
                              } },
                        ],
                        language: { emptyTable: 'Tidak ada asesor.' },
                    });
                    new bootstrap.Modal(document.getElementById('asesor-modal')).show();
                };

                if (asesorCache) {
                    initTable(asesorCache);
                    return;
                }
                $.getJSON(asesorDataUrl, function (res) {
                    asesorCache = res.data || [];
                    initTable(asesorCache);
                }).fail(function () {
                    showToast('Ubah Asesor', 'Gagal memuat daftar asesor.', 'danger');
                });
            }

            $(document).off('click.gen', '.ubah-btn').on('click.gen', '.ubah-btn', function () {
                openUbahModal($(this).data('assignment'), $(this).data('slot'));
            });

            $(document).off('click.gen', '.pilih-asesor-btn').on('click.gen', '.pilih-asesor-btn', function () {
                if (!asesorTable || !ubahCtx) return;
                const row = asesorTable.row($(this).closest('tr')).data();
                if (!row) return;
                const fd = new FormData();
                fd.append('_token', csrf);
                fd.append('assignment_id', ubahCtx.assignment_id);
                fd.append('slot', ubahCtx.slot);
                fd.append('user_id', row.id);

                const btn = $(this).prop('disabled', true);
                fetch(asesorSetUrl, { method: 'POST', body: fd, headers: { 'X-Requested-With': 'XMLHttpRequest' } })
                    .then(function (res) { return res.json().catch(function () { return null; }).then(function (d) { return { ok: res.ok, data: d }; }); })
                    .then(function (r) {
                        btn.prop('disabled', false);
                        if (r.ok && r.data && r.data.ok) {
                            showToast('Asesor Diganti', 'Asesor ' + (ubahCtx.slot === 'a' ? 'A' : 'B') + ' baris ini menjadi: ' + (r.data.nia ? r.data.nia + ' (' + r.data.name + ')' : r.data.name) + (r.data.team_code ? ' — Tim ' + r.data.team_code : ''), 'success');
                            const modal = bootstrap.Modal.getInstance(document.getElementById('asesor-modal'));
                            if (modal) modal.hide();
                            $('#result-table').DataTable().ajax.reload(null, false);
                        } else {
                            showToast('Gagal', (r.data && r.data.message) || 'Terjadi kesalahan.', 'danger');
                        }
                    })
                    .catch(function () {
                        btn.prop('disabled', false);
                        showToast('Gagal', 'Koneksi bermasalah.', 'danger');
                    });
            });

            // ---------- DataTables: Manual Override ----------
            if ($.fn && $.fn.DataTable && $('#override-table').length) {
                $('#override-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: { url: dataUrl, type: 'GET' },
                    pageLength: 10,
                    order: [[1, 'asc']],
                    columns: [
                        { data: null, render: function () { return ''; }, orderable: false, searchable: false },
                        { data: 'code', render: codeCell },
                        { data: 'nia_a' },
                        { data: 'nama_a' },
                        { data: 'nia_b' },
                        { data: 'nama_b' },
                        { data: 'action', orderable: false, searchable: false },
                    ],
                    drawCallback: numberingCol,
                });
            }

            // ---------- Pilih lembaga multi (Manual Override) ----------
            const lembagaMap = {};

            function lembagaLabel(o) {
                return (o.npsn ? o.npsn + ' · ' : '') + o.name + (o.kabupaten ? ' · ' + o.kabupaten : '');
            }

            function fillLembagaDatalist(items) {
                const dl = document.getElementById('lembaga-datalist');
                if (!dl) return;
                dl.innerHTML = '';
                items.forEach(function (o) {
                    const v = lembagaLabel(o);
                    lembagaMap[v] = { id: o.id, npsn: o.npsn, name: o.name, kabupaten: o.kabupaten };
                    const opt = document.createElement('option');
                    opt.value = v;
                    dl.appendChild(opt);
                });
            }

            function chipsFor($input) {
                return $input.closest('div').find('.selected-chips');
            }

            function renderChips($box, chips) {
                $box.empty();
                chips.forEach(function (c) {
                    const chip = document.createElement('span');
                    chip.className = 'badge bg-light text-dark border d-inline-flex align-items-center gap-1';
                    chip.innerHTML = '<span>' + esc(c.name) + '</span>'
                        + '<button type="button" class="btn-close btn-close-sm chip-remove" style="font-size:8px" data-id="' + c.id + '" aria-label="Hapus"></button>';
                    $box.append(chip);
                });
                const $assign = $box.closest('div').find('.assign-btn');
                $assign.prop('disabled', chips.length === 0);
                $assign.text(chips.length > 0 ? 'Assign (' + chips.length + ' lembaga)' : 'Assign Lembaga Terpilih');
            }

            $(document).off('input.gen', '.lembaga-input').on('input.gen', '.lembaga-input', debounce(function () {
                const q = this.value.trim();
                if (q === '') return;
                $.getJSON(optionsUrl, { q: q }, function (res) {
                    fillLembagaDatalist(res.results || []);
                });
            }, 250));

            $(document).off('change.gen', '.lembaga-input').on('change.gen', '.lembaga-input', function () {
                const picked = lembagaMap[this.value];
                const $input = $(this);
                const $box = chipsFor($input);
                if (!picked) return;
                const chips = $box.data('chips') || [];
                const max = parseInt($box.data('max') || '0', 10);
                if (chips.some(function (c) { return c.id === picked.id; })) {
                    showToast('Pilih Lembaga', 'Lembaga tersebut sudah dipilih untuk tim ini.', 'warning');
                    $input.val('');
                    return;
                }
                if (chips.length >= max) {
                    showToast('Pilih Lembaga', 'Sisa kuota tim hanya ' + max + ' lembaga.', 'warning');
                    $input.val('');
                    return;
                }
                chips.push({ id: picked.id, name: picked.name + ' (NPSN ' + picked.npsn + ')' });
                $box.data('chips', chips);
                renderChips($box, chips);
                $input.val('');
            });

            $(document).off('click.gen', '.chip-remove').on('click.gen', '.chip-remove', function () {
                const id = $(this).data('id');
                const $box = $(this).closest('.selected-chips');
                const chips = ($box.data('chips') || []).filter(function (c) { return c.id !== id; });
                $box.data('chips', chips);
                renderChips($box, chips);
            });

            $(document).off('click.gen', '.assign-btn').on('click.gen', '.assign-btn', function () {
                const $wrap = $(this).closest('div');
                const teamId = $(this).data('team');
                const chips = $wrap.find('.selected-chips').data('chips') || [];
                if (!chips.length) {
                    showToast('Assign', 'Tambahkan minimal 1 lembaga terlebih dahulu.', 'warning');
                    return;
                }
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = assignUrl;
                let html = '<input type="hidden" name="_token" value="' + csrf + '">'
                    + '<input type="hidden" name="team_id" value="' + teamId + '">';
                chips.forEach(function (c) {
                    html += '<input type="hidden" name="lembaga_ids[]" value="' + c.id + '">';
                });
                form.innerHTML = html;
                document.body.appendChild(form);
                form.submit();
            });

            // ---------- Tombol Copy (hasil pairing) ----------
            function fallbackCopy(text) {
                const ta = document.createElement('textarea');
                ta.value = text;
                ta.style.position = 'fixed';
                ta.style.opacity = '0';
                document.body.appendChild(ta);
                ta.select();
                try {
                    document.execCommand('copy');
                    showToast('Copy', 'Disalin ke clipboard.', 'success');
                } catch (e) {
                    showToast('Copy', 'Gagal menyalin otomatis. Pilih manual dari tabel.', 'danger');
                }
                document.body.removeChild(ta);
            }

            $('#copy-result-btn').on('click', function () {
                $.getJSON(copyUrl, function (data) {
                    const lines = [data.headers.join('\t')];
                    (data.rows || []).forEach(function (r) { lines.push(r.join('\t')); });
                    const text = lines.join('\n');
                    if (navigator.clipboard && navigator.clipboard.writeText) {
                        navigator.clipboard.writeText(text).then(function () {
                            showToast('Copy', (data.rows || []).length + ' baris disalin ke clipboard.', 'success');
                        }, function () { fallbackCopy(text); });
                    } else {
                        fallbackCopy(text);
                    }
                }).fail(function () {
                    showToast('Copy', 'Gagal mengambil data untuk disalin.', 'danger');
                });
            });

            // ---------- Cancel (batalkan pasangan -> lembaga kembali ke daftar tersisa) ----------
            $(document).off('click.gen', '.cancel-assignment').on('click.gen', '.cancel-assignment', function () {
                const assignmentId = $(this).data('assignment');
                if (!confirm('Batalkan pasangan lembaga ini? Lembaga akan kembali ke daftar Lembaga Tersisa.')) return;
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = unassignBase.replace('__ASSIGNMENT__', assignmentId);
                form.innerHTML = '<input type="hidden" name="_token" value="' + csrf + '">'
                    + '<input type="hidden" name="assignment_id" value="' + assignmentId + '">'
                    + '<input type="hidden" name="_method" value="DELETE">';
                document.body.appendChild(form);
                form.submit();
            });
        }

        initGenerationPage();
        if (window.__registerDataTableInit) window.__registerDataTableInit('generation', initGenerationPage);
    </script>
@endpush
