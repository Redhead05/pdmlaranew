@extends('app.layout')
@section('title', 'news Management')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        #news-table,
        #news-table th,
        #news-table td {
            border: none !important;
            /*box-shadow: none !important;*/
        }
    </style>
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
                    <h1>News</h1>
                    <div class="mb-3">
                        <button type="button" class="btn btn btn-primary py-2 px-4 text-white fw-semibold" data-bs-toggle="modal" data-bs-target="#exampleModallg">
                            <i class="material-symbols-outlined align-middle">add</i> Create
                        </button>
                        @include('menu.adminlanding.news.create')
                    </div>
                    <div class="default-table-area all-products">
                        <div class="table-responsive">
                            <table id="news-table" class="display table align-middle" style="width:100%">
                                <thead>
                                <tr>
                                    <th style="width:60px">No</th>
                                    <th>Thumbnail</th>
                                    <th>Title</th>
{{--                                    <th>Description</th>--}}
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th style="width:150px">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($news as $i => $item)
                                    @php
                                        $detail = $item->detail;
                                        $thumbnailUrl = $detail && $detail->thumbnail
                                            ? Storage::url($detail->thumbnail)
                                            : asset('assets/logo_BANPDMJATIM.png');
                                    @endphp
                                    <tr>
                                        <td>{{ $news->firstItem() + $i }}</td>
                                        <td>
                                            <img src="{{ $thumbnailUrl }}" alt="thumb" style="width:80px; height:auto; border-radius:6px; object-fit:cover;">
                                        </td>
                                        <td>{{ $item->title }}</td>
{{--                                        <td>{{ \Illuminate\Support\Str::limit(strip_tags($detail->description ?? '-'), 120) }}</td>--}}
                                        <td>{{ $item->category->name ?? '-' }}</td>
                                        <td>
                                            <span class="badge {{ $item->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $item->is_active ? 'Published' : 'Draft' }}
                                            </span>
                                        </td>
                                        <td>{{ optional($item->created_at)->format('Y-m-d') ?? '-' }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-1">
                                                <button type="button"
                                                        class="btn btn-sm btn-outline-success"
                                                        title="Edit"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#editModal-{{ $item->id }}">
                                                    <i class="material-symbols-outlined fs-16">edit</i>
                                                </button>
                                                <!-- include edit modal partial (place this inside the loop, after the row) -->
                                                @include('menu.adminlanding.news.edit', ['news' => $item, 'categories' => $categories])

                                                <form action="{{ route('adminlanding.news.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Delete this item?');" class="m-0 d-inline-flex align-items-center">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                        <i class="material-symbols-outlined fs-16 align-middle">delete</i>
                                                    </button>
                                                </form>
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
                    const table = $('#news-table').DataTable({
                        responsive: true,
                        pageLength: 10,
                        columnDefs: [
                            { orderable: false, searchable: false, targets: 0 }, // No
                            { orderable: false, searchable: false, targets: 6 }  // Action
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
