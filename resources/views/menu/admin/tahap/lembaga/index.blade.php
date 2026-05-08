@extends('app.layout')
@section('title', 'lembaga')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@section('content')
    <div class="container-fluid">
        <div class="main-content d-flex flex-column">
            <div class="card bg-white border-0 rounded-3 mb-4">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <h4 class="mb-0">Lembaga — Tahap {{ $tahap->tahap ?? '-' }}</h4>
                            <div class="text-muted">SK: {{ $tahap->surat_keputusan ?? '-' }}</div>
                        </div>

                        <div class="d-flex gap-2">
                            <a href="{{ route('admin.tahap.lembaga.template', ['tahap' => $tahap->slug]) }}" class="btn btn-outline-secondary">Download Template (.csv)</a>

                            {{-- Inline upload form: submits to upload route --}}
                            <form action="{{ route('admin.tahap.lembaga.upload', ['tahap' => $tahap->slug]) }}" method="POST" enctype="multipart/form-data" class="d-flex align-items-center">
                                @csrf
                                <input type="file" name="file" accept=".csv" class="form-control form-control-sm me-2" required>
                                <button class="btn btn-primary btn-sm">Upload</button>
                            </form>
                        </div>
                    </div>

                    {{-- Toast messages --}}
                    <div aria-live="polite" aria-atomic="true" class="position-relative">
                        <div id="toast-container" class="position-fixed top-0 end-0 p-3" style="z-index: 1080"></div>
                    </div>

                    <div class="table-responsive mt-3">

                        <table id="lembaga-table" class="display table table-sm align-middle" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NPSN</th>
                                    <th>Nama</th>
                                    <th>Kabupaten</th>
                                    <th>Kecamatan</th>
                                    <th>Latitude</th>
                                    <th>Longitude</th>
                                    <th>Action</th>
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
            if (!$.fn || !$.fn.DataTable) return;

            const table = $('#lembaga-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('admin.tahap.lembaga.index', ['tahap' => $tahap->slug]) }}",
                    type: 'GET'
                },
                // preview handling via session on server
                columns: [
                    { data: null, orderable: false, searchable: false },
                    { data: 'npsn' },
                    { data: 'satuan_pen' },
                    { data: 'kabupaten' },
                    { data: 'kecamatan' },
                    { data: 'latitude' },
                    { data: 'longitude' },
                    { data: 'action', orderable: false, searchable: false }
                ],
                pageLength: 25,
                responsive: true,
                order: [[1, 'asc']],
                drawCallback: function (settings) {
                    var api = this.api();
                    api.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
                        cell.innerHTML = settings._iDisplayStart + i + 1;
                    });
                }
            });

            // show toasts based on session flash
            const toastContainer = $('#toast-container');
            function showToast(title, body, type = 'success') {
                const toastId = 'toast-' + Math.random().toString(36).substr(2, 9);
                const toastHtml = `
                    <div id="${toastId}" class="toast align-items-center text-bg-${type} border-0 mb-2" role="alert" aria-live="assertive" aria-atomic="true">
                      <div class="d-flex">
                        <div class="toast-body">
                          <strong>${title}</strong><br>${body}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                      </div>
                    </div>`;
                toastContainer.append(toastHtml);
                const el = document.getElementById(toastId);
                const bsToast = new bootstrap.Toast(el, { delay: 8000 });
                bsToast.show();
            }

            // server flashed messages
            @if(session('success'))
                showToast('Sukses', `{!! addslashes(session('success')) !!}`, 'success');
            @endif

            @if(session('unmatched') && count(session('unmatched')) > 0)
                showToast('NPSN Tidak Ditemukan', `{!! addslashes(implode('<br>', (array) session('unmatched'))) !!}`, 'warning');
            @endif

            @if(session('conflicts') && count(session('conflicts')) > 0)
                @php
                    $confLines = [];
                    foreach((array) session('conflicts') as $c) {
                        $tname = $c['tahap'] ?? 'Unknown Tahap';
                        $npsn = $c['npsn'] ?? '';
                        $confLines[] = "Tahap [{$tname}] - NPSN: {$npsn}";
                    }
                @endphp
                showToast('Conflict', `{!! addslashes('Beberapa lembaga sudah terpakai di tahap lain:<br>' . implode('<br>', $confLines)) !!}`, 'danger');
            @endif
        });
    </script>
@endpush
