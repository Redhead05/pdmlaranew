
<!-- Start Sidebar Area -->
<div class="sidebar-area" id="sidebar-area">
    <div class="logo position-relative">
        <a href="index.html" class="d-block text-decoration-none position-relative">
            <img src="assets/images/logo-icon.png" alt="logo-icon">
            <span class="logo-text fw-bold text-dark">Trezo</span>
        </a>
        <button class="sidebar-burger-menu bg-transparent p-0 border-0 opacity-0 z-n1 position-absolute top-50 end-0 translate-middle-y" id="sidebar-burger-menu">
            <i data-feather="x"></i>
        </button>
    </div>

    <aside id="layout-menu" class="layout-menu menu-vertical menu active" data-simplebar>
        <ul class="menu-inner">

            @role('admin')
            <li class="menu-item open">
                <a href="{{ route ('dashboard.admin') }}" class="menu-link {{ Request::routeIs('dashboard.admin') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">cloud_circle</span>

                    <span class="title"> Dashboard</span>
                </a>
            </li>
            <li class="menu-item open">
                <a href="{{ route ('attendance.admin') }}" class="menu-link {{ Request::routeIs('attendance.admin') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">foggy</span>
                    <span class="title"> Attendance</span>
                </a>
            </li>

            <li class="menu-item">
                <a href="contacts.html" class="menu-link">
                    <span class="material-symbols-outlined menu-icon">contact_page</span>
                    <span class="title">Contacts</span>
                </a>
            </li>
            @elserole('asesor')
            <li class="menu-item open">
                <a href="#" class="menu-link {{ Request::routeIs('#') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">cloud_circle</span>

                    <span class="title"> Dashboard</span>
                </a>
            </li>
            <li class="menu-item open">
                <a href="#" class="menu-link {{ Request::routeIs('#') ? 'active' : '' }}">
                    <span class="material-symbols-outlined">foggy</span>
                    <span class="title"> Attendance</span>
                </a>
            </li>
            <li class="menu-item">
                <a href="chat.html" class="menu-link">
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
                        <a href="inbox.html" class="menu-link">
                            Inbox
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="compose.html" class="menu-link">
                            Compose
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="read-email.html" class="menu-link">
                            Read Email
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="snoozed.html" class="menu-link">
                            Snoozed
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="draft.html" class="menu-link">
                            Draft
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="sent-mail.html" class="menu-link">
                            Sent Mail
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="trash-email.html" class="menu-link">
                            Trash
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="spam.html" class="menu-link">
                            Spam
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="starred.html" class="menu-link">
                            Starred
                        </a>
                    </li>
                    <li class="menu-item">
                        <a href="important.html" class="menu-link">
                            Important
                        </a>
                    </li>
                </ul>
            </li>
            @elserole('user')
            <li class="menu-item">
                <a href="kanban-board.html" class="menu-link">
                    <span class="material-symbols-outlined menu-icon">team_dashboard</span>
                    <span class="title">Kanban Board</span>
                </a>
            </li>
            @endrole
        </ul>
    </aside>
</div>
<!-- End Sidebar Area -->
