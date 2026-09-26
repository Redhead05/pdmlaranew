@extends('app.layout')
@section('title', 'Sertifikat Saya')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@section('content')
    <div class="container-fluid">
        <div class="main-content d-flex flex-column">
            <div class="card bg-white border-0 rounded-3 mb-4">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h4 class="mb-0">Sertifikat Saya</h4>
                        <form method="get" class="d-flex align-items-center gap-2">
                            <select name="year" onchange="this.form.submit()" class="form-select w-auto">
                                <option value="">Semua tahun</option>
                                @foreach($years as $y)
                                    <option value="{{ $y }}" @if((string)$year == (string)$y) selected @endif>{{ $y }}</option>
                                @endforeach
                            </select>
                        </form>
                    </div>

                    <div class="default-table-area all-products">
                        <div class="table-responsive">
                            <table class="display table align-middle" id="certTable" style="width:100%">
                                <thead>
                                    <tr>
                                        <th>No.</th>
                                        <th>Judul</th>
                                        <th>No Surat</th>
                                        <th>Tanggal Buat</th>
                                        <th>Berlaku Sampai</th>
                                        <th class="text-end">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($certifications as $cert)
                                        <tr>
                                            <td></td>
                                            <td>{{ $cert->title }}</td>
                                            <td>{{ $cert->certificate_number ?? '-' }}</td>
                                            <td>{{ optional($cert->issued_at)->format('Y-m-d') }}</td>
                                            <td>{{ optional($cert->expires_at)->format('Y-m-d') ?? '-' }}</td>
                                            <td class="text-end">
                                                @if($cert->file_path)
                                                    <a href="{{ route('asesor.certifications.download', $cert) }}" class="btn btn-sm btn-outline-primary">Download</a>
                                                @endif
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
    </div>
@endsection

@push('scripts')
    <script>
        (function(){
            const table = $('#certTable').DataTable({
                responsive: true,
                order: [],
                pageLength: 25,
                columnDefs: [
                    { orderable: false, targets: [0, -1] },
                ],
            });

            table.on('order.dt search.dt', function () {
                table.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
                    cell.innerHTML = i + 1;
                });
            }).draw();
        })();
    </script>
@endpush
