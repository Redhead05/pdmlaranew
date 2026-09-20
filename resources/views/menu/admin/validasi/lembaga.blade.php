@extends('app.layout')
@section('title', 'Pairing Lembaga — ' . $validasi->nama)

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
                        <li class="breadcrumb-item"><a href="{{ route('admin.validasi.index') }}">Validasi</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.validasi.show', $validasi) }}">{{ $validasi->nama }}</a></li>
                        <li class="breadcrumb-item active">Pairing Lembaga</li>
                    </ol>
                </nav>

                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                    <div>
                        <h4 class="mb-1">Pairing Lembaga &amp; Asesor Validasi</h4>
                        <p class="text-muted mb-0 fs-14">
                            Lembaga dari visitasi yang sudah terkunci dapat dipasangkan ke asesor yang menyatakan "Bisa".
                            Lembaga tidak boleh dipasangkan dengan asesor dari kabupaten/kota yang sama.
                        </p>
                    </div>
                    <a href="{{ route('admin.validasi.show', $validasi) }}" class="btn btn-outline-secondary">
                        <i class="material-symbols-outlined align-middle" style="font-size:18px">arrow_back</i> Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="card bg-white border-0 rounded-3 mb-4">
            <div class="card-body p-4">
                <div class="d-flex flex-wrap align-items-center gap-2 mb-3">
                    <button type="button" class="btn btn-sm btn-success btn-auto-pair">
                        <span class="material-symbols-outlined align-middle" style="font-size:16px">bolt</span> Pair Otomatis
                    </button>
                    <button type="button" class="btn btn-sm btn-primary btn-bulk-assign">
                        <span class="material-symbols-outlined align-middle" style="font-size:16px">person_add</span> Assign Asesor (terpilih)
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-danger btn-bulk-unassign">
                        <span class="material-symbols-outlined align-middle" style="font-size:16px">link_off</span> Lepas Pasangan (terpilih)
                    </button>
                    <span class="vr mx-1 d-none d-md-inline"></span>
                    <button type="button" id="copy-btn" class="btn btn-sm btn-outline-secondary">
                        <span class="material-symbols-outlined align-middle" style="font-size:16px">content_copy</span> Copy
                    </button>
                    <a href="{{ route('admin.validasi.lembaga.download', $validasi) }}" class="btn btn-sm btn-outline-success">
                        <span class="material-symbols-outlined align-middle" style="font-size:16px">download</span> Download Excel
                    </a>
                    <form method="POST" action="{{ route('admin.validasi.lembaga.import', $validasi) }}" enctype="multipart/form-data" class="d-flex gap-2">
                        @csrf
                        <input type="file" name="file" accept=".csv,.txt,.xlsx" class="form-control form-control-sm" style="max-width:240px" required>
                        <button type="submit" class="btn btn-sm btn-primary">Import</button>
                    </form>
                    @if($validasi->pairing_locked_at)
                        <span class="vr mx-1 d-none d-md-inline"></span>
                        @if($validasi->surat_tugas_notification_sent_at || $validasi->surat_tugas_slug)
                            <a href="{{ route('admin.validasi.surat-tugas', $validasi) }}" class="btn btn-sm btn-outline-primary">
                                <span class="material-symbols-outlined align-middle" style="font-size:16px">description</span> Lihat Surat Tugas
                            </a>
                        @endif
                        <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#suratTugasModal">
                            <span class="material-symbols-outlined align-middle" style="font-size:16px">send</span>
                            {{ $validasi->surat_tugas_notification_sent_at ? 'Kirim Ulang Surat Tugas' : 'Kirim Surat Tugas' }}
                        </button>
                    @endif
                </div>

                <div class="table-responsive">
                    <table id="lembaga-table" class="display table table-sm align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th style="width:36px"><input type="checkbox" class="form-check-input select-all"></th>
                                <th>No</th>
                                <th>NPSN</th>
                                <th>Nama Lembaga</th>
                                <th>Kabupaten</th>
                                <th>NIA Asesor</th>
                                <th>Nama Asesor</th>
                                <th>Home City</th>
                                <th class="d-none"></th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal assign asesor --}}
<div class="modal fade" id="assignModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Assign Asesor</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="assign-lembaga-ids" value="[]">
                <p class="text-muted fs-13 mb-2" id="assign-hint">Pilih asesor (hanya yang menyatakan "Bisa" dan bukan dari kabupaten/kota yang sama).</p>
                <select class="form-select" id="assign-user-select"></select>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-sm btn-primary" id="assign-submit">Simpan</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal kirim surat tugas --}}
@php $suggestedSt = 'ST/VAL/'.str_pad((string) $validasi->id, 3, '0', STR_PAD_LEFT).'/'.now()->format('Y'); @endphp
<div class="modal fade" id="suratTugasModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.validasi.surat-tugas.send', $validasi) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Kirim Surat Tugas Validasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label fs-14">Nomor Surat Tugas</label>
                    <input type="text" name="nomor_st" class="form-control" value="{{ $validasi->surat_tugas_number ?? $suggestedSt }}" required>
                    <div class="form-text">Notifikasi akan dikirim ke seluruh asesor yang menyatakan "Bisa".</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary">Kirim</button>
                </div>
            </form>
        </div>
    </div>
</div>

@include('partial.confirm-modal')

{{-- Toast --}}
<div class="toast-container position-fixed top-0 end-0 p-3" id="toast-container" style="z-index:1080"></div>
@endsection

@push('scripts')
    <script>
        $(function () {
            const csrf = '{{ csrf_token() }}';
            const dataUrl = "{{ route('admin.validasi.lembaga.data', $validasi) }}";
            const optionsUrl = "{{ route('admin.validasi.lembaga.asesor-options', $validasi) }}";
            const assignUrl = "{{ route('admin.validasi.lembaga.assign', $validasi) }}";
            const unassignUrl = "{{ route('admin.validasi.lembaga.unassign', $validasi) }}";
            const copyUrl = "{{ route('admin.validasi.lembaga.copy', $validasi) }}";
            const autoPairUrl = "{{ route('admin.validasi.lembaga.auto-pair', $validasi) }}";

            function esc(s){return String(s ?? '').replace(/[&<>"']/g, c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));}
            function showToast(title, msg, type){ type=type||'success'; const box=document.getElementById('toast-container'); if(!box) return; const el=document.createElement('div'); el.className='toast align-items-center text-bg-'+type+' border-0 show'; el.innerHTML='<div class="d-flex"><div class="toast-body"><strong>'+esc(title)+'</strong><div class="fs-14">'+msg+'</div></div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div>'; box.appendChild(el); new bootstrap.Toast(el,{delay:6000}).show(); }
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

            @if(session('success')) showToast('Sukses', @json(session('success')), 'success'); @endif
            @if(session('error')) showToast('Gagal', @json(session('error')), 'danger'); @endif
            @if(session('info')) showToast('Info', @json(session('info')), 'info'); @endif

            // Pair Otomatis (AJAX) -> reload datatable
            $(document).on('click', '.btn-auto-pair', function () {
                askConfirm('Pair Otomatis', 'Pasangkan otomatis semua lembaga yang belum terpasang ke asesor "Bisa" (kabupaten/kota tidak boleh sama)?', function () {
                    $.ajax({ url: autoPairUrl, type: 'POST', data: { _token: csrf }, dataType: 'json' })
                        .done(function (res) { showToast('Pair Otomatis', res.message, res.ok ? 'success' : 'warning'); table.ajax.reload(null, false); })
                        .fail(function (x) { showToast('Gagal', (x.responseJSON && x.responseJSON.message) || 'Terjadi kesalahan.', 'danger'); });
                });
            });

            const table = $('#lembaga-table').DataTable({
                processing: true, serverSide: true, ajax: { url: dataUrl, type: 'GET' }, pageLength: 10, order: [[2, 'asc']],
                columns: [
                    { data: null, orderable: false, searchable: false, render: function(d,t,row){ return t==='display' ? '<input type="checkbox" class="form-check-input row-select" data-id="'+row.lembaga_id+'">' : ''; } },
                    { data: null, render: function(){ return ''; }, orderable: false, searchable: false },
                    { data: 'npsn' },
                    { data: 'name' },
                    { data: 'kabupaten' },
                    { data: 'nia_asesor', orderable: false, searchable: false },
                    { data: 'nama_asesor' },
                    { data: 'home_city' },
                    { data: 'asesor_nia', visible: false },
                ],
                language: { emptyTable: 'Tidak ada lembaga visitasi yang terkunci.' },
                drawCallback: function (settings) {
                    this.api().column(1, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) { cell.innerHTML = settings._iDisplayStart + i + 1; });
                },
            });

            function selectedIds() {
                return $('#lembaga-table').find('.row-select:checked').map(function(){ return $(this).data('id'); }).get();
            }

            function openAssign(ids, lembagaIdForFilter) {
                $('#assign-lembaga-ids').val(JSON.stringify(ids));
                $('#assign-user-select').html('<option value="">Memuat…</option>');
                const params = lembagaIdForFilter ? { lembaga_id: lembagaIdForFilter } : {};
                $.getJSON(optionsUrl, params, function (res) {
                    const list = res.data || [];
                    let html = '<option value="">— pilih asesor —</option>';
                    list.forEach(function (a) { html += '<option value="'+a.id+'">'+esc(a.name)+' (NIA '+esc(a.nia)+') — '+esc(a.kota)+'</option>'; });
                    $('#assign-user-select').html(html);
                }).fail(function(){ $('#assign-user-select').html('<option value="">Gagal memuat asesor</option>'); });
                new bootstrap.Modal(document.getElementById('assignModal')).show();
            }

            // Ubah NIA langsung di datatable -> nama & city ikut berubah (reload)
            $(document).on('change', '.nia-select', function () {
                const lembagaId = $(this).data('lembaga');
                const userId = $(this).val();
                const url = userId ? assignUrl : unassignUrl;
                const payload = userId ? { lembaga_ids: [lembagaId], user_id: userId } : { lembaga_ids: [lembagaId] };
                $.ajax({ url: url, type: 'POST', data: Object.assign({ _token: csrf }, payload), dataType: 'json' })
                    .done(function (res) { showToast('Berhasil', res.message, 'success'); table.ajax.reload(null, false); })
                    .fail(function (x) { showToast('Gagal', (x.responseJSON && x.responseJSON.message) || 'Terjadi kesalahan.', 'danger'); table.ajax.reload(null, false); });
            });
            $(document).on('click', '.btn-bulk-assign', function(){
                const ids = selectedIds();
                if (!ids.length) { showToast('Pilih', 'Pilih minimal 1 lembaga.', 'warning'); return; }
                openAssign(ids, null);
            });
            $(document).on('click', '.btn-bulk-unassign', function(){
                const ids = selectedIds();
                if (!ids.length) { showToast('Pilih', 'Pilih minimal 1 lembaga.', 'warning'); return; }
                askConfirm('Lepas Pasangan', 'Lepas pasangan asesor dari ' + ids.length + ' lembaga terpilih?', function(){
                    $.ajax({ url: unassignUrl, type:'POST', data:{_token:csrf, lembaga_ids: ids}, dataType:'json' })
                        .done(function(res){ showToast('Berhasil', res.message, 'success'); table.ajax.reload(null, false); })
                        .fail(function(x){ showToast('Gagal', (x.responseJSON && x.responseJSON.message) || 'Terjadi kesalahan.', 'danger'); });
                });
            });

            // Copy hasil pairing
            function fallbackCopy(text) {
                const ta = document.createElement('textarea');
                ta.value = text; ta.style.position = 'fixed'; ta.style.opacity = '0';
                document.body.appendChild(ta); ta.select();
                try { document.execCommand('copy'); showToast('Copy', 'Disalin ke clipboard.', 'success'); } catch (e) { showToast('Copy', 'Gagal menyalin otomatis.', 'danger'); }
                document.body.removeChild(ta);
            }
            $('#copy-btn').on('click', function(){
                $.getJSON(copyUrl, function(data){
                    const lines = [data.headers.join('	')];
                    (data.rows || []).forEach(function(r){ lines.push(r.join('	')); });
                    const text = lines.join('\n');
                    if (navigator.clipboard && navigator.clipboard.writeText) {
                        navigator.clipboard.writeText(text).then(function(){ showToast('Copy', (data.rows||[]).length+' baris disalin.', 'success'); }, function(){ fallbackCopy(text); });
                    } else { fallbackCopy(text); }
                }).fail(function(){ showToast('Copy', 'Gagal mengambil data.', 'danger'); });
            });
            $(document).on('change', '.select-all', function(){ $('#lembaga-table').find('.row-select').prop('checked', this.checked); });

            $('#assign-submit').on('click', function(){
                const ids = JSON.parse($('#assign-lembaga-ids').val() || '[]');
                const uid = $('#assign-user-select').val();
                if (!ids.length || !uid) { showToast('Pilih', 'Pilih lembaga dan asesor.', 'warning'); return; }
                $.ajax({ url: assignUrl, type:'POST', data:{_token:csrf, lembaga_ids: ids, user_id: uid}, dataType:'json' })
                    .done(function(res){ showToast('Berhasil', res.message, 'success'); bootstrap.Modal.getInstance(document.getElementById('assignModal')).hide(); table.ajax.reload(null, false); })
                    .fail(function(x){ showToast('Gagal', (x.responseJSON && x.responseJSON.message) || 'Terjadi kesalahan.', 'danger'); });
            });
        });
    </script>
@endpush
