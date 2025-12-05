
<!DOCTYPE html>
<html lang="zxx">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    @include('partial.styles')

    <title>Attendance</title>

    <style>
        /* Force dark mode overrides */
        body.dark-mode {
            background-color: #071024 !important;
            color: #e6f7ff !important;
        }
        body.dark-mode .page-banner-area {
            background: linear-gradient(180deg, rgba(3,10,23,0.85), rgba(3,10,23,0.95));
        }
        body.dark-mode a, body.dark-mode .text-secondary {
            color: #9be7ff !important;
        }
        body.dark-mode .glass-card {
            background: rgba(255,255,255,0.03) !important;
            border-color: rgba(255,255,255,0.06) !important;
            color: #e6f7ff !important;
        }
        .signature-pad-wrapper canvas {
            background: rgba(255,255,255,0.02);
            border: 1px solid rgba(255,255,255,0.08);
        }
        #switch-toggle { display: none !important; }
    </style>

    <style>
        /* placeholders / glass-card */
        #name,
        #phone,
        #unsur,
        #instansi,
        #domisili,
        .form-control.bg-transparent::placeholder {
            color: #ffffff;
            opacity: 1;
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(8px) saturate(120%);
            -webkit-backdrop-filter: blur(8px) saturate(120%);
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(2, 6, 23, 0.32);
            padding: 28px;
        }
        .glass-card .form-control,
        .glass-card .form-select,
        .glass-card textarea {
            background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.08);
            color: #fff;
        }

        /* Typed text / caret - scoped to public form */
        #attendance-sign-form .form-control,
        #attendance-sign-form .form-select,
        #attendance-sign-form textarea {
          color: #ffffff !important;
          caret-color: #ffffff !important;
        }

        /* Placeholder color */
        #attendance-sign-form .form-control::placeholder,
        #attendance-sign-form textarea::placeholder,
        #attendance-sign-form .form-select::placeholder {
          color: rgba(255,255,255,0.7) !important;
          opacity: 1 !important;
        }

        /* Focus visual */
        #attendance-sign-form .form-control:focus,
        #attendance-sign-form .form-select:focus,
        #attendance-sign-form textarea:focus {
          color: #ffffff !important;
          outline: none;
          box-shadow: 0 0 0 0.18rem rgba(255,255,255,0.06) !important;
          border-color: rgba(255,255,255,0.12) !important;
        }

        /* Chrome autofill (scoped to form) */
        #attendance-sign-form input:-webkit-autofill,
        #attendance-sign-form textarea:-webkit-autofill,
        #attendance-sign-form select:-webkit-autofill {
          -webkit-text-fill-color: #ffffff !important;
          box-shadow: 0 0 0 1000px rgba(7,16,36,1) inset !important;
          transition: background-color 5000s ease-in-out 0s !important;
        }

        /* Ensure selects readable */
        #attendance-sign-form select {
          background-color: transparent !important;
          color: #ffffff !important;
        }
    </style>
</head>
<body class="scrollspy-example dark-mode" data-bs-spy="scroll" data-bs-target="#navbar-example2" data-bs-root-margin="0px 0px -40%" data-bs-smooth-scroll="true" tabindex="0">

    @php
        use Carbon\Carbon;
        $now = Carbon::now(config('app.timezone'));
        try {
            $endDate = Carbon::parse($attendance->end_date, config('app.timezone'));
        } catch (\Exception $e) {
            $endDate = null;
        }
    @endphp

    <!-- Start Banner Area -->
    <div class="page-banner-area" id="home">
        <div class="container position-relative z-1">
            <div class="banner-content text-center mb-0">
                <h1 class="fs-60 mb-0">Presensi Umum BANP</h1>
                <h1 class="fs-20 mb-0">{{ $attendance->title }} | {!! ($attendance->description ?? 'Description') !!}</h1>
            </div>

            <img src="{{ asset('assets/images/landing/shape-5.png') }}" class="shape-5" alt="shape">
            <img src="{{ asset('assets/images/landing/shape-6.png') }}" class="shape-6" alt="shape">
        </div>
    </div>
    <!-- End Banner Area -->

    <!-- Start Contact Us Area -->
    <div class="contact-us-area position-relative z-2" id="contact">
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="col-12 col-lg-8">
                    <div class="contact-us-form ms-xl-4 mx-auto text-center glass-card">
                        @include('partial.flash')
                        @if($endDate && $now->lte($endDate))
                            <form id="attendance-sign-form" method="POST" action="{{ route('attendance.public.store') }}" class="mx-auto" style="max-width:900px;">
                                @csrf
                                <div class="row g-3 mb-3 text-start">
                                    <div class="col-12 col-md-6">
                                        <label for="name" class="label text-secondary fw-semibold">Nama</label>
                                        <input type="text" name="name" id="name" class="form-control form-control-lg bg-transparent" value="{{ old('name') }}" placeholder="Nama Anda Yang menulis Form ini" required style="height:56px; font-size:1rem;">
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label for="phone" class="label text-secondary fw-semibold">Nomor HP</label>
                                        <input type="tel" name="phone" id="phone" class="form-control form-control-lg bg-transparent" value="{{ old('phone') }}" placeholder="0812xxxxxxx" required style="height:56px; font-size:1rem;">
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label for="unsur" class="label text-secondary fw-semibold">Unsur</label>
                                        <input type="text" name="unsur" id="unsur" class="form-control form-control-lg bg-transparent" value="{{ old('unsur') }}" placeholder="Unsur" required style="height:56px; font-size:1rem;">
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label for="instansi" class="label text-secondary fw-semibold">Instansi</label>
                                        <input type="text" name="instansi" id="instansi" class="form-control form-control-lg bg-transparent" value="{{ old('instansi') }}" placeholder="Instansi / Department" required style="height:56px; font-size:1rem;">
                                    </div>

                                    <div class="col-12 col-md-6">
                                        <label for="domisili" class="label text-secondary fw-semibold">Domisili</label>
                                        <input type="text" name="domisili" id="domisili" class="form-control form-control-lg bg-transparent" value="{{ old('domisili') }}" placeholder="Kota / Kabupaten" required style="height:56px; font-size:1rem;">
                                    </div>
                                </div>

                                <div class="form-group mb-3 text-start">
                                    <label class="label text-secondary fw-semibold">Signature</label>
                                    <div class="signature-pad-wrapper mb-2">
                                        <canvas id="signature-pad"></canvas>
                                    </div>
                                    <div class="d-flex flex-wrap gap-2">
                                        <button type="button" id="clear-signature" class="btn btn-secondary btn-lg">Clear</button>
                                    </div>
                                </div>

                                <input type="hidden" name="signature" id="signature-input">
                                <input type="hidden" name="attendance_id" value="{{ $attendance->id ?? '' }}">

                                <div class="form-group mb-0 mt-3">
                                    <button type="submit" class="btn btn-primary py-3 px-4 w-100 btn-lg">
                                        <i class="ri-save-line fs-18 text-white position-relative top-1 me-1"></i>
                                        <span>Save</span>
                                    </button>
                                </div>
                            </form>
                        @else
                            <div class="text-center text-danger mt-4">Absensi Sudah di Tutup.</div>
                        @endif

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Contact Us Area -->

    <button type="button" id="backtotop">
        <i class="ri-arrow-up-s-line"></i>
    </button>

    @include('partial.scripts')
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
    <script>
        (function () {
            const form = document.getElementById('attendance-sign-form');
            const canvas = document.getElementById('signature-pad');
            const input = document.getElementById('signature-input');
            const clearBtn = document.getElementById('clear-signature');

            if (!canvas || !form) return;

            function resizeCanvas() {
                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                const width = canvas.clientWidth;
                const height = canvas.clientHeight || 220;
                canvas.width = Math.floor(width * ratio);
                canvas.height = Math.floor(height * ratio);
                canvas.style.height = height + 'px';
                const ctx = canvas.getContext('2d');
                ctx.scale(ratio, ratio);
            }

            setTimeout(resizeCanvas, 50);
            window.addEventListener('resize', resizeCanvas);

            const signaturePad = new SignaturePad(canvas, { backgroundColor: 'rgba(255,255,255,0)' });

            clearBtn.addEventListener('click', () => {
                signaturePad.clear();
                input.value = '';
            });

            form.addEventListener('submit', function (e) {
                const attendanceId = form.querySelector('input[name="attendance_id"]').value;
                if (!attendanceId) {
                    e.preventDefault();
                    alert('Attendance ID is missing.');
                    return;
                }

                if (signaturePad.isEmpty()) {
                    e.preventDefault();
                    alert('Please provide a signature before saving.');
                    return;
                }

                input.value = signaturePad.toDataURL('image/png');
            });
        })();
    </script>
</body>
</html>
