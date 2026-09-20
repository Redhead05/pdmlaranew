@extends('app.layout')
@section('title', 'Upload Berkas Visitasi — ' . $tahap->tahap)

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
                        <li class="breadcrumb-item"><a href="{{ route('asesor.visitasi.index') }}">Visitasi</a></li>
                        <li class="breadcrumb-item active">Upload Berkas</li>
                    </ol>
                </nav>
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                    <div>
                        <h4 class="mb-1">Upload Berkas — Tahap {{ $tahap->tahap }}</h4>
                        <p class="text-muted mb-0 fs-14">SK: {{ $tahap->surat_keputusan ?? '-' }} &middot; Upload berkas per lembaga (PDF/JPG maks 2 MB).</p>
                    </div>
                    <a href="{{ route('asesor.visitasi.index') }}" class="btn btn-outline-secondary">
                        <span class="material-symbols-outlined align-middle" style="font-size:18px">arrow_back</span> Kembali
                    </a>
                </div>
            </div>
        </div>

        <div class="card bg-white border-0 rounded-3 mb-4">
            <div class="card-body p-4">
                <ul class="nav nav-tabs mb-3" id="berkasTabs" role="tablist">
                    <li class="nav-item"><button type="button" class="nav-link active" data-status="all">Semua</button></li>
                    <li class="nav-item"><button type="button" class="nav-link" data-status="pending">Pending</button></li>
                    <li class="nav-item"><button type="button" class="nav-link" data-status="approved">Diterima</button></li>
                    <li class="nav-item"><button type="button" class="nav-link" data-status="rejected">Ditolak</button></li>
                </ul>

                @if($lembagas->isEmpty())
                    <div class="alert alert-info mb-0">Belum ada lembaga penugasan untuk tahap ini.</div>
                @else
                    <div class="table-responsive">
                        <table class="table table-sm align-middle" id="lembagaTable">
                            <thead>
                                <tr>
                                    <th>No</th><th>NPSN</th><th>Nama Lembaga</th><th>Kabupaten</th><th>Koordinat Lembaga</th><th>Status</th><th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($lembagas as $i => $l)
                                @php
                                    $ex = $existing->get($l->id);
                                    $hasCoord = $l->latitude !== null && $l->longitude !== null;
                                    $rowStatus = $ex ? $ex->status : 'pending';
                                @endphp
                                <tr data-status="{{ $rowStatus }}">
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $l->npsn }}</td>
                                    <td class="fw-semibold">{{ $l->satuan_pen }}</td>
                                    <td>{{ $l->kabupaten ?? '-' }}</td>
                                    <td>
                                        @if($hasCoord)
                                            <span class="badge bg-success">Ada ({{ $l->latitude }}, {{ $l->longitude }})</span>
                                        @else
                                            <span class="badge bg-danger">Belum ada</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($ex)
                                            @if($ex->status === 'approved')
                                                <span class="badge bg-success">Diterima</span>
                                            @elseif($ex->status === 'rejected')
                                                <button type="button" class="btn btn-sm btn-outline-danger border-0 px-2 py-0 js-toggle-komentar" data-target="#komentar-{{ $l->id }}" title="Lihat alasan">
                                                    <span class="material-symbols-outlined align-middle" style="font-size:16px">report</span> Ditolak
                                                </button>
                                                @if($ex->admin_komentar)
                                                    <div class="small text-danger mt-1 d-none" id="komentar-{{ $l->id }}">{{ $ex->admin_komentar }}</div>
                                                @endif
                                            @else
                                                <span class="badge bg-warning text-dark">Pending</span>
                                            @endif
                                        @else
                                            <span class="badge bg-secondary">Belum upload</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="d-flex gap-1 flex-wrap">
                                            @if($ex)
                                                <button type="button" class="btn btn-sm btn-outline-secondary btn-review" data-id="{{ $ex->id }}">
                                                    <span class="material-symbols-outlined align-middle" style="font-size:16px">visibility</span> Review
                                                </button>
                                            @endif
                                            @if(!$ex || $ex->status !== 'approved')
                                                <button type="button" class="btn btn-sm btn-primary btn-open-upload"
                                                        data-lembaga="{{ $l->id }}" data-nama="{{ $l->satuan_pen }}">
                                                    <span class="material-symbols-outlined align-middle" style="font-size:16px">upload_file</span> Upload
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Modal upload --}}
<div class="modal fade" id="uploadModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <form id="berkasForm" enctype="multipart/form-data">
                <input type="hidden" name="tahap_id" value="{{ $tahap->id }}">
                <input type="hidden" name="lembaga_id" value="">
                <input type="hidden" name="latitude" value="">
                <input type="hidden" name="longitude" value="">
                <div class="modal-header">
                    <h5 class="modal-title">Upload Berkas — <span id="uploadLembagaNama"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fs-14">Tanggal Pelaksanaan Visitasi</label>
                            <input type="date" name="tanggal_visitasi" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fs-14">Jenis Perjalanan</label>
                            <select name="jenis_perjalanan" class="form-select" required>
                                <option value="pulang_pergi">Pulang / Pergi</option>
                                <option value="menginap">Menginap</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fs-14">a. Scan SPPD (PDF/JPG maks 2 MB)</label>
                            <input type="file" name="scan_sppd" accept=".jpg,.jpeg,.png,.webp,.pdf" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fs-14">d. Surat Perjalanan Dinas (PDF/JPG maks 2 MB)</label>
                            <input type="file" name="surat_perjalanan_dinas" accept=".jpg,.jpeg,.png,.webp,.pdf" class="form-control">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fs-14">c. Bukti Transport (PDF/JPG maks 2 MB)</label>
                            <input type="file" name="bukti_transport" accept=".jpg,.jpeg,.png,.webp,.pdf" class="form-control" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fs-14">Nominal Transport (Rp)</label>
                            <input type="text" name="nominal_transport" class="form-control js-rupiah" placeholder="contoh: 150.000">
                        </div>
                    </div>

                    <div class="js-menginap-wrap d-none">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fs-14">b. Bukti Menginap Hotel (PDF/JPG maks 2 MB)</label>
                                <input type="file" name="bukti_menginap" accept=".jpg,.jpeg,.png,.webp,.pdf" class="form-control">
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fs-14">Nominal Menginap (Rp)</label>
                                <input type="text" name="nominal_menginap" class="form-control js-rupiah" placeholder="contoh: 500.000">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fs-14">e. Pakta Integritas Visitasi (PDF/JPG maks 2 MB)</label>
                            <input type="file" name="pakta_integritas" accept=".jpg,.jpeg,.png,.webp,.pdf" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fs-14">f. Berita Acara Visitasi (PDF/JPG maks 2 MB)</label>
                            <input type="file" name="berita_acara" accept=".jpg,.jpeg,.png,.webp,.pdf" class="form-control">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label fs-14">g. Daftar Hadir Pembukaan &amp; Penutupan (PDF/JPG maks 2 MB)</label>
                            <input type="file" name="daftar_hadir" accept=".jpg,.jpeg,.png,.webp,.pdf" class="form-control">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label fs-14">Foto Depan Lembaga (wajib ada metadata GPS)</label>
                            <div class="input-group">
                                <input type="file" name="foto_depan" accept="image/*" class="form-control js-foto-depan" required>
                                <button type="button" class="btn btn-outline-primary js-check-gps">Check GPS</button>
                            </div>
                            <div class="js-gps-result small mt-2"></div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-sm btn-primary">Kirim (Pending)</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Modal review --}}
<div class="modal fade" id="reviewModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Review Berkas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="reviewModalBody"></div>
        </div>
    </div>
</div>

{{-- Lightbox --}}
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content bg-dark">
            <div class="modal-header border-0">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 text-center">
                <img id="imageModalImg" src="data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==" class="img-fluid" style="max-height:85vh" alt="gambar">
            </div>
        </div>
    </div>
</div>

<div class="toast-container position-fixed top-0 end-0 p-3" id="toast-container" style="z-index:1080"></div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/exifr/dist/full.umd.js"></script>
<script>
$(function () {
    var csrf = '{{ csrf_token() }}';
    var storeUrl = "{{ route('asesor.visitasi.berkas.store') }}";
    var showUrl = "{{ route('asesor.visitasi.berkas.show', ':id') }}";

    function esc(s){return String(s ?? '').replace(/[&<>"']/g, function(c){return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c];});}
    function rupiah(n){ return (n != null && n !== '') ? 'Rp ' + Number(n).toLocaleString('id-ID') : '-'; }
    function toast(title, msg, type){ type=type||'success'; var box=document.getElementById('toast-container'); var el=document.createElement('div'); el.className='toast align-items-center text-bg-'+type+' border-0 show ksg-toast-in'; el.innerHTML='<div class="d-flex"><div class="toast-body"><strong>'+esc(title)+'</strong><div class="fs-14">'+esc(msg)+'</div></div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div>'; box.appendChild(el); new bootstrap.Toast(el,{delay:6000}).show(); }

    // Tab filter
    $('#berkasTabs').on('click', '.nav-link', function () {
        $('#berkasTabs .nav-link').removeClass('active');
        $(this).addClass('active');
        var st = $(this).data('status');
        $('#lembagaTable tbody tr').each(function () {
            if (st === 'all' || $(this).data('status') === st) $(this).show(); else $(this).hide();
        });
    });

    // Toggle alasan penolakan
    $(document).on('click', '.js-toggle-komentar', function () {
        var $t = $($(this).data('target'));
        var willShow = $t.hasClass('d-none');
        $t.toggleClass('d-none');
        if (willShow) window.KsgMotion && KsgMotion.reveal($t);
    });

    // Rupiah formatting
    $(document).on('input', '.js-rupiah', function () {
        var digits = this.value.replace(/\D/g, '');
        this.value = digits ? Number(digits).toLocaleString('id-ID') : '';
    });

    // Open upload modal
    $('.btn-open-upload').on('click', function () {
        $('#berkasForm')[0].reset();
        $('#berkasForm input[name="lembaga_id"]').val($(this).data('lembaga'));
        $('#uploadLembagaNama').text($(this).data('nama'));
        $('.js-menginap-wrap').addClass('d-none');
        $('.js-gps-result').html('');
        new bootstrap.Modal(document.getElementById('uploadModal')).show();
    });

    $('#berkasForm select[name="jenis_perjalanan"]').on('change', function () {
        $('.js-menginap-wrap').toggleClass('d-none', $(this).val() !== 'menginap');
    });

    // Check GPS (client-side)
    $('.js-check-gps').on('click', function () {
        var foto = $('#berkasForm input[name="foto_depan"]')[0].files[0];
        if (!foto) { toast('Check GPS', 'Pilih foto depan terlebih dahulu.', 'warning'); return; }
        if (!window.exifr || !exifr.gps) { toast('Check GPS', 'Pustaka EXIF belum termuat.', 'danger'); return; }
        $('.js-gps-result').html('<span class="text-muted">Memeriksa metadata…</span>');
        exifr.gps(foto).then(function (gps) {
            if (gps && gps.latitude != null && gps.longitude != null) {
                $('#berkasForm input[name="latitude"]').val(gps.latitude);
                $('#berkasForm input[name="longitude"]').val(gps.longitude);
                $('.js-gps-result').html('<span class="text-success">GPS ditemukan: '+gps.latitude+', '+gps.longitude+'</span>');
            } else {
                $('#berkasForm input[name="latitude"]').val('');
                $('#berkasForm input[name="longitude"]').val('');
                $('.js-gps-result').html('<span class="text-danger">Metadata GPS tidak ditemukan pada foto ini.</span>');
            }
        }).catch(function () {
            $('.js-gps-result').html('<span class="text-danger">Gagal membaca metadata GPS.</span>');
        });
    });

    // Compression
    function compressImage(file, maxW, quality, cb) {
        var reader = new FileReader();
        reader.onload = function (e) {
            var img = new Image();
            img.onload = function () {
                var w = img.width, h = img.height;
                if (w > maxW) { h = Math.round(h * maxW / w); w = maxW; }
                var canvas = document.createElement('canvas');
                canvas.width = w; canvas.height = h;
                canvas.getContext('2d').drawImage(img, 0, 0, w, h);
                canvas.toBlob(function (blob) {
                    cb(new File([blob], file.name.replace(/\.[^.]+$/, '.jpg'), { type: 'image/jpeg' }));
                }, 'image/jpeg', quality);
            };
            img.src = e.target.result;
        };
        reader.readAsDataURL(file);
    }

    function compressFile(file) {
        return new Promise(function (resolve, reject) {
            if (file.size <= 2 * 1024 * 1024) { resolve(file); return; }
            if (!/^image\//.test(file.type)) { reject(new Error('Berkas ' + file.name + ' melebihi 2 MB.')); return; }
            compressImage(file, 1280, 0.7, resolve);
        });
    }

    // Submit
    var fileFields = ['bukti_transport', 'bukti_menginap', 'foto_depan', 'scan_sppd', 'surat_perjalanan_dinas', 'pakta_integritas', 'berita_acara', 'daftar_hadir'];

    $('#berkasForm').on('submit', function (e) {
        e.preventDefault();
        var form = this;
        var $btn = $(form).find('button[type="submit"]').prop('disabled', true);

        var jobs = [];
        fileFields.forEach(function (k) {
            var input = form.querySelector('input[name="'+k+'"]');
            if (input && input.files[0]) {
                jobs.push(compressFile(input.files[0]).then(function (f) { return [k, f]; }));
            }
        });

        Promise.all(jobs).then(function (compressed) {
            var fd = new FormData();
            fd.append('_token', csrf);
            fd.append('tahap_id', form.querySelector('input[name="tahap_id"]').value);
            fd.append('lembaga_id', form.querySelector('input[name="lembaga_id"]').value);
            fd.append('tanggal_visitasi', form.querySelector('input[name="tanggal_visitasi"]').value);
            fd.append('jenis_perjalanan', form.querySelector('select[name="jenis_perjalanan"]').value);
            fd.append('latitude', form.querySelector('input[name="latitude"]').value);
            fd.append('longitude', form.querySelector('input[name="longitude"]').value);
            fd.append('nominal_transport', (form.querySelector('input[name="nominal_transport"]').value || '').replace(/\D/g, ''));
            fd.append('nominal_menginap', (form.querySelector('input[name="nominal_menginap"]').value || '').replace(/\D/g, ''));
            compressed.forEach(function (r) { fd.append(r[0], r[1]); });
            return $.ajax({ url: storeUrl, type: 'POST', data: fd, processData: false, contentType: false, dataType: 'json' });
        }).then(function (res) {
            toast('Berhasil', res.message, 'success');
            bootstrap.Modal.getInstance(document.getElementById('uploadModal')).hide();
            setTimeout(function () { location.reload(); }, 900);
        }).catch(function (x) {
            var m = (x.responseJSON && x.responseJSON.message) || x.message || 'Gagal mengirim berkas.';
            if (x.responseJSON && x.responseJSON.errors) m = Object.values(x.responseJSON.errors).flat().join(' ');
            toast('Gagal', m, 'danger');
            $btn.prop('disabled', false);
        });
    });

    // Review modal
    $(document).on('click', '.btn-review', function () {
        var id = $(this).data('id');
        $('#reviewModalBody').html('<div class="text-center text-muted py-5">Memuat…</div>');
        new bootstrap.Modal(document.getElementById('reviewModal')).show();
        $.getJSON(showUrl.replace(':id', id), function (d) {
            var html = '<div class="row mb-3">'
                + '<div class="col-md-4"><div class="small text-muted">Tanggal Visitasi</div><div class="fw-semibold">'+esc(d.tanggal_visitasi)+'</div></div>'
                + '<div class="col-md-4"><div class="small text-muted">Jenis Perjalanan</div><div class="fw-semibold">'+esc(d.jenis_perjalanan)+'</div></div>'
                + '<div class="col-md-4"><div class="small text-muted">Status</div><div>'+esc(d.status)+'</div></div>'
                + '</div>';
            html += '<div class="row mb-3">'
                + '<div class="col-md-6"><div class="small text-muted">Nominal Transport</div><div class="fw-semibold">'+rupiah(d.nominal_transport)+'</div></div>'
                + '<div class="col-md-6"><div class="small text-muted">Nominal Menginap</div><div class="fw-semibold">'+rupiah(d.nominal_menginap)+'</div></div>'
                + '</div>';
            if (d.admin_komentar) {
                html += '<div class="alert alert-danger">Komentar Admin: '+esc(d.admin_komentar)+'</div>';
            }
            if (d.gps) {
                html += '<div class="small text-muted mb-2">GPS Foto: '+d.gps[0]+', '+d.gps[1]+'</div>';
            }
            html += '<div class="row g-3">';
            (d.files || []).forEach(function (f) {
                html += reviewFileCol(f.label, f.url);
            });
            html += '</div>';
            $('#reviewModalBody').html(html);
        }).fail(function () { $('#reviewModalBody').html('<div class="text-danger text-center py-4">Gagal memuat data.</div>'); });
    });

    function reviewFileCol(label, url) {
        if (!url) return '';
        if (/\.(jpg|jpeg|png|webp|gif)$/i.test(url)) {
            return '<div class="col-md-3 mb-3"><div class="small text-muted mb-1">'+esc(label)+'</div><a href="javascript:void(0)" class="js-lightbox d-block" data-src="'+url+'"><img src="'+url+'" class="img-fluid rounded border" style="max-height:150px" alt="'+esc(label)+'"></a></div>';
        }
        return '<div class="col-md-3 mb-3"><div class="small text-muted mb-1">'+esc(label)+'</div><a href="'+url+'" target="_blank" class="btn btn-sm btn-outline-secondary"><span class="material-symbols-outlined align-middle" style="font-size:16px">picture_as_pdf</span> Buka</a></div>';
    }

    $(document).on('click', '.js-lightbox', function () {
        $('#imageModalImg').attr('src', $(this).data('src'));
        new bootstrap.Modal(document.getElementById('imageModal')).show();
    });
});
</script>
@endpush