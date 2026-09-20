@extends('app.layout')
@section('title', 'Validasi')

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
                        <h4 class="mb-1">Validasi</h4>
                        <p class="text-muted mb-0 fs-14">Buat validasi (Ya/Tidak + TTD) lalu pantau jawaban asesor.</p>
                    </div>
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#createValidasiModal">
                        <i class="material-symbols-outlined align-middle" style="font-size:18px">add</i> Buat Validasi
                    </button>
                </div>

                <div class="table-responsive">
                    <table id="validasi-table" class="display table table-sm align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th style="width:48px">No</th>
                                <th>Nama</th>
                                <th>No. SK</th>
                                <th>Periode</th>
                                <th>Bisa</th>
                                <th>Tidak Bisa</th>
                                <th>Belum Mengisi</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($validasis as $v)
                                @php
                                    $belum = max(0, $totalAsesor - ($v->bisa_count + $v->tidak_count));
                                @endphp
                                <tr>
                                    <td></td>
                                    <td class="fw-semibold">{{ $v->nama }}</td>
                                    <td>{{ $v->surat_keputusan ?? '-' }}</td>
                                    <td class="text-nowrap">{{ optional($v->start_date)->translatedFormat('d M Y') ?? '-' }} — {{ optional($v->end_date)->translatedFormat('d M Y') ?? '-' }}</td>
                                    <td><span class="badge bg-success">{{ $v->bisa_count }}</span></td>
                                    <td><span class="badge bg-danger">{{ $v->tidak_count }}</span></td>
                                    <td><span class="badge bg-secondary">{{ $belum }}</span></td>
                                    <td>
                                        @if($v->pairing_locked_at)
                                            <span class="badge bg-danger"><i class="material-symbols-outlined align-middle" style="font-size:14px">lock</i> Terkunci</span>
                                        @else
                                            <span class="badge bg-light text-dark border">Terbuka</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1 flex-wrap">
                                            <a href="{{ route('admin.validasi.show', $v) }}" class="btn btn-sm btn-outline-primary" title="Lihat" aria-label="Lihat"><i class="material-symbols-outlined align-middle" style="font-size:18px">visibility</i></a>
                                            <form action="{{ route('admin.validasi.destroy', $v) }}" method="POST" class="js-confirm-form d-inline"
                                                  data-title="Hapus Validasi" data-message="Hapus validasi ini beserta notifikasinya?">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-outline-danger" title="Hapus" aria-label="Hapus"><i class="material-symbols-outlined align-middle" style="font-size:18px">delete</i></button>
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

{{-- Modal buat validasi --}}
<div class="modal fade" id="createValidasiModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.validasi.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Buat Validasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fs-14">Nama Validasi</label>
                        <input type="text" name="nama" class="form-control" required placeholder="contoh: Validasi Tahap 5">
                    </div>
                    <div class="mb-3">
                        <label class="form-label fs-14">Nomor SK</label>
                        <input type="text" name="surat_keputusan" class="form-control" placeholder="opsional">
                    </div>
                    <div class="row">
                        <div class="col-6">
                            <label class="form-label fs-14">Mulai</label>
                            <input type="datetime-local" name="start_date" class="form-control">
                        </div>
                        <div class="col-6">
                            <label class="form-label fs-14">Selesai</label>
                            <input type="datetime-local" name="end_date" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@include('partial.confirm-modal')
@endsection

@push('scripts')
    <script>
        $(function () {
            function askConfirm(title, message, onOk) {
                $('#confirmModalTitle').text(title);
                $('#confirmModalBody').text(message);
                $('#confirmModalOk').off('click').on('click', function () {
                    bootstrap.Modal.getInstance(document.getElementById('confirmModal')).hide();
                    onOk();
                });
                new bootstrap.Modal(document.getElementById('confirmModal')).show();
            }
            $(document).on('submit', '.js-confirm-form', function (e) {
                e.preventDefault();
                const form = this;
                askConfirm($(form).data('title'), $(form).data('message'), function () { form.submit(); });
            });

            if ($.fn && $.fn.DataTable && $('#validasi-table').length) {
                $('#validasi-table').DataTable({
                    pageLength: 25,
                    order: [[1, 'asc']],
                    columnDefs: [{ orderable: false, searchable: false, targets: [0, 8] }],
                    language: { emptyTable: 'Belum ada validasi.' },
                    drawCallback: function (settings) {
                        this.api().column(0, { search: 'applied', order: 'applied' }).nodes()
                            .each(function (cell, i) { cell.innerHTML = settings._iDisplayStart + i + 1; });
                    }
                });
            }
        });
    </script>
@endpush
