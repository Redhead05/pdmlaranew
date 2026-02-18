@extends('app.layout')
@section('title', 'Gallery Management')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Styling untuk thumbnail gallery */
        .thumb {
            position: relative;
            width: 80px;
            height: 60px;
            overflow: hidden;
            border-radius: 4px;
            display: inline-block;
            background: #f5f5f5;
        }

        .thumb img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        /* Play button untuk video */
        .thumb .play {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            width: 24px;
            height: 24px;
            background: rgba(0, 0, 0, 0.7);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .thumb .play .triangle {
            width: 0;
            height: 0;
            border-left: 8px solid white;
            border-top: 5px solid transparent;
            border-bottom: 5px solid transparent;
            margin-left: 2px;
        }

        /* Hover effect */
        .thumb:hover {
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            cursor: pointer;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .thumb {
                width: 60px;
                height: 45px;
            }

            .thumb .play {
                width: 20px;
                height: 20px;
            }

            .thumb .play .triangle {
                border-left: 6px solid white;
                border-top: 4px solid transparent;
                border-bottom: 4px solid transparent;
            }
        }
    </style>
@endpush

@section('content')
    <div class="container-fluid">
        <div class="main-content d-flex flex-column">
            <div class="card bg-white border-0 rounded-3 mb-4">
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show m-4" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show m-4" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
                @if ($errors->any())
                    <div class="alert alert-danger m-4">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1 class="mb-0">Gallery Management</h1>
                        <button type="button" class="btn btn-primary py-2 px-4 text-white fw-semibold" data-bs-toggle="modal" data-bs-target="#exampleModallg">
                            <i class="material-symbols-outlined align-middle">add</i> Create Gallery
                        </button>
                    </div>
                    @include('menu.adminlanding.gallery.create')

                    <div class="default-table-area all-products">
                        <div class="table-responsive">
                            <table id="gallery-table" class="display table align-middle" style="width:100%">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Title</th>
                                    <th>Description</th>
                                    <th>Photo</th>
                                    <th>Category</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @forelse ($galleries as $index => $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->title }}</td>
                                        <td>{{ \Illuminate\Support\Str::limit($item->description ?? '-', 90) }}</td>
                                        <td>
                                            @php
                                                $preview = $item->image_url ?? asset('assets/logo_BANPDMJATIM.png');
                                                $isVideo = optional($item->category)->name === 'video';
                                                // Anggap preview image jika URL berakhir jpg|png|gif|webp atau mengandung uc?export=view
                                                $isImagePreview = preg_match('/\.(jpe?g|png|gif|webp)(\?|$)/i', $preview) || strpos($preview, 'uc?export=view') !== false || strpos($preview, 'drive.googleusercontent.com') !== false;
                                            @endphp
                                            @if ($isVideo && ! $isImagePreview)
                                                {{-- tampilkan placeholder dengan play (karena Drive video preview bukan file gambar langsung) --}}
                                                <a href="{{ $preview }}" target="_blank" rel="noopener noreferrer" title="Open preview">
                                                    <div class="thumb">
                                                        <img src="{{ asset('assets/logo_BANPDMJATIM.png') }}" alt="video-thumb-{{ $item->id }}">
                                                        <div class="play"><span class="triangle"></span></div>
                                                        </div>
                                                </a>
                                            @else
                                                <div class="thumb">
                                                    <img src="{{ $preview }}" alt="thumb-{{ $item->id }}">
                                                    @if ($isVideo)
                                                        <div class="play"><span class="triangle"></span></div>
                                                    @endif
                                                </div>
                                            @endif

                                        </td>
                                        <td>{{ $item->category?->name ?? '-' }}</td>
                                        <td>
                                            <span class="badge {{ $item->is_active ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $item->is_active ? 'Published' : 'Draft' }}
                                            </span>
                                        </td>
                                        <td>{{ optional($item->created_at)->format('Y-m-d') }}</td>
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
                                                @include('menu.adminlanding.gallery.edit', ['gallery' => $item, 'categories' => $categories])

                                                <form action="{{ route('adminlanding.gallery.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Delete this item?');" class="m-0 d-inline-flex align-items-center">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger" title="Delete">
                                                        <i class="material-symbols-outlined fs-16 align-middle">delete</i>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>

                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No gallery items found.</td>
                                    </tr>
                                @endforelse
                                </tbody>
                            </table>
                        </div>

                    </div>
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
        $(document).ready(function () {
            const table = $('#gallery-table').DataTable({
                responsive: true,
                pageLength: 10,
                lengthMenu: [[10, 25, 50, 100, -1], [10, 25, 50, 100, "All"]],
                order: [[6, 'desc']], // Order by Created date descending
                columnDefs: [
                    { orderable: false, searchable: false, targets: 0 }, // No
                    { orderable: false, searchable: false, targets: 3 }, // Photo
                    { orderable: false, searchable: false, targets: 7 }  // Action
                ],
                language: {
                    search: "Search:",
                    lengthMenu: "Show _MENU_ entries",
                    info: "Showing _START_ to _END_ of _TOTAL_ entries",
                    infoEmpty: "Showing 0 to 0 of 0 entries",
                    infoFiltered: "(filtered from _MAX_ total entries)",
                    emptyTable: "No gallery items found",
                    zeroRecords: "No matching records found",
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    }
                }
            });

            // Re-number the "No" column after ordering, searching, paging or drawing
            table.on('order.dt search.dt page.dt draw.dt', function () {
                table.column(0, { order: 'applied', search: 'applied' }).nodes().each(function (cell, i) {
                    cell.innerHTML = i + 1;
                });
            });

            // Initial numbering
            table.draw();
        });
    </script>
@endpush
