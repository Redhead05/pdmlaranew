<aside class="w-64 bg-gray-200 min-h-screen p-4">
    <ul>
        @role('admin')
        <li><a href="{{ route('dashboard.admin') }}">Dashboard</a></li>
        <li><a href="#">User</a></li>
        <li><a href="#">Jobs</a></li>
        @elserole('asesor')
        <li><a href="{{ route('dashboard.asesor') }}">Jobs</a></li>
        <li><a href="#">Attendance</a></li>
        <li><a href="#">Pelaporan</a></li>
        @elserole('user')
        <li><a href="{{ route('dashboard.user') }}">Sertifikat</a></li>
        @endrole
    </ul>
</aside>
