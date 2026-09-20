@extends('app.layout')
@section('title', 'Penugasan — Visitasi')

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
                        <li class="breadcrumb-item"><a href="{{ route('asesor.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Visitasi</li>
                    </ol>
                </nav>

                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
                    <div>
                        <h4 class="mb-1">Penugasan Saya</h4>
                        <p class="text-muted mb-0 fs-14">
                            Daftar surat tugas visitasi yang diterbitkan untuk Anda. Klik <em>Lihat</em> untuk membaca
                            atau <em>Download</em> untuk mencetak/menyimpan PDF.
                        </p>
                    </div>
                    <span class="badge bg-primary px-3 py-2"><span class="material-symbols-outlined align-middle" style="font-size:16px">assignment</span> Surat Tugas</span>
                </div>

                <div class="table-responsive">
                    <table id="visitasi-table" class="display table table-sm align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th style="width:48px">No</th>
                                <th>Tahap</th>
                                <th>No. SK</th>
                                <th>No. ST</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
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
            if (!$.fn || !$.fn.DataTable || !$('#visitasi-table').length) return;

            const url = "{{ route('asesor.visitasi.data') }}";

            $('#visitasi-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: { url: url, type: 'GET' },
                pageLength: 10,
                order: [[1, 'asc']],
                columns: [
                    { data: null, render: function () { return ''; }, orderable: false, searchable: false },
                    { data: 'tahap' },
                    { data: 'surat_keputusan' },
                    { data: 'nomor_st' },
                    { data: 'action', orderable: false, searchable: false },
                ],
                language: {
                    emptyTable: 'Belum ada surat tugas untuk Anda.',
                    search: 'Cari:',
                    lengthMenu: 'Tampilkan _MENU_ data',
                },
                drawCallback: function (settings) {
                    this.api().column(0, { search: 'applied', order: 'applied' }).nodes()
                        .each(function (cell, i) { cell.innerHTML = settings._iDisplayStart + i + 1; });
                },
            });
        });
    </script>
@endpush
