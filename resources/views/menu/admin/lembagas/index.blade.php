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
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h1 class="mb-0">Master Lembaga</h1>
                        <div>
                            <a href="{{ route('admin.lembagas.create') }}" class="btn btn-sm btn-primary me-2">Tambah Lembaga</a>
                            <a href="{{ route('admin.lembagas.index') }}" class="btn btn-sm btn-outline-secondary">Refresh</a>
                        </div>
                    </div>

                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if (session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    <div class="table-responsive">
                        <table id="lembagas-master-table" class="display table align-middle" style="width:100%">
                            <thead>
                            <tr>
                                <th>No</th>
                                <th>NPSN</th>
                                <th>Nama</th>
                                <th>Alamat</th>
                                <th>Kecamatan</th>
                                <th>Kabupaten</th>
                                <th>Latitude</th>
                                <th>Longitude</th>
                                <th>Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($lembagas as $i => $l)
                                <tr>
                                    <td></td>
                                    <td>{{ $l->npsn }}</td>
                                    <td>{{ $l->satuan_pen }}</td>
                                    <td>{{ \Illuminate\Support\Str::limit($l->alamat ?? '-', 80) }}</td>
                                    <td>{{ $l->kecamatan ?? '-' }}</td>
                                    <td>{{ $l->kabupaten ?? '-' }}</td>
                                    <td>{{ $l->latitude ?? '-' }}</td>
                                    <td>{{ $l->longitude ?? '-' }}</td>
                                    <td>
                                        <a href="{{ route('admin.lembagas.edit', $l->id) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                        <form action="{{ route('admin.lembagas.destroy', $l->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus lembaga ini?');">
                                            @csrf
                                            @method('DELETE')
                                            <button class="btn btn-sm btn-outline-danger">Delete</button>
                                        </form>
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
            const table = $('#lembagas-master-table').DataTable({
                responsive: true,
                pageLength: 25,
                columnDefs: [
                    { orderable: false, searchable: false, targets: 0 },
                    { orderable: false, searchable: false, targets: 8 }
                ]
            });

            // number column
            table.on('order.dt search.dt page.dt draw.dt', function () {
                table.column(0, { order: 'applied', search: 'applied' }).nodes().each(function (cell, i) {
                    cell.innerHTML = i + 1;
                });
            });
            table.draw();
        });
    </script>
@endpush
