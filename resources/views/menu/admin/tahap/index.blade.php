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
                        <h1 class="mb-0">Tahap</h1>
                    </div>

                    <button type="button" class="btn btn btn-primary py-2 px-4 text-white fw-semibold" data-bs-toggle="modal" data-bs-target="#exampleModallg">
                        <i class="material-symbols-outlined align-middle">add</i> Create
                    </button>
                    @include('menu.admin.tahap.create')

                    <div class="default-table-area all-products mt-3">
                        <div class="table-responsive">
                            <table id="user-table" class="display table align-middle" style="width:100%">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>tahap</th>
                                    <th>SK</th>
                                    <th>start</th>
                                    <th>end</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($tahaps as $i => $tahap)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $tahap->tahap }}</td>
                                        <td>{{ $tahap->surat_keputusan }}</td>
                                        <td>{{ optional($tahap->start_date)->format('d-m-Y H:i') ?? '-' }}</td>
                                        <td>{{ optional($tahap->end_date)->format('d-m-Y H:i') ?? '-' }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-1">
                                                <a href="{{ route('admin.tahap.show', $tahap) }}"
                                                   class="ps-0 border-0 bg-transparent lh-1 position-relative top-2"
                                                   data-bs-toggle="tooltip"
                                                   title="Detail">
                                                    <i class="material-symbols-outlined fs-16 text-info">visibility</i>
                                                </a>
                                                <a href="javascript:void(0)"
                                                   class="ps-0 border-0 bg-transparent lh-1 position-relative top-2"
                                                   data-bs-toggle="modal"
                                                   data-bs-target="#editModal-{{ $tahap->id }}"
                                                   title="Edit">
                                                    <i class="material-symbols-outlined fs-16 text-body">edit</i>
                                                </a>
                                            </div>

                                            @include('menu.admin.tahap.edit', ['tahap' => $tahap])
                                        </td>
                                    </tr>
                                @endforeach
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
                pageLength: 10,
                language: {
                    emptyTable: "No data."
                }
            });
        });
    </script>
@endpush
