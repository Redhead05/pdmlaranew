@extends('app.layout')
@section('title', 'Master Lembaga')

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
                        <h1 class="mb-0">Master Lembaga</h1>
                    </div>

                    <div class="default-table-area all-products mt-3">
                        <div class="table-responsive">
                            <table id="lembagas-master-table" class="display table align-middle" style="width:100%">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NPSN</th>
                                    <th>Nama</th>
                                    <th>Kab/Kota</th>
                                    <th>Jenjang</th>
                                    <th>Latitude</th>
                                    <th>Longitude</th>
                                </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
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
            const table = $('#lembagas-master-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    // index route will return JSON when called via AJAX
                    url: "{{ route('admin.masterlembaga.index') }}",
                    type: 'GET'
                },
                columns: [
                    { data: null, orderable: false, searchable: false },
                    { data: 'npsn' },
                    { data: 'satuan_pen' },
                    { data: 'kabupaten' },
                    { data: 'jenjang' },
                    { data: 'latitude' },
                    { data: 'longitude' },
                ],
                pageLength: 25,
                order: [[1, 'asc']],
                responsive: true,
                drawCallback: function (settings) {
                    var api = this.api();
                    api.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
                        cell.innerHTML = settings._iDisplayStart + i + 1;
                    });
                }
            });
        });
    </script>
@endpush
