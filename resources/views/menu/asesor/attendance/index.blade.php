@extends('app.layout')

@section('title', 'Dashboard Asesor')
@section('content')
    <div class="container-fluid">
        <div class="main-content d-flex flex-column">
            <div class="main-content-container overflow-hidden">
                <div class="row justify-content-center">
            <div class="col-xl-4 col-xxl-3 col-sm-6">
                <div class="card bg-white border-0 rounded-3 mb-4 transition-y">
                    <div class="card-body p-4">
                        <div class="position-relative mb-3">
                            <a href="event-details.html">
                                <img src="{{ asset('assets/images/event-1.jpg') }}" class="rounded-3" alt="event">
                            </a>
                            <div class="bg-white pb-2 ps-2 position-absolute top-0 end-0 rounded-3 rounded-top-0 rounded-end-0 bg-for-dark-mode">
                                <span class="wh-60 lh-60 bg-primary hover-bg d-inline-block text-white text-center rounded-3 fs-16 fw-bold">$120</span>
                            </div>
                        </div>
                        <a class="text-secondary text-decoration-none fs-18 fw-bold hover d-block mb-2">Title Rakorda 2</a>
                        <div class="row mb-3">
                            <div class="col-6 d-flex align-items-center">
{{--                                <span class="material-symbols-outlined me-2 text-primary">schedule</span>--}}
                                <div>
                                    <div class="fw-bold text-decoration-none fs-18 hover d-block">Open</div>
                                    <div class="text-secondary fs-15 text-nowrap">{{ $form_open_date ?? '10-06-2025 08:00' }}</div>
                                </div>
                            </div>
                            <div class="col-6 d-flex align-items-center">
{{--                                <span class="material-symbols-outlined me-2 text-primary">schedule</span>--}}
                                <div>
                                    <div class="text-decoration-none fs-18 fw-bold hover d-block">Close</div>
                                    <div class="text-secondary fs-15 text-nowrap">{{ $form_close_date ?? '10-06-2025 17:00' }}</div>
                                </div>
                            </div>
                        </div>
                        <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined" rel="stylesheet">
                        <div class="signature-pad-wrapper">
                                <canvas id="signature-pad-1" width="280" height="140" style="border:1px solid #ccc; border-radius:8px;"></canvas>
                            </div>
                        <div>
                            <div class="p-4 d-flex justify-content-center gap-2">
                                <button type="button" class="btn btn-secondary" id="clear-signature">Clear</button>
                                <button type="button" class="btn btn-primary" id="save-signature">Save</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-xxl-3 col-sm-6">
                <div class="card bg-white border-0 rounded-3 mb-4 transition-y">
                    <div class="card-body p-4">
                        <div class="position-relative mb-3">
                            <a href="event-details.html">
                                <img src="assets/images/event-2.jpg" class="rounded-3" alt="event">
                            </a>
                            <div class="bg-white pb-2 ps-2 position-absolute top-0 end-0 rounded-3 rounded-top-0 rounded-end-0 bg-for-dark-mode">
                                <span class="wh-60 lh-60 bg-primary hover-bg d-inline-block text-white text-center rounded-3 fs-16 fw-bold">$120</span>
                            </div>
                        </div>
                        <a href="event-details.html" class="text-secondary text-decoration-none fs-18 fw-bold hover d-block mb-2">Leadership Summit 2024</a>

                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text.</p>

                        <ul class="ps-0 mb-4 list-unstyled d-flex align-items-center">
                            <li>
                                <a href="my-profile.html" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Olivia Clark">
                                    <img src="assets/images/user-10.jpg" class="wh-40 rounded-circle border border-2 border-color-white" alt="user">
                                </a>
                            </li>
                            <li class="ms-m-15">
                                <a href="my-profile.html" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Ava Turner">
                                    <img src="assets/images/user-11.jpg" class="wh-40 rounded-circle border border-2 border-color-white" alt="user">
                                </a>
                            </li>
                        </ul>

                        <div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="d-block">Seat Booked</span>
                                <span class="fw-medium d-block text-secondary">556</span>
                            </div>
                            <div class="progress bg-primary bg-opacity-10" style="height: 4px;" role="progressbar" aria-label="Primary example" aria-valuenow="30" aria-valuemin="0" aria-valuemax="100">
                                <div class="progress-bar bg-primary" style="width: 30%; height: 4px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-xxl-3 col-sm-6">
                <div class="card bg-white border-0 rounded-3 mb-4 transition-y">
                    <div class="card-body p-4">
                        <div class="position-relative mb-3">
                            <a href="event-details.html">
                                <img src="assets/images/event-3.jpg" class="rounded-3" alt="event">
                            </a>
                            <div class="bg-white pb-2 ps-2 position-absolute top-0 end-0 rounded-3 rounded-top-0 rounded-end-0 bg-for-dark-mode">
                                <span class="wh-60 lh-60 bg-primary hover-bg d-inline-block text-white text-center rounded-3 fs-16 fw-bold">$120</span>
                            </div>
                        </div>
                        <a href="event-details.html" class="text-secondary text-decoration-none fs-18 fw-bold hover d-block mb-2">Product Launch Webinar</a>

                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text.</p>

                        <ul class="ps-0 mb-4 list-unstyled d-flex align-items-center">
                            <li>
                                <a href="my-profile.html" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Olivia Clark">
                                    <img src="assets/images/user-12.jpg" class="wh-40 rounded-circle border border-2 border-color-white" alt="user">
                                </a>
                            </li>
                            <li class="ms-m-15">
                                <a href="my-profile.html" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Chloe Lewis">
                                    <img src="assets/images/user-13.jpg" class="wh-40 rounded-circle border border-2 border-color-white" alt="user">
                                </a>
                            </li>
                            <li class="ms-m-15">
                                <a href="my-profile.html" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Ava Turner">
                                    <img src="assets/images/user-14.jpg" class="wh-40 rounded-circle border border-2 border-color-white" alt="user">
                                </a>
                            </li>
                        </ul>

                        <div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="d-block">Seat Booked</span>
                                <span class="fw-medium d-block text-secondary">356</span>
                            </div>
                            <div class="progress bg-primary bg-opacity-10" style="height: 4px;" role="progressbar" aria-label="Primary example" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100">
                                <div class="progress-bar bg-primary" style="width: 60%; height: 4px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-xxl-3 col-sm-6">
                <div class="card bg-white border-0 rounded-3 mb-4 transition-y">
                    <div class="card-body p-4">
                        <div class="position-relative mb-3">
                            <a href="event-details.html">
                                <img src="assets/images/event-4.jpg" class="rounded-3" alt="event">
                            </a>
                            <div class="bg-white pb-2 ps-2 position-absolute top-0 end-0 rounded-3 rounded-top-0 rounded-end-0 bg-for-dark-mode">
                                <span class="wh-60 lh-60 bg-primary hover-bg d-inline-block text-white text-center rounded-3 fs-16 fw-bold">$120</span>
                            </div>
                        </div>
                        <a href="event-details.html" class="text-secondary text-decoration-none fs-18 fw-bold hover d-block mb-2">AI in Healthcare Symposium</a>

                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text.</p>

                        <ul class="ps-0 mb-4 list-unstyled d-flex align-items-center">
                            <li>
                                <a href="my-profile.html" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Olivia Clark">
                                    <img src="assets/images/user-15.jpg" class="wh-40 rounded-circle border border-2 border-color-white" alt="user">
                                </a>
                            </li>
                        </ul>

                        <div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="d-block">Seat Booked</span>
                                <span class="fw-medium d-block text-secondary">3226</span>
                            </div>
                            <div class="progress bg-primary bg-opacity-10" style="height: 4px;" role="progressbar" aria-label="Primary example" aria-valuenow="70" aria-valuemin="0" aria-valuemax="100">
                                <div class="progress-bar bg-primary" style="width: 70%; height: 4px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-xxl-3 col-sm-6">
                <div class="card bg-white border-0 rounded-3 mb-4 transition-y">
                    <div class="card-body p-4">
                        <div class="position-relative mb-3">
                            <a href="event-details.html">
                                <img src="assets/images/event-5.jpg" class="rounded-3" alt="event">
                            </a>
                            <div class="bg-white pb-2 ps-2 position-absolute top-0 end-0 rounded-3 rounded-top-0 rounded-end-0 bg-for-dark-mode">
                                <span class="wh-60 lh-60 bg-primary hover-bg d-inline-block text-white text-center rounded-3 fs-16 fw-bold">$120</span>
                            </div>
                        </div>
                        <a href="event-details.html" class="text-secondary text-decoration-none fs-18 fw-bold hover d-block mb-2">Tech Summit 2024</a>

                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text.</p>

                        <ul class="ps-0 mb-4 list-unstyled d-flex align-items-center">
                            <li>
                                <a href="my-profile.html" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Olivia Clark">
                                    <img src="assets/images/user-16.jpg" class="wh-40 rounded-circle border border-2 border-color-white" alt="user">
                                </a>
                            </li>
                            <li class="ms-m-15">
                                <a href="my-profile.html" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Alexander Garcia">
                                    <img src="assets/images/user-17.jpg" class="wh-40 rounded-circle border border-2 border-color-white" alt="user">
                                </a>
                            </li>
                        </ul>

                        <div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="d-block">Seat Booked</span>
                                <span class="fw-medium d-block text-secondary">4109</span>
                            </div>
                            <div class="progress bg-primary bg-opacity-10" style="height: 4px;" role="progressbar" aria-label="Primary example" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                                <div class="progress-bar bg-primary" style="width: 50%; height: 4px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-xxl-3 col-sm-6">
                <div class="card bg-white border-0 rounded-3 mb-4 transition-y">
                    <div class="card-body p-4">
                        <div class="position-relative mb-3">
                            <a href="event-details.html">
                                <img src="assets/images/event-6.jpg" class="rounded-3" alt="event">
                            </a>
                            <div class="bg-white pb-2 ps-2 position-absolute top-0 end-0 rounded-3 rounded-top-0 rounded-end-0 bg-for-dark-mode">
                                <span class="wh-60 lh-60 bg-primary hover-bg d-inline-block text-white text-center rounded-3 fs-16 fw-bold">$120</span>
                            </div>
                        </div>
                        <a href="event-details.html" class="text-secondary text-decoration-none fs-18 fw-bold hover d-block mb-2">Startup Pitch Day</a>

                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text.</p>

                        <ul class="ps-0 mb-4 list-unstyled d-flex align-items-center">
                            <li>
                                <a href="my-profile.html" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Olivia Clark">
                                    <img src="assets/images/user-18.jpg" class="wh-40 rounded-circle border border-2 border-color-white" alt="user">
                                </a>
                            </li>
                            <li class="ms-m-15">
                                <a href="my-profile.html" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Alexander Garcia">
                                    <img src="assets/images/user-19.jpg" class="wh-40 rounded-circle border border-2 border-color-white" alt="user">
                                </a>
                            </li>
                            <li class="ms-m-15">
                                <a href="my-profile.html" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Chloe Lewis">
                                    <img src="assets/images/user-20.jpg" class="wh-40 rounded-circle border border-2 border-color-white" alt="user">
                                </a>
                            </li>
                        </ul>

                        <div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="d-block">Seat Booked</span>
                                <span class="fw-medium d-block text-secondary">432</span>
                            </div>
                            <div class="progress bg-primary bg-opacity-10" style="height: 4px;" role="progressbar" aria-label="Primary example" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100">
                                <div class="progress-bar bg-primary" style="width: 40%; height: 4px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-xxl-3 col-sm-6">
                <div class="card bg-white border-0 rounded-3 mb-4 transition-y">
                    <div class="card-body p-4">
                        <div class="position-relative mb-3">
                            <a href="event-details.html">
                                <img src="assets/images/event-7.jpg" class="rounded-3" alt="event">
                            </a>
                            <div class="bg-white pb-2 ps-2 position-absolute top-0 end-0 rounded-3 rounded-top-0 rounded-end-0 bg-for-dark-mode">
                                <span class="wh-60 lh-60 bg-primary hover-bg d-inline-block text-white text-center rounded-3 fs-16 fw-bold">$120</span>
                            </div>
                        </div>
                        <a href="event-details.html" class="text-secondary text-decoration-none fs-18 fw-bold hover d-block mb-2">Workshop: Digital Marketing</a>

                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text.</p>

                        <ul class="ps-0 mb-4 list-unstyled d-flex align-items-center">
                            <li>
                                <a href="my-profile.html" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Olivia Clark">
                                    <img src="assets/images/user-21.jpg" class="wh-40 rounded-circle border border-2 border-color-white" alt="user">
                                </a>
                            </li>
                        </ul>

                        <div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="d-block">Seat Booked</span>
                                <span class="fw-medium d-block text-secondary">1728</span>
                            </div>
                            <div class="progress bg-primary bg-opacity-10" style="height: 4px;" role="progressbar" aria-label="Primary example" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100">
                                <div class="progress-bar bg-primary" style="width: 60%; height: 4px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-xxl-3 col-sm-6">
                <div class="card bg-white border-0 rounded-3 mb-4 transition-y">
                    <div class="card-body p-4">
                        <div class="position-relative mb-3">
                            <a href="event-details.html">
                                <img src="assets/images/event-8.jpg" class="rounded-3" alt="event">
                            </a>
                            <div class="bg-white pb-2 ps-2 position-absolute top-0 end-0 rounded-3 rounded-top-0 rounded-end-0 bg-for-dark-mode">
                                <span class="wh-60 lh-60 bg-primary hover-bg d-inline-block text-white text-center rounded-3 fs-16 fw-bold">$120</span>
                            </div>
                        </div>
                        <a href="event-details.html" class="text-secondary text-decoration-none fs-18 fw-bold hover d-block mb-2">Charity Gala Dinner</a>

                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text.</p>

                        <ul class="ps-0 mb-4 list-unstyled d-flex align-items-center">
                            <li>
                                <a href="my-profile.html" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Olivia Clark">
                                    <img src="assets/images/user-22.jpg" class="wh-40 rounded-circle border border-2 border-color-white" alt="user">
                                </a>
                            </li>
                            <li class="ms-m-15">
                                <a href="my-profile.html" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Alexander Garcia">
                                    <img src="assets/images/user-23.jpg" class="wh-40 rounded-circle border border-2 border-color-white" alt="user">
                                </a>
                            </li>
                            <li class="ms-m-15">
                                <a href="my-profile.html" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Chloe Lewis">
                                    <img src="assets/images/user-24.jpg" class="wh-40 rounded-circle border border-2 border-color-white" alt="user">
                                </a>
                            </li>
                            <li class="ms-m-15">
                                <a href="my-profile.html" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Ava Turner">
                                    <img src="assets/images/user-25.jpg" class="wh-40 rounded-circle border border-2 border-color-white" alt="user">
                                </a>
                            </li>
                        </ul>

                        <div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="d-block">Seat Booked</span>
                                <span class="fw-medium d-block text-secondary">1306</span>
                            </div>
                            <div class="progress bg-primary bg-opacity-10" style="height: 4px;" role="progressbar" aria-label="Primary example" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100">
                                <div class="progress-bar bg-primary" style="width: 90%; height: 4px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-xxl-3 col-sm-6">
                <div class="card bg-white border-0 rounded-3 mb-4 transition-y">
                    <div class="card-body p-4">
                        <div class="position-relative mb-3">
                            <a href="event-details.html">
                                <img src="assets/images/event-9.jpg" class="rounded-3" alt="event">
                            </a>
                            <div class="bg-white pb-2 ps-2 position-absolute top-0 end-0 rounded-3 rounded-top-0 rounded-end-0 bg-for-dark-mode">
                                <span class="wh-60 lh-60 bg-primary hover-bg d-inline-block text-white text-center rounded-3 fs-16 fw-bold">$120</span>
                            </div>
                        </div>
                        <a href="event-details.html" class="text-secondary text-decoration-none fs-18 fw-bold hover d-block mb-2">Web Development Seminar</a>

                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text.</p>

                        <ul class="ps-0 mb-4 list-unstyled d-flex align-items-center">
                            <li>
                                <a href="my-profile.html" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Olivia Clark">
                                    <img src="assets/images/user-26.jpg" class="wh-40 rounded-circle border border-2 border-color-white" alt="user">
                                </a>
                            </li>
                            <li class="ms-m-15">
                                <a href="my-profile.html" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Alexander Garcia">
                                    <img src="assets/images/user-27.jpg" class="wh-40 rounded-circle border border-2 border-color-white" alt="user">
                                </a>
                            </li>
                        </ul>

                        <div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="d-block">Seat Booked</span>
                                <span class="fw-medium d-block text-secondary">2756</span>
                            </div>
                            <div class="progress bg-primary bg-opacity-10" style="height: 4px;" role="progressbar" aria-label="Primary example" aria-valuenow="50" aria-valuemin="0" aria-valuemax="100">
                                <div class="progress-bar bg-primary" style="width: 50%; height: 4px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-xxl-3 col-sm-6">
                <div class="card bg-white border-0 rounded-3 mb-4 transition-y">
                    <div class="card-body p-4">
                        <div class="position-relative mb-3">
                            <a href="event-details.html">
                                <img src="assets/images/event-10.jpg" class="rounded-3" alt="event">
                            </a>
                            <div class="bg-white pb-2 ps-2 position-absolute top-0 end-0 rounded-3 rounded-top-0 rounded-end-0 bg-for-dark-mode">
                                <span class="wh-60 lh-60 bg-primary hover-bg d-inline-block text-white text-center rounded-3 fs-16 fw-bold">$120</span>
                            </div>
                        </div>
                        <a href="event-details.html" class="text-secondary text-decoration-none fs-18 fw-bold hover d-block mb-2">Networking Mixer</a>

                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text.</p>

                        <ul class="ps-0 mb-4 list-unstyled d-flex align-items-center">
                            <li>
                                <a href="my-profile.html" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Olivia Clark">
                                    <img src="assets/images/user-28.jpg" class="wh-40 rounded-circle border border-2 border-color-white" alt="user">
                                </a>
                            </li>
                            <li class="ms-m-15">
                                <a href="my-profile.html" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Alexander Garcia">
                                    <img src="assets/images/user-29.jpg" class="wh-40 rounded-circle border border-2 border-color-white" alt="user">
                                </a>
                            </li>
                        </ul>

                        <div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="d-block">Seat Booked</span>
                                <span class="fw-medium d-block text-secondary">1467</span>
                            </div>
                            <div class="progress bg-primary bg-opacity-10" style="height: 4px;" role="progressbar" aria-label="Primary example" aria-valuenow="85" aria-valuemin="0" aria-valuemax="100">
                                <div class="progress-bar bg-primary" style="width: 85%; height: 4px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-xxl-3 col-sm-6">
                <div class="card bg-white border-0 rounded-3 mb-4 transition-y">
                    <div class="card-body p-4">
                        <div class="position-relative mb-3">
                            <a href="event-details.html">
                                <img src="assets/images/event-1.jpg" class="rounded-3" alt="event">
                            </a>
                            <div class="bg-white pb-2 ps-2 position-absolute top-0 end-0 rounded-3 rounded-top-0 rounded-end-0 bg-for-dark-mode">
                                <span class="wh-60 lh-60 bg-primary hover-bg d-inline-block text-white text-center rounded-3 fs-16 fw-bold">$120</span>
                            </div>
                        </div>
                        <a href="event-details.html" class="text-secondary text-decoration-none fs-18 fw-bold hover d-block mb-2">Annual Conference 2024</a>

                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text.</p>

                        <ul class="ps-0 mb-4 list-unstyled d-flex align-items-center">
                            <li>
                                <a href="my-profile.html" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Olivia Clark">
                                    <img src="assets/images/user-6.jpg" class="wh-40 rounded-circle border border-2 border-color-white" alt="user">
                                </a>
                            </li>
                            <li class="ms-m-15">
                                <a href="my-profile.html" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Alexander Garcia">
                                    <img src="assets/images/user-7.jpg" class="wh-40 rounded-circle border border-2 border-color-white" alt="user">
                                </a>
                            </li>
                            <li class="ms-m-15">
                                <a href="my-profile.html" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Chloe Lewis">
                                    <img src="assets/images/user-8.jpg" class="wh-40 rounded-circle border border-2 border-color-white" alt="user">
                                </a>
                            </li>
                        </ul>

                        <div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="d-block">Seat Booked</span>
                                <span class="fw-medium d-block text-secondary">799</span>
                            </div>
                            <div class="progress bg-primary bg-opacity-10" style="height: 4px;" role="progressbar" aria-label="Primary example" aria-valuenow="90" aria-valuemin="0" aria-valuemax="100">
                                <div class="progress-bar bg-primary" style="width: 90%; height: 4px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-4 col-xxl-3 col-sm-6">
                <div class="card bg-white border-0 rounded-3 mb-4 transition-y">
                    <div class="card-body p-4">
                        <div class="position-relative mb-3">
                            <a href="event-details.html">
                                <img src="assets/images/event-2.jpg" class="rounded-3" alt="event">
                            </a>
                            <div class="bg-white pb-2 ps-2 position-absolute top-0 end-0 rounded-3 rounded-top-0 rounded-end-0 bg-for-dark-mode">
                                <span class="wh-60 lh-60 bg-primary hover-bg d-inline-block text-white text-center rounded-3 fs-16 fw-bold">$120</span>
                            </div>
                        </div>
                        <a href="event-details.html" class="text-secondary text-decoration-none fs-18 fw-bold hover d-block mb-2">Leadership Summit 2024</a>

                        <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text.</p>

                        <ul class="ps-0 mb-4 list-unstyled d-flex align-items-center">
                            <li>
                                <a href="my-profile.html" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Olivia Clark">
                                    <img src="assets/images/user-10.jpg" class="wh-40 rounded-circle border border-2 border-color-white" alt="user">
                                </a>
                            </li>
                            <li class="ms-m-15">
                                <a href="my-profile.html" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Ava Turner">
                                    <img src="assets/images/user-11.jpg" class="wh-40 rounded-circle border border-2 border-color-white" alt="user">
                                </a>
                            </li>
                            <li class="ms-m-15">
                                <a href="my-profile.html" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Ava Turner">
                                    <img src="assets/images/user-12.jpg" class="wh-40 rounded-circle border border-2 border-color-white" alt="user">
                                </a>
                            </li>
                            <li class="ms-m-15">
                                <a href="my-profile.html" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-title="Ava Turner">
                                    <img src="assets/images/user-13.jpg" class="wh-40 rounded-circle border border-2 border-color-white" alt="user">
                                </a>
                            </li>
                        </ul>

                        <div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="d-block">Seat Booked</span>
                                <span class="fw-medium d-block text-secondary">3241</span>
                            </div>
                            <div class="progress bg-primary bg-opacity-10" style="height: 4px;" role="progressbar" aria-label="Primary example" aria-valuenow="40" aria-valuemin="0" aria-valuemax="100">
                                <div class="progress-bar bg-primary" style="width: 40%; height: 4px;"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
                <div class="col-lg-12">
                    <div class="card bg-white border-0 rounded-3 mb-4">
                        <div class="card-body p-4">
                            <div class="d-flex justify-content-center justify-content-sm-between align-items-center text-center flex-wrap gap-2 showing-wrap">
                                <span>Showing 12 of 30 Results</span>

                                <nav aria-label="Page navigation example">
                                    <ul class="pagination mb-0 justify-content-center">
                                        <li class="page-item">
                                            <a class="page-link icon" href="events.html" aria-label="Previous">
                                                <i class="material-symbols-outlined">keyboard_arrow_left</i>
                                            </a>
                                        </li>
                                        <li class="page-item"><a class="page-link active" href="events.html">1</a></li>
                                        <li class="page-item"><a class="page-link" href="events.html">2</a></li>
                                        <li class="page-item"><a class="page-link" href="events.html">3</a></li>
                                        <li class="page-item"><a class="page-link" href="events.html">4</a></li>
                                        <li class="page-item">
                                            <a class="page-link icon" href="events.html" aria-label="Next">
                                                <i class="material-symbols-outlined">keyboard_arrow_right</i>
                                            </a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const canvas = document.getElementById('signature-pad-1');
            const signaturePad = new SignaturePad(canvas);

            document.getElementById('clear-signature').addEventListener('click', function () {
                signaturePad.clear();
            });

            document.getElementById('save-signature').addEventListener('click', function () {
                if (signaturePad.isEmpty()) {
                    alert('Please provide a signature first.');
                } else {
                    const dataURL = signaturePad.toDataURL();
                    // You can send dataURL to your server via AJAX or display it
                    alert('Signature saved! (Base64):\n' + dataURL);
                }
            });
        });
    </script>
@endpush
