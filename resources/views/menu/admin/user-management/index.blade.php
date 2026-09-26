@extends('app.layout')
@section('title', 'User Management — Hak Akses')

@section('content')
    <div class="container-fluid">
        <div class="main-content d-flex flex-column">
            <div class="card bg-white border-0 rounded-3 mb-4">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show m-4" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show m-4" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h1 class="mb-1">User Management</h1>
                            <p class="text-muted mb-0 fs-14">Centang menu untuk memberikan / mencabut hak akses tiap user.</p>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table id="user-management-table" class="display table align-middle" style="width:100%">
                            <thead>
                            <tr>
                                <th>NIA</th>
                                <th>Nama</th>
                                <th>Email</th>
                                @foreach ($permissions as $permission)
                                    <th class="text-center text-wrap">{{ $permission['label'] }}</th>
                                @endforeach
                            </tr>
                            </thead>
                            <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>{{ $user->nia }}</td>
                                    <td>{{ $user->name }}</td>
                                    <td>{{ $user->email }}</td>
                                    @foreach ($permissions as $permission)
                                        <td class="text-center">
                                            <div class="form-check d-inline-block">
                                                <input class="form-check-input permission-check"
                                                       type="checkbox"
                                                       data-user-id="{{ $user->id }}"
                                                       data-permission="{{ $permission['permission'] }}"
                                                       {{ $user->hasPermissionTo($permission['permission']) ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                    @endforeach
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('#user-management-table').DataTable({
                pageLength: 10,
                ordering: true,
                searching: true,
                scrollX: true,
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampil _MENU_ data",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                    paginate: { first: "Awal", last: "Akhir", next: "Berikut", previous: "Sebelum" }
                }
            });

            $(document).on('change', '.permission-check', function () {
                const checkbox = $(this);
                const userId = checkbox.data('user-id');
                const permission = checkbox.data('permission');
                const checked = checkbox.is(':checked') ? 1 : 0;

                checkbox.prop('disabled', true);

                $.ajax({
                    url: "{{ route('admin.user-management.toggle-permission') }}",
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        user_id: userId,
                        permission: permission,
                        checked: checked
                    },
                    success: function (response) {
                        if (!response.success) {
                            checkbox.prop('checked', !checked);
                        }
                    },
                    error: function () {
                        checkbox.prop('checked', !checked);
                    },
                    complete: function () {
                        checkbox.prop('disabled', false);
                    }
                });
            });
        });
    </script>
@endpush
