<x-guest-layout>
    <div class="container">
        <div class="main-content d-flex flex-column p-0">
            <div class="m-auto m-1230">
                <div class="row align-items-center">
                    <div class="col-lg-6 d-none d-lg-block">
                        <img src="{{ asset('assets/images/forgot.jpg') }}" class="rounded-3" alt="forgot">
                    </div>

                    <div class="col-lg-6">
                        <div class="mw-480 ms-lg-auto">
                            <div class="d-inline-block mb-4">
                                <img src="{{ asset('assets/logo_BANPDMJATIM.png') }}" class="rounded-3 for-light-logo" alt="logo">
                                <img src="{{ asset('assets/images/white-logo.svg') }}" class="rounded-3 for-dark-logo" alt="logo">
                            </div>

                            <h3 class="fs-28 mb-2">{{ __('Lupa Password?') }}</h3>
                            <p class="fw-medium fs-16 mb-4">
                                {{ __("Masukan email kamu yang sudah terdaftar di MAS-JATIM") }}<br>
                            </p>

                            {{-- Session Status --}}
                            <x-auth-session-status class="mb-4" :status="session('status')" />

                            <form method="POST" action="{{ route('password.email') }}">
                                @csrf

                                <div class="form-group mb-4">
                                    <label for="email" class="label text-secondary">{{ __('Email') }}</label>
                                    <x-text-input id="email" class="form-control h-[55px]" type="email" name="email" :value="old('email')" required autofocus placeholder="example@BANPDM-JAWATIMUR.com" />
                                    <x-input-error :messages="$errors->get('email')" class="mt-2" />
                                </div>

                                <div class="form-group mb-4">
                                    <button type="submit" class="btn btn-primary fw-medium py-2 px-3 w-100 d-flex align-items-center justify-content-center">
                                        <i class="material-symbols-outlined text-white fs-20 me-2">email</i>
                                        <span>{{ __('Email Password Reset Link') }}</span>
                                    </button>
                                </div>

                                <div class="form-group">
                                    <p class="mb-0">
                                        <a href="{{ route('login') }}" class="fw-medium text-primary text-decoration-none">{{ __('Back to login') }}</a>
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // optional: hide preloader after load if needed
            // window.addEventListener('load', () => {
            //     const pre = document.getElementById('preloader');
            //     if (pre) pre.style.display = 'none';
            // });
        </script>
    @endpush
</x-guest-layout>
