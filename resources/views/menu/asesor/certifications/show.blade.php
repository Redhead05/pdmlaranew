@extends('layouts.app')

@section('content')
    <div class="container">
        <h3>{{ $certification->title }}</h3>
        <p><strong>Number:</strong> {{ $certification->certificate_number }}</p>
        <p><strong>Issuer:</strong> {{ $certification->issuer }}</p>
        <p><strong>Issued at:</strong> {{ optional($certification->issued_at)->format('Y-m-d') }}</p>
        <p><strong>Expires at:</strong> {{ optional($certification->expires_at)->format('Y-m-d') }}</p>
        <p>{{ $certification->notes }}</p>
        @if($certification->file_path)
            <a href="{{ route('asesor.certifications.download', $certification) }}" class="btn btn-primary">Download File</a>
        @endif
        <a href="{{ route('asesor.certifications.index') }}" class="btn btn-secondary">Back</a>
    </div>
@endsection
