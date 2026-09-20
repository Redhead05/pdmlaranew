@extends('app.layout')
@section('title', 'Validasi — ' . $validasi->nama)

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @include('partial.table-ux')
    <style>
        /* Motion: feedback & state legibility pada halaman show validasi */
        .toast.ksg-toast-in { animation: ksg-toast-in .22s cubic-bezier(0.16, 1, 0.3, 1) both; }
        @keyframes ksg-toast-in { from { opacity: 0; transform: translateY(8px); } to { opacity: 1; transform: translateY(0); } }

        tr.row-updated, tr.row-updated td { animation: ksg-row-pulse .6s ease-out; }
        @keyframes ksg-row-pulse { 0% { background-color: #D8FFC8; } 100% { background-color: transparent; } }
        body[data-theme="dark"] tr.row-updated,
        body[data-theme="dark"] tr.row-updated td { animation-name: ksg-row-pulse-dark; }
        @keyframes ksg-row-pulse-dark { 0% { background-color: rgba(55, 216, 10, .18); } 100% { background-color: transparent; } }

        .ksg-locked-badge { animation: ksg-locked-in .35s cubic-bezier(0.16, 1, 0.3, 1) both; }
        @keyframes ksg-locked-in { from { opacity: 0; transform: translateY(-4px); } to { opacity: 1; transform: translateY(0); } }

        @media (prefers-reduced-motion: reduce) {
            .toast.ksg-toast-in, tr.row-updated, tr.row-updated td, .ksg-locked-badge { animation: none; }
        }
    </style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="main-content d-flex flex-column">

        {{-- Header --}}
        <div class="card bg-white border-0 rounded-3 mb-4">
            <div class="card-body p-4">
                <nav aria-label="breadcrumb" class="mb-3">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.validasi.index') }}">Validasi</a></li>
                        <li class="breadcrumb-item active">{{ $validasi->nama }}</li>
                    </ol>
                </nav>

                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                    <div>
                        <h4 class="mb-1">Hasil Validasi — {{ $validasi->nama }}</h4>
                        <p class="text-muted mb-0 fs-14">
                            @if($validasi->surat_keputusan) SK: {{ $validasi->surat_keputusan }} &middot; @endif
                            Periode: {{ optional($validasi->start_date)->translatedFormat('d M Y') ?? '-' }} — {{ optional($validasi->end_date)->translatedFormat('d M Y') ?? '-' }}
                        </p>
                        @if($locked)
                            <span class="badge bg-danger mt-2 ksg-locked-badge"><i class="material-symbols-outlined align-middle" style="font-size:16px">lock</i> Terkunci — {{ optional($validasi->pairing_locked_at)->translatedFormat('d M Y H:i') }}</span>
                        @endif
                    </div>

                    <div class="d-flex flex-wrap gap-2">
                        @if($locked)
                            <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#pairRuleModal">
                                <i class="material-symbols-outlined align-middle" style="font-size:18px">hub</i> Pair Lembaga
                            </button>
                            <a href="{{ route('admin.validasi.lembaga.index', $validasi) }}" class="btn btn-outline-primary" title="Kelola pairing & surat tugas">
                                <i class="material-symbols-outlined align-middle" style="font-size:18px">table_view</i> Kelola Lembaga
                            </a>
                            <form action="{{ route('admin.validasi.unlock', $validasi) }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-outline-danger">
                                    <i class="material-symbols-outlined align-middle" style="font-size:18px">lock_open</i> Buka Kunci
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.validasi.lock', $validasi) }}" method="POST"
                                  class="js-confirm-form" data-title="Kunci Validasi" data-message="Kunci validasi? Setelah dikunci, jawaban asesor tidak dapat diubah.">
                                @csrf
                                <button type="submit" class="btn btn-warning text-dark">
                                    <i class="material-symbols-outlined align-middle" style="font-size:18px">lock</i> Kunci Validasi
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <div class="d-flex flex-wrap gap-2 mt-3">
                    <div class="ksg-stat badge bg-light text-dark border text-start px-3 py-2">
                        <div class="ksg-value fs-16 fw-semibold">{{ number_format($stats['bisa']) }}</div>
                        <div class="ksg-label fs-12 opacity-75">Bisa</div>
                    </div>
                    <div class="ksg-stat badge bg-light text-dark border text-start px-3 py-2">
                        <div class="ksg-value fs-16 fw-semibold">{{ number_format($stats['tidak']) }}</div>
                        <div class="ksg-label fs-12 opacity-75">Tidak Bisa</div>
                    </div>
                    <div class="ksg-stat badge bg-light text-dark border text-start px-3 py-2">
                        <div class="ksg-value fs-16 fw-semibold">{{ number_format($stats['belum']) }}</div>
                        <div class="ksg-label fs-12 opacity-75">Belum Mengisi</div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabs --}}
        <div class="card bg-white border-0 rounded-3 mb-4">
            <div class="card-body p-4">
                <ul class="nav nav-tabs" id="validasiTabs" role="tablist">
                    <li class="nav-item"><button class="nav-link active" data-bs-toggle="tab" data-bs-target="#pane-bisa" type="button">Bisa ({{ $stats['bisa'] }})</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#pane-tidak" type="button">Tidak Bisa ({{ $stats['tidak'] }})</button></li>
                    <li class="nav-item"><button class="nav-link" data-bs-toggle="tab" data-bs-target="#pane-belum" type="button">Belum Mengisi ({{ $stats['belum'] }})</button></li>
                </ul>

                <div class="tab-content pt-3">
                    <div class="tab-pane fade show active" id="pane-bisa">
                        <div class="d-flex flex-wrap gap-2 mb-2">
                            <button type="button" class="btn btn-sm btn-outline-danger btn-bulk" data-action="belum" data-table="#table-bisa">Pindah ke Belum Mengisi (terpilih)</button>
                        </div>
                        <div class="table-responsive">
                            <table id="table-bisa" class="display table table-sm align-middle" style="width:100%">
                                <thead><tr><th><input type="checkbox" class="form-check-input select-all" data-table="#table-bisa"></th><th>No</th><th>NIA</th><th>Nama</th><th>Kab/Kota</th><th>Gender</th><th>TTD</th><th>Aksi</th></tr></thead>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pane-tidak">
                        <div class="d-flex flex-wrap gap-2 mb-2">
                            <button type="button" class="btn btn-sm btn-outline-primary btn-bulk" data-action="bisa" data-table="#table-tidak">Pindah ke Bisa (terpilih)</button>
                            <button type="button" class="btn btn-sm btn-outline-danger btn-bulk" data-action="belum" data-table="#table-tidak">Pindah ke Belum Mengisi (terpilih)</button>
                        </div>
                        <div class="table-responsive">
                            <table id="table-tidak" class="display table table-sm align-middle" style="width:100%">
                                <thead><tr><th><input type="checkbox" class="form-check-input select-all" data-table="#table-tidak"></th><th>No</th><th>NIA</th><th>Nama</th><th>Kab/Kota</th><th>Gender</th><th>Alasan</th><th>Aksi</th></tr></thead>
                            </table>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="pane-belum">
                        <div class="d-flex flex-wrap gap-2 mb-2">
                            <button type="button" class="btn btn-sm btn-outline-primary btn-bulk" data-action="bisa" data-table="#table-belum">Jadikan Bisa (terpilih)</button>
                        </div>
                        <div class="table-responsive">
                            <table id="table-belum" class="display table table-sm align-middle" style="width:100%">
                                <thead><tr><th><input type="checkbox" class="form-check-input select-all" data-table="#table-belum"></th><th>No</th><th>NIA</th><th>Nama</th><th>Kab/Kota</th><th>Gender</th><th>Aksi</th></tr></thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal: pindah ke Tidak Bisa (alasan + bukti) --}}
<div class="modal fade" id="pindahTidakModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.validasi.set-tidak', $validasi) }}" method="POST">
                @csrf
                <input type="hidden" name="user_id" value="">
                <div class="modal-header">
                    <h5 class="modal-title">Pindah ke Tidak Bisa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label fs-14">Alasan</label>
                        <textarea name="alasan" class="form-control" rows="2" placeholder="Alasan tidak bisa (min. 5 karakter)"></textarea>
                    </div>
                    <div class="mb-2">
                        <label class="form-label fs-14">Bukti (gambar)</label>
                        <input type="hidden" name="bukti" value="">
                        <input type="file" class="form-control js-bukti-file" accept="image/*">
                        <div class="js-bukti-preview mt-2" style="display:none;">
                            <img src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" height="70" class="rounded border" alt="bukti">
                        </div>
                        <div class="form-text">Gambar dikompresi (maks lebar 1000px, JPEG) dan disimpan sebagai base64.</div>
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

{{-- Modal: pair lembaga dengan aturan jumlah per asesor --}}
<div class="modal fade" id="pairRuleModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.validasi.lembaga.pair-rule', $validasi) }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Pair Lembaga Otomatis</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <label class="form-label fs-14">Jumlah lembaga per asesor</label>
                    <select name="per_asesor" class="form-select" required>
                        @foreach([2,3,4,5,6,7,8,9,10] as $n)
                            <option value="{{ $n }}">{{ $n }}</option>
                        @endforeach
                    </select>
                    <div class="form-text">Setiap asesor "Bisa" dipasangkan dengan jumlah lembaga ini (kab/kota tidak boleh sama). Sisa lembaga dibiarkan kosong.</div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary">Pair</button>
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
            const bisaUrl = "{{ route('admin.validasi.bisa', $validasi) }}";
            const tidakUrl = "{{ route('admin.validasi.tidak-bisa', $validasi) }}";
            const belumUrl = "{{ route('admin.validasi.belum-mengisi', $validasi) }}";
            const setBisaUrl = "{{ route('admin.validasi.set-bisa', $validasi) }}";
            const setBelumUrl = "{{ route('admin.validasi.set-belum', $validasi) }}";
            const setTidakUrl = "{{ route('admin.validasi.set-tidak', $validasi) }}";
            const bulkSetBisaUrl = "{{ route('admin.validasi.bulk-set-bisa', $validasi) }}";
            const bulkSetBelumUrl = "{{ route('admin.validasi.bulk-set-belum', $validasi) }}";

            function esc(s){return String(s ?? '').replace(/[&<>"']/g, c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));}
            function showToast(title, msg, type){ type=type||'success'; const box=document.getElementById('toast-container'); if(!box) return; const el=document.createElement('div'); el.className='toast align-items-center text-bg-'+type+' border-0 fade ksg-toast-in show'; el.innerHTML='<div class="d-flex"><div class="toast-body"><strong>'+esc(title)+'</strong><div class="fs-14">'+msg+'</div></div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div>'; box.appendChild(el); new bootstrap.Toast(el,{delay:6000}).show(); }
            function pulseRows($els){ if(!$els || !$els.length) return; $els.addClass('row-updated'); setTimeout(function(){ $els.removeClass('row-updated'); }, 650); }
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

            function noCol(){ return { data:null, render:function(){return '';}, orderable:false, searchable:false }; }
            function checkboxCol(){ return { data:null, orderable:false, searchable:false, render:function(d,t,row){ if(t!=='display') return ''; return '<input type="checkbox" class="form-check-input row-select" data-user="'+row.user_id+'">'; } }; }
            function numbering(settings){ this.api().column(1,{search:'applied',order:'applied'}).nodes().each(function(c,i){c.innerHTML=settings._iDisplayStart+i+1;}); }

            function init(sel, url, cols, empty){
                if(!$.fn || !$.fn.DataTable || !$(sel).length) return;
                $(sel).DataTable({ processing:true, serverSide:true, ajax:{url:url,type:'GET'}, pageLength:10, order:[[1,'asc']], columns:cols, language:{emptyTable:empty}, drawCallback:numbering });
            }

            function actionBisa(userId){ return '<button type="button" class="btn btn-sm btn-primary btn-jadikan-bisa" data-user="'+userId+'">Jadikan Bisa</button>'; }

            function editDropdown(row, mode) {
                const items = [];
                let buktiHtml = '';
                if (mode === 'bisa') {
                    items.push('<a class="dropdown-item btn-pindah" data-action="tidak" data-user="'+row.user_id+'" href="javascript:void(0)">Pindah ke Tidak Bisa</a>');
                    items.push('<a class="dropdown-item btn-pindah" data-action="belum" data-user="'+row.user_id+'" href="javascript:void(0)">Pindah ke Belum Mengisi</a>');
                } else {
                    if (row.bukti) {
                        buktiHtml = '<a href="'+row.bukti+'" target="_blank"><img src="'+row.bukti+'" height="42" class="rounded border me-1" alt="bukti"></a>';
                    }
                    items.push('<a class="dropdown-item btn-pindah" data-action="bisa" data-user="'+row.user_id+'" href="javascript:void(0)">Pindah ke Bisa</a>');
                    items.push('<a class="dropdown-item btn-pindah" data-action="belum" data-user="'+row.user_id+'" href="javascript:void(0)">Pindah ke Belum Mengisi</a>');
                }
                return buktiHtml
                    + '<div class="dropdown d-inline-block">'
                    + '<button type="button" class="btn btn-sm btn-outline-secondary dropdown-toggle" data-bs-toggle="dropdown">Edit</button>'
                    + '<ul class="dropdown-menu">' + items.map(function(i){ return '<li>'+i+'</li>'; }).join('') + '</ul>'
                    + '</div>';
            }

            init('#table-bisa', bisaUrl, [
                checkboxCol(),
                noCol(),
                { data:'nia' }, { data:'name' }, { data:'kota' }, { data:'gender' },
                { data:'ttd', orderable:false, searchable:false, render:function(d,t){ if(t!=='display') return ''; return d ? '<img src="'+d+'" height="42" alt="ttd">' : '<span class="text-muted">—</span>'; } },
                { data:null, orderable:false, searchable:false, render:function(d,t,row){ return t==='display' ? editDropdown(row, 'bisa') : ''; } },
            ], 'Belum ada asesor yang menyatakan bisa.');

            let tidakInit=false, belumInit=false;
            $('#validasiTabs').on('shown.bs.tab', function(e){
                const target = $(e.target).attr('data-bs-target');
                if (target === '#pane-tidak' && !tidakInit) {
                    tidakInit = true;
                    init('#table-tidak', tidakUrl, [ checkboxCol(), noCol(), {data:'nia'},{data:'name'},{data:'kota'},{data:'gender'},{data:'alasan'},
                        { data:null, orderable:false, searchable:false, render:function(d,t,row){ return t==='display' ? editDropdown(row, 'tidak') : ''; } } ], 'Tidak ada yang menolak.');
                }
                if (target === '#pane-belum' && !belumInit) {
                    belumInit = true;
                    init('#table-belum', belumUrl, [ checkboxCol(), noCol(), {data:'nia'},{data:'name'},{data:'kota'},{data:'gender'}, {data:null, orderable:false, searchable:false, render:function(d,t,row){ return actionBisa(row.user_id); }} ], 'Semua asesor sudah mengisi.');
                }
            });

            $(document).on('click', '.btn-jadikan-bisa', function(){
                const uid=$(this).data('user');
                const $row=$(this).closest('tr');
                const $btn=$(this);
                askConfirm('Jadikan Bisa', 'Jadikan asesor ini "Bisa"? Jawaban sebelumnya akan diganti.', function(){
                    $btn.prop('disabled', true);
                    $.ajax({ url:setBisaUrl, type:'POST', data:{_token:csrf, user_id:uid}, dataType:'json' })
                        .done(function(res){ pulseRows($row); showToast('Berhasil', (res && res.message) || 'Asesor ditandai "Bisa".','success'); setTimeout(function(){ location.reload(); }, 800); })
                        .fail(function(x){ $btn.prop('disabled', false); showToast('Gagal', (x.responseJSON && x.responseJSON.message) || 'Terjadi kesalahan.','danger'); });
                });
            });

            $(document).on('click', '.btn-pindah', function(){
                const action = $(this).data('action'); // 'bisa' | 'tidak' | 'belum'
                const uid = $(this).data('user');
                if (action === 'tidak') { openPindahTidakModal(uid); return; }
                const url = action === 'bisa' ? setBisaUrl : setBelumUrl;
                const label = action === 'bisa' ? 'Bisa' : 'Belum Mengisi';
                const $row=$(this).closest('tr');
                const $btn=$(this);
                askConfirm('Pindahkan', 'Pindahkan asesor ini ke "' + label + '"?', function(){
                    $btn.prop('disabled', true);
                    $.ajax({ url:url, type:'POST', data:{_token:csrf, user_id:uid}, dataType:'json' })
                        .done(function(res){ pulseRows($row); showToast('Berhasil', (res && res.message) || 'Berhasil dipindahkan.','success'); setTimeout(function(){ location.reload(); }, 800); })
                        .fail(function(x){ $btn.prop('disabled', false); showToast('Gagal', (x.responseJSON && x.responseJSON.message) || 'Terjadi kesalahan.','danger'); });
                });
            });

            // ---- Checkbox multi-baris ----
            $(document).on('change', '.select-all', function(){
                const sel = $(this).data('table');
                const checked = this.checked;
                $(sel).find('.row-select').prop('checked', checked);
            });
            $(document).on('click', '.btn-bulk', function(){
                const action = $(this).data('action'); // 'bisa' | 'belum'
                const sel = $(this).data('table');
                const $btn = $(this);
                const ids = $(sel).find('.row-select:checked').map(function(){ return $(this).data('user'); }).get();
                if (!ids.length) { showToast('Pilih Data', 'Pilih minimal 1 baris terlebih dahulu.', 'warning'); return; }
                const url = action === 'bisa' ? bulkSetBisaUrl : bulkSetBelumUrl;
                const $rows = $(sel).find('.row-select:checked').closest('tr');
                askConfirm('Aksi Massal', 'Terapkan aksi ke ' + ids.length + ' asesor terpilih?', function(){
                    $btn.prop('disabled', true);
                    $.ajax({ url:url, type:'POST', data:{_token:csrf, user_ids:ids}, dataType:'json' })
                        .done(function(res){ pulseRows($rows); showToast('Berhasil', (res && res.message) || 'Berhasil.','success'); setTimeout(function(){ location.reload(); }, 800); })
                        .fail(function(x){ $btn.prop('disabled', false); showToast('Gagal', (x.responseJSON && x.responseJSON.message) || 'Terjadi kesalahan.','danger'); });
                });
            });

            // ---- Modal: pindah ke Tidak Bisa (alasan + bukti) ----
            function compressImage(file, cb) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const img = new Image();
                    img.onload = function () {
                        const maxW = 1000;
                        let w = img.width, h = img.height;
                        if (w > maxW) { h = Math.round(h * maxW / w); w = maxW; }
                        const c = document.createElement('canvas');
                        c.width = w; c.height = h;
                        c.getContext('2d').drawImage(img, 0, 0, w, h);
                        cb(c.toDataURL('image/jpeg', 0.6));
                    };
                    img.src = e.target.result;
                };
                reader.readAsDataURL(file);
            }
            function openPindahTidakModal(uid) {
                $('#pindahTidakModal input[name="user_id"]').val(uid);
                $('#pindahTidakModal textarea[name="alasan"]').val('');
                $('#pindahTidakModal input[name="bukti"]').val('');
                $('#pindahTidakModal .js-bukti-file').val('');
                $('#pindahTidakModal .js-bukti-preview').hide();
                new bootstrap.Modal(document.getElementById('pindahTidakModal')).show();
            }
            $(document).on('change', '#pindahTidakModal .js-bukti-file', function () {
                const file = this.files[0];
                if (!file) return;
                compressImage(file, function (b64) {
                    $('#pindahTidakModal input[name="bukti"]').val(b64);
                    $('#pindahTidakModal .js-bukti-preview img').attr('src', b64);
                    $('#pindahTidakModal .js-bukti-preview').show();
                });
            });
            $('#pindahTidakModal').on('submit', 'form', function () {
                const alasan = $('#pindahTidakModal textarea[name="alasan"]').val().trim();
                const bukti = $('#pindahTidakModal input[name="bukti"]').val();
                if (alasan.length < 5) { alert('Alasan wajib diisi minimal 5 karakter.'); return false; }
                if (!bukti || bukti.length < 20) { alert('Bukti gambar wajib diunggah.'); return false; }
                return true;
            });
        });
    </script>
@endpush
