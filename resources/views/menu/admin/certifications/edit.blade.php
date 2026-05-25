@extends('app.layout')


@section('content')
    <div class="container">
        <h1>Edit Certification</h1>
        <form action="{{ route('admin.certifications.update', $certification) }}" method="post" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            @include('admin.certifications._form')
        </form>
    </div>
@endsection

