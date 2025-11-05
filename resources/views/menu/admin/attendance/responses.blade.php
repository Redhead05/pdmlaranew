@extends('app.layout')
@section('title', 'Attendance Responses')

@push('styles')
    <link rel="stylesheet" href="https://cdn.datatables.net/2.3.4/css/dataTables.dataTables.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
@endpush

@section('content')
    <div class="container-fluid">
        <div class="main-content d-flex flex-column">
            <div class="card bg-white border-0 rounded-3 mb-4">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h1>Attendance Responses: {{ $attendance->title }}</h1>
                            <p class="text-muted mb-0">Type: {{ ucfirst($attendance->type) }} | Total Responses: {{ $responses->count() }}</p>
                        </div>
                        <a href="{{ route('admin.attendance.index') }}" class="btn btn-secondary">
                            <i class="material-symbols-outlined align-middle">arrow_back</i> Back
                        </a>
                    </div>

                    <div class="default-table-area all-products">
                        <div class="table-responsive">
                            <table id="responses-table" class="display table align-middle" style="width:100%">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>User</th>
                                    <th>Submitted At</th>
                                    <th>IP Address</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($responses as $i => $response)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $response->name }}</td>
                                        <td>{{ $response->email ?? 'N/A' }}</td>
                                        <td>{{ $response->user ? $response->user->name : 'Guest' }}</td>
                                        <td>{{ $response->created_at->format('Y-m-d H:i:s') }}</td>
                                        <td>{{ $response->ip }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#detailModal-{{ $response->id }}">
                                                <i class="material-symbols-outlined fs-16">info</i> Details
                                            </button>
                                        </td>
                                    </tr>

                                    <!-- Detail Modal -->
                                    <div class="modal fade" id="detailModal-{{ $response->id }}" tabindex="-1" aria-labelledby="detailModalLabel-{{ $response->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-lg">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="detailModalLabel-{{ $response->id }}">Response Details</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="mb-3">
                                                        <strong>Name:</strong> {{ $response->name }}
                                                    </div>
                                                    <div class="mb-3">
                                                        <strong>Email:</strong> {{ $response->email ?? 'N/A' }}
                                                    </div>
                                                    <div class="mb-3">
                                                        <strong>IP Address:</strong> {{ $response->ip }}
                                                    </div>
                                                    <div class="mb-3">
                                                        <strong>Submitted:</strong> {{ $response->created_at->format('Y-m-d H:i:s') }}
                                                    </div>
                                                    @if($response->payload)
                                                        <div class="mb-3">
                                                            <strong>Additional Data:</strong>
                                                            <table class="table table-sm table-bordered mt-2">
                                                                @foreach($response->payload as $key => $value)
                                                                    @if($key !== 'signature')
                                                                        <tr>
                                                                            <td class="fw-semibold">{{ ucfirst($key) }}</td>
                                                                            <td>{{ $value }}</td>
                                                                        </tr>
                                                                    @endif
                                                                @endforeach
                                                            </table>
                                                        </div>
                                                        @if(isset($response->payload['signature']))
                                                            <div class="mb-3">
                                                                <strong>Signature:</strong><br>
                                                                <img src="{{ $response->payload['signature'] }}" alt="Signature" class="img-fluid border mt-2" style="max-width: 400px;">
                                                            </div>
                                                        @endif
                                                    @endif
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="//code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="//cdn.datatables.net/2.3.4/js/dataTables.min.js"></script>
    <script>
        $(document).ready(function () {
            $('#responses-table').DataTable({
                responsive: true,
                pageLength: 10,
                order: [[4, 'desc']], // Sort by submitted at descending
                columnDefs: [
                    { orderable: false, searchable: false, targets: 0 },
                    { orderable: false, searchable: false, targets: 6 }
                ]
            });
        });
    </script>
@endpush
