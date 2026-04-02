@extends('app.layout')
@section('title', 'Kesanggupan')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@section('content')
    <div class="container-fluid">
        <div class="main-content d-flex flex-column">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="mb-0">Kesanggupan</h1>
            </div>

            @if ($kesanggupans->isEmpty())
                <div class="alert alert-info">Belum ada form kesanggupan untuk akun ini.</div>
            @else
                <div class="row g-3">
                    @foreach ($kesanggupans as $kesanggupan)
                        @php
                            $suffix = $kesanggupan->id;
                            $isYa = (bool) old('kesediaan', $kesanggupan->kesediaan);

                            $startDate = $kesanggupan->tahap?->start_date;
                            $endDate = $kesanggupan->tahap?->end_date;

                            $now = now();
                            $isNotStarted = $startDate && $now->lt($startDate);
                            $isExpired = $endDate && $now->gt($endDate);
                            $isLocked = $isNotStarted || $isExpired;

                            $allowedKesanggupan = collect($kesanggupan->tahap?->allowed_kesanggupan ?? [])
                                ->map(fn ($v) => (int) $v)
                                ->filter(fn ($v) => $v > 0)
                                ->unique()
                                ->values()
                                ->all();
                        @endphp

                        <div class="col-xl-4 col-xxl-3 col-sm-6">
                            <div class="card bg-white border-0 rounded-3 mb-4 transition-y">
                                <div class="card-body p-4">
                                    <div class="position-relative mb-3">
                                        <a href="#">
                                            <img src="{{ asset('assets/images/event-1.jpg') }}" class="rounded-3 img-fluid" alt="event">
                                        </a>

                                        <div class="mt-3">
                                            <form class="js-kesanggupan-form"
                                                  data-url="{{ route('asesor.kesanggupan.update', $kesanggupan) }}"
                                                  data-start="{{ $startDate?->toIso8601String() }}"
                                                  data-end="{{ $endDate?->toIso8601String() }}">
                                                @csrf
                                                @method('PUT')

                                                <div class="alert alert-success d-none js-success" role="alert">Data berhasil disimpan.</div>
                                                <div class="alert alert-danger d-none js-error" role="alert"></div>

                                                <div class="mb-2 small text-secondary fs-15">
                                                    Tahap: <span class="fw-semibold">{{ $kesanggupan->tahap?->tahap ?? '-' }}</span>
                                                </div>

                                                @if($isLocked)
                                                    <div class="alert alert-warning py-2 small mb-3">
                                                        @if($isNotStarted)
                                                            <span class="badge text-bg-secondary">Belum dibuka</span>
                                                        @elseif($isExpired)
                                                            <span class="badge text-bg-danger">Ditutup</span>
                                                        @else
                                                            <span class="badge text-bg-success">Dibuka</span>
                                                        @endif
                                                    </div>
                                                @endif

                                                <div class="mb-3 p-2 rounded-3 border bg-light">
                                                    <div class="d-flex justify-content-between align-items-center mt-1">
                                                        <span class="small text-muted">Kesanggupan</span>
                                                        <span class="small fw-semibold js-countdown" data-state="{{ $isLocked ? 'locked' : 'open' }}">
                                                            @if($isNotStarted)
                                                                Belum dibuka
                                                            @elseif($isExpired)
                                                                Ditutup
                                                            @else
                                                                --:--:--
                                                            @endif
                                                        </span>
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
                                                            <label for="kesediaan-{{ $suffix }}" class="form-label fw-semibold">Kesediaan</label>
                                                            <select id="kesediaan-{{ $suffix }}" name="kesediaan" class="form-select js-kesediaan text-secondary" required>
                                                                <option value="1" @selected($isYa)>Ya</option>
                                                                <option value="0" @selected(!$isYa)>Tidak</option>
                                                            </select>
                                                        </div>

                                                        <div class="mb-3 js-wrap-kesanggupan @if(!$isYa) d-none @endif">
                                                            <label for="kesanggupan-{{ $suffix }}" class="form-label fw-semibold">Kesanggupan</label>
                                                            <select id="kesanggupan-{{ $suffix }}" name="kesanggupan"
                                                                    class="form-select js-kesanggupan text-secondary"
                                                                    @if(!$isYa) disabled @endif>
                                                                <option value="" @selected(blank(old('kesanggupan_'.$suffix, $kesanggupan->kesanggupan))) disabled>Pilih kesanggupan...</option>
                                                                @foreach($allowedKesanggupan as $opt)
                                                                    <option value="{{ $opt }}" @selected((string) old('kesanggupan_'.$suffix, $kesanggupan->kesanggupan) === (string) $opt)>
                                                                        {{ $opt }}
                                                                    </option>
                                                                @endforeach
                                                            </select>
                                                            <div class="form-text">Pilihan sesuai pengaturan admin pada tahap ini.</div>
                                                        </div>

                                                        <div class="mb-3 js-wrap-alasan @if($isYa) d-none @endif">
                                                            <label for="alasan-{{ $suffix }}" class="form-label fw-semibold">Alasan</label>
                                                            <textarea id="alasan-{{ $suffix }}" name="alasan" class="form-control js-alasan"
                                                                      rows="3" @if($isYa) disabled @endif
                                                                      placeholder="Wajib diisi jika memilih Tidak">{{ old('alasan_'.$suffix, $kesanggupan->alasan) }}</textarea>
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
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>

    <script>
        (function ($) {
            function setUIState($form) {
                // kalau form terkunci, jangan ubah apapun
                if ($form.find('.js-lock-scope').is(':disabled')) return;

                const isYa = String($form.find('select[name="kesediaan"]').val()) === '1';

                const $wrapKesanggupan = $form.find('.js-wrap-kesanggupan');
                const $kesanggupan = $form.find('[name="kesanggupan"]');

                const $wrapAlasan = $form.find('.js-wrap-alasan');
                const $alasan = $form.find('textarea[name="alasan"]');

                if (isYa) {
                    $wrapKesanggupan.removeClass('d-none');
                    $kesanggupan.prop('disabled', false);

                    $wrapAlasan.addClass('d-none');
                    $alasan.prop('disabled', true).val('');
                } else {
                    $wrapKesanggupan.addClass('d-none');
                    $kesanggupan.prop('disabled', true).val('');

                    $wrapAlasan.removeClass('d-none');
                    $alasan.prop('disabled', false);
                }
            }

            function payload($form) {
                const kesediaan = $form.find('select[name="kesediaan"]').val();

                const data = {
                    _token: $form.find('input[name="_token"]').val(),
                    _method: 'PUT',
                    kesediaan: kesediaan,
                };

                if (String(kesediaan) === '1') {
                    data.kesanggupan = $form.find('[name="kesanggupan"]').val();
                    data.alasan = '';
                } else {
                    data.kesanggupan = '';
                    data.alasan = $form.find('textarea[name="alasan"]').val();
                }

                return data;
            }

            function showError($form, message) {
                $form.find('.js-success').addClass('d-none');
                $form.find('.js-error').removeClass('d-none').text(message || 'Gagal menyimpan.');
            }

            function showSuccess($form) {
                $form.find('.js-error').addClass('d-none').text('');
                $form.find('.js-success').removeClass('d-none');
                setTimeout(function () {
                    $form.find('.js-success').addClass('d-none');
                }, 1200);
            }

            function save($form) {
                // kalau terkunci, stop
                if ($form.find('.js-lock-scope').is(':disabled')) {
                    showError($form, 'Form dikunci.');
                    return;
                }

                $.ajax({
                    url: $form.data('url'),
                    method: 'POST',
                    data: payload($form),
                    headers: { Accept: 'application/json' }
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

            $(document).on('change', '.js-kesediaan', function () {
                const $form = $(this).closest('form.js-kesanggupan-form');
                setUIState($form);
            });

            $(document).on('click', '.js-save', function () {
                const $form = $(this).closest('form.js-kesanggupan-form');
                setUIState($form);
                save($form);
            });

            function pad2(n) {
                return String(n).padStart(2, '0');
            }

            function formatHMS(totalSeconds) {
                const s = Math.max(0, parseInt(totalSeconds, 10) || 0);
                const h = Math.floor(s / 3600);
                const m = Math.floor((s % 3600) / 60);
                const sec = s % 60;
                return `${pad2(h)}:${pad2(m)}:${pad2(sec)}`;
            }

            function lockForm($form) {
                $form.find('.js-lock-scope').prop('disabled', true);
                $form.find('.js-save').prop('disabled', true).addClass('disabled');
            }

            function updateCountdown() {
                $('.js-kesanggupan-form').each(function () {
                    const $form = $(this);
                    const start = $form.data('start');
                    const end = $form.data('end');
                    const $count = $form.find('.js-countdown');

                    if (!$count.length) return;

                    if (!start || !end || typeof dayjs === 'undefined') {
                        $count.text('-');
                        return;
                    }

                    const now = dayjs();
                    const startAt = dayjs(start);
                    const endAt = dayjs(end);

                    if (now.isBefore(startAt)) {
                        const diff = startAt.diff(now, 'second');
                        $count.text('Belum dibuka (' + formatHMS(diff) + ')');
                        lockForm($form);
                        return;
                    }

                    if (now.isAfter(endAt)) {
                        $count.text('Ditutup');
                        lockForm($form);
                        return;
                    }

                    // sedang dibuka
                    const left = endAt.diff(now, 'second');
                    $count.text(formatHMS(left));
                });
            }

            $(function () {
                // init state pertama
                $('form.js-kesanggupan-form').each(function () {
                    setUIState($(this));
                });

                updateCountdown();
                setInterval(updateCountdown, 1000);
            });
        })(jQuery);
    </script>
@endpush
