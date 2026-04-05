@extends('app.layout')
@section('title', 'Kesanggupan')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.min.css">
    {{-- Bootstrap sudah dimuat oleh layout/partial, jangan load ulang di halaman agar tidak konflik styling/JS --}}
@endpush

@section('content')
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
                        <button type="button" class="btn btn-primary fw-medium text-white py-2 px-4 rounded-pill">generate</button>
                    </div>

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
                                        <th>Nama</th>
                                        <th>Email</th>
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
                                            <td>{{ $k->user->name ?? '-' }}</td>
                                            <td>{{ $k->user->email ?? '-' }}</td>
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
