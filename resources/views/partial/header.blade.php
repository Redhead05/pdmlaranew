<!-- File: `resources/views/partial/header.blade.php` -->
<div class="container-fluid">
    <div class="main-content d-flex flex-column">
        <header class="header-area bg-white mb-4 rounded-bottom-15" id="header-area">
    <div class="row align-items-center">
        <div class="col-lg-4 col-sm-6">
            <div class="left-header-content">
                <ul class="d-flex align-items-center ps-0 mb-0 list-unstyled justify-content-center justify-content-sm-start">
                    <li>
                        <button class="header-burger-menu bg-transparent p-0 border-0" id="header-burger-menu">
                            <span class="material-symbols-outlined">menu</span>
                        </button>
                    </li>
                    <li>
                        <form class="src-form position-relative">
                            <input type="text" class="form-control" placeholder="Search here.....">
                            <button type="submit" class="src-btn position-absolute top-50 end-0 translate-middle-y bg-transparent p-0 border-0">
                                <span class="material-symbols-outlined">search</span>
                            </button>
                        </form>
                    </li>
                    <li>
                        <div class="dropdown notifications apps">
                            <button class="btn btn-secondary border-0 p-0 position-relative" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="material-symbols-outlined">apps</span>
                            </button>
                            <div class="dropdown-menu dropdown-lg p-0 border-0 py-4 px-3 max-h-312" data-simplebar>
                                <div class="notification-menu d-flex flex-wrap justify-content-between gap-4">
                                    <!-- small app links (keep as in calendar design) -->
                                    <a href="https://www.figma.com/" target="_blank" class="dropdown-item p-0 text-center">
                                        <img src="{{ asset('assets/images/figma.svg') }}" class="wh-25" alt="figma">
                                        <span>Figma</span>
                                    </a>
                                    <a href="https://www.github.com/" target="_blank" class="dropdown-item p-0 text-center">
                                        <img src="{{ asset('assets/images/github.svg') }}" class="wh-25" alt="github">
                                        <span>Github</span>
                                    </a>
                                    <!-- add more links as needed -->
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>

        <div class="col-lg-8 col-sm-6">
            <div class="right-header-content mt-2 mt-sm-0">
                <ul class="d-flex align-items-center justify-content-center justify-content-sm-end ps-0 mb-0 list-unstyled">
                    <li class="header-right-item">
                        <div class="light-dark">
                            <button class="switch-toggle settings-btn dark-btn p-0 bg-transparent border-0" id="switch-toggle">
                                <span class="dark"><i class="material-symbols-outlined">light_mode</i></span>
                                <span class="light"><i class="material-symbols-outlined">dark_mode</i></span>
                            </button>
                        </div>
                    </li>

                    <li class="header-right-item">
                        <div class="dropdown notifications language">
                            <button class="btn btn-secondary dropdown-toggle border-0 p-0 position-relative" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="material-symbols-outlined">translate</span>
                            </button>
                            <div class="dropdown-menu dropdown-lg p-0 border-0 dropdown-menu-end">
                                <span class="fw-semibold fs-15 text-secondary title">Choose Language</span>
                                <div class="max-h-275" data-simplebar>
                                    <!-- language items -->
                                    <div class="notification-menu">
                                        <a href="javascript:void(0);" class="dropdown-item">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <img src="{{ asset('assets/images/usa.svg') }}" class="wh-30 rounded-circle" alt="usa">
                                                </div>
                                                <div class="flex-grow-1 ms-2">
                                                    <span class="text-secondary fw-medium fs-14">English</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>

                    <li class="header-right-item">
                        <button class="fullscreen-btn bg-transparent p-0 border-0" id="fullscreen-button">
                            <i class="material-symbols-outlined text-body">fullscreen</i>
                        </button>
                    </li>

                    <li class="header-right-item">
                        <div class="dropdown notifications noti">
                            <button class="btn btn-secondary border-0 p-0 position-relative badge" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <span class="material-symbols-outlined">notifications</span>
                            </button>
                            <div class="dropdown-menu dropdown-lg p-0 border-0 p-0 dropdown-menu-end">
                                <div class="d-flex justify-content-between align-items-center title">
                                    <span class="fw-semibold fs-15 text-secondary">Notifications <span class="fw-normal text-body fs-14">(03)</span></span>
                                    <button class="p-0 m-0 bg-transparent border-0 fs-14 text-primary">Clear All</button>
                                </div>
                                <div class="max-h-217" data-simplebar>
                                    <!-- notifications list -->
                                    <div class="notification-menu">
                                        <a href="notification.html" class="dropdown-item">
                                            <div class="d-flex align-items-center">
                                                <div class="flex-shrink-0">
                                                    <i class="material-symbols-outlined text-primary">sms</i>
                                                </div>
                                                <div class="flex-grow-1 ms-3">
                                                    <p>You have requested to <span class="fw-semibold">withdrawal</span></p>
                                                    <span class="fs-13">2 hrs ago</span>
                                                </div>
                                            </div>
                                        </a>
                                    </div>
                                </div>

                                <a href="notification.html" class="dropdown-item text-center text-primary d-block view-all fw-medium rounded-bottom-3">
                                    <span>See All Notifications </span>
                                </a>
                            </div>
                        </div>
                    </li>

                    <!-- Auth / Guest area -->
                    @guest
                        <li class="header-right-item">
                            <div class="dropdown">
                                <button class="btn btn-primary d-flex align-items-center gap-2" type="button" id="loginDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="material-symbols-outlined">login</i>
                                    <span>Login</span>
                                </button>

                                <div class="dropdown-menu dropdown-menu-end p-3" aria-labelledby="loginDropdown" style="min-width: 320px;">
                                    <form method="POST" action="{{ route('login') }}">
                                        @csrf
                                        <div class="mb-2">
                                            <label class="form-label fs-13">Email</label>
                                            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required autofocus>
                                            @error('email') <span class="text-danger small">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="mb-2">
                                            <label class="form-label fs-13">Password</label>
                                            <input type="password" name="password" class="form-control" required>
                                            @error('password') <span class="text-danger small">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center mb-2">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                                <label class="form-check-label fs-13" for="remember">Remember me</label>
                                            </div>
                                            <a href="{{ route('password.request') }}" class="fs-13">Forgot?</a>
                                        </div>
                                        <div class="d-grid">
                                            <button type="submit" class="btn btn-primary">Sign In</button>
                                        </div>
                                    </form>

                                    <div class="text-center mt-2">
                                        <span class="fs-13">Don't have an account? </span>
                                        <a href="{{ route('register') }}" class="fw-medium">Register</a>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endguest

                    @auth
                        <li class="header-right-item">
                            <div class="dropdown admin-profile">
                                <div class="d-xxl-flex align-items-center bg-transparent border-0 text-start p-0 cursor dropdown-toggle" data-bs-toggle="dropdown" role="button" aria-expanded="false">
                                    <div class="flex-shrink-0">
                                        <img class="rounded-circle wh-40 administrator" src="{{ asset('assets/images/administrator.jpg') }}" alt="admin">
                                    </div>
                                    <div class="flex-grow-1 ms-2 d-none d-xxl-block">
                                        <div class="d-flex align-content-center">
                                            <h3 class="mb-0">{{ Auth::user()->name }}</h3>
                                        </div>
                                    </div>
                                </div>

                                <div class="dropdown-menu border-0 bg-white dropdown-menu-end p-3">
                                    <div class="d-flex align-items-center info mb-3">
                                        <div class="flex-shrink-0">
                                            <img class="rounded-circle wh-30 administrator" src="{{ asset('assets/images/administrator.jpg') }}" alt="admin">
                                        </div>
                                        <div class="flex-grow-1 ms-2">
                                            <h3 class="fw-medium mb-0">{{ Auth::user()->name }}</h3>
                                            <span class="fs-12">{{ Auth::user()->email }}</span>
                                        </div>
                                    </div>

                                    <ul class="admin-link ps-0 mb-0 list-unstyled">
                                        <li>
                                            <a class="dropdown-item admin-item-link d-flex align-items-center text-body" href="{{ route('profile.edit') }}">
                                                <i class="material-symbols-outlined">account_circle</i>
                                                <span class="ms-2">My Profile</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item admin-item-link d-flex align-items-center text-body" href="#">
                                                <i class="material-symbols-outlined">chat</i>
                                                <span class="ms-2">Messages</span>
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item admin-item-link d-flex align-items-center text-body" href="#">
                                                <i class="material-symbols-outlined">settings</i>
                                                <span class="ms-2">Settings</span>
                                            </a>
                                        </li>
                                    </ul>

                                    <div class="mt-2 pt-2 border-top">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="btn btn-outline-danger w-100">
                                                <i class="material-symbols-outlined">logout</i> Logout
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endauth

                    <li class="header-right-item">
                        <button class="theme-settings-btn p-0 border-0 bg-transparent" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasScrolling" aria-controls="offcanvasScrolling">
                            <i class="material-symbols-outlined" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Click On Theme Settings">settings</i>
                        </button>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</header>
</div>
</div>
