<x-guest-layout>
    {{-- Session Status --}}
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <div class="container">
        <div class="main-content d-flex flex-column p-0">
            <div class="m-auto m-1230">
                <div class="row align-items-center">
                    <div class="col-sm-6 d-none d-lg-block">
                        <img src="{{ asset('assets/images/sign-in.jpg') }}" class="rounded-3" alt="login">
                    </div>
                    <div class="col-lg-6">
                        <div class="mw-480 ms-lg-auto">
                            <div class="d-inline-block mb-4">
                                <img src="{{ asset('assets/logo_BANPDMJATIM.png') }}" class="rounded-3 for-light-logo" alt="logo">
                                <img src="{{ asset('assets/images/white-logo.svg') }}" class="rounded-3 for-dark-logo" alt="logo">
                            </div>

                            <h3 class="fs-28 mb-2">{{ __('Selamat Datang di MAS-JATIM !') }}</h3>
                            <p class="fw-medium fs-16 mb-4">{{ __('Management Akreditasi Sistem - Jawa Timur') }}</p>

                            <form method="POST" action="{{ route('login') }}">
                                @csrf

                                <div class="form-group mb-4">
                                    <label for="email" class="form-label d-block mb-2">{{ __('Email') }}</label>
                                    <x-text-input id="email" class="form-control h-[55px] rounded-md px-[17px] w-100" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="example@trezo.com" />
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                </div>

                                <div class="form-group mb-4 position-relative" id="passwordHideShow">
                                    <label for="password" class="form-label d-block mb-2">{{ __('Password') }}</label>
                                    <x-text-input id="password" class="form-control h-[55px] rounded-md px-[17px] w-100" type="password" name="password" required autocomplete="current-password" placeholder="Type password" />
                                    <button type="button" class="btn btn-sm position-absolute" id="toggleButton" style="right:16px;bottom:16px;">
                                        <i class="ri-eye-off-line"></i>
                                    </button>
                                    <x-input-error :messages="$errors->get('password')" class="mt-2" />
                                </div>

                                <div class="form-group mb-3">
                                    <div class="form-check">
                                        {{-- ensure checkbox submits a value and keeps checked state after validation error --}}
                                        <input id="remember_me" type="checkbox" class="form-check-input" name="remember" value="1" {{ old('remember') ? 'checked' : '' }}>
                                        <label for="remember_me" class="form-check-label ms-2 text-muted">{{ __('Remember me') }}</label>
                                    </div>
                                </div>

                                <div class="form-group mb-3 d-flex align-items-center justify-content-between gap-2">
                                    @if (Route::has('password.request'))
                                        <a href="{{ route('password.request') }}" class="text-primary fw-semibold">{{ __('Forgot Password?') }}</a>
                                    @endif

                                        <button type="submit" class="btn btn-primary d-flex align-items-center justify-content-center gap-2 w-100 py-2" aria-label="{{ __('Log In') }}">
                                            <i class="material-symbols-outlined" aria-hidden="true">login</i>
                                            <span>{{ __('Log In') }}</span>
                                        </button>
                                </div>
                            </form>

                            {{-- register link intentionally removed --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const form = document.querySelector('form');
                if (!form) return;
                let submitted = false;

                form.addEventListener('submit', function (e) {
                    if (submitted) {
                        e.preventDefault();
                        return;
                    }
                    submitted = true;
                    const btn = form.querySelector('button[type="submit"]');
                    if (btn) {
                        btn.disabled = true;
                        btn.setAttribute('aria-disabled', 'true');
                    }
                });

                // tambahan: jika user menekan Enter cepat-2, cegah submit kedua
                form.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter' && submitted) {
                        e.preventDefault();
                    }
                });

                // show/hide password toggle
                const toggleButton = document.getElementById('toggleButton');
                const passwordInput = document.getElementById('password');
                if (toggleButton && passwordInput) {
                    toggleButton.addEventListener('click', function (e) {
                        e.preventDefault();
                        const icon = toggleButton.querySelector('i');
                        if (passwordInput.type === 'password') {
                            passwordInput.type = 'text';
                            if (icon) {
                                icon.classList.remove('ri-eye-off-line');
                                icon.classList.add('ri-eye-line');
                            }
                        } else {
                            passwordInput.type = 'password';
                            if (icon) {
                                icon.classList.remove('ri-eye-line');
                                icon.classList.add('ri-eye-off-line');
                            }
                        }
                    });
                }
            });
        </script>
    @endpush
</x-guest-layout>
