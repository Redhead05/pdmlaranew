@extends('app.layout')
@section('title', 'Berkas Visitasi')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    @include('partial.table-ux')
@endpush

@section('content')
<div class="container-fluid">
    <div class="main-content d-flex flex-column">
        <div class="card bg-white border-0 rounded-3 mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
                    <div>
                        <h4 class="mb-1">Berkas Visitasi</h4>
                        <p class="text-muted mb-0 fs-14">Periksa berkas visitasi asesor per SK &amp; NPSN, lalu setujui atau tolak dengan komentar.</p>
                    </div>
                </div>

                <ul class="nav nav-tabs mb-3" id="berkasTabs" role="tablist">
                    <li class="nav-item"><button type="button" class="nav-link active" data-status="all">Semua</button></li>
                    <li class="nav-item"><button type="button" class="nav-link" data-status="pending">Pending</button></li>
                    <li class="nav-item"><button type="button" class="nav-link" data-status="approved">Diterima</button></li>
                    <li class="nav-item"><button type="button" class="nav-link" data-status="rejected">Ditolak</button></li>
                </ul>

                <div class="table-responsive">
                    <table id="berkas-table" class="display table table-sm align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th style="width:48px">No</th>
                                <th>NPSN</th>
                                <th>Lembaga</th>
                                <th>Kabupaten</th>
                                <th>NIA Asesor</th>
                                <th>Asesor</th>
                                <th>SK</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal detail --}}
<div class="modal fade" id="berkasModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Berkas Visitasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="berkasModalBody"></div>
            <div class="modal-footer">
                <form method="POST" id="rejectForm" class="d-flex gap-2 w-100">
                    @csrf
                    <input type="text" id="rejectComment" name="admin_komentar" class="form-control" placeholder="Komentar bagian yang salah (wajib saat menolak)…" style="min-width:240px">
                    <button type="submit" class="btn btn-sm btn-danger text-nowrap">Tolak</button>
                </form>
                <form method="POST" id="approveForm">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-success text-nowrap">Approve</button>
                </form>
            </div>
        </div>
    </div>
</div>

{{-- Lightbox gambar --}}
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content bg-dark">
            <div class="modal-header border-0">
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-0 text-center">
                <img id="imageModalImg" src="" class="img-fluid" style="max-height:85vh" alt="gambar">
            </div>
        </div>
    </div>
</div>

<div class="toast-container position-fixed top-0 end-0 p-3" id="toast-container" style="z-index:1080"></div>
@endsection

@push('scripts')
<script>
$(function () {
    function esc(s){return String(s ?? '').replace(/[&<>"']/g, function(c){return {'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c];});}
    function rupiah(n){ return (n != null && n !== '') ? 'Rp ' + Number(n).toLocaleString('id-ID') : '-'; }
    function toast(title, msg, type){ type=type||'success'; var box=document.getElementById('toast-container'); var el=document.createElement('div'); el.className='toast align-items-center text-bg-'+type+' border-0 show'; el.innerHTML='<div class="d-flex"><div class="toast-body"><strong>'+esc(title)+'</strong><div class="fs-14">'+esc(msg)+'</div></div><button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button></div>'; box.appendChild(el); new bootstrap.Toast(el,{delay:6000}).show(); }

    @if(session('success')) toast('Sukses', @json(session('success')), 'success'); @endif

    var table = null;
    if ($.fn && $.fn.DataTable && $('#berkas-table').length) {
        table = $('#berkas-table').DataTable({
            processing: true, serverSide: true,
            ajax: {
                url: "{{ route('admin.berkas.data') }}",
                type: 'GET',
                data: function (d) { d.status = $('#berkasTabs .nav-link.active').data('status') || 'all'; }
            },
            pageLength: 10, order: [],
            columns: [
                { data: null, render: function(){ return ''; }, orderable: false, searchable: false },
                { data: 'npsn' },
                { data: 'lembaga' },
                { data: 'kabupaten' },
                { data: 'nia' },
                { data: 'asesor' },
                { data: 'sk' },
                { data: 'tanggal_visitasi' },
                { data: 'status_badge', orderable: false, searchable: false },
                { data: 'action', orderable: false, searchable: false },
            ],
            language: { emptyTable: 'Belum ada berkas visitasi.' },
            drawCallback: function (settings) {
                this.api().column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) { cell.innerHTML = settings._iDisplayStart + i + 1; });
            }
        });
    }

    $('#berkasTabs').on('click', '.nav-link', function () {
        $('#berkasTabs .nav-link').removeClass('active');
        $(this).addClass('active');
        if (table) table.ajax.reload(null, false);
    });

    var showUrl = "{{ route('admin.berkas.show', ':id') }}";
    var approveUrl = "{{ route('admin.berkas.approve', ':id') }}";
    var rejectUrl = "{{ route('admin.berkas.reject', ':id') }}";
    var currentId = null;

    $(document).on('click', '.btn-check-berkas', function () {
        currentId = $(this).data('id');
        $('#berkasModalBody').html('<div class="text-center text-muted py-5">Memuat…</div>');
        $('#rejectComment').val('');
        $('#approveForm').attr('action', approveUrl.replace(':id', currentId));
        $('#rejectForm').attr('action', rejectUrl.replace(':id', currentId));
        new bootstrap.Modal(document.getElementById('berkasModal')).show();

        $.getJSON(showUrl.replace(':id', currentId), function (d) {
            var html = '';
            html += '<div class="row mb-3">'
                + '<div class="col-md-3"><div class="small text-muted">NPSN</div><div class="fw-semibold">'+esc(d.npsn)+'</div></div>'
                + '<div class="col-md-3"><div class="small text-muted">Lembaga</div><div class="fw-semibold">'+esc(d.lembaga)+'</div></div>'
                + '<div class="col-md-2"><div class="small text-muted">NIA</div><div class="fw-semibold">'+esc(d.nia)+'</div></div>'
                + '<div class="col-md-2"><div class="small text-muted">Asesor</div><div class="fw-semibold">'+esc(d.asesor)+'</div></div>'
                + '<div class="col-md-2"><div class="small text-muted">Tanggal</div><div class="fw-semibold">'+esc(d.tanggal_visitasi)+'</div></div>'
                + '</div>';
            html += '<div class="row mb-3">'
                + '<div class="col-md-4"><div class="small text-muted">SK / Tahap</div><div>'+esc(d.sk)+' — Tahap '+esc(d.tahap)+'</div></div>'
                + '<div class="col-md-2"><div class="small text-muted">Jenis Perjalanan</div><div>'+esc(d.jenis_perjalanan)+'</div></div>'
                + '<div class="col-md-3"><div class="small text-muted">GPS Foto</div><div>'+(d.latitude != null ? d.latitude+', '+d.longitude : '-')+'</div></div>'
                + '<div class="col-md-3"><div class="small text-muted">Koordinat Lembaga (DB)</div><div>'+(d.lembaga_lat != null ? d.lembaga_lat+', '+d.lembaga_lng : 'Belum ada')+'</div></div>'
                + '</div>';
            html += '<div class="row mb-3">'
                + '<div class="col-md-4"><div class="small text-muted">Jarak Asesor ↔ Lembaga (OSRM)</div><div class="fw-semibold">'+(d.jarak_km != null ? Number(d.jarak_km).toLocaleString('id-ID')+' km' : '-')+'</div></div>'
                + '</div>';
            html += '<div class="row mb-3">'
                + '<div class="col-md-3"><div class="small text-muted">Nominal Transport</div><div class="fw-semibold">'+rupiah(d.nominal_transport)+'</div></div>'
                + '<div class="col-md-3"><div class="small text-muted">Nominal Menginap</div><div class="fw-semibold">'+rupiah(d.nominal_menginap)+'</div></div>'
                + '<div class="col-md-3"><div class="small text-muted">Status</div><div>'+esc(d.status)+'</div></div>'
                + '<div class="col-md-3"><div class="small text-muted">Komentar Admin</div><div>'+esc(d.admin_komentar || '-')+'</div></div>'
                + '</div>';
            html += '<div class="row g-3">';
            html += fileCol('Scan SPPD', d.scan_sppd);
            html += fileCol('Bukti Menginap Hotel', d.bukti_menginap);
            html += fileCol('Bukti Transport', d.bukti_transport);
            html += fileCol('Surat Perjalanan Dinas', d.surat_perjalanan_dinas);
            html += fileCol('Pakta Integritas Visitasi', d.pakta_integritas);
            html += fileCol('Berita Acara Visitasi', d.berita_acara);
            html += fileCol('Daftar Hadir Pembukaan & Penutupan', d.daftar_hadir);
            html += fileCol('Foto Depan Lembaga', d.foto_depan);
            html += '</div>';
            $('#berkasModalBody').html(html);
        }).fail(function(){ $('#berkasModalBody').html('<div class="text-danger text-center py-4">Gagal memuat detail.</div>'); });
    });

    function fileCol(label, url) {
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

    $('#rejectForm').on('submit', function (e) {
        if (!$('#rejectComment').val().trim()) { e.preventDefault(); toast('Tolak', 'Komentar wajib diisi saat menolak.', 'warning'); }
    });
});
</script>
@endpush