@php
    use Carbon\Carbon;
@endphp
@extends('app.layout')

@section('title', 'Dashboard Asesor')
@section('content')
    <div class="container-fluid">
        <div class="main-content d-flex flex-column">
            <div class="main-content-container overflow-hidden">
                <div class="row justify-content-center">
                    @if($attendanceData->count())
                        @foreach($attendanceData as $data)
                            @php
                                $attendance = $data['attendance'];
                                $isSigned = $data['isSigned'];
                                $signature = $data['signature'];
                                $now = Carbon::now(config('app.timezone'));
                                $endDate = Carbon::parse($attendance->end_date, config('app.timezone'));
                            @endphp
                            <div class="col-xl-4 col-xxl-3 col-sm-6">
                                <div class="card bg-white border-0 rounded-3 mb-4 transition-y">
                                    <div class="card-body p-4">
                                        <div class="position-relative mb-3">
                                            <a href="#">
                                                <img src="{{ asset('assets/images/event-1.jpg') }}" class="rounded-3" alt="event">
                                            </a>
                                            @if($isSigned)
                                                <div class="bg-white pb-2 ps-2 position-absolute top-0 end-0 rounded-3 rounded-top-0 rounded-end-0 bg-for-dark-mode">
                                                    <span class="material-symbols-outlined wh-60 lh-60 bg-primary hover-bg d-inline-block text-white text-center rounded-3 fs-16 fw-bold">
                                                        check
                                                    </span>
                                                </div>
                                            @endif
                                        </div>
                                        @if(session('success') && old('attendance_id') === $attendance->id)
                                            <div class="alert alert-success">{{ session('success') }}</div>
                                        @endif
                                        <a class="text-secondary text-decoration-none fs-18 fw-bold hover d-block mb-2">
                                            {{ $attendance->title }}
                                        </a>
                                        <div class="row mb-3">
                                            <div class="col-6 d-flex align-items-center">
                                                <div>
                                                    <div class="fw-bold text-decoration-none fs-18 hover d-block">Open</div>
                                                    <div class="text-secondary fs-15 text-nowrap">{{ $attendance->start_date }}</div>
                                                </div>
                                            </div>
                                            <div class="col-6 d-flex align-items-center">
                                                <div>
                                                    <div class="text-decoration-none fs-18 fw-bold hover d-block">Close</div>
                                                    <div class="text-secondary fs-15 text-nowrap">{{ $attendance->end_date }}</div>
                                                </div>
                                            </div>
                                        </div>
                                        @if($now->lte($endDate))
                                            <form method="POST" action="{{ route('asesor.attendance.store') }}" id="signature-form-{{ $attendance->id }}">
                                                @csrf
                                                <input type="hidden" name="attendance_id" value="{{ $attendance->id }}">
                                                <input type="hidden" id="saved-signature-{{ $attendance->id }}" value="{{ $signature }}">
                                                <input type="hidden" name="signature" id="signature-input-{{ $attendance->id }}">
                                                <div class="signature-pad-wrapper">
                                                    <canvas id="signature-pad-{{ $attendance->id }}" width="280" height="140" style="border:1px solid #ccc; border-radius:8px;"></canvas>
                                                </div>
                                                <div class="p-4 d-flex justify-content-center gap-2">
                                                    <button type="button" class="btn btn-secondary" id="clear-signature-{{ $attendance->id }}">Clear</button>
                                                    <button type="submit" class="btn btn-primary">Save</button>
                                                </div>
                                            </form>

                                        @else
                                            <div class="text-danger text-center mt-3">Attendance has ended.</div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div class="d-flex justify-content-center">
                            {{ $attendances->links() }}
                        </div>
                    @else
                        <div class="text-center text-secondary">No attendance available.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            @foreach($attendanceData as $data)
            (function() {
                const id = {{ $data['attendance']->id }};
                const canvas = document.getElementById('signature-pad-' + id);
                if (!canvas) return;
                const signaturePad = new SignaturePad(canvas);
                const savedSignature = document.getElementById('saved-signature-' + id).value;

                // Load saved signature if exists
                if (savedSignature) {
                    const image = new Image();
                    image.onload = function () {
                        signaturePad.clear();
                        const ctx = canvas.getContext('2d');
                        ctx.drawImage(image, 0, 0, canvas.width, canvas.height);
                    };
                    image.src = savedSignature;
                }

                document.getElementById('clear-signature-' + id).addEventListener('click', function () {
                    signaturePad.clear();
                });

                document.getElementById('signature-form-' + id).addEventListener('submit', function (e) {
                    if (signaturePad.isEmpty()) {
                        e.preventDefault();
                        alert('Please provide a signature first.');
                        return false;
                    }
                    document.getElementById('signature-input-' + id).value = signaturePad.toDataURL();
                });
            })();
            @endforeach
        });
    </script>
@endpush
