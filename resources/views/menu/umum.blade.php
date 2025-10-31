{{-- resources/views/menu/internal.blade.php --}}
<!DOCTYPE html>
<html lang="zxx">
<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    @include('partial.styles')

    <title>Attendance</title>

    <style>
        /* css */
        /* target specific inputs and general form-control placeholders */
        #name,
        #phone,
        #unsur,
        #instansi,
        #domisili,
        .form-control.bg-transparent::placeholder {
            color: #ffffff;
            opacity: 1; /* ensure full opacity */
        }

        /* vendor prefixes for wider support */
        #name::-webkit-input-placeholder,
        #phone::-webkit-input-placeholder,
        #unsur::-webkit-input-placeholder,
        #instansi::-webkit-input-placeholder,
        #domisili::-webkit-input-placeholder,
        .form-control.bg-transparent::-webkit-input-placeholder {
            color: #ffffff;
            opacity: 1;
        }

        #name:-ms-input-placeholder,
        #phone:-ms-input-placeholder,
        #unsur:-ms-input-placeholder,
        #instansi:-ms-input-placeholder,
        #domisili:-ms-input-placeholder,
        .form-control.bg-transparent:-ms-input-placeholder {
            color: #ffffff;
            opacity: 1;
        }

        #name::-ms-input-placeholder,
        #phone::-ms-input-placeholder,
        #unsur::-ms-input-placeholder,
        #instansi::-ms-input-placeholder,
        #domisili::-ms-input-placeholder,
        .form-control.bg-transparent::-ms-input-placeholder {
            color: #ffffff;
            opacity: 1;
        }

        /* keep focus styles consistent */
        #name:focus,
        #phone:focus,
        #unsur:focus,
        #instansi:focus,
        #domisili:focus {
            color: #ffffff;
        }
        /* Glass card + inputs */
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

        /* white border select */
        #name-select-internal.form-select {
            border: 1px solid #ffffff !important;
            box-shadow: none !important;
            color: #ffffff !important;
            background-color: transparent !important;
            border-radius: 0.5rem;
        }
        #name-select-internal.form-select:focus {
            border-color: #ffffff !important;
            box-shadow: 0 0 0 0.18rem rgba(255,255,255,0.08) !important;
            outline: none;
        }

        /* Canvas styling and responsive heights */
        .signature-pad-wrapper canvas {
            width: 100%;
            height: 220px;
            border-radius: 8px;
            background: rgba(255,255,255,0.02);
            border: 1px solid rgba(255,255,255,0.08);
            touch-action: none; /* improve touch drawing */
            display: block;
        }

        /* Spacing tweaks */
        .contact-us-area { padding-top: 30px; padding-bottom: 30px; }
        .top-title span { color: #9be7ff; }
        .glass-card h2 { color: #e6f7ff; font-size: 1.25rem; }

        /* Mobile adjustments */
        @media (max-width: 576px) {
            .glass-card { padding: 18px; border-radius: 10px; }
            .signature-pad-wrapper canvas { height: 160px; }
            #name-select-internal.form-select { height: 56px !important; font-size: 0.95rem !important; }
            .glass-card h2 { font-size: 1.05rem; }
            .contact-us-area { padding-top: 20px; padding-bottom: 20px; }
            .btn-lg { padding: 12px 16px; font-size: 0.95rem; }
        }
    </style>
</head>
<body data-bs-spy="scroll" data-bs-target="#navbar-example2" data-bs-root-margin="0px 0px -40%" data-bs-smooth-scroll="true" class="scrollspy-example" tabindex="0">
    <!-- Start Banner Area -->
    <div class="page-banner-area" id="home">
        <div class="container position-relative z-1">
            <div class="banner-content text-center mb-0">
                <h1 class="fs-60 mb-0">Presensi Umum BANP</h1>
                <h1 class="fs-20 mb-0">Zoom Meeting | Tittle</h1>
            </div>

            <img src="assets/images/landing/shape-5.png" class="shape-5" alt="shape">
            <img src="assets/images/landing/shape-6.png" class="shape-6" alt="shape">
        </div>
    </div>
    <!-- End Banner Area -->

    <!-- Start Contact Us Area -->
    <div class="contact-us-area position-relative z-2" id="contact">
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="col-12 col-lg-8">
                    <div class="contact-us-form ms-xl-4 mx-auto text-center glass-card">

                        <form id="attendance-sign-form" method="POST" action="{{ route('asesor.attendance.store') }}" class="mx-auto" style="max-width:900px;">
                            @csrf
                            <!-- New fields: Nomor HP, Unsur, Instansi, Domisili -->
                            <div class="row g-3 mb-3 text-start">
                                <div class="col-12 col-md-6">
                                    <label for="name" class="label text-secondary fw-semibold">Nama</label>
                                    <input type="tel" name="name" id="name" class="form-control form-control-lg bg-transparent" value="{{ old('phone') }}" placeholder="Nama Anda Yang menulis Form ini" required style="height:56px; font-size:1rem;">
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
                            <!-- end new fields -->
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

                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Contact Us Area -->

    <!-- Back To Up -->
    <button type="button" id="backtotop">
        <i class="ri-arrow-up-s-line"></i>
    </button>

    <button class="switch-toggle settings-btn dark-btn p-0 bg-transparent position-absolute top-0 d-none" id="switch-toggle">
        <span class="dark"><i class="material-symbols-outlined">light_mode</i></span>
        <span class="light"><i class="material-symbols-outlined">dark_mode</i></span>
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

            // Resize canvas for DPR using clientHeight for responsive height
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

            // call after styles applied
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
                // allow normal submit
            });
        })();
    </script>
</body>
</html>
