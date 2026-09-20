@extends('app.layout')
@section('title', 'Kesanggupan Asesor')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @include('partial.table-ux')
@endpush

@section('content')
<div class="container-fluid">
    <div class="main-content d-flex flex-column">
        <div class="card bg-white border-0 rounded-3 mb-4">
            <div class="card-body p-4">
                <nav aria-label="breadcrumb" class="mb-3">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.tahap.index') }}">Visitasi</a></li>
                        <li class="breadcrumb-item active">Kesanggupan Asesor</li>
                    </ol>
                </nav>

                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-2">
                    <div>
                        <h4 class="mb-1">Data Kesanggupan Asesor</h4>
                        <p class="text-muted mb-0 fs-14">Tahap yang masih berjalan — siapa bersedia/tidak, berapa lembaga yang disanggupi, dan alasannya.</p>
                    </div>
                    <span class="badge bg-primary px-3 py-2">{{ $kesanggupans->count() }} isian</span>
                </div>

                <div class="table-responsive">
                    <table id="kesanggupan-table" class="display table table-sm align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Asesor</th>
                                <th>Tahap</th>
                                <th>Bersedia</th>
                                <th>Kesanggupan</th>
                                <th>Alasan</th>
                                <th>Diisi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($kesanggupans as $k)
                                <tr>
                                    <td></td>
                                    <td>
                                        <div class="fw-semibold">{{ $k->user?->name ?? '-' }}</div>
                                        @if($k->user?->nia)
                                            <div class="text-muted fs-13">({{ $k->user->nia }})</div>
                                        @endif
                                    </td>
                                    <td>{{ $k->tahap?->tahap ?? '-' }}</td>
                                    <td>
                                        @if($k->kesediaan)
                                            <span class="badge bg-success">Bersedia</span>
                                        @else
                                            <span class="badge bg-danger">Tidak</span>
                                        @endif
                                    </td>
                                    <td>{{ $k->kesediaan ? ($k->kesanggupan ?? '-') : '-' }}</td>
                                    <td>{{ $k->alasan ? \Illuminate\Support\Str::limit($k->alasan, 90) : '-' }}</td>
                                    <td class="text-nowrap">{{ optional($k->updated_at ?? $k->created_at)->format('d M Y') }}</td>
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
    <script>
        $(function () {
            if ($.fn && $.fn.DataTable && $('#kesanggupan-table').length) {
                $('#kesanggupan-table').DataTable({
                    pageLength: 25,
                    order: [[1, 'asc']],
                    columnDefs: [
                        { orderable: false, searchable: false, targets: [0] }
                    ],
                    language: { emptyTable: 'Belum ada data kesanggupan.' },
                    drawCallback: function (settings) {
                        const api = this.api();
                        api.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
                            cell.innerHTML = settings._iDisplayStart + i + 1;
                        });
                    }
                });
            }
        });
    </script>
@endpush
