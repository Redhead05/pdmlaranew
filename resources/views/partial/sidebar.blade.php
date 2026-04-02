<!-- Start Sidebar Area -->
<div class="sidebar-area" id="sidebar-area">
    <div class="logo position-relative">
        <a href="{{route('adminlanding.dashboard')}}" data-turbo-frame="main_frame" class="d-block text-decoration-none position-relative">
            <img src="{{ asset('assets/logo_BANPDMJATIM.png') }}" alt="logo-icon">
        </a>
        <button class="sidebar-burger-menu bg-transparent p-0 border-0 opacity-0 z-n1 position-absolute top-50 end-0 translate-middle-y" id="sidebar-burger-menu">
            <i data-feather="x"></i>
        </button>
    </div>

    <aside id="layout-menu" class="layout-menu menu-vertical menu active" data-simplebar>
        <ul class="menu-inner">
            @role('admin')
            <li class="menu-item open">
                <a href="{{ route ('admin.dashboard') }}" data-turbo-frame="main_frame" class="menu-link {{ Request::routeIs('admin.dashboard') ? 'active' : '' }}">
                    <div class="flex gap-2">
                        <span class="ri-eth-line"></span>
                        <span class="title"> Dashboard</span>
                    </div>
                </a>
            </li>
            <li class="menu-item open">
                <a href="{{ route ('admin.user.index') }}" data-turbo-frame="main_frame" class="menu-link {{ Request::routeIs('admin.user.index') ? 'active' : '' }}">
                    <div class="flex gap-2">
                        <i class="ri-group-2-fill"></i>
                        <span class="title">User</span>
                    </div>
                </a>
            </li>
            <li class="menu-item open">
                <a href="{{ route ('admin.attendance.index') }}" data-turbo-frame="main_frame" class="menu-link {{ Request::routeIs(['admin.attendance.index','admin.attendance.detail']) ? 'active' : '' }}">
                    <div class="flex gap-2">
                        <i class="ri-calendar-check-line"></i>
                        <span class="title"> Attendance</span>
                    </div>
                </a>
            </li>
            <li @class(['menu-item', 'open' => Request::routeIs('admin.tahap.*','admin.kesanggupan.*')])>
                <a href="javascript:void(0);" @class(['menu-link', 'menu-toggle', 'active' => Request::routeIs('admin.tahap.*','admin.kesanggupan.*')])>
                    <i class="ri-crosshair-line"></i>
                    <span class="title">Visitasi</span>
                </a>
                <ul class="menu-sub">
                    <li @class(['menu-item', 'open' => Request::routeIs('admin.tahap.index')])>
                        <a href="{{ route('admin.tahap.index') }}" data-turbo-frame="main_frame" @class(['menu-link', 'active' => Request::routeIs('admin.tahap.index')])>
                            Tahap
                        </a>
                    </li>
                    <li @class(['menu-item', 'open' => Request::routeIs('admin.kesanggupan.index')])>
                        <a href="{{ route('admin.kesanggupan.index') }}" data-turbo-frame="main_frame" @class(['menu-link', 'active' => Request::routeIs('admin.kesanggupan.index')])>
                            Kesanggupan
                        </a>
                    </li>
                </ul>
            </li>
            @endrole

            @hasanyrole('adminlanding|admin')
            <li class="menu-item open ">
                <a href="{{ route ('adminlanding.dashboard') }}" data-turbo-frame="main_frame" class="menu-link {{ Request::routeIs('adminlanding.dashboard') ? 'active' : '' }}">
                    <div class="flex gap-2">
                        <span class="ri-eth-line"></span>
                        <span class="title"> Dashboard Landing Page</span>
                    </div>
                </a>
            </li>
            <li class="menu-item open">
                <a href="{{ route ('adminlanding.home.index') }}" data-turbo-frame="main_frame" class="menu-link {{ Request::routeIs('adminlanding.home.index') ? 'active' : '' }}">
                    <div class="flex gap-2">
                        <span class="ri-home-line"></span>
                        <span class="title">Home</span>
                    </div>
                </a>
            </li>
            <li class="menu-item open">
                <a href="{{ route ('adminlanding.gallery.index') }}" data-turbo-frame="main_frame" class="menu-link {{ Request::routeIs('adminlanding.gallery.index') ? 'active' : '' }}">
                    <div class="flex gap-2">
                        <i class="ri-image-2-line"></i>
                        <span class="title">Gallery</span>
                    </div>
                </a>
            </li>
            <li class="menu-item open">
                <a href="{{ route ('adminlanding.news.index') }}" data-turbo-frame="main_frame" class="menu-link {{ Request::routeIs('adminlanding.news.index') ? 'active' : '' }}">
                    <div class="flex gap-2">
                        <i class="ri-article-line"></i>
                        <span class="title">News</span>
                    </div>
                </a>
            </li>
            <li class="menu-item open">
                <a href="{{ route ('adminlanding.employee.index') }}" data-turbo-frame="main_frame" class="menu-link {{ Request::routeIs('adminlanding.StrukturOrganisasi.index') ? 'active' : '' }}">
                    <div class="flex gap-2">
                        <i class="ri-home-office-fill"></i>
                        <span class="title">Struktur organisasi</span>
                    </div>
                </a>
            </li>
            <li class="menu-item open">
                <a href="{{ route ('adminlanding.faq.index') }}" data-turbo-frame="main_frame" class="menu-link {{ Request::routeIs('adminlanding.StrukturOrganisasi.index') ? 'active' : '' }}">
                    <div class="flex gap-2">
                        <i class="ri-question-fill"></i>
                        <span class="title">Faq</span>
                    </div>
                </a>
            </li>
            <li class="menu-item open">
                <a href="{{ route ('adminlanding.chat.index') }}" data-turbo-frame="main_frame" class="menu-link {{ Request::routeIs('adminlanding.chat.index') ? 'active' : '' }}">
                    <div class="flex gap-2">
                        <i class="ri-question-answer-fill"></i>
                        <span class="title">Chat</span>
                    </div>
                </a>
            </li>
            <li class="menu-item open">
                <a href="{{ route ('adminlanding.chat.index') }}" data-turbo-frame="main_frame" class="menu-link {{ Request::routeIs('adminlanding.chat.index') ? 'active' : '' }}">
                    <div class="flex gap-2">
                        <i class="ri-question-answer-fill"></i>
                        <span class="title">Chat</span>
                    </div>
                </a>
            </li>
            <li class="menu-item open">
                <a href="{{ route ('adminlanding.chat.index') }}" data-turbo-frame="main_frame" class="menu-link {{ Request::routeIs('adminlanding.chat.index') ? 'active' : '' }}">
                    <div class="flex gap-2">
                        <i class="ri-question-answer-fill"></i>
                        <span class="title">Chat</span>
                    </div>
                </a>
            </li>
            <li class="menu-item open">
                <a href="{{ route ('adminlanding.chat.index') }}" data-turbo-frame="main_frame" class="menu-link {{ Request::routeIs('adminlanding.chat.index') ? 'active' : '' }}">
                    <div class="flex gap-2">
                        <i class="ri-question-answer-fill"></i>
                        <span class="title">Chat</span>
                    </div>
                </a>
            </li>
            @endhasanyrole
            @role('asesor')
            <li class="menu-item open">
                <a href="{{ route ('asesor.dashboard') }}" data-turbo-frame="main_frame" class="menu-link {{ Request::routeIs('dashboard.asesor') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">cloud_circle</span>
                    <span class="title"> Dashboard</span>
                </a>
            </li>
            <li class="menu-item open">
                <a href="{{ route ('asesor.attendance.index') }}" data-turbo-frame="main_frame" class="menu-link {{ Request::routeIs('asesor.attendance.index') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">foggy</span>
                    <span class="title"> Attendance</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="{{ route ('asesor.kesanggupan.index') }}" data-turbo-frame="main_frame" class="menu-link">
                    <span class="material-symbols-outlined menu-icon">chat</span>
                    <span class="title">Kesanggupan</span>
                </a>
            </li>

            <li class="menu-item">
                <a href="javascript:void(0);" class="menu-link menu-toggle active">
                    <span class="material-symbols-outlined menu-icon">mail</span>
                    <span class="title">Email</span>
                </a>
                <ul class="menu-sub">
                    <li class="menu-item">
                        <a href="inbox.html" data-turbo-frame="main_frame" class="menu-link">
                            Inbox
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="compose.html" data-turbo-frame="main_frame" class="menu-link">
                            Compose
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="read-email.html" data-turbo-frame="main_frame" class="menu-link">
                            Read Email
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="snoozed.html" data-turbo-frame="main_frame" class="menu-link">
                            Snooze
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="draft.html" data-turbo-frame="main_frame" class="menu-link">
                            Draft
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="sent-mail.html" data-turbo-frame="main_frame" class="menu-link">
                            Sent Mail
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="trash-email.html" data-turbo-frame="main_frame" class="menu-link">
                            Trash
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="spam.html" data-turbo-frame="main_frame" class="menu-link">
                            Spam
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="starred.html" data-turbo-frame="main_frame" class="menu-link">
                            Starred
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="important.html" data-turbo-frame="main_frame" class="menu-link">
                            Important
                        </a>
                    </li>
                </ul>
            </li>
            @elserole('user')
            <li class="menu-item">
                <a href="kanban-board.html" data-turbo-frame="main_frame" class="menu-link">
                    <span class="material-symbols-outlined menu-icon">team_dashboard</span>
                    <span class="title">Kanban Board</span>
                </a>
            </li>
            @endrole
        </ul>
    </aside>
</div>
<!-- End Sidebar Area -->
