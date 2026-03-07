@extends('app.layout')
@section('title', 'User Management')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@section('content')
    <!-- Start Main Content Area -->
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
                @if ($errors->any())
                    <div class="alert alert-danger m-4">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1 class="mb-0">User Management</h1>

                    </div>
                    <a href="{{ route('admin.user.create') }}" class="btn btn-primary py-2 px-4 text-white fw-semibold">
                        <i class="material-symbols-outlined align-middle">add</i> Create User
                    </a>
                    <div class="default-table-area all-products">
                        <div class="table-responsive">
                            <table id="user-table" class="display table align-middle" style="width:100%">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NIA</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Gender</th>
                                    <th>City</th>
                                    <th>Status</th>
                                    <th>Location</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($users as $i => $user)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $user->nia }}</td>
                                        <td>{{ $user->name }}</td>
                                        <td>{{ $user->email }}</td>
                                        <td>
                                            @foreach($user->roles as $role)
                                                <span class="badge bg-primary">{{ ucfirst($role->name) }}</span>
                                            @endforeach
                                        </td>
                                        <td>{{ $user->detail->gender ?? '-' }}</td>
                                        <td>{{ $user->detail->home_city ?? '-' }}</td>
                                        <td>
                                            <span class="badge status-badge {{ $user->is_active ? 'bg-success' : 'bg-danger' }}"
                                                  style="cursor: pointer;"
                                                  data-user-id="{{ $user->id }}"
                                                  data-user-slug="{{ $user->slug }}"
                                                  data-is-active="{{ $user->is_active ? '1' : '0' }}"
                                                  title="Click to toggle status">
                                                {{ $user->is_active ? 'Active' : 'Inactive' }}
                                            </span>
                                        </td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input location-toggle" type="checkbox" role="switch" id="locationSwitch-{{ $user->id }}" data-user-slug="{{ $user->slug }}" {{ optional($user->detail)->location_enabled ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center gap-1">
                                                <a href="{{ route('admin.user.show', ['user' => $user]) }}" class="ps-0 border-0 bg-transparent lh-1 position-relative top-2" data-bs-toggle="tooltip" data-bs-title="View">
                                                    <i class="material-symbols-outlined fs-16 text-info">visibility</i>
                                                </a>
                                                <a href="{{ route('admin.user.edit', ['user' => $user]) }}" class="ps-0 border-0 bg-transparent lh-1 position-relative top-2" data-bs-toggle="tooltip" data-bs-title="Edit">
                                                    <i class="material-symbols-outlined fs-16 text-body">edit</i>
                                                </a>
                                                <button type="button" class="ps-0 border-0 bg-transparent lh-1 position-relative top-2" data-bs-toggle="modal" data-bs-target="#deleteModal-{{ $user->id }}" data-bs-title="Delete">
                                                    <i class="material-symbols-outlined fs-16 text-danger">delete</i>
                                                </button>
                                            </div>

                                            <!-- Delete Modal -->
                                            <div class="modal fade" id="deleteModal-{{ $user->id }}" tabindex="-1" aria-labelledby="deleteModalLabel-{{ $user->id }}" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="deleteModalLabel-{{ $user->id }}">Delete User</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Are you sure you want to delete user <strong>{{ $user->name }}</strong>?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                            <form action="{{ route('admin.user.destroy', ['user' => $user]) }}" method="POST" class="d-inline">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="btn btn-danger">Delete</button>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        $(document).ready(function() {
            // Setup CSRF token for AJAX requests
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            const table = $('#user-table').DataTable({
                pageLength: 10,
                ordering: true,
                searching: true,
                columnDefs: [
                    { orderable: false, searchable: false, targets: 0 }, // No
                    { orderable: false, searchable: false, targets: 9 }  // Action
                ],
                language: {
                    search: "Search:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    }
                }
            });

            // Re-number the "No" column after ordering, searching, paging or drawing
            table.on('order.dt search.dt page.dt draw.dt', function () {
                table.column(0, { order: 'applied', search: 'applied' }).nodes().each(function (cell, i) {
                    cell.innerHTML = i + 1;
                });
            });

            // initial numbering
            table.draw();

            // Toggle status on badge click
            $(document).on('click', '.status-badge', function() {
                const badge = $(this);
                const userId = badge.data('user-id');
                const userSlug = badge.data('user-slug');
                const currentStatus = badge.data('is-active');

                // Disable badge temporarily to prevent multiple clicks
                badge.css('pointer-events', 'none').css('opacity', '0.6');

                // Send AJAX request
                $.ajax({
                    url: '{{ url("admin/user") }}/' + userSlug + '/toggle-status',
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(response) {
                        if (response.success) {
                            // Update badge appearance and data
                            if (response.is_active) {
                                badge.removeClass('bg-danger').addClass('bg-success');
                                badge.text('Active');
                                badge.data('is-active', '1');
                            } else {
                                badge.removeClass('bg-success').addClass('bg-danger');
                                badge.text('Inactive');
                                badge.data('is-active', '0');
                            }

                            // Show success message
                            // showAlert('success', response.message);
                        }
                    },
                    error: function(xhr) {
                        const errorMsg = xhr.responseJSON?.message || 'Failed to update status';
                        // showAlert('danger', errorMsg);
                    },
                    complete: function() {
                        // Re-enable badge
                        badge.css('pointer-events', 'auto').css('opacity', '1');
                    }
                });
            });

            // Toggle location on switch change
            $(document).on('change', '.location-toggle', function() {
                const toggle = $(this);
                const userSlug = toggle.data('user-slug');
                const isChecked = toggle.is(':checked') ? 1 : 0;

                // Disable toggle temporarily to prevent multiple clicks
                toggle.prop('disabled', true);

                // Send AJAX request
                $.ajax({
                    url: '{{ url("admin/user") }}/' + userSlug + '/toggle-location',
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        location_enabled: isChecked
                    },
                    success: function(response) {
                        if (response.success) {
                            // Show success message
                            // showAlert('success', response.message);
                        }
                    },
                    error: function(xhr) {
                        const errorMsg = xhr.responseJSON?.message || 'Failed to update location';
                        // showAlert('danger', errorMsg);

                        // Revert toggle state on error
                        toggle.prop('checked', !isChecked);
                    },
                    complete: function() {
                        // Re-enable toggle
                        toggle.prop('disabled', false);
                    }
                });
            });

            // Function to show alert messages
            function showAlert(type, message) {
                const alertHtml = `
                    <div class="alert alert-${type} alert-dismissible fade show m-4" role="alert">
                        ${message}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                `;

                // Remove existing alerts
                $('.card-body').prev('.alert').remove();

                // Add new alert
                $('.card').prepend(alertHtml);

                // Auto dismiss after 3 seconds
                setTimeout(function() {
                    $('.alert').fadeOut('slow', function() {
                        $(this).remove();
                    });
                }, 3000);
            }
        });
    </script>
@endpush
