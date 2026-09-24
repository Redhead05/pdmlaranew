@extends('app.layout')
@section('title', 'Pilih Lembaga — ' . $tahap->tahap)

@push('styles')
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
                    <div class="d-flex align-items-center gap-2 flex-wrap">
                        <button type="button" class="btn btn-outline-primary" id="btn-upload-npsn"
                                title="Upload Excel/CSV berisi kolom NPSN. NPSN yang cocok akan otomatis dicentang.">
                            <i class="material-symbols-outlined align-middle" style="font-size:18px">upload_file</i>
                            Cek NPSN (Excel)
                        </button>
                        <input type="file" id="npsn-file" class="d-none" accept=".xlsx,.xls,.csv,.txt">
                        <form id="attach-form" method="POST" action="{{ route('admin.tahap.lembaga.attach', ['tahap' => $tahap->slug]) }}">
                            @csrf
                            <div id="selected-ids"></div>
                            <button type="submit" class="btn btn-success" id="submit-btn" disabled>
                                Tambah <span id="count-label">0</span> Lembaga
                            </button>
                        </form>
                    </div>
                </div>

                <div class="text-muted fs-13 mt-2">
                    <i class="material-symbols-outlined align-middle" style="font-size:16px">info</i>
                    Upload file Excel/CSV dengan kolom <strong>npsn</strong>. NPSN yang ada di aplikasi akan otomatis dicentang; NPSN yang tidak ditemukan akan ditampilkan di toast.
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

{{-- Toast container --}}
<div class="toast-container position-fixed top-0 end-0 p-3" id="toast-container" style="z-index:1080"></div>
@endsection

@push('scripts')
    <script>
        function initPilihLembagaPage() {
            var $table = $('#pilih-lembaga-table');
            if (!$table.length || $table.data('init')) return;
            if (!$.fn || !$.fn.DataTable) return;
            $table.data('init', true);

            const selected = new Set();

            function escHtml(s) {
                return String(s ?? '').replace(/[&<>"']/g, c => ({
                    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
                }[c]));
            }

            function showToast(title, message, type) {
                if (!message) return;
                type = type || 'success';
                const box = document.getElementById('toast-container');
                if (!box) return;
                const el = document.createElement('div');
                el.className = 'toast align-items-center text-bg-' + type + ' border-0 show';
                el.innerHTML = '<div class="d-flex">'
                    + '<div class="toast-body"><strong>' + escHtml(title) + '</strong>'
                    + '<div class="fs-14">' + message + '</div></div>'
                    + '<button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>'
                    + '</div>';
                box.appendChild(el);
                new bootstrap.Toast(el, { delay: 10000 }).show();
                el.addEventListener('hidden.bs.toast', () => el.remove());
            }

            const table = $table.DataTable({
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

            // ----- Upload NPSN (Excel/CSV) -> auto-centang + toast NPSN tidak ada -----
            const npsnUrl = "{{ route('admin.tahap.lembaga.check-npsn', ['tahap' => $tahap->slug]) }}";
            const npsnFileInput = $('#npsn-file');
            const npsnButton = $('#btn-upload-npsn');

            npsnButton.on('click', function () {
                npsnFileInput.trigger('click');
            });

            npsnFileInput.on('change', function () {
                const file = this.files && this.files[0];
                if (!file) return;

                const fd = new FormData();
                fd.append('file', file);

                npsnButton.prop('disabled', true);
                $.ajax({
                    url: npsnUrl,
                    type: 'POST',
                    data: fd,
                    processData: false,
                    contentType: false,
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    dataType: 'json'
                }).done(function (res) {
                    let checked = 0;
                    (res.available || []).forEach(function (item) {
                        selected.add(String(item.id));
                        checked++;
                    });

                    if (checked > 0) {
                        // Redraw halaman aktif; drawCallback memulihkan centang dari Set.
                        table.ajax.reload(null, false);
                        showToast('Sukses', checked + ' NPSN ditemukan dan dicentang.', 'success');
                    }

                    const missing = res.missing || [];
                    if (missing.length > 0) {
                        showToast('NPSN Tidak Ditemukan', missing.map(escHtml).join(', '), 'danger');
                    }

                    const already = res.already || [];
                    if (already.length > 0) {
                        showToast('Sudah di Tahap Ini', already.map(escHtml).join(', '), 'info');
                    }

                    const conflict = res.conflict || [];
                    if (conflict.length > 0) {
                        showToast('Dipakai Tahap Lain', conflict.map(function (c) {
                            return escHtml(c.npsn) + ' (' + escHtml(c.tahap) + ')';
                        }).join(', '), 'warning');
                    }

                    updateCount();
                }).fail(function (xhr) {
                    const res = xhr.responseJSON || {};
                    showToast('Gagal', escHtml(res.message || 'Terjadi kesalahan saat membaca file.'), 'danger');
                }).always(function () {
                    npsnButton.prop('disabled', false);
                    npsnFileInput.val('');
                });
            });
        }

        initPilihLembagaPage();
        if (window.__registerDataTableInit) window.__registerDataTableInit('pilih-lembaga', initPilihLembagaPage);
    </script>
@endpush
