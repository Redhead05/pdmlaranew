@extends('app.layout')
@section('title', 'Create User')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@section('content')
    <div class="container-fluid">
        <div class="main-content d-flex flex-column">
            <div class="card bg-white border-0 rounded-3 mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h1 class="mb-0">Create User</h1>
                        <a href="{{ route('admin.user.index') }}" class="btn btn-secondary">
                            <i class="material-symbols-outlined align-middle">arrow_back</i> Back
                        </a>
                    </div>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.user.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <!-- Left Column -->
                            <div class="col-md-6">
                                <h4 class="mb-3">Account Information</h4>

                                <!-- Name Field -->
                                <div class="mb-3">
                                    <label for="name" class="form-label">Name <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}" required>
                                </div>

                                <!-- NIA Field -->
                                <div class="mb-3">
                                    <label for="nia" class="form-label">NIA <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nia" name="nia" value="{{ old('nia') }}" required>
                                </div>

                                <!-- Email Field -->
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" required>
                                </div>

                                <!-- Password Field -->
                                <div class="mb-3">
                                    <label for="password" class="form-label">Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>

                                <!-- Password Confirmation Field -->
                                <div class="mb-3">
                                    <label for="password_confirmation" class="form-label">Confirm Password <span class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                                </div>

                                <!-- Role Field -->
                                <div class="mb-3">
                                    <label for="role" class="form-label">Role <span class="text-danger">*</span></label>
                                    <select class="form-select" id="role" name="role" required>
                                        <option value="">Select Role</option>
                                        @foreach($roles as $role)
                                            <option value="{{ $role->name }}" {{ old('role') == $role->name ? 'selected' : '' }}>
                                                {{ ucfirst($role->name) }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Status Field -->
                                <div class="mb-3">
                                    <label for="is_active" class="form-label">Status <span class="text-danger">*</span></label>
                                    <select class="form-select" id="is_active" name="is_active" required>
                                        <option value="1" {{ old('is_active') == '1' ? 'selected' : '' }}>Active</option>
                                        <option value="0" {{ old('is_active') == '0' ? 'selected' : '' }}>Inactive</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Right Column -->
                            <div class="col-md-6">
                                <h4 class="mb-3">Personal Information</h4>

                                <!-- Gender Field -->
                                <div class="mb-3">
                                    <label for="gender" class="form-label">Gender <span class="text-danger">*</span></label>
                                    <select class="form-select" id="gender" name="gender" required>
                                        <option value="">Select Gender</option>
                                        <option value="L" {{ old('gender') == 'L' ? 'selected' : '' }}>Male</option>
                                        <option value="P" {{ old('gender') == 'P' ? 'selected' : '' }}>Female</option>
                                    </select>
                                </div>

                                <!-- Home Address Field -->
                                <div class="mb-3">
                                    <label for="address_home" class="form-label">Home Address</label>
                                    <textarea class="form-control" id="address_home" name="address_home" rows="3">{{ old('address_home') }}</textarea>
                                </div>

                                <!-- Home City Field -->
                                <div class="mb-3">
                                    <label for="home_city" class="form-label">Home City</label>
                                    <input type="text" class="form-control" id="home_city" name="home_city" value="{{ old('home_city') }}">
                                </div>

                                <!-- Work Address Field -->
                                <div class="mb-3">
                                    <label for="address_work" class="form-label">Work Address</label>
                                    <textarea class="form-control" id="address_work" name="address_work" rows="3">{{ old('address_work') }}</textarea>
                                </div>

                                <!-- Work City Field -->
                                <div class="mb-3">
                                    <label for="work_city" class="form-label">Work City</label>
                                    <input type="text" class="form-control" id="work_city" name="work_city" value="{{ old('work_city') }}">
                                </div>

                                <!-- Type Asesor Field -->
                                <div class="mb-3">
                                    <label for="type_asesor" class="form-label">Type Asesor</label>
                                    <input type="text" class="form-control" id="type_asesor" name="type_asesor" value="{{ old('type_asesor') }}">
                                </div>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Create User</button>
                            <a href="{{ route('admin.user.index') }}" class="btn btn-secondary">Cancel</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endpush

