<div class="container-fluid">
    <div class="main-content d-flex flex-column">
        <!-- Start Header Area -->
        <header class="header-area bg-white mb-2 rounded-bottom-15" id="header-area">
            <div class="row align-items-center">
                <div class="col-lg-4 col-sm-6">
                    <div class="left-header-content">
                        <ul class="d-flex align-items-center ps-0 mb-0 list-unstyled justify-content-center justify-content-sm-start">
                            <li>
                                <button class="header-burger-menu bg-transparent p-0 border-0" id="header-burger-menu">
                                    <i class="ri-menu-line"></i>
                                </button>
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
                                        <span class="dark"><i class="ri-sun-line"></i></span>
                                        <span class="light"><i class="ri-moon-line"></i></span>
                                    </button>
                                </div>
                            </li>

                            <li class="header-right-item">
                                <button class="fullscreen-btn bg-transparent p-0 border-0" id="fullscreen-button">
                                    <i class="ri-fullscreen-line text-body"></i>
                                </button>
                            </li>
                            <li class="header-right-item">
                                @php
                                    $headerNotifications = auth()->check()
                                        ? auth()->user()->unreadNotifications()->latest()->limit(6)->get()
                                        : collect();
                                    $headerNotificationCount = auth()->check()
                                        ? auth()->user()->unreadNotifications()->count()
                                        : 0;
                                @endphp
                                <div class="dropdown notifications noti">
                                    <button class="btn btn-secondary border-0 p-0 position-relative" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="ri-notification-3-line"></i>
                                        @if($headerNotificationCount > 0)
                                            <span id="notificationBadge" class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size:10px;">
                                                {{ $headerNotificationCount > 99 ? '99+' : $headerNotificationCount }}
                                            </span>
                                        @endif
                                    </button>
                                    <div class="dropdown-menu dropdown-lg p-0 border-0 dropdown-menu-end">
                                        <div class="d-flex justify-content-between align-items-center title">
                                            <span class="fw-semibold fs-15 text-secondary">Notifications <span class="fw-normal text-body fs-14">({{ $headerNotificationCount }})</span></span>
                                            <a href="{{ route('notifications.index') }}" class="p-0 m-0 bg-transparent border-0 fs-14 text-primary text-decoration-none">Lihat Semua</a>
                                        </div>

                                        <div class="max-h-217" data-simplebar>
                                            @forelse($headerNotifications as $notification)
                                                <div class="notification-menu {{ is_null($notification->read_at) ? 'unseen' : '' }}">
                                                    <form action="{{ route('notifications.read', $notification->id) }}" method="POST">
                                                        @csrf
                                                        <button type="submit" class="dropdown-item text-start border-0 bg-transparent w-100">
                                                            <div class="d-flex align-items-center">
                                                                <div class="flex-shrink-0">
                                                                    <i class="ri-task-line text-primary"></i>
                                                                </div>
                                                                <div class="flex-grow-1 ms-3">
                                                                    <p class="mb-0 text-body">{{ $notification->data['title'] ?? 'Notifikasi' }}</p>
                                                                    <span class="fs-13">{{ $notification->created_at->diffForHumans() }}</span>
                                                                </div>
                                                            </div>
                                                        </button>
                                                    </form>
                                                </div>
                                            @empty
                                                <div class="text-center text-muted py-4 fs-14">Belum ada notifikasi baru.</div>
                                            @endforelse
                                        </div>

                                        <a href="{{ route('notifications.index') }}" class="dropdown-item text-center text-primary d-block view-all fw-medium rounded-bottom-3">
                                            <span>See All Notifications </span>
                                        </a>
                                    </div>
                                </div>
                            </li>
                            <script>
                            document.addEventListener('click', function (e) {
                                var form = e.target.closest('.notification-menu form');
                                if (!form) return;
                                e.preventDefault();
                                var token = document.querySelector('meta[name="csrf-token"]');
                                fetch(form.action, {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': token ? token.content : '',
                                        'Accept': 'application/json',
                                        'X-Requested-With': 'XMLHttpRequest'
                                    }
                                }).then(function (r) { return r.json(); }).then(function (res) {
                                    var item = form.closest('.notification-menu');
                                    if (item) item.remove();
                                    var badge = document.getElementById('notificationBadge');
                                    if (badge) {
                                        var n = parseInt(badge.textContent.replace(/D/g, ''), 10) || 0;
                                        n = Math.max(0, n - 1);
                                        if (n <= 0) badge.remove();
                                        else badge.textContent = n > 99 ? '99+' : n;
                                    }
                                    if (res.url) window.location.href = res.url;
                                }).catch(function () {
                                    if (form.action) window.location.href = form.action;
                                });
                            });
                            </script>
                            <li class="header-right-item">
                                <div class="dropdown admin-profile">
                                    {{-- Trigger: ubah dari div d-xxl-flex ke button d-flex agar selalu klikable --}}
                                    <button type="button"
                                            class="d-flex align-items-center bg-transparent border-0 text-start p-0 gap-2 dropdown-toggle"
                                            data-bs-toggle="dropdown"
                                            aria-expanded="false"
                                            style="cursor:pointer; outline:none;">
                                        <img class="rounded-circle wh-40 administrator"
                                             src="{{ asset('assets/images/administrator.jpg') }}" alt="admin">
                                        <span class="d-none d-md-block fw-medium text-body" style="font-size:14px; line-height:1.2;">
                                            {{ auth()->check() ? auth()->user()->name : 'User' }}
                                        </span>
                                    </button>

                                    <div class="dropdown-menu border-0 bg-white dropdown-menu-end shadow-sm" style="min-width:210px; border-radius:10px;">
                                        <ul class="admin-link ps-0 mb-0 list-unstyled">
                                            <li>
                                                @php
                                                    use Illuminate\Support\Facades\Route as RouteFacade;
                                                    $profileRoute = '#';
                                                    if (auth()->check() && method_exists(auth()->user(), 'hasRole')) {
                                                        if (auth()->user()->hasRole('asesor') && RouteFacade::has('asesor.profile.edit')) {
                                                            $profileRoute = route('asesor.profile.edit');
                                                        } elseif ((auth()->user()->hasRole('admin') || auth()->user()->hasRole('adminlanding')) && RouteFacade::has('adminlanding.profile.edit')) {
                                                            $profileRoute = route('adminlanding.profile.edit');
                                                        } elseif (RouteFacade::has('profile.edit')) {
                                                            $profileRoute = route('profile.edit');
                                                        }
                                                    } elseif (RouteFacade::has('profile.edit')) {
                                                        $profileRoute = route('profile.edit');
                                                    }
                                                @endphp
                                                <a class="dropdown-item admin-item-link d-flex align-items-center text-body" href="{{ $profileRoute }}">
                                                    <i class="ri-account-circle-line"></i>
                                                    <span class="ms-2">My Profile</span>
                                                </a>
                                            </li>
                                        </ul>
                                        <ul class="admin-link ps-0 mb-0 list-unstyled">
                                            <li>
                                                <a href="#" class="dropdown-item admin-item-link d-flex align-items-center text-body"
                                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                    <i class="ri-logout-box-line"></i>
                                                    <span class="ms-2">Logout</span>
                                                </a>
                                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                                    @csrf
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </li>
                            <li class="header-right-item">
                                <button class="theme-settings-btn p-0 border-0 bg-transparent" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasScrolling" aria-controls="offcanvasScrolling">
                                    <i class="ri-settings-3-line" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-title="Click On Theme Settings"></i>
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </header>
    </div>
</div>
