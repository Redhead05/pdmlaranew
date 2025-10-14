@extends('app.layout')
@section('title', 'Attendance')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.min.css">
@endpush

@section('content')
    <!-- Start Main Content Area -->
    <div class="container-fluid">
        <div class="main-content d-flex flex-column">

            <div class="card bg-white border-0 rounded-3 mb-4">
                <div class="card-body p-4">
                    <h1>Attendances</h1>
                    <div class="mb-3">
{{--                        {{ route('attendance.create') }}--}}
                        <a href="#" class="btn btn-primary">
                            <i class="material-symbols-outlined align-middle">add</i> Create Attendance
                        </a>
                    </div>
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
                                    @foreach($attendance as $i => $item)
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td>{{ $item->title }}</td>
                                            <td>{{ $item->description }}</td>
                                            <td>{{ ucfirst($item->type) }}</td>
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
    <script src="//code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="//cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#attendance-table').DataTable({
                responsive: true,
                pageLength: 10,
                columnDefs: [
                    { orderable: false, searchable: false, targets: 4 }
                ]
            });
        });
    </script>
@endpush
