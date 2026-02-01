@extends('app.layout')
@section('title', 'Faq')
@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush
@section('content')
    <div class="container-fluid">
        <div class="main-content d-flex flex-column">
            <div class="card bg-white border-0 rounded-3 mb-4">
                <div class="card-body p-4">
                    <h1>Faq</h1>
                    <div class="mb-3">
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#faqCreateModal">
                            <i class="material-symbols-outlined align-middle">add</i> Create
                        </button>

                        {{-- include create modal once (fix: do not pass undefined $item) --}}
                        @include('menu.adminlanding.faq.create')
                    </div>
                    <div class="default-table-area all-products">
                        <div class="table-responsive">
                        <table id="news-table" class="display table align-middle" style="width:100%">
                            <thead>
                            <tr>
                                <th style="width:60px">No</th>
                                <th>Question</th>
                                <th>Status</th>
                                <th>Created</th>
                                <th style="width:150px">Action</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($faqs as $index => $faq)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{!! \Illuminate\Support\Str::limit(strip_tags($faq->question ?? ''), 100) !!}</td>
                                    <td>
                                        @if($faq->is_active)
                                            <span class="badge bg-success">Published</span>
                                        @else
                                            <span class="badge bg-secondary">Draft</span>
                                        @endif
                                    </td>
                                    <td>{{ $faq->created_at ? $faq->created_at->format('d M Y') : '-' }}</td>
                                    <td>
                                        <a href="#" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#faqEditModal-{{ $faq->id }}">Edit</a>

                                        <form action="{{ route('adminlanding.faq.destroy', $faq->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Delete this FAQ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="text-center">No FAQs found.</td>
                                </tr>
                            @endforelse
                            </tbody>

                            {{-- include create modal and per-item edit modals (edit blade expects $faq) --}}
                            @include('menu.adminlanding.faq.create')
                            @foreach($faqs as $faq)
                                @include('menu.adminlanding.faq.edit', ['faq' => $faq])
                            @endforeach
                        </table>

{{--                        {{ $faqs->links() }}--}}
                    </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
@endpush
@push('scripts')
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


