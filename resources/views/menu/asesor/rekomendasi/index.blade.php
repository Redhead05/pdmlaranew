@extends('app.layout')
@section('title', 'Rekomendasi Lembaga')

@section('content')
<div class="container-fluid">
    <div class="main-content d-flex flex-column">
        <div class="card bg-white border-0 rounded-3 mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                    <div>
                        <h4 class="mb-1">Rekomendasi Lembaga Terdekat</h4>
                        <p class="text-muted mb-0 fs-14">
                            Lembaga diurutkan dari yang terdekat dengan lokasi Anda, sesuai jumlah kesanggupan.
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <div class="card bg-white border-0 rounded-3 mb-4">
            <div class="card-body p-4">

                {{-- Pilih Tahap --}}
                @if($kesanggupans->isEmpty())
                    <p class="text-muted mb-0">Anda belum mengisi kesanggupan pada tahap mana pun.</p>
                @else
                    <form method="GET" action="{{ route('asesor.rekomendasi-lembaga') }}" class="mb-4">
                        <label class="form-label fw-semibold">Pilih Tahap</label>
                        <div class="d-flex gap-2">
                            <select name="tahap_id" class="form-select" style="max-width: 320px;" onchange="this.form.submit()">
                                @foreach($kesanggupans as $k)
                                    <option value="{{ $k->tahap_id }}" @selected($selected && $selected->tahap_id === $k->tahap_id)>
                                        {{ $k->tahap->tahap }} — Kesanggupan {{ $k->kesanggupan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>

                    @if($selected)
                        <p class="text-muted mb-3">
                            Kuota visitasi Anda pada tahap ini: <strong>{{ $kuota }}</strong> lembaga.
                        </p>

                        @if($recommendations->isEmpty())
                            <p class="text-muted mb-0">Lokasi Anda belum diatur (latitude/longitude) atau belum ada lembaga pada tahap ini.</p>
                        @else
                            <div class="table-responsive">
                                <table class="table align-middle">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>Lembaga</th>
                                            <th>Alamat</th>
                                            <th>Jarak</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recommendations as $i => $r)
                                            <tr>
                                                <td>{{ $i + 1 }}</td>
                                                <td>
                                                    <div class="fw-semibold">{{ $r['lembaga']->satuan_pen }}</div>
                                                    <div class="text-muted fs-13">NPSN: {{ $r['lembaga']->npsn }}</div>
                                                </td>
                                                <td>{{ $r['lembaga']->alamat }}, {{ $r['lembaga']->kabupaten }}</td>
                                                <td>{{ number_format($r['distance_km'], 1) }} km</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    @endif
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
