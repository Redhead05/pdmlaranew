@extends('app.layout')
@section('title', 'Attendance Detail')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@section('content')
    <div class="container-fluid">
        <div class="main-content d-flex flex-column">
            <div class="card bg-white border-0 rounded-3 mb-4">
                <div class="card-body p-4">
                    <h1>Attendance Detail: {{ $attendance->title }}</h1>
                    <div class="default-table-area all-products">
                        <div class="table-responsive">
                            <table id="details-table" class="display table align-middle" style="width:100%">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>User</th>
                                    <th>Signed At</th>
                                    <th>Signature</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($details as $i => $detail)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $detail->user->name ?? '-' }}</td>
                                        <td>{{ $detail->signed_at }}</td>
                                        <td>{{ $detail->signature }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <a href="{{ route('admin.attendance.index') }}" wire:navigate class="btn btn-secondary mt-3">Back</a>
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
            $('#details-table').DataTable({
                responsive: true,
                pageLength: 10,
                columnDefs: [
                    { orderable: false, searchable: false, targets: 0 }
                ]
            });
        });
    </script>
@endpush
