@extends('app.layout')
@section('title', 'Gallery Management')

@section('content')
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
                    <h1>Gallery</h1>

                    <div class="mb-3 d-flex align-items-center gap-2">
                        <button type="button" class="btn btn-primary py-2 px-4 te xt-white fw-semibold" data-bs-toggle="modal" data-bs-target="#exampleModallg">
                            <i class="material-symbols-outlined align-middle">add</i> Create
                        </button>
                        @include('menu.adminlanding.gallery.create')
                    </div>

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
                                        <td>{{ $galleries->firstItem() + $index }}</td>
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
    <script src="//code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="//cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            const table = $('#gallery-table').DataTable({
                responsive: true,
                pageLength: 10,
                columnDefs: [
                    { orderable: false, searchable: false, targets: 0 }, // No
                    { orderable: false, searchable: false, targets: 7 }  // Action
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
