@extends('app.layout')
@section('title', 'organizationstructure Management')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

@endpush

@section('content')
    <!-- Start Main Content Area -->
    <div class="container-fluid">
        <div class="main-content d-flex flex-column">
            <div class="card bg-white border-0 rounded-3 mb-4">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="card-body p-4">
                    <h1>Struktur Organisasi</h1>
                    <div class="mb-3">
                        <button type="button" class="btn btn btn-primary py-2 px-4 text-white fw-semibold" data-bs-toggle="modal" data-bs-target="#exampleModallg">
                            <i class="material-symbols-outlined align-middle">add</i> Create
                        </button>
                        @include('menu.adminlanding.organization_structure.create')
                    </div>
                    <div class="default-table-area all-products">
                        <div class="table-responsive">
                            <table id="organizationstructure-table" class="display table align-middle" style="width:100%">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>Position</th>
                                    <th>Period</th>
                                    <th>Avatar</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($items as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->user->name ?? $item->user_id }}</td>
                                        <td>{{ $item->position ?? $item->position }}</td>
                                        <td>{{ $item->period ?? $item->period }}</td>
                                        <td class="text-center">
                                            @if($item->avatar)
                                                <img src="{{ Storage::url($item->avatar) }}" alt="avatar" style="width:60px; height:auto; border-radius:6px;">
                                            @else
                                                <img src="{{ asset('assets/logo_BANPDMJATIM.png') }}" alt="fallback" style="width:60px; height:auto; border-radius:6px;">
                                            @endif
                                        </td>
                                        <td>
                                                <div class="d-flex align-items-center gap-1">
                                                <div class="btn-group" role="group" aria-label="actions">
                                                {{-- existing action buttons (edit/delete/detail) remain here --}}


                                            </div>
{{--                                                <a href="{{ route('admin.organizationstructure.detail', $item->slug) }}" class="ps-0 border-0 bg-transparent lh-1 position-relative top-2" data-bs-toggle="tooltip" data-bs-title="Detail">--}}
                                                    <i class="material-symbols-outlined fs-16 text-success">info</i>
{{--                                                </a>--}}
{{--                                                <button class="ps-0 border-0 bg-transparent lh-1 position-relative top-2" data-bs-toggle="modal" data-bs-target="#editModal-{{ $item->id }}" data-bs-title="Edit">--}}
                                                    <i class="material-symbols-outlined fs-16 text-body">edit</i>
{{--                                                </button>--}}
{{--                                                @include('menu.admin.organizationstructure.edit', ['item' => $item])--}}
                                                <button type="button"
                                                        class="ps-0 border-0 bg-transparent lh-1 position-relative top-2"
                                                        data-bs-toggle="modal"
{{--                                                        data-bs-target="#deleteModal-{{ $item->id }}"--}}
                                                        data-bs-title="Delete">
                                                    <i class="material-symbols-outlined fs-16 text-danger">delete</i>
                                                </button>
{{--                                                @include('menu.admin.organizationstructure.delete')--}}
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
                    const table = $('#organizationstructure-table').DataTable({
                        responsive: true,
                        pageLength: 10,
                        columnDefs: [
                            { orderable: false, searchable: false, targets: 0 }, // No
                            { orderable: false, searchable: false, targets: 5 }  // Action
                        ]
                    });

                    // Re-number the "No" column after ordering, searching, paging or drawing
                    table.on('order.dt search.dt page.dt draw.dt', function () {
                        table.column(0, { order: 'applied', search: 'applied' }).nodes().each(function (cell, i) {
                            cell.innerHTML = i + 1;
                        });
                    });

                    // initial numbering
                    table.draw();
                });
            </script>
@endpush
