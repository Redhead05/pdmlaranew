<x-guest-layout>
    <div class="container">
        <div class="main-content d-flex flex-column p-0">
            <div class="m-auto m-1230">
                <div class="row align-items-center">
                    <div class="col-lg-6 d-none d-lg-block">
                        <img src="{{ asset('assets/images/reset.jpg') }}" class="rounded-3" alt="reset">
                    </div>

                    <div class="col-lg-6">
                        <div class="mw-480 ms-lg-auto">
                            <div class="d-inline-block mb-4">
                                <img src="{{ asset('assets/images/logo.svg') }}" class="rounded-3 for-light-logo" alt="logo">
                                <img src="{{ asset('assets/images/white-logo.svg') }}" class="rounded-3 for-dark-logo" alt="logo">
                            </div>

                            <h3 class="fs-28 mb-2">{{ __('Reset Password?') }}</h3>
                            <p class="fw-medium fs-16 mb-4">
                                {{ __('Enter your new password and confirm it another time in the field below.') }}
                            </p>

                            <form method="POST" action="{{ route('password.store') }}">
                                @csrf

                                <!-- Password Reset Token -->
                                <input type="hidden" name="token" value="{{ $request->route('token') }}">

                                <!-- Email Address -->
                                <div class="form-group mb-3">
                                    <label class="label text-secondary" for="email">{{ __('Email') }}</label>
                                    <input
                                        id="email"
                                        type="email"
                                        name="email"
                                        class="form-control @error('email') is-invalid @enderror"
                                        value="{{ old('email', $request->email) }}"
                                        required
                                        autofocus
                                        autocomplete="username"
                                        placeholder="{{ __('Enter your email') }}"
                                    >
                                    @error('email')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- New Password -->
                                <div class="form-group mb-3">
                                    <label class="label text-secondary" for="password">{{ __('New Password') }}</label>
                                    <input
                                        id="password"
                                        type="password"
                                        name="password"
                                        class="form-control @error('password') is-invalid @enderror"
                                        required
                                        autocomplete="new-password"
                                        placeholder="{{ __('Type your new password') }}"
                                    >
                                    @error('password')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <!-- Confirm Password -->
                                <div class="form-group mb-3">
                                    <label class="label text-secondary" for="password_confirmation">{{ __('Confirm Password') }}</label>
                                    <input
                                        id="password_confirmation"
                                        type="password"
                                        name="password_confirmation"
                                        class="form-control @error('password_confirmation') is-invalid @enderror"
                                        required
                                        autocomplete="new-password"
                                        placeholder="{{ __('Type your confirm password') }}"
                                    >
                                    @error('password_confirmation')
                                    <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group mb-3">
                                    <button type="submit" class="btn btn-primary fw-medium py-2 px-3 w-100">
                                        <div class="d-flex align-items-center justify-content-center py-1">
                                            <i class="material-symbols-outlined text-white fs-20 me-2">autorenew</i>
                                            <span>{{ __('Reset Password') }}</span>
                                        </div>
                                    </button>
                                </div>

                                <div class="form-group">
                                    <p>
                                        {{ __('Back to') }}
                                        <a href="{{ route('login') }}" class="fw-medium text-primary text-decoration-none">
                                            {{ __('Login') }}
                                        </a>
                                    </p>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
