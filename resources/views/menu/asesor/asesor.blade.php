@extends('app.layout')

@section('title', 'Dashboard Asesor')
@section('content')
    <!-- Start Main Content Area -->
    <div class="container-fluid">
        <div class="main-content d-flex flex-column">

            <div class="card bg-white border-0 rounded-3 mb-4">
                <div class="card-body p-4">
                    <h1>Attendances</h1>
                    <div class="default-table-area all-products">
                        <div class="table-responsive">
                            <table id="attendance-table" class="display table align-middle" style="width:100%">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Type</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr>
                                    <td>1</td>
                                    <td>Monthly Report</td>
                                    <td>Attendance summary for September</td>
                                    <td>Report</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-1">
                                            <button class="ps-0 border-0 bg-transparent lh-1 position-relative top-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="View">
                                                <i class="material-symbols-outlined fs-16 text-primary">visibility</i>
                                            </button>
                                            <button class="ps-0 border-0 bg-transparent lh-1 position-relative top-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit">
                                                <i class="material-symbols-outlined fs-16 text-body">edit</i>
                                            </button>
                                            <button class="ps-0 border-0 bg-transparent lh-1 position-relative top-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete">
                                                <i class="material-symbols-outlined fs-16 text-danger">delete</i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>2</td>
                                    <td>Late Notice</td>
                                    <td>List of late arrivals</td>
                                    <td>Notification</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-1">
                                            <button class="ps-0 border-0 bg-transparent lh-1 position-relative top-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="View">
                                                <i class="material-symbols-outlined fs-16 text-primary">visibility</i>
                                            </button>
                                            <button class="ps-0 border-0 bg-transparent lh-1 position-relative top-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit">
                                                <i class="material-symbols-outlined fs-16 text-body">edit</i>
                                            </button>
                                            <button class="ps-0 border-0 bg-transparent lh-1 position-relative top-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete">
                                                <i class="material-symbols-outlined fs-16 text-danger">delete</i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <tr>
                                    <td>3</td>
                                    <td>Weekly Summary</td>
                                    <td>Summary for week 42</td>
                                    <td>Summary</td>
                                    <td>
                                        <div class="d-flex align-items-center gap-1">
                                            <button class="ps-0 border-0 bg-transparent lh-1 position-relative top-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="View">
                                                <i class="material-symbols-outlined fs-16 text-primary">visibility</i>
                                            </button>
                                            <button class="ps-0 border-0 bg-transparent lh-1 position-relative top-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Edit">
                                                <i class="material-symbols-outlined fs-16 text-body">edit</i>
                                            </button>
                                            <button class="ps-0 border-0 bg-transparent lh-1 position-relative top-2" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Delete">
                                                <i class="material-symbols-outlined fs-16 text-danger">delete</i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
