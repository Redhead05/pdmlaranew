@extends('app.layout')
@section('title', 'Pilih Lembaga — ' . $tahap->tahap)

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="container-fluid">
    <div class="main-content d-flex flex-column">
        <div class="card bg-white border-0 rounded-3 mb-4">
            <div class="card-body p-4">

                <nav aria-label="breadcrumb" class="mb-3">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.tahap.index') }}">Visitasi</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.tahap.lembaga.index', ['tahap' => $tahap->slug]) }}">Lembaga Tahap</a></li>
                        <li class="breadcrumb-item active">Pilih dari Master</li>
                    </ol>
                </nav>

                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1">Pilih Lembaga dari Master</h4>
                        <p class="text-muted mb-0 fs-14">Tahap: {{ $tahap->tahap }} &nbsp;|&nbsp; Centang lalu klik "Tambah".</p>
                    </div>
                    <form id="attach-form" method="POST" action="{{ route('admin.tahap.lembaga.attach', ['tahap' => $tahap->slug]) }}">
                        @csrf
                        <div id="selected-ids"></div>
                        <button type="submit" class="btn btn-success" id="submit-btn" disabled>
                            Tambah <span id="count-label">0</span> Lembaga
                        </button>
                    </form>
                </div>

                <div class="table-responsive mt-3">
                    <table id="pilih-lembaga-table" class="display table table-sm align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th><input type="checkbox" id="check-all"></th>
                                <th>No</th>
                                <th>NPSN</th>
                                <th>Nama</th>
                                <th>Kab/Kota</th>
                                <th>Kecamatan</th>
                                <th>Jenjang</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
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
        $(function () {
            const selected = new Set();

            const table = $('#pilih-lembaga-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.tahap.lembaga.pilih', ['tahap' => $tahap->slug]) }}",
                    type: 'GET'
                },
                columns: [
                    { data: 'select', orderable: false, searchable: false },
                    { data: null, orderable: false, searchable: false },
                    { data: 'npsn' },
                    { data: 'satuan_pen' },
                    { data: 'kabupaten' },
                    { data: 'kecamatan' },
                    { data: 'jenjang' },
                    { data: 'status', orderable: false, searchable: false },
                ],
                pageLength: 25,
                order: [[2, 'asc']],
                drawCallback: function (settings) {
                    const api = this.api();
                    api.column(1, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
                        cell.innerHTML = settings._iDisplayStart + i + 1;
                    });
                    // Pertahankan status centang setelah redraw (pindah halaman / search).
                    $('#pilih-lembaga-table .lembaga-check').each(function () {
                        this.checked = selected.has(this.dataset.id);
                    });
                    updateCount();
                }
            });

            // Delegasi event centang (bertahan lintas halaman).
            $('#pilih-lembaga-table tbody').on('change', '.lembaga-check', function () {
                const id = this.dataset.id;
                if (this.checked) selected.add(id); else selected.delete(id);
                updateCount();
            });

            // Centang semua pada halaman aktif saja.
            $('#check-all').on('change', function () {
                const checked = this.checked;
                $('#pilih-lembaga-table .lembaga-check:not(:disabled)').each(function () {
                    this.checked = checked;
                    if (checked) selected.add(this.dataset.id); else selected.delete(this.dataset.id);
                });
                updateCount();
            });

            function updateCount() {
                const n = selected.size;
                $('#count-label').text(n);
                $('#submit-btn').prop('disabled', n === 0);
            }

            // Suntik hidden inputs sebelum submit.
            $('#attach-form').on('submit', function () {
                const box = $('#selected-ids').empty();
                selected.forEach(id => box.append(`<input type="hidden" name="lembaga_ids[]" value="${id}">`));
            });
        });
    </script>
@endpush
