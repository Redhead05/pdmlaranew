@extends('app.layout')
@section('title', 'Validasi')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @include('partial.table-ux')
    <style>
        .ttd-canvas { border: 1px dashed #cbd5e1; border-radius: 10px; background: #fff; cursor: crosshair; width: 100%; max-width: 360px; }
    </style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="main-content d-flex flex-column">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h1 class="mb-0">Validasi</h1>
        </div>

        @if ($validasis->isEmpty())
            <div class="alert alert-info">Belum ada validasi yang dibuka untuk akun ini.</div>
        @else
            <div class="row g-3">
                @foreach ($validasis as $v)
                    @php
                        $suffix = $v->id;
                        $jwb = $jawaban[$v->id] ?? null;
                        $isYa = (bool) ($jwb?->kesediaan ?? false);
                        $isLocked = (bool) $v->pairing_locked_at;
                    @endphp

                    <div class="col-xl-4 col-xxl-3 col-sm-6">
                        <div class="card bg-white border-0 rounded-3 mb-4 transition-y">
                            <div class="card-body p-4">
                                <div class="position-relative mb-3">
                                    <a href="#">
                                        <img src="{{ asset('assets/images/event-1.jpg') }}" class="rounded-3 img-fluid" alt="validasi">
                                    </a>

                                    <div class="mt-3">
                                        <form class="js-validasi-form"
                                              data-url="{{ route('asesor.validasi.simpan', $v) }}"
                                              data-ttd="{{ $jwb?->ttd ?? '' }}">
                                            @csrf
                                            <input type="hidden" name="bukti" value="{{ $jwb?->bukti ?? '' }}">

                                            <div class="alert alert-success d-none js-success" role="alert">Jawaban validasi tersimpan.</div>
                                            <div class="alert alert-danger d-none js-error" role="alert"></div>

                                            <div class="mb-2 small text-secondary fs-15">
                                                Validasi: <span class="fw-semibold">{{ $v->nama }}</span>
                                            </div>

                                            <div class="mb-2">
                                                @if($isLocked)
                                                    <span class="badge text-bg-danger">Terkunci</span>
                                                @elseif($jwb)
                                                    <span class="badge text-bg-success">Terisi</span>
                                                @else
                                                    <span class="badge text-bg-secondary">Belum</span>
                                                @endif
                                            </div>

                                            <div class="mb-3 p-2 rounded-3 border bg-light">
                                                <div class="d-flex justify-content-between align-items-center">
                                                    <span class="small text-muted">No. SK</span>
                                                    <span class="small fw-semibold">{{ $v->surat_keputusan ?? '-' }}</span>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center mt-1">
                                                    <span class="small text-muted">Periode</span>
                                                    <span class="small fw-semibold text-end">{{ optional($v->start_date)->translatedFormat('d M Y') ?? '-' }} — {{ optional($v->end_date)->translatedFormat('d M Y') ?? '-' }}</span>
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <h1>
                                                    <div class="d-flex justify-content-between align-items-center fs-15">
                                                        <span class="fw-semibold">NIA</span>
                                                        <span class="text-secondary">{{ $authUser['nia'] ?? '-' }}</span>
                                                    </div>
                                                </h1>
                                            </div>

                                            <div class="mb-3">
                                                <h1>
                                                    <div class="d-flex justify-content-between align-items-center fs-15">
                                                        <span class="fw-semibold">Nama</span>
                                                        <span class="text-secondary">{{ $authUser['name'] ?? '-' }}</span>
                                                    </div>
                                                </h1>
                                            </div>

                                            <div class="mb-3">
                                                <h1>
                                                    <div class="d-flex justify-content-between align-items-center fs-15">
                                                        <span class="fw-semibold">Kab/Kot</span>
                                                        <span class="text-secondary">{{ $authUser['work_city'] ?? '-' }}</span>
                                                    </div>
                                                </h1>
                                            </div>

                                            <fieldset class="js-lock-scope" @disabled($isLocked)>
                                                <h1 class="fs-15">
                                                    <div class="mb-3">
                                                        <label for="kesediaan-{{ $suffix }}" class="form-label fw-semibold">Keterangan</label>
                                                        <select id="kesediaan-{{ $suffix }}" name="kesediaan" class="form-select js-kesediaan text-secondary" required>
                                                            <option value="1" @selected($isYa)>Ya (Bisa)</option>
                                                            <option value="0" @selected(!$isYa)>Tidak</option>
                                                        </select>
                                                    </div>

                                                    <div class="mb-3 js-wrap-alasan @if($isYa) d-none @endif">
                                                        <label for="alasan-{{ $suffix }}" class="form-label fw-semibold">Alasan</label>
                                                        <textarea id="alasan-{{ $suffix }}" name="alasan" class="form-control js-alasan"
                                                                  rows="3" @if($isYa) disabled @endif
                                                                  placeholder="Wajib diisi jika memilih Tidak">{{ $jwb?->alasan }}</textarea>
                                                    </div>

                                                    <div class="mb-3 js-wrap-bukti @if($isYa) d-none @endif">
                                                        <label for="bukti-{{ $suffix }}" class="form-label fw-semibold">Bukti (gambar)</label>
                                                        <input type="file" id="bukti-{{ $suffix }}" class="form-control js-bukti-file" accept="image/*" @if($isYa) disabled @endif>
                                                        <div class="js-bukti-preview mt-1 @if(blank($jwb?->bukti)) d-none @endif">
                                                            <img src="{{ $jwb?->bukti }}" height="60" class="rounded border" alt="bukti">
                                                            <button type="button" class="btn btn-sm btn-outline-danger btn-clear-bukti ms-1">Hapus</button>
                                                        </div>
                                                        <div class="form-text">Gambar dikompresi (maks lebar 1000px, JPEG) agar ukuran tersimpan kecil.</div>
                                                    </div>

                                                    <div class="mb-3">
                                                        <label class="form-label fw-semibold">Tanda Tangan (TTD)</label>
                                                        <canvas class="ttd-canvas" width="360" height="120"></canvas>
                                                        <button type="button" class="btn btn-sm btn-outline-secondary btn-clear-ttd mt-1">Hapus TTD</button>
                                                    </div>
                                                </h1>

                                                <button type="button" class="btn btn-primary w-100 js-save">Simpan</button>
                                            </fieldset>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        {{-- Surat tugas validasi pribadi --}}
        <div class="card bg-white border-0 rounded-3 mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                    <div>
                        <h4 class="mb-1">Surat Tugas</h4>
                        <p class="text-muted mb-0 fs-14">Surat tugas validasi yang sudah diterbitkan untuk Anda.</p>
                    </div>
                </div>

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
@endsection

@push('scripts')
    <script>
        (function ($) {
            // ---- Signature pad ----
            function initSignature(canvas) {
                const ctx = canvas.getContext('2d');
                ctx.lineWidth = 2;
                ctx.lineCap = 'round';
                ctx.lineJoin = 'round';
                ctx.strokeStyle = '#1f3c88';
                let drawing = false;

                function pos(e) {
                    const r = canvas.getBoundingClientRect();
                    const t = e.touches ? e.touches[0] : e;
                    return {
                        x: (t.clientX - r.left) * canvas.width / r.width,
                        y: (t.clientY - r.top) * canvas.height / r.height,
                    };
                }

                canvas.addEventListener('mousedown', function (e) { drawing = true; const p = pos(e); ctx.beginPath(); ctx.moveTo(p.x, p.y); });
                canvas.addEventListener('mousemove', function (e) { if (!drawing) return; const p = pos(e); ctx.lineTo(p.x, p.y); ctx.stroke(); });
                canvas.addEventListener('mouseup', function () { drawing = false; });
                canvas.addEventListener('mouseleave', function () { drawing = false; });
                canvas.addEventListener('touchstart', function (e) { e.preventDefault(); drawing = true; const p = pos(e); ctx.beginPath(); ctx.moveTo(p.x, p.y); });
                canvas.addEventListener('touchmove', function (e) { e.preventDefault(); if (!drawing) return; const p = pos(e); ctx.lineTo(p.x, p.y); ctx.stroke(); });
                canvas.addEventListener('touchend', function () { drawing = false; });

                return ctx;
            }

            function drawTtd(canvas, dataUrl) {
                const ctx = canvas.getContext('2d');
                ctx.clearRect(0, 0, canvas.width, canvas.height);
                if (!dataUrl) return;
                const img = new Image();
                img.onload = function () { ctx.drawImage(img, 0, 0, canvas.width, canvas.height); };
                img.src = dataUrl;
            }

            function setUIState($form) {
                if ($form.find('.js-lock-scope').is(':disabled')) return;

                const isYa = String($form.find('select[name="kesediaan"]').val()) === '1';
                const $alasanWrap = $form.find('.js-wrap-alasan');
                const $alasan = $form.find('textarea[name="alasan"]');
                const $buktiWrap = $form.find('.js-wrap-bukti');
                const $buktiFile = $form.find('.js-bukti-file');

                if (isYa) {
                    $alasanWrap.addClass('d-none');
                    $alasan.prop('disabled', true).val('');
                    $buktiWrap.addClass('d-none');
                    $buktiFile.prop('disabled', true);
                } else {
                    $alasanWrap.removeClass('d-none');
                    $alasan.prop('disabled', false);
                    $buktiWrap.removeClass('d-none');
                    $buktiFile.prop('disabled', false);
                }
            }

            function payload($form) {
                const kesediaan = $form.find('select[name="kesediaan"]').val();
                const canvas = $form.find('.ttd-canvas')[0];
                const ttd = canvas ? canvas.toDataURL('image/png') : '';

                const data = {
                    _token: $form.find('input[name="_token"]').val(),
                    kesediaan: kesediaan,
                    ttd: ttd,
                };

                if (String(kesediaan) === '1') {
                    data.alasan = '';
                    data.bukti = '';
                } else {
                    data.alasan = $form.find('textarea[name="alasan"]').val();
                    data.bukti = $form.find('input[name="bukti"]').val();
                }

                return data;
            }

            function showError($form, message) {
                $form.find('.js-success').addClass('d-none');
                $form.find('.js-error').removeClass('d-none').text(message || 'Gagal menyimpan.');
                window.KsgMotion && KsgMotion.feedback($form.find('.js-error'));
            }

            function showSuccess($form) {
                $form.find('.js-error').addClass('d-none').text('');
                $form.find('.js-success').removeClass('d-none');
                window.KsgMotion && KsgMotion.feedback($form.find('.js-success'));
                window.KsgMotion && KsgMotion.pulse($form.closest('.card'));
                setTimeout(function () {
                    $form.find('.js-success').addClass('d-none');
                }, 1200);
            }

            function save($form) {
                if ($form.find('.js-lock-scope').is(':disabled')) {
                    showError($form, 'Validasi terkunci.');
                    return;
                }

                const canvas = $form.find('.ttd-canvas')[0];
                const ttd = canvas ? canvas.toDataURL('image/png') : '';
                if (!ttd || ttd === 'data:image/png;base64,') {
                    showError($form, 'Silakan isi tanda tangan (TTD) terlebih dahulu.');
                    return;
                }

                const kesediaan = String($form.find('select[name="kesediaan"]').val());
                if (kesediaan === '0') {
                    const bukti = $form.find('input[name="bukti"]').val();
                    if (!bukti || bukti.length < 20) {
                        showError($form, 'Silakan unggah bukti gambar jika memilih Tidak.');
                        return;
                    }
                }

                $.ajax({
                    url: $form.data('url'),
                    method: 'POST',
                    data: payload($form),
                    headers: { Accept: 'application/json' },
                }).done(function () {
                    showSuccess($form);
                }).fail(function (xhr) {
                    const msg =
                        xhr.responseJSON?.message ||
                        (xhr.responseJSON?.errors ? Object.values(xhr.responseJSON.errors).flat().join(' ') : null) ||
                        'Gagal menyimpan.';

                    showError($form, msg);
                });
            }

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

            $(document).on('change', '.js-kesediaan', function () {
                const $form = $(this).closest('form.js-validasi-form');
                const isYa = String($(this).val()) === '1';
                setUIState($form);
                if (!isYa) {
                    window.KsgMotion && KsgMotion.reveal($form.find('.js-wrap-alasan'));
                    window.KsgMotion && KsgMotion.reveal($form.find('.js-wrap-bukti'));
                }
            });

            $(document).on('change', '.js-bukti-file', function () {
                const $form = $(this).closest('form');
                const file = this.files[0];
                if (!file) return;
                compressImage(file, function (b64) {
                    $form.find('input[name="bukti"]').val(b64);
                    $form.find('.js-bukti-preview img').attr('src', b64);
                    $form.find('.js-bukti-preview').removeClass('d-none');
                });
            });

            $(document).on('click', '.btn-clear-bukti', function () {
                const $form = $(this).closest('form');
                $form.find('input[name="bukti"]').val('');
                $form.find('.js-bukti-file').val('');
                $form.find('.js-bukti-preview').addClass('d-none');
            });

            $(document).on('click', '.btn-clear-ttd', function () {
                const $form = $(this).closest('form');
                const canvas = $form.find('.ttd-canvas')[0];
                if (canvas) canvas.getContext('2d').clearRect(0, 0, canvas.width, canvas.height);
            });

            $(document).on('click', '.js-save', function () {
                const $form = $(this).closest('form.js-validasi-form');
                setUIState($form);
                save($form);
            });

            $(function () {
                $('form.js-validasi-form').each(function () {
                    const $form = $(this);
                    const canvas = $form.find('.ttd-canvas')[0];
                    if (canvas) {
                        initSignature(canvas);
                        drawTtd(canvas, $form.attr('data-ttd'));
                    }
                    setUIState($form);
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
                            { data: null, render: function () { return ''; }, orderable: false, searchable: false },
                            { data: 'validasi' },
                            { data: 'surat_keputusan' },
                            { data: 'nomor_st' },
                            { data: 'action', orderable: false, searchable: false },
                        ],
                        language: { emptyTable: 'Belum ada surat tugas validasi untuk Anda.' },
                        drawCallback: function (settings) {
                            this.api().column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
                                cell.innerHTML = settings._iDisplayStart + i + 1;
                            });
                        },
                    });
                }
            });
        })(jQuery);
    </script>
@endpush
