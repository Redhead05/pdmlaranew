@extends('app.layout')
@section('title', 'Visitasi')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@section('content')
    <div class="container-fluid">
        <div class="main-content d-flex flex-column">
            <div class="card bg-white border-0 rounded-3 mb-4">

                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1 class="mb-0">User Management</h1>
                    </div>

                    <a href="#create-user" class="btn btn-primary py-2 px-4 text-white fw-semibold">
                        <span class="material-symbols-outlined align-middle">add</span> Create User
                    </a>

                    <div class="default-table-area all-products mt-3">
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
                                <!-- Sample row 1 -->
                                <tr>
                                    <td>1</td>
                                    <td>1001</td>
                                    <td>Jane Doe</td>
                                    <td>jane@example.com</td>
                                    <td><span class="badge bg-primary">Admin</span></td>
                                    <td>Female</td>
                                    <td>Surabaya</td>
                                    <td>
                      <span class="badge bg-success" style="cursor: pointer;"
                            data-user-id="1" data-user-slug="jane-doe" data-is-active="1" title="Click to toggle status">
                        Active
                      </span>
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input location-toggle" type="checkbox" role="switch" id="locationSwitch-1" checked>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-1">
                                            <a href="#view-1" class="ps-0 border-0 bg-transparent lh-1 position-relative top-2" data-bs-toggle="tooltip" title="View">
                                                <i class="material-symbols-outlined fs-16 text-info">visibility</i>
                                            </a>
                                            <a href="#edit-1" class="ps-0 border-0 bg-transparent lh-1 position-relative top-2" data-bs-toggle="tooltip" title="Edit">
                                                <i class="material-symbols-outlined fs-16 text-body">edit</i>
                                            </a>
                                            <button type="button" class="ps-0 border-0 bg-transparent lh-1 position-relative top-2" data-bs-toggle="modal" data-bs-target="#deleteModal-1" title="Delete">
                                                <i class="material-symbols-outlined fs-16 text-danger">delete</i>
                                            </button>
                                        </div>

                                        <!-- Delete Modal for user 1 -->
                                        <div class="modal fade" id="deleteModal-1" tabindex="-1" aria-labelledby="deleteModalLabel-1" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteModalLabel-1">Delete User</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Are you sure you want to delete user <strong>Jane Doe</strong>?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="button" class="btn btn-danger">Delete</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End modal -->
                                    </td>
                                </tr>

                                <!-- Sample row 2 -->
                                <tr>
                                    <td>2</td>
                                    <td>1002</td>
                                    <td>John Smith</td>
                                    <td>john@example.com</td>
                                    <td><span class="badge bg-primary">User</span></td>
                                    <td>Male</td>
                                    <td>Malang</td>
                                    <td>
                      <span class="badge bg-danger" style="cursor: pointer;"
                            data-user-id="2" data-user-slug="john-smith" data-is-active="0" title="Click to toggle status">
                        Inactive
                      </span>
                                    </td>
                                    <td>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input location-toggle" type="checkbox" role="switch" id="locationSwitch-2">
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center gap-1">
                                            <a href="#view-2" class="ps-0 border-0 bg-transparent lh-1 position-relative top-2" data-bs-toggle="tooltip" title="View">
                                                <i class="material-symbols-outlined fs-16 text-info">visibility</i>
                                            </a>
                                            <a href="#edit-2" class="ps-0 border-0 bg-transparent lh-1 position-relative top-2" data-bs-toggle="tooltip" title="Edit">
                                                <i class="material-symbols-outlined fs-16 text-body">edit</i>
                                            </a>
                                            <button type="button" class="ps-0 border-0 bg-transparent lh-1 position-relative top-2" data-bs-toggle="modal" data-bs-target="#deleteModal-2" title="Delete">
                                                <i class="material-symbols-outlined fs-16 text-danger">delete</i>
                                            </button>
                                        </div>

                                        <!-- Delete Modal for user 2 -->
                                        <div class="modal fade" id="deleteModal-2" tabindex="-1" aria-labelledby="deleteModalLabel-2" aria-hidden="true">
                                            <div class="modal-dialog">
                                                <div class="modal-content">
                                                    <div class="modal-header">
                                                        <h5 class="modal-title" id="deleteModalLabel-2">Delete User</h5>
                                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                    </div>
                                                    <div class="modal-body">
                                                        Are you sure you want to delete user <strong>John Smith</strong>?
                                                    </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                        <button type="button" class="btn btn-danger">Delete</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- End modal -->
                                    </td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </div> <!-- .default-table-area -->
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
        // Initialize DataTable (if desired)
        $(document).ready(function () {
            $('#user-table').DataTable({
                pageLength: 10
            });
        });
    </script>
@endpush
