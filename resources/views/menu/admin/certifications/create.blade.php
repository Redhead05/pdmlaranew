@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Create Certification</h1>
        <form action="{{ route('admin.certifications.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            @include('admin.certifications._form')
        </form>
    </div>
@endsection

