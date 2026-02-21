@extends('app.layout')
@section('title', 'organizationstructure Management')

@section('content')
    <div class="container-fluid">
        <div class="main-content d-flex flex-column">
            <div class="card bg-white border-0 rounded-3 mb-4">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
                <div class="card-body p-4">
                    <h1>Chat</h1>
                    <p>Fitur chat masih dalam pengembangan. Mohon bersabar.</p>
                </div>
            </div>
        </div>
    </div>
@endsection
