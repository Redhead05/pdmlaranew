@extends('app.layout')
@section('title', 'Pairing Tim & Lembaga — ' . $tahap->tahap)

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@section('content')
<div class="container-fluid">
    <div class="main-content d-flex flex-column">

        {{-- Header --}}
        <div class="card bg-white border-0 rounded-3 mb-4">
            <div class="card-body p-4">
                <nav aria-label="breadcrumb" class="mb-3">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.tahap.index') }}">Visitasi</a></li>
                        <li class="breadcrumb-item active">Pairing — {{ $tahap->tahap }}</li>
                    </ol>
                </nav>

                <div class="d-flex justify-content-between align-items-start flex-wrap gap-3">
                    <div>
                        <h4 class="mb-1">Pairing Tim Asesor & Lembaga</h4>
                        <p class="text-muted mb-0 fs-14">{{ $tahap->tahap }}</p>
                    </div>

                    @if($teams->isNotEmpty() && $tahap->lembagas()->count() > 0)
                    <form method="POST" action="{{ route('admin.tahap.generate', ['tahap' => $tahap->slug]) }}"
                          onsubmit="return confirm('Jalankan auto-match ulang? Seluruh pasangan akan direset lalu dipasangkan ulang secara otomatis.');">
                        @csrf
                        <button type="submit" class="btn btn-success">
                            <i class="material-symbols-outlined align-middle" style="font-size:18px">bolt</i>
                            Jalankan Auto-Match
                        </button>
                    </form>
                    @endif
                </div>

                {{-- Statistik ringkas --}}
                <div class="d-flex flex-wrap gap-2 mt-3">
                    <span class="badge bg-light text-dark border px-3 py-2">Tim: {{ $teams->count() }}</span>
                    <span class="badge bg-light text-dark border px-3 py-2">Lembaga: {{ $tahap->lembagas()->count() }}</span>
                    <span class="badge bg-light text-dark border px-3 py-2">Terpasang: {{ $assignments->count() }}</span>
                    <span class="badge bg-warning text-dark px-3 py-2">Belum Penuh: {{ $unmatchedTeams->count() }}</span>
                </div>

                {{-- Flash --}}
                @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show mt-3 mb-0">{{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show mt-3 mb-0">{{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
            </div>
        </div>

        {{-- Hasil per Tim (DataTables) --}}
        <div class="card bg-white border-0 rounded-3 mb-4">
            <div class="card-body p-4">
                <h5 class="fw-semibold mb-3">Hasil Auto-Match per Tim</h5>

                @if($teams->isEmpty())
                    <p class="text-muted mb-0">Belum ada tim final untuk tahap ini.</p>
                @else
                    <div class="table-responsive">
                        <table id="result-table" class="display table align-middle" style="width:100%">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Team</th>
                                    <th>Nama</th>
                                    <th>City</th>
                                    <th>Jumlah Kesanggupan</th>
                                    <th>Lembaga</th>
                                    <th>Detail</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($teams as $team)
                                    <tr>
                                        <td></td>
                                        <td>{{ $team->code ?? 'T'.$team->id }}</td>
                                        <td>{{ $team->members->map(fn($m) => $m->user->name ?? '-')->implode(', ') }}</td>
                                        <td>{{ $team->members->map(fn($m) => $m->user->detail?->work_city)->filter()->unique()->implode(', ') ?: '-' }}</td>
                                        <td>{{ $team->kuota() }}</td>
                                        <td>
                                            <span class="badge {{ $team->lembagas->count() >= $team->kuota() ? 'bg-success' : 'bg-secondary' }}">
                                                {{ $team->lembagas->count() }}/{{ $team->kuota() }}
                                            </span>
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-primary detail-btn" data-team-id="{{ $team->id }}">
                                                Detail
                                            </button>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>

        {{-- Manual Override --}}
        <div class="card bg-white border-0 rounded-3 mb-4">
            <div class="card-body p-4">
                <h5 class="fw-semibold mb-3">Manual Override — Tim yang Belum Penuh</h5>

                @if($unmatchedTeams->isEmpty())
                    <p class="text-muted mb-0">Semua tim sudah mencapai kuota.</p>
                @elseif($availableLembagas->isEmpty())
                    <p class="text-muted mb-0">Tidak ada lembaga tersisa untuk di-assign manual.</p>
                @else
                    <div class="table-responsive">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th>Tim</th>
                                    <th>Anggota</th>
                                    <th>Terpasang</th>
                                    <th>Pilih Lembaga</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($unmatchedTeams as $team)
                                    <tr>
                                        <td>{{ $team->code ?? 'T'.$team->id }}</td>
                                        <td>{{ $team->members->map(fn($m) => $m->user->name ?? '-')->implode(', ') }}</td>
                                        <td>{{ $team->lembagas->count() }}/{{ $team->kuota() }}</td>
                                        <td>
                                            <form method="POST" action="{{ route('admin.tahap.generation.assign', ['tahap' => $tahap->slug]) }}"
                                                  class="d-flex gap-2">
                                                @csrf
                                                <input type="hidden" name="team_id" value="{{ $team->id }}">
                                                <select name="lembaga_id" class="form-select form-select-sm" required>
                                                    <option value="">-- Pilih Lembaga --</option>
                                                    @foreach($availableLembagas as $l)
                                                        <option value="{{ $l->id }}">{{ $l->satuan_pen }} ({{ $l->kabupaten }})</option>
                                                    @endforeach
                                                </select>
                                                <button class="btn btn-sm btn-primary text-nowrap">Assign</button>
                                            </form>
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

{{-- Modal Detail (satu modal global) --}}
<div class="modal fade" id="detail-modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="detail-modal-title">Detail</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="detail-modal-body"></div>
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
            // DataTables client-side untuk hasil per tim.
            if ($.fn && $.fn.DataTable) {
                $('#result-table').DataTable({
                    pageLength: 10,
                    order: [[1, 'asc']],
                    columnDefs: [
                        { orderable: false, searchable: false, targets: [0, 6] }
                    ],
                    language: { emptyTable: 'Belum ada tim final.' },
                    drawCallback: function (settings) {
                        const api = this.api();
                        api.column(0, { search: 'applied', order: 'applied' }).nodes().each(function (cell, i) {
                            cell.innerHTML = settings._iDisplayStart + i + 1;
                        });
                    }
                });
            }

            // Data untuk modal Detail.
            const teamDetail = @json($teamDetail);
            const unassignBase = "{{ route('admin.tahap.generation.unassign', ['tahap' => $tahap->slug, 'assignment' => '__ASSIGNMENT__']) }}";
            const csrf = '{{ csrf_token() }}';

            function esc(s) {
                return String(s ?? '').replace(/[&<>"']/g, c => ({
                    '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;'
                }[c]));
            }

            $(document).on('click', '.detail-btn', function () {
                const d = teamDetail[String(this.dataset.teamId)];
                if (!d) return;

                $('#detail-modal-title').text('Detail — ' + d.code + ' (' + d.members.map(m => m.name).join(', ') + ')');

                let html = '';
                if (!d.lembagas.length) {
                    html = '<p class="text-muted mb-0">Belum ada lembaga terpasang.</p>';
                } else {
                    html = '<div class="table-responsive"><table class="table table-sm align-middle">'
                        + '<thead><tr><th>Lembaga</th><th>Jarak per Asesor</th><th>Status</th><th></th></tr></thead><tbody>';

                    d.lembagas.forEach(l => {
                        const dist = l.distances.map((km, i) => {
                            const name = d.members[i] ? d.members[i].name : ('Asesor ' + (i + 1));
                            return name + ': ' + (km === null ? '-' : km + ' km');
                        }).join('<br>');

                        const status = l.is_manual
                            ? '<span class="badge bg-warning text-dark">Manual</span>'
                            : '<span class="badge bg-success">Otomatis</span>';

                        html += '<tr>'
                            + '<td><div class="fw-semibold">' + esc(l.name) + '</div>'
                            + '<div class="text-muted fs-13">NPSN: ' + esc(l.npsn) + ' · ' + esc(l.kabupaten) + '</div></td>'
                            + '<td class="fs-13">' + dist + '</td>'
                            + '<td>' + status + '</td>'
                            + '<td class="text-end"><form method="POST" action="' + unassignBase.replace('__ASSIGNMENT__', l.assignment_id) + '" onsubmit="return confirm(\'Lepas lembaga ini?\');">'
                            + '<input type="hidden" name="_token" value="' + csrf + '">'
                            + '<input type="hidden" name="_method" value="DELETE">'
                            + '<button class="btn btn-sm btn-outline-danger">Lepas</button>'
                            + '</form></td></tr>';
                    });

                    html += '</tbody></table></div>';
                }

                $('#detail-modal-body').html(html);
                new bootstrap.Modal(document.getElementById('detail-modal')).show();
            });
        });
    </script>
@endpush
