@extends('app.layout')
@section('title', 'Validasi')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @include('partial.table-ux')
    <style>
        .ttd-canvas { border: 1px dashed #cbd5e1; border-radius: 10px; background: #fff; cursor: crosshair; }
    </style>
@endpush

@php
    $validasiFormData = $validasis->map(function ($v) use ($jawaban) {
        $j = $jawaban[$v->id] ?? null;
        return [
            'id' => $v->id,
            'nama' => $v->nama,
            'locked' => (bool) $v->pairing_locked_at,
            'action' => route('asesor.validasi.simpan', $v),
            'kesediaan' => $j ? (bool) $j->kesediaan : null,
            'alasan' => $j ? (string) $j->alasan : null,
            'bukti' => $j ? (string) $j->bukti : null,
            'ttd' => $j ? (string) $j->ttd : null,
        ];
    })->values();
@endphp

@section('content')
<div class="container-fluid">
    <div class="main-content d-flex flex-column">
        <div class="card bg-white border-0 rounded-3 mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                    <div>
                        <h4 class="mb-1">Validasi</h4>
                        <p class="text-muted mb-0 fs-14">Isi keterangan <strong>Ya/Tidak</strong> beserta tanda tangan (TTD) untuk setiap validasi yang dibuka.</p>
                    </div>
                </div>

                <ul class="nav nav-tabs" id="validasiTabs" role="tablist">
                    <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#pane-form" type="button">Form Validasi</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#pane-st" type="button">Surat Tugas</button></li>
                </ul>

                <div class="tab-content pt-3">
                    {{-- Tab form: data table + aksi modal --}}
                    <div class="tab-pane fade show active" id="pane-form">
                        <div class="table-responsive">
                            <table id="validasi-table" class="display table table-sm align-middle" style="width:100%">
                                <thead>
                                    <tr>
                                        <th style="width:48px">No</th>
                                        <th>Validasi</th>
                                        <th>No. SK</th>
                                        <th>Periode</th>
                                        <th>Status</th>
                                        <th style="width:120px">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($validasis as $v)
                                        @php $jwb = $jawaban[$v->id] ?? null; @endphp
                                        <tr>
                                            <td></td>
                                            <td>
                                                <strong>{{ $v->nama }}</strong>
                                                @if($jwb && $jwb->ttd)
                                                    <div class="text-muted fs-12">TTD terisi</div>
                                                @endif
                                            </td>
                                            <td>{{ $v->surat_keputusan ?? '-' }}</td>
                                            <td class="text-nowrap">{{ optional($v->start_date)->translatedFormat('d M Y') ?? '-' }} — {{ optional($v->end_date)->translatedFormat('d M Y') ?? '-' }}</td>
                                            <td>
                                                @if($v->pairing_locked_at)
                                                    <span class="badge bg-danger">Terkunci</span>
                                                @elseif($jwb)
                                                    <span class="badge bg-success">Terisi</span>
                                                @else
                                                    <span class="badge bg-secondary">Belum</span>
                                                @endif
                                            </td>
                                            <td>
                                                <button type="button" class="btn btn-sm btn-outline-primary btn-validasi-form"
                                                    data-id="{{ $v->id }}" data-bs-toggle="modal" data-bs-target="#validasiFormModal"
                                                    {{ $v->pairing_locked_at ? 'disabled title="Terkunci"' : '' }}>
                                                    <span class="material-symbols-outlined align-middle" style="font-size:16px">{{ $jwb ? 'edit' : 'edit_note' }}</span>
                                                    {{ $jwb ? 'Ubah' : 'Isi' }}
                                                </button>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- Tab surat tugas --}}
                    <div class="tab-pane fade" id="pane-st">
                        <div class="table-responsive">
                            <table id="st-table" class="display table table-sm align-middle" style="width:100%">
                                <thead>
                                    <tr>
                                        <th style="width:48px">No</th>
                                        <th>Validasi</th>
                                        <th>No. SK</th>
                                        <th>No. ST</th>
                                        <th>Aksi</th>
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

{{-- Modal form validasi (diisi dinamis via JS) --}}
<div class="modal fade" id="validasiFormModal" tabindex="-1" aria-labelledby="validasiFormTitle" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form class="js-validasi-form" method="POST" action="">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="validasiFormTitle">Form Validasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="ttd" value="">
                    <div class="mb-3">
                        <label class="form-label fs-14 mb-1">Keterangan</label>
                        <div class="d-flex gap-3">
                            <label class="form-check"><input class="form-check-input" type="radio" name="kesediaan" value="1" required> Ya (Bisa)</label>
                            <label class="form-check"><input class="form-check-input" type="radio" name="kesediaan" value="0" required> Tidak</label>
                        </div>
                    </div>
                    <div class="mb-3 js-alasan-wrap" style="display:none;">
                        <label class="form-label fs-14 mb-1">Alasan (jika Tidak)</label>
                        <textarea name="alasan" class="form-control" rows="2"></textarea>
                    </div>
                    <div class="mb-3 js-bukti-wrap" style="display:none;">
                        <label class="form-label fs-14 mb-1">Bukti (gambar) — wajib jika Tidak</label>
                        <input type="hidden" name="bukti" value="">
                        <input type="file" class="form-control js-bukti-file" accept="image/*">
                        <div class="js-bukti-preview mt-1" style="display:none;">
                            <img src="" height="60" class="rounded border" alt="bukti">
                            <button type="button" class="btn btn-sm btn-outline-danger btn-clear-bukti ms-1">Hapus</button>
                        </div>
                        <div class="form-text">Gambar dikompresi (maks lebar 1000px, JPEG) agar ukuran tersimpan kecil.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fs-14 mb-1">Tanda Tangan (TTD)</label>
                        <canvas class="ttd-canvas" width="360" height="120"></canvas>
                        <button type="button" class="btn btn-sm btn-outline-secondary btn-clear-ttd mt-1">Hapus TTD</button>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Kirim Jawaban</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        $(function () {
            // ---- Signature pad ----
            document.querySelectorAll('.ttd-canvas').forEach(function (canvas) {
                const ctx = canvas.getContext('2d');
                ctx.lineWidth = 2; ctx.lineCap = 'round'; ctx.strokeStyle = '#1f3c88';
                let drawing = false;
                function pos(e) { const r = canvas.getBoundingClientRect(); const t = e.touches ? e.touches[0] : e; return { x: (t.clientX - r.left) * canvas.width / r.width, y: (t.clientY - r.top) * canvas.height / r.height }; }
                canvas.addEventListener('mousedown', function(e){ drawing = true; const p = pos(e); ctx.beginPath(); ctx.moveTo(p.x, p.y); });
                canvas.addEventListener('mousemove', function(e){ if(!drawing) return; const p = pos(e); ctx.lineTo(p.x, p.y); ctx.stroke(); });
                canvas.addEventListener('mouseup', function(){ drawing = false; });
                canvas.addEventListener('mouseleave', function(){ drawing = false; });
                canvas.addEventListener('touchstart', function(e){ e.preventDefault(); drawing = true; const p = pos(e); ctx.beginPath(); ctx.moveTo(p.x, p.y); });
                canvas.addEventListener('touchmove', function(e){ e.preventDefault(); if(!drawing) return; const p = pos(e); ctx.lineTo(p.x, p.y); ctx.stroke(); });
                canvas.addEventListener('touchend', function(){ drawing = false; });
            });

            $(document).on('click', '.btn-clear-ttd', function () {
                const form = $(this).closest('form');
                const canvas = form.find('.ttd-canvas')[0];
                if (canvas) canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);
                form.find('input[name="ttd"]').val('');
            });

            // ---- Alasan + bukti toggle ----
            $(document).on('change', 'input[name="kesediaan"]', function () {
                const form = $(this).closest('form');
                form.find('.js-alasan-wrap').toggle($(this).val() === '0');
                form.find('.js-bukti-wrap').toggle($(this).val() === '0');
            });

            // ---- Bukti: file -> base64 terkompresi ----
            function compressImage(file, cb) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const img = new Image();
                    img.onload = function () {
                        const maxW = 1000;
                        let w = img.width, h = img.height;
                        if (w > maxW) { h = Math.round(h * maxW / w); w = maxW; }
                        const c = document.createElement('canvas');
                        c.width = w; c.height = h;
                        c.getContext('2d').drawImage(img, 0, 0, w, h);
                        cb(c.toDataURL('image/jpeg', 0.6));
                    };
                    img.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
            $(document).on('change', '.js-bukti-file', function () {
                const form = $(this).closest('form');
                const file = this.files[0];
                if (!file) return;
                compressImage(file, function (b64) {
                    form.find('input[name="bukti"]').val(b64);
                    form.find('.js-bukti-preview img').attr('src', b64);
                    form.find('.js-bukti-preview').show();
                });
            });
            $(document).on('click', '.btn-clear-bukti', function () {
                const form = $(this).closest('form');
                form.find('input[name="bukti"]').val('');
                form.find('.js-bukti-file').val('');
                form.find('.js-bukti-preview').hide();
            });

            // ---- Submit: serialize TTD + validasi bukti ----
            $(document).on('submit', '.js-validasi-form', function () {
                const canvas = this.querySelector('.ttd-canvas');
                const hidden = this.querySelector('input[name="ttd"]');
                let val = canvas ? canvas.toDataURL('image/png') : '';
                if (!val || val === 'data:image/png;base64,') {
                    // pakai TTD tersimpan bila kanvas tidak diubah
                    val = hidden ? hidden.value : '';
                }
                if (!val || val.length < 10) { alert('Silakan isi tanda tangan (TTD) terlebih dahulu.'); return false; }
                hidden.value = val;

                const kes = this.querySelector('input[name="kesediaan"]:checked');
                if (kes && kes.value === '0') {
                    const bukti = this.querySelector('input[name="bukti"]').value;
                    if (!bukti || bukti.length < 20) { alert('Silakan unggah bukti gambar jika memilih Tidak.'); return false; }
                }
                return true;
            });

            // ---- Surat tugas DataTable ----
            if ($.fn && $.fn.DataTable && $('#st-table').length) {
                $('#st-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: { url: "{{ route('asesor.validasi.penugasan-data') }}", type: 'GET' },
                    pageLength: 10,
                    order: [[1, 'asc']],
                    columns: [
                        { data: null, render: function(){ return ''; }, orderable: false, searchable: false },
                        { data: 'validasi' },
                        { data: 'surat_keputusan' },
                        { data: 'nomor_st' },
                        { data: 'action', orderable: false, searchable: false },
                    ],
                    language: { emptyTable: 'Belum ada surat tugas validasi untuk Anda.' },
                    drawCallback: function (settings) {
                        this.api().column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) { cell.innerHTML = settings._iDisplayStart + i + 1; });
                    }
                });
            }
        });
    </script>
    <script>
        $(function () {
            // ---- Form validasi: modal dinamis ----
            window.__VALIDASI__ = @json($validasiFormData ?? []);
            window.__drawTtd = function (canvas, dataUrl) {
                const ctx = canvas.getContext('2d');
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                if (!dataUrl) return;
                const img = new Image();
                img.onload = function () { ctx.drawImage(img, 0, 0, canvas.width, canvas.height); };
                img.src = dataUrl;
            };

            $(document).on('click', '.btn-validasi-form', function () {
                const id = String($(this).data('id'));
                const d = (window.__VALIDASI__ || []).find(function (x) { return String(x.id) === id; });
                if (!d) return;

                const form = $('#validasiFormModal form.js-validasi-form');
                form.attr('action', d.action);
                $('#validasiFormTitle').text('Form Validasi — ' + d.nama);

                form.find('input[name="ttd"]').val(d.ttd || '');
                form.find('input[name="kesediaan"][value="1"]').prop('checked', d.kesediaan === true);
                form.find('input[name="kesediaan"][value="0"]').prop('checked', d.kesediaan === false);
                form.find('textarea[name="alasan"]').val(d.alasan || '');
                form.find('input[name="bukti"]').val(d.bukti || '');
                form.find('.js-bukti-file').val('');
                if (d.bukti) {
                    form.find('.js-bukti-preview img').attr('src', d.bukti);
                    form.find('.js-bukti-preview').show();
                } else {
                    form.find('.js-bukti-preview img').attr('src', '');
                    form.find('.js-bukti-preview').hide();
                }
                form.find('.js-alasan-wrap').toggle(d.kesediaan === false);
                form.find('.js-bukti-wrap').toggle(d.kesediaan === false);

                const canvas = form.find('.ttd-canvas')[0];
                if (canvas) window.__drawTtd(canvas, d.ttd);
            });

            // ---- Form validasi: client-side DataTable ----
            if ($.fn && $.fn.DataTable && $('#validasi-table').length) {
                $('#validasi-table').DataTable({
                    pageLength: 10,
                    order: [[1, 'asc']],
                    columnDefs: [
                        { orderable: false, searchable: false, targets: [0, 5] },
                    ],
                    language: { emptyTable: 'Belum ada validasi yang dibuka.' },
                    drawCallback: function (settings) {
                        this.api().column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
                            cell.innerHTML = settings._iDisplayStart + i + 1;
                        });
                    }
                });
            }
        });
    </script>
@endpush
