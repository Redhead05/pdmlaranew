@extends('app.layout')
@section('title', 'Kesanggupan')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.min.css">
    {{-- Bootstrap sudah dimuat oleh layout/partial, jangan load ulang di halaman agar tidak konflik styling/JS --}}
@endpush

@section('content')
    @php
        // Pastikan variabel yang dipakai di view selalu terdefinisi agar tidak terjadi Undefined variable
        $can = $can ?? collect();
        $cannot = $cannot ?? collect();
        $notFilledUsers = $notFilledUsers ?? collect();
        $run = $run ?? null;
        $teams = $teams ?? collect();
        $unmatched = $unmatched ?? collect();
    @endphp

    <div class="container-fluid">
        <div class="main-content d-flex flex-column">
            <div class="card bg-white border-0 rounded-3 mb-4">
                <div class="card-body p-4">

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h1 class="mb-0">Kesanggupan</h1>

                            <div class="text-secondary">
                                Tahap {{ $tahap->tahap }} | SK: {{ $tahap->surat_keputusan }}
                            </div>
                        </div>

                        {{-- Generate form: posts to admin.kesanggupan.generate-teams --}}
                        @if($tahap->end_date && $tahap->end_date->lte(now()))
                            <form action="{{ route('admin.kesanggupan.generate-teams', ['tahap' => $tahap->id]) }}" method="POST">
                                @csrf
                                <input type="hidden" name="team_size" value="2">
                                <button type="submit" class="btn btn-primary fw-medium text-white py-2 px-4 rounded-pill">Generate Draft Teams</button>
                            </form>
                        @else
                            <div class="text-muted small">
                                Generate teams hanya bisa dilakukan setelah tahap selesai. End date: {{ $tahap->end_date?->format('d M Y H:i') ?? 'N/A' }}
                            </div>
                        @endif
                    </div>

                    {{-- If a run exists, show draft overview --}}
                    @if(isset($run) && $run)
                        <div class="card mb-4 border-1">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <div>
                                        <strong>Draft Run:</strong> #{{ $run->id }} — status: <span class="badge bg-warning text-dark">{{ ucfirst($run->status) }}</span>
                                        <div class="text-muted small">Dibuat oleh: {{ optional($run->created_by ? \App\Models\User::find($run->created_by) : null)->name ?? '-' }} | {{ $run->created_at?->format('d M Y H:i') }}</div>
                                    </div>
                                    <div class="d-flex gap-2">
                                        {{-- Download, Upload, Cancel, Finalize forms --}}
                                        <a href="{{ route('admin.kesanggupan.team-draft.download', ['tahap' => $tahap->id]) }}" class="btn btn-outline-secondary btn-sm">Download Excel (.xlsx)</a>

                                        <form action="{{ route('admin.kesanggupan.team-draft.upload', ['tahap' => $tahap->id]) }}" method="POST" enctype="multipart/form-data" class="d-inline-block ms-2">
                                            @csrf
                                            <input type="hidden" name="run_id" value="{{ $run->id }}">
                                            <label class="btn btn-outline-primary btn-sm mb-0">Upload CSV<input type="file" name="file" accept=".csv" onchange="this.form.submit()" hidden></label>
                                        </form>

                                        <form action="{{ route('admin.kesanggupan.team-draft.cancel', ['tahap' => $tahap->id]) }}" method="POST" class="d-inline ms-2" onsubmit="return confirm('Batalkan draft ini? Semua data draft akan dihapus');">
                                            @csrf
                                            <input type="hidden" name="run_id" value="{{ $run->id }}">
                                            <button type="submit" class="btn btn-outline-danger btn-sm">Cancel Draft</button>
                                        </form>

                                        {{-- Finalize form --}}
                                        <form action="{{ route('admin.kesanggupan.finalize-teams', ['tahap' => $tahap->id]) }}" method="POST" onsubmit="return confirm('Finalize teams? Setelah difinalisasi tidak dapat diubah.');" class="ms-2">
                                            @csrf
                                            <input type="hidden" name="run_id" value="{{ $run->id }}">
                                            <button type="submit" class="btn btn-success btn-sm" {{ (count($unmatched ?? []) > 0) ? 'disabled' : '' }}>Finalize Teams</button>
                                        </form>
                                    </div>
                                </div>

                                {{-- Teams list --}}
                                <div class="mb-3">
                                    <h5>Draft Teams</h5>
                                    @forelse($teams as $team)
                                        <div class="card mb-2">
                                            <div class="card-body d-flex justify-content-between align-items-center">
                                                <div>
                                                    <strong>{{ $team->team_code ?? ('Team ' . $team->id) }}</strong>
                                                    <div class="small text-muted">Anggota: {{ $team->members->count() }} (max 3)</div>
                                                </div>
                                                <div class="d-flex align-items-center gap-2">
                                                    {{-- Assign dropdown + button --}}
                                                    <form action="{{ route('admin.kesanggupan.team-draft.assign', ['tahap' => $tahap->id]) }}" method="POST" class="d-flex gap-2 align-items-center">
                                                        @csrf
                                                        <input type="hidden" name="run_id" value="{{ $run->id }}">
                                                        <input type="hidden" name="team_id" value="{{ $team->id }}">
                                                        <select name="user_id" class="form-select form-select-sm" style="min-width:220px;">
                                                            <option value="">-- Pilih Unmatched --</option>
                                                            @foreach($unmatched as $u)
                                                                <option value="{{ $u->id }}">{{ $u->name }} — {{ $u->email }} ({{ $u->detail->work_city ?? '-' }})</option>
                                                            @endforeach
                                                        </select>
                                                        <button type="submit" class="btn btn-primary btn-sm">Assign</button>
                                                    </form>
                                                </div>
                                            </div>
                                            <div class="card-footer bg-light">
                                                <div class="row">
                                                    @foreach($team->members as $member)
                                                        <div class="col-md-4 mb-2">
                                                            <div class="d-flex justify-content-between align-items-center">
                                                                <div>
                                                                    <strong>{{ $member->user->nia ?? '' }} - {{ $member->user->name ?? '-' }}</strong>
                                                                    <div class="small text-muted">{{ $member->user->email ?? '-' }} | {{ $member->user->detail->work_city ?? '-' }}</div>
                                                                    <div class="small text-muted">Kesanggupan: {{ \App\Models\Kesanggupan::where('tahap_id', $run->tahap_id)->where('user_id', $member->user->id)->value('kesanggupan') ?? '-' }}</div>
                                                                </div>
                                                                <div>
                                                                    {{-- Unassign button --}}
                                                                    <form action="{{ route('admin.kesanggupan.team-draft.unassign', ['tahap' => $tahap->id]) }}" method="POST" onsubmit="return confirm('Remove member from team?');">
                                                                        @csrf
                                                                        <input type="hidden" name="run_id" value="{{ $run->id }}">
                                                                        <input type="hidden" name="member_id" value="{{ $member->id }}">
                                                                        <button type="submit" class="btn btn-outline-danger btn-sm">Remove</button>
                                                                    </form>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @empty
                                        <div class="text-muted">Belum ada tim</div>
                                    @endforelse
                                </div>

                                {{-- Unmatched list --}}
                                <div>
                                    <h5>Unmatched (Belum ter-assign)</h5>
                                    <div class="table-responsive">
                                        <table class="table table-sm">
                                            <thead>
                                            <tr>
                                                <th>#</th>
                                                <th>Nama</th>
                                                <th>Email</th>
                                                <th>Work City</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            @forelse($unmatched as $i => $u)
                                                <tr>
                                                    <td>{{ $i + 1 }}</td>
                                                    <td>{{ $u->name }}</td>
                                                    <td>{{ $u->email }}</td>
                                                    <td>{{ $u->detail->work_city ?? '-' }}</td>
                                                    <td>
                                                        {{-- Create new team + assign --}}
                                                        <form action="{{ route('admin.kesanggupan.team-draft.assign', ['tahap' => $tahap->id]) }}" method="POST" class="d-inline">
                                                            @csrf
                                                            <input type="hidden" name="run_id" value="{{ $run->id }}">
                                                            {{-- Create new team on the fly by leaving team_id empty and controller will create a new team if needed --}}
                                                            <input type="hidden" name="team_id" value="{{ optional($teams->first())->id ?? '' }}">
                                                            <input type="hidden" name="user_id" value="{{ $u->id }}">
                                                            <button type="submit" class="btn btn-sm btn-outline-primary">Assign to First Team</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="4" class="text-muted">Tidak ada unmatched</td></tr>
                                            @endforelse
                                            </tbody>
                                        </table>
                                    </div>
                                </div>

                            </div>
                        </div>
                    @endif

                    {{-- Tabs --}}
                    <ul class="nav nav-tabs" id="kesanggupanTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button
                                class="nav-link active"
                                id="tab-can"
                                data-bs-toggle="tab"
                                data-bs-target="#pane-can"
                                type="button"
                                role="tab"
                                aria-controls="pane-can"
                                aria-selected="true"
                            >
                                Bisa ({{ $can->count() }})
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button
                                class="nav-link"
                                id="tab-cannot"
                                data-bs-toggle="tab"
                                data-bs-target="#pane-cannot"
                                type="button"
                                role="tab"
                                aria-controls="pane-cannot"
                                aria-selected="false"
                            >
                                Tidak Bisa ({{ $cannot->count() }})
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button
                                class="nav-link"
                                id="tab-notfilled"
                                data-bs-toggle="tab"
                                data-bs-target="#pane-notfilled"
                                type="button"
                                role="tab"
                                aria-controls="pane-notfilled"
                                aria-selected="false"
                            >
                                Belum Mengisi ({{ $notFilledUsers->count() }})
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content pt-3" id="kesanggupanTabsContent">
                        {{-- Can --}}
                        <div class="tab-pane fade show active default-table-area all-products mt-3" id="pane-can" role="tabpanel" aria-labelledby="tab-can" tabindex="0">
                            <div class="table-responsive">
                                <table id="kesanggupan-can" class="display table align-middle" style="width:100%">
                                    <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>NIA</th>
                                        <th>Nama</th>
                                        <th>Kab/Kot (Work City)</th>
                                        <th>Gender</th>
                                        <th>Tipe Asesor</th>
                                        <th>Latitude</th>
                                        <th>Longitude</th>
                                        <th>Kesanggupan</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($can as $i => $k)
                                        @php
                                            $detail = $k->user?->detail;
                                        @endphp
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td>{{ $k->user->nia ?? '-' }}</td>
                                            <td>{{ $k->user->name ?? '-' }}</td>
                                            <td>{{ $detail->work_city ?? '-' }}</td>
                                            <td>{{ $detail->gender ?? '-' }}</td>
                                            <td>{{ $detail->type_asesor ?? '-' }}</td>
                                            <td>{{ $detail->latitude ?? '-' }}</td>
                                            <td>{{ $detail->longitude ?? '-' }}</td>
                                            <td>{{ $k->kesanggupan ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="9" class="text-center text-muted">No data.</td></tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Cannot --}}
                        <div class="tab-pane fade default-table-area all-products mt-3" id="pane-cannot" role="tabpanel" aria-labelledby="tab-cannot" tabindex="0">
                            <div class="table-responsive">
                                <table id="kesanggupan-cannot" class="display table align-middle" style="width:100%">
                                    <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Kab/Kot (Work City)</th>
                                        <th>Gender</th>
                                        <th>Tipe Asesor</th>
                                        <th>Latitude</th>
                                        <th>Longitude</th>
                                        <th>Alasan</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($cannot as $i => $k)
                                        @php
                                            $detail = $k->user?->detail;
                                        @endphp
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td>{{ $k->user->name ?? '-' }}</td>
                                            <td>{{ $k->user->email ?? '-' }}</td>
                                            <td>{{ $detail->work_city ?? '-' }}</td>
                                            <td>{{ $detail->gender ?? '-' }}</td>
                                            <td>{{ $detail->type_asesor ?? '-' }}</td>
                                            <td>{{ $detail->latitude ?? '-' }}</td>
                                            <td>{{ $detail->longitude ?? '-' }}</td>
                                            <td>{{ $k->alasan ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="9" class="text-center text-muted">No data.</td>
                                        </tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                        {{-- Not filled --}}
                        <div class="tab-pane fade default-table-area all-products mt-3" id="pane-notfilled" role="tabpanel" aria-labelledby="tab-notfilled" tabindex="0">
                            <div class="table-responsive">
                                <table id="kesanggupan-notfilled" class="display table align-middle" style="width:100%">
                                    <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nama</th>
                                        <th>Email</th>
                                        <th>Kab/Kot (Work City)</th>
                                        <th>Gender</th>
                                        <th>Tipe Asesor</th>
                                        <th>Latitude</th>
                                        <th>Longitude</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @forelse($notFilledUsers as $i => $u)
                                        @php
                                            $detail = $u->detail;
                                        @endphp
                                        <tr>
                                            <td>{{ $i + 1 }}</td>
                                            <td>{{ $u->name }}</td>
                                            <td>{{ $u->email }}</td>
                                            <td>{{ $detail->work_city ?? '-' }}</td>
                                            <td>{{ $detail->gender ?? '-' }}</td>
                                            <td>{{ $detail->type_asesor ?? '-' }}</td>
                                            <td>{{ $detail->latitude ?? '-' }}</td>
                                            <td>{{ $detail->longitude ?? '-' }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="8" class="text-center text-muted">No data.</td></tr>
                                    @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>

                </div> {{-- card-body --}}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
    {{-- Bootstrap bundle sudah dimuat oleh layout/partial, jangan load ulang di halaman --}}
    <script>
        $(function () {
            if (!$.fn || !$.fn.DataTable) return;

            const options = {
                pageLength: 10,
                autoWidth: false,
                // penting: jangan destroy wrapper saat pindah tab
                destroy: false,
                retrieve: true,
            };

            function initOnce(selector) {
                const $el = $(selector);
                if (!$el.length) return;

                // Jangan init ulang kalau sudah pernah
                if ($.fn.DataTable.isDataTable($el)) return;

                // Pastikan tbody punya struktur kolom konsisten
                const colCount = $el.find('thead th').length;
                $el.find('tbody tr').each(function () {
                    const tdCount = $(this).children('td').length;
                    if (tdCount > 0 && tdCount !== colCount) {
                        $(this).remove();
                    }
                });

                $el.DataTable(options);
            }

            function adjustVisible() {
                // adjust hanya untuk tabel yg sudah di-init
                $($.fn.dataTable.tables(true)).DataTable().columns.adjust();
            }

            // Init semua tabel sekali di awal (aman, tabel di tab tersembunyi tetap boleh di-init)
            initOnce('#kesanggupan-can');
            initOnce('#kesanggupan-cannot');
            initOnce('#kesanggupan-notfilled');

            // Saat tab dibuka: cukup adjust kolom
            document.querySelectorAll('button[data-bs-toggle="tab"]').forEach((btn) => {
                btn.addEventListener('shown.bs.tab', () => {
                    adjustVisible();
                });
            });

            // adjust pertama kali
            adjustVisible();
        });
    </script>
@endpush
