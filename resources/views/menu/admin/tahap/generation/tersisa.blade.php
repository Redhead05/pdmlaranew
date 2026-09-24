@extends('app.layout')
@section('title', 'Lembaga Belum Terpetakan — ' . $tahap->tahap)

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
                        <li class="breadcrumb-item"><a href="{{ route('admin.tahap.index') }}">Visitasi</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.tahap.generation.index', ['tahap' => $tahap->slug]) }}">Pairing — {{ $tahap->tahap }}</a></li>
                        <li class="breadcrumb-item active">Lembaga Tersisa</li>
                    </ol>
                </nav>

                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                    <div>
                        <h4 class="mb-1">Lembaga Belum Terpetakan (Tersisa)</h4>
                        <p class="text-muted mb-0 fs-14">
                            {{ $count }} lembaga pada tahap <strong>{{ $tahap->tahap }}</strong> yang belum mendapat tim asesor.
                            Pasangkan melalui <em>Manual Override</em> atau unduh file pairing lalu isi sheet <em>Manual Override</em>.
                        </p>
                    </div>
                    <a href="{{ route('admin.tahap.generation.index', ['tahap' => $tahap->slug]) }}" class="btn btn-outline-secondary">
                        <i class="material-symbols-outlined align-middle" style="font-size:18px">arrow_back</i>
                        Kembali ke Pairing
                    </a>
                </div>
            </div>
        </div>

        <div class="card bg-white border-0 rounded-3 mb-4">
            <div class="card-body p-4">
                @if($count === 0)
                    <p class="text-muted mb-0">Tidak ada lembaga tersisa — semua lembaga sudah terpasang ke tim.</p>
                @else
                    <div class="table-responsive">
                        <table id="remaining-table" class="display table table-sm align-middle" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>NPSN</th>
                                    <th>Nama Lembaga</th>
                                    <th>Kab/Kota</th>
                                    <th>Kecamatan</th>
                                    <th>Jenjang</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        function initTersisaPage() {
            var $table = $('#remaining-table');
            if (!$table.length || $table.data('init')) return;
            if (!$.fn || !$.fn.DataTable) return;
            $table.data('init', true);

            const remainingUrl = "{{ route('admin.tahap.generation.remaining-data', ['tahap' => $tahap->slug]) }}";
            const detachBase = "{{ route('admin.tahap.generation.remaining-detach', ['tahap' => $tahap->slug, 'lembaga' => '__ID__']) }}";
            const csrf = '{{ csrf_token() }}';

            $(document).off('click.tersisa', '.hapus-lembaga-btn').on('click.tersisa', '.hapus-lembaga-btn', function () {
                const id = $(this).data('id');
                if (!confirm('Hapus lembaga ini dari tahap? Lembaga tidak akan diikutsertakan pada tahap ini.')) return;
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = detachBase.replace('__ID__', id);
                form.innerHTML = '<input type="hidden" name="_token" value="' + csrf + '">';
                document.body.appendChild(form);
                form.submit();
            });

            $table.DataTable({
                processing: true,
                serverSide: true,
                ajax: { url: remainingUrl, type: 'GET' },
                pageLength: 10,
                order: [[2, 'asc']],
                columns: [
                    { data: null, render: function () { return ''; }, orderable: false, searchable: false },
                    { data: 'npsn' },
                    { data: 'satuan_pen' },
                    { data: 'kabupaten' },
                    { data: 'kecamatan' },
                    { data: 'jenjang' },
                    { data: 'action', orderable: false, searchable: false,
                      render: function (data, type, row) {
                          return '<button type="button" class="btn btn-sm btn-outline-danger hapus-lembaga-btn" data-id="' + row.id + '" title="Hapus dari tahap">'
                              + '<span class="material-symbols-outlined align-middle" style="font-size:16px">delete</span></button>';
                      } },
                ],
                drawCallback: function (settings) {
                    const api = this.api();
                    api.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
                        cell.innerHTML = settings._iDisplayStart + i + 1;
                    });
                }
            });
        }

        initTersisaPage();
        if (window.__registerDataTableInit) window.__registerDataTableInit('tersisa', initTersisaPage);
    </script>
@endpush
