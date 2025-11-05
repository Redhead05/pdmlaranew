<!DOCTYPE html>
<html lang="zxx">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    @include('partial.styles')

    <title>{{ $attendance->title }} - Attendance Form</title>

    <style>
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

        /* Placeholder styling */
        .form-control.bg-transparent::placeholder {
            color: #ffffff;
            opacity: 0.7;
        }

        .form-control.bg-transparent::-webkit-input-placeholder {
            color: #ffffff;
            opacity: 0.7;
        }

        .form-control.bg-transparent:focus {
            color: #ffffff;
        }

        /* Canvas styling */
        .signature-pad-wrapper canvas {
            width: 100%;
            height: 220px;
            border-radius: 8px;
            background: rgba(255,255,255,0.02);
            border: 1px solid rgba(255,255,255,0.08);
            touch-action: none;
            display: block;
        }

        .contact-us-area { padding-top: 30px; padding-bottom: 30px; }
        .top-title span { color: #9be7ff; }
        .glass-card h2 { color: #e6f7ff; font-size: 1.25rem; }

        .user-info {
            background: rgba(255,255,255,0.05);
            border: 1px solid rgba(255,255,255,0.1);
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 20px;
        }

        @media (max-width: 576px) {
            .glass-card { padding: 18px; border-radius: 10px; }
            .signature-pad-wrapper canvas { height: 160px; }
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
                <h1 class="fs-60 mb-0">{{ $attendance->title }}</h1>
                <h1 class="fs-20 mb-0">{{ $attendance->description }}</h1>
            </div>

            <img src="/assets/images/landing/shape-5.png" class="shape-5" alt="shape">
            <img src="/assets/images/landing/shape-6.png" class="shape-6" alt="shape">
        </div>
    </div>
    <!-- End Banner Area -->

    <!-- Start Contact Us Area -->
    <div class="contact-us-area position-relative z-2" id="contact">
        <div class="container">
            <div class="row justify-content-center align-items-center">
                <div class="col-12 col-lg-8">
                    <div class="contact-us-form ms-xl-4 mx-auto text-center glass-card">
                        @if ($errors->any())
                            <div class="alert alert-danger text-start mb-4">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <!-- Display authenticated user info -->
                        <div class="user-info text-start">
                            <p class="mb-1 text-white"><strong>Name:</strong> {{ Auth::user()->name }}</p>
                            <p class="mb-0 text-white"><strong>Email:</strong> {{ Auth::user()->email }}</p>
                        </div>

                        <form id="attendance-sign-form" method="POST" action="{{ route('attendance.submit', $attendance) }}" class="mx-auto" style="max-width:900px;">
                            @csrf

                            <div class="form-group mb-3 text-start">
                                <label for="notes" class="label text-secondary fw-semibold">Notes (Optional)</label>
                                <textarea name="notes" id="notes" class="form-control form-control-lg bg-transparent" rows="3" placeholder="Add any additional notes here...">{{ old('notes') }}</textarea>
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

                            <div class="form-group mb-0 mt-3">
                                <button type="submit" class="btn btn-primary py-3 px-4 w-100 btn-lg">
                                    <i class="ri-save-line fs-18 text-white position-relative top-1 me-1"></i>
                                    <span>Submit</span>
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
                if (signaturePad.isEmpty()) {
                    e.preventDefault();
                    alert('Please provide a signature before submitting.');
                    return;
                }

                input.value = signaturePad.toDataURL('image/png');
            });
        })();
    </script>
</body>
</html>
