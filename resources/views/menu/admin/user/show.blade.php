@extends('app.layout')
@section('title', 'User Detail')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@section('content')
    <div class="container-fluid">
        <div class="main-content d-flex flex-column">
            <div class="card bg-white border-0 rounded-3 mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1 class="mb-0">User Detail</h1>
                        <div>
                            <a href="{{ route('admin.user.edit', $user->id) }}" class="btn btn-warning text-white me-2">
                                <i class="material-symbols-outlined align-middle">edit</i> Edit
                            </a>
                            <a href="{{ route('admin.user.index') }}" class="btn btn-secondary">
                                <i class="material-symbols-outlined align-middle">arrow_back</i> Back
                            </a>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Left Column -->
                        <div class="col-md-6">
                            <h4 class="mb-3 text-primary">Account Information</h4>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Name</label>
                                <p class="form-control-plaintext">{{ $user->name }}</p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">NIA</label>
                                <p class="form-control-plaintext">{{ $user->nia }}</p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Email</label>
                                <p class="form-control-plaintext">{{ $user->email }}</p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Role</label>
                                <p class="form-control-plaintext">
                                    @foreach($user->roles as $role)
                                        <span class="badge bg-primary">{{ ucfirst($role->name) }}</span>
                                    @endforeach
                                </p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Status</label>
                                <p class="form-control-plaintext">
                                    @if($user->is_active)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-danger">Inactive</span>
                                    @endif
                                </p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Created At</label>
                                <p class="form-control-plaintext">{{ $user->created_at->format('d M Y H:i') }}</p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Updated At</label>
                                <p class="form-control-plaintext">{{ $user->updated_at->format('d M Y H:i') }}</p>
                            </div>
                        </div>

                        <!-- Right Column -->
                        <div class="col-md-6">
                            <h4 class="mb-3 text-primary">Personal Information</h4>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Gender</label>
                                <p class="form-control-plaintext">
                                    @if($user->detail?->gender == 'L')
                                        Male
                                    @elseif($user->detail?->gender == 'P')
                                        Female
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Home Address</label>
                                <p class="form-control-plaintext">{{ $user->detail?->address_home ?? '-' }}</p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Home City</label>
                                <p class="form-control-plaintext">{{ $user->detail?->home_city ?? '-' }}</p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Work Address</label>
                                <p class="form-control-plaintext">{{ $user->detail?->address_work ?? '-' }}</p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Work City</label>
                                <p class="form-control-plaintext">{{ $user->detail?->work_city ?? '-' }}</p>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fw-bold">Type Asesor</label>
                                <p class="form-control-plaintext">{{ $user->detail?->type_asesor ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endpush

