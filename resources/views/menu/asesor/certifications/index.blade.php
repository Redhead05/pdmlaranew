@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="card bg-white border-0 rounded-3 mb-4">
            <div class="card-body p-3">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="mb-0">My Certifications</h4>
                    <form method="get">
                        <select name="year" onchange="this.form.submit()" class="form-control">
                            <option value="">All years</option>
                            @foreach($years as $y)
                                <option value="{{ $y }}" @if($year == $y) selected @endif>{{ $y }}</option>
                            @endforeach
                        </select>
                    </form>
                </div>

                @if($certifications->isEmpty())
                    <p>No certifications found.</p>
                @else
                    @foreach($certifications as $yr => $items)
                        <h5 class="mt-3">Year: {{ $yr }}</h5>
                        <ul class="list-group mb-3">
                            @foreach($items as $cert)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <strong>{{ $cert->title }}</strong>
                                        <div class="text-muted small">Issued: {{ optional($cert->issued_at)->format('Y-m-d') }} — Issuer: {{ $cert->issuer }}</div>
                                    </div>
                                    <div>
                                        @if($cert->file_path)
                                            <a href="{{ route('asesor.certifications.download', $cert) }}" class="btn btn-sm btn-outline-primary">Download</a>
                                        @endif
                                        <a href="{{ route('asesor.certifications.show', $cert) }}" class="btn btn-sm btn-secondary">Details</a>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
@endsection
