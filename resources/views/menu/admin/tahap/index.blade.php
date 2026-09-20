@extends('app.layout')
@section('title', 'Visitasi — Tahap')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @include('partial.table-ux')
@endpush

@section('content')
<div class="container-fluid">
    <div class="main-content d-flex flex-column">
        <div class="card bg-white border-0 rounded-3 mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
                    <div>
                        <h4 class="mb-1">Tahap</h4>
                        <p class="text-muted mb-0 fs-14">Kelola tahap visitasi, pantau kesanggupan asesor, lalu pasangkan tim dan lembaga.</p>
                    </div>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModallg">
                        <i class="material-symbols-outlined align-middle" style="font-size:18px">add</i> Buat Tahap
                    </button>
                </div>

                <div class="table-responsive">
                    <table id="tahap-table" class="display table table-sm align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th style="width:48px">No</th>
                                <th>Tahap</th>
                                <th>No. SK</th>
                                <th>Periode</th>
                                <th>Bisa</th>
                                <th>Tidak Bisa</th>
                                <th>Belum Mengisi</th>
                                <th>Lembaga</th>
                                <th>Tim</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($tahaps as $t)
                                @php
                                    $belum = max(0, $totalAsesor - ($t->bisa_count + $t->tidak_count));
                                @endphp
                                <tr>
                                    <td></td>
                                    <td>
                                        <div class="fw-semibold">{{ $t->tahap }}</div>
                                        @if($t->pairing_locked_at)
                                            <span class="badge bg-danger"><i class="material-symbols-outlined align-middle" style="font-size:13px">lock</i> Terkunci</span>
                                        @elseif($t->finalized_teams_count > 0)
                                            <span class="badge bg-success">Final</span>
                                        @else
                                            <span class="badge bg-light text-dark border">Terbuka</span>
                                        @endif
                                    </td>
                                    <td>{{ $t->surat_keputusan ?? '-' }}</td>
                                    <td class="text-nowrap">{{ optional($t->start_date)->translatedFormat('d M Y') ?? '-' }} — {{ optional($t->end_date)->translatedFormat('d M Y') ?? '-' }}</td>
                                    <td><span class="badge bg-success">{{ $t->bisa_count }}</span></td>
                                    <td><span class="badge bg-danger">{{ $t->tidak_count }}</span></td>
                                    <td><span class="badge bg-secondary">{{ $belum }}</span></td>
                                    <td><span class="badge bg-light text-dark border">{{ $t->lembagas_count }}</span></td>
                                    <td><span class="badge bg-light text-dark border">{{ $t->finalized_teams_count }}</span></td>
                                    <td>
                                        <div class="d-flex gap-1 flex-wrap">
                                            <a href="{{ route('admin.tahap.show', $t) }}" class="btn btn-sm btn-outline-primary" title="Lihat asesor" aria-label="Lihat asesor">
                                                <i class="material-symbols-outlined align-middle" style="font-size:18px">visibility</i>
                                            </a>
                                            <a href="{{ route('admin.tahap.lembaga.index', ['tahap' => $t->slug]) }}" class="btn btn-sm btn-outline-secondary" title="Lihat lembaga" aria-label="Lihat lembaga">
                                                <i class="material-symbols-outlined align-middle" style="font-size:18px">account_balance</i>
                                            </a>
                                            @if($t->generation_runs_count > 0)
                                                <a href="{{ route('admin.tahap.generation.index', ['tahap' => $t->slug]) }}" class="btn btn-sm btn-outline-warning" title="Hasil generate" aria-label="Hasil generate">
                                                    <i class="material-symbols-outlined align-middle" style="font-size:18px">insights</i>
                                                </a>
                                            @endif
                                            <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#editModal-{{ $t->id }}" title="Edit" aria-label="Edit">
                                                <i class="material-symbols-outlined align-middle" style="font-size:18px">edit</i>
                                            </button>
                                        </div>
                                        @include('menu.admin.tahap.edit', ['tahap' => $t])
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

@include('menu.admin.tahap.create')
@endsection

@push('scripts')
    <script>
        $(function () {
            if ($.fn && $.fn.DataTable && $('#tahap-table').length) {
                $('#tahap-table').DataTable({
                    pageLength: 25,
                    order: [[1, 'asc']],
                    columnDefs: [{ orderable: false, searchable: false, targets: [0, 9] }],
                    language: { emptyTable: 'Belum ada tahap.' },
                    drawCallback: function (settings) {
                        this.api().column(0, { search: 'applied', order: 'applied' }).nodes()
                            .each(function (cell, i) { cell.innerHTML = settings._iDisplayStart + i + 1; });
                    }
                });
            }
        });
    </script>
@endpush
