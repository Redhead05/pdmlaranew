@extends('app.layout')


@section('content')
<div class="container">
    <h1>Certification #{{ $certification->id }}</h1>

    <p><strong>User:</strong> {{ $certification->user->name ?? '-' }}</p>
    <p><strong>Title:</strong> {{ $certification->title }}</p>
    <p><strong>Number:</strong> {{ $certification->certificate_number }}</p>
    <p><strong>Issuer:</strong> {{ $certification->issuer }}</p>
    <p><strong>Issued at:</strong> {{ optional($certification->issued_at)->format('Y-m-d') }}</p>
    <p><strong>Expires at:</strong> {{ optional($certification->expires_at)->format('Y-m-d') }}</p>
    @if($certification->file_path)
        <p><a href="{{ Storage::disk('public')->url($certification->file_path) }}" target="_blank">Download file</a></p>
    @endif

    <a href="{{ route('admin.certifications.index') }}" class="btn btn-secondary">Back</a>
</div>
@endsection

