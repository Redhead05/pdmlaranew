<!DOCTYPE html>
<html lang="zxx">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    @include('partial.styles')

    <title>Thank You - {{ $attendance->title }}</title>

    <style>
        .glass-card {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(8px) saturate(120%);
            -webkit-backdrop-filter: blur(8px) saturate(120%);
            border-radius: 12px;
            box-shadow: 0 8px 24px rgba(2, 6, 23, 0.32);
            padding: 40px;
            text-align: center;
        }

        .contact-us-area { padding-top: 50px; padding-bottom: 50px; }
        .success-icon {
            font-size: 80px;
            color: #4CAF50;
            margin-bottom: 20px;
        }
        .glass-card h2 { color: #e6f7ff; font-size: 2rem; margin-bottom: 15px; }
        .glass-card p { color: #cfe8f3; font-size: 1.1rem; }

        @media (max-width: 576px) {
            .glass-card { padding: 30px 20px; }
            .success-icon { font-size: 60px; }
            .glass-card h2 { font-size: 1.5rem; }
            .glass-card p { font-size: 1rem; }
        }
    </style>
</head>
<body data-bs-spy="scroll" data-bs-target="#navbar-example2" data-bs-root-margin="0px 0px -40%" data-bs-smooth-scroll="true" class="scrollspy-example" tabindex="0">
    <!-- Start Banner Area -->
    <div class="page-banner-area" id="home">
        <div class="container position-relative z-1">
            <div class="banner-content text-center mb-0">
                <h1 class="fs-60 mb-0">Thank You!</h1>
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
                <div class="col-12 col-lg-6">
                    <div class="glass-card">
                        <div class="success-icon">
                            <i class="ri-checkbox-circle-line"></i>
                        </div>
                        <h2>Attendance Submitted Successfully!</h2>
                        <p>Your attendance for <strong>{{ $attendance->title }}</strong> has been recorded.</p>
                        <p class="mt-4">You may now close this window.</p>
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
</body>
</html>
