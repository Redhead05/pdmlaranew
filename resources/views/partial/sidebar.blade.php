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
                    <span class="material-symbols-outlined">cloud_circle</span>
                    <span class="title"> Dashboard</span>
                </a>
            </li>
            <li class="menu-item open">
                <a href="{{ route ('admin.user.index') }}" data-turbo-frame="main_frame" class="menu-link {{ Request::routeIs('admin.user.index') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">cloud_circle</span>
                    <span class="title">User</span>
                </a>
            </li>
            <li class="menu-item open">
                <a href="{{ route ('admin.attendance.index') }}" data-turbo-frame="main_frame" class="menu-link {{ Request::routeIs(['admin.attendance.index','admin.attendance.detail']) ? 'active' : '' }}">
                    <span class="material-symbols-outlined">foggy</span>
                    <span class="title"> Attendance</span>
                </a>
            </li>

            <li class="menu-item">
                <a href="contacts.html" data-turbo-frame="main_frame" class="menu-link">
                    <span class="material-symbols-outlined menu-icon">contact_page</span>
                    <span class="title">Contacts</span>
                </a>
            </li>
            @elserole('adminlanding')
            <li class="menu-item open">
                <a href="{{ route ('adminlanding.dashboard') }}" data-turbo-frame="main_frame" class="menu-link {{ Request::routeIs('adminlanding.dashboard') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">cloud_circle</span>
                    <span class="title"> Dashboard Landing Page</span>
                </a>
            </li>
            <li class="menu-item open">
                <a href="{{ route ('adminlanding.home.index') }}" data-turbo-frame="main_frame" class="menu-link {{ Request::routeIs('adminlanding.home.index') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">cloud_circle</span>
                    <span class="title">Home</span>
                </a>
            </li>
            <li class="menu-item open">
                <a href="{{ route ('adminlanding.gallery.index') }}" data-turbo-frame="main_frame" class="menu-link {{ Request::routeIs('adminlanding.gallery.index') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">cloud_circle</span>
                    <span class="title">Gallery</span>
                </a>
            </li>
            <li class="menu-item open">
                <a href="{{ route ('adminlanding.news.index') }}" data-turbo-frame="main_frame" class="menu-link {{ Request::routeIs('adminlanding.news.index') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">cloud_circle</span>
                    <span class="title">News</span>
                </a>
            </li>
            <li class="menu-item open">
                <a href="{{ route ('adminlanding.StrukturOrganisasi.index') }}" data-turbo-frame="main_frame" class="menu-link {{ Request::routeIs('adminlanding.StrukturOrganisasi.index') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">cloud_circle</span>
                    <span class="title">Struktur organisasi</span>
                </a>
            </li>
            @elserole('asesor')
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
                <a href="chat.html" data-turbo-frame="main_frame" class="menu-link">
                    <span class="material-symbols-outlined menu-icon">chat</span>
                    <span class="title">Chat</span>
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
                            Snoozed
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
