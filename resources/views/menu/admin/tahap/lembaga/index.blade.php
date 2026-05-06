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

                    @if(session('error'))
                        <div class="alert alert-danger">{!! nl2br(e(session('error'))) !!}</div>
                    @endif
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

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
                            <tbody>
                                @foreach($lembagas as $i => $l)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $l->npsn }}</td>
                                        <td>{{ $l->satuan_pen }}</td>
                                        <td>{{ $l->kabupaten ?? ($l->kabupaten ?? '-') }}</td>
                                        <td>{{ $l->kecamatan ?? ($l->kecamatan ?? '-') }}</td>
                                        <td>{{ $l->latitude ?? '-' }}</td>
                                        <td>{{ $l->longitude ?? '-' }}</td>
                                        <td>
                                            <form action="{{ route('admin.tahap.lembaga.detach', ['tahap' => $tahap->slug, 'lembaga' => $l->id]) }}" method="POST" onsubmit="return confirm('Hapus lembaga ini dari tahap?');" class="d-inline">
                                                @csrf
                                                <button class="btn btn-sm btn-outline-danger">Detach</button>
                                            </form>
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
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
    <script>
        $(function () {
            if (!$.fn || !$.fn.DataTable) return;

            $('#lembaga-table').DataTable({
                pageLength: 25,
                responsive: true,
                columns: [
                    { width: '5%' },
                    null,
                    null,
                    null,
                    null,
                    null,
                    null,
                    { orderable: false, searchable: false, width: '10%' }
                ]
            });
        });
    </script>
@endpush
