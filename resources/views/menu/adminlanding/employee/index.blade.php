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
                        <button type="button" class="btn btn btn-primary py-2 px-4 text-white fw-semibold"
                                data-bs-toggle="modal" data-bs-target="#exampleModallg">
                            <i class="material-symbols-outlined align-middle">add</i> Create
                        </button>
                        @include('menu.adminlanding.employee.create')
                    </div>
                    <div class="default-table-area all-products">
                        <div class="table-responsive">
                            <table id="organizationstructure-table" class="display table align-middle"
                                   style="width:100%">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Position</th>
                                    <th>Period</th>
                                    <th>Email</th>
                                    <th>Avatar</th>
                                    <th>Socials</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($items as $item)
                                    <tr>
                                        <td>
                                           {{ $loop->iteration }}
                                        </td>

                                        {{-- Prefer explicit name on employee; fallback to related user if present --}}
                                        <td>{{ $item->name ?? $item->user->name ?? '—' }}</td>

                                        <td>{{ $item->position ?? '—' }}</td>

                                        {{-- Period combines start_year and end_year; show "Present" if end missing --}}
                                        <td>
                                            @if($item->start_year)
                                                {{ $item->start_year }}
                                                -
                                                {{ $item->end_year ?? 'Present' }}
                                            @else
                                                —
                                            @endif
                                        </td>

                                        <td>
                                            @if($item->email)
                                                <a href="mailto:{{ $item->email }}">{{ $item->email }}</a>
                                            @else
                                                —
                                            @endif
                                        </td>

                                        <td class="text-center">
                                            @if($item->photo)
                                                <img src="{{ Storage::url($item->photo) }}" alt="avatar"
                                                     style="width:60px; height:auto; border-radius:6px;">
                                            @else
                                                <img src="{{ asset('assets/logo_BANPDMJATIM.png') }}" alt="fallback"
                                                     style="width:60px; height:auto; border-radius:6px;">
                                            @endif
                                        </td>

                                        <td>
                                            @if($item->instagram)
                                                <a href="{{ $item->instagram }}" target="_blank" class="me-1">IG</a>
                                            @endif
                                            @if($item->facebook)
                                                <a href="{{ $item->facebook }}" target="_blank" class="me-1">FB</a>
                                            @endif
                                            @if($item->linkedin)
                                                <a href="{{ $item->linkedin }}" target="_blank">LN</a>
                                            @endif
                                            @if(!$item->instagram && !$item->facebook && !$item->linkedin)
                                                —
                                            @endif
                                        </td>

                                        <td>
                                            <div class="d-flex align-items-center gap-1">
                                                <div class="btn-group" role="group" aria-label="actions">
                                                    <a href="{{ route('adminlanding.employee.edit', $item->id) }}" class="btn btn-sm btn-light" title="Edit">
                                                        <i class="material-symbols-outlined fs-16 text-body">edit</i>
                                                    </a>
                                                    <form action="{{ route('adminlanding.employee.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Delete this employee?');" style="display:inline;">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-light" title="Delete">
                                                            <i class="material-symbols-outlined fs-16 text-danger">delete</i>
                                                        </button>
                                                    </form>
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
        @endsection

        @push('scripts')
            <script src="//code.jquery.com/jquery-3.6.0.min.js"></script>
            <script src="//cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
            <script>
                $(document).ready(function () {
                    const table = $('#organizationstructure-table').DataTable({
                        responsive: true,
                        // show all rows by default (client-side). Users can still choose a page size.
                        pageLength: -1,
                        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'All']],
                        columnDefs: [
                            { orderable: false, searchable: false, targets: 0 }, // No
                            { orderable: false, searchable: false, targets: 7 }  // Action
                        ]
                    });

                    // Re-number the "No" column after ordering/searching/paging/draw
                    table.on('order.dt search.dt page.dt draw.dt', function () {
                        table.column(0, { order: 'applied', search: 'applied' }).nodes().each(function (cell, i) {
                            cell.innerHTML = i + 1;
                        });
                    });

                    table.draw();
                });
            </script>
    @endpush
