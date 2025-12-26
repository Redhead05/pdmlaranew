@extends('frontend.layout.app')

@section('content')

    <section class="wrapper !bg-[#ffffff]">
        <div class="container pt-20 xl:pt-28 lg:pt-28 md:pt-28 pb-16 xl:pb-20 lg:pb-20 md:pb-20">
            <div class="flex flex-wrap mx-[-15px] !mb-3">
                <div class="md:w-10/12 lg:w-full xl:w-10/12 xxl:w-9/12 w-full flex-[0_0_auto] !px-[15px] max-w-full !mx-auto !text-center">
                    <h2 class="!text-[.75rem] uppercase !text-[#aab0bc] !mb-3 !tracking-[0.02rem] !leading-[1.35]">Struktur Organisasi</h2>
                    <h3 class="!text-[calc(1.305rem_+_0.66vw)] font-bold xl:!text-[1.8rem] !leading-[1.3] !mb-7 lg:!px-36 xl:!px-32">BAN PDM - JAWA TIMUR</h3>
                    <div class="form-select-wrapper !mb-4">
                        <select class="form-select text-center" aria-label="Default select example">
                            <option selected>Open this select menu</option>
                            <option value="1">2018-2022</option>
                            <option value="2">2022-2023</option>
                            <option value="3">2024-2025</option>
                        </select>
                    </div>

                </div>
            </div>

            {{-- First row: only Ketua and Sekretaris --}}
            <div class="flex flex-wrap mx-[-15px] !mb-6 justify-center">
                <div class="md:w-6/12 lg:w-4/12 xl:w-3/12 w-full flex-[0_0_auto] !px-[15px] max-w-full md:!mt-[40px]">
                    <div class="!relative">
                        <div class="shape !rounded-[.4rem] !bg-[#edf2fc] rellax xl:block lg:block md:block absolute" style="bottom: -0.75rem; right: -0.75rem; width: 98%; height: 98%; z-index:0"></div>
                        <div class="card">
                            <figure class="card-img-top">
                                <img class="max-w-full h-auto" src="{{ asset('assets/fe/assets/img/avatars/t1.jpg') }}" alt="{{ asset('assets/fe/assets/img/avatars/t1@2x.jpg') }} 2x" loading="lazy">
                            </figure>
                            <div class="card-body px-6 py-5">
                                <h4 class="!mb-1">A</h4>
                                <p class="!mb-0 text-[.85rem]">Ketua</p>

                                <!-- Social icons -->
                                <div class="mt-3 flex items-center space-x-3">
                                    <a href="#" aria-label="Instagram" class="text-gray-500 hover:text-pink-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5zm0 2a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3H7zm5 3.5a4.5 4.5 0 1 1 0 9 4.5 4.5 0 0 1 0-9zm0 2a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM18.5 6a.9.9 0 1 1 0 1.8.9.9 0 0 1 0-1.8z"/></svg>
                                    </a>
                                    <a href="#" aria-label="Facebook" class="text-gray-500 hover:text-blue-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12a10 10 0 1 0-11.5 9.9v-7h-2.2V12h2.2V9.6c0-2.2 1.3-3.4 3.2-3.4.9 0 1.8.1 1.8.1v2h-1c-1 0-1.3.6-1.3 1.2V12h2.3l-.4 2.9h-1.9v7A10 10 0 0 0 22 12z"/></svg>
                                    </a>
                                    <a href="#" aria-label="LinkedIn" class="text-gray-500 hover:text-blue-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M4.98 3.5A2.5 2.5 0 1 1 4.96 8H4.98A2.5 2.5 0 0 1 4.98 3.5zM3 9h4v12H3zM9 9h3.8v1.7h.1c.5-.9 1.6-1.7 3.3-1.7 3.5 0 4.2 2.3 4.2 5.3V21H16v-5.1c0-1.2 0-2.7-1.6-2.7-1.6 0-1.9 1.3-1.9 2.6V21H9z"/></svg>
                                    </a>
                                    <a href="mailto:example@example.com" aria-label="Email" class="text-gray-500 hover:text-green-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20 4H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2zm0 4-8 5L4 8V6l8 5 8-5v2z"/></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="md:w-6/12 lg:w-4/12 xl:w-3/12 w-full flex-[0_0_auto] !px-[15px] max-w-full md:!mt-[40px]">
                    <div class="!relative">
                        <div class="shape !rounded-[.4rem] !bg-[#edf2fc] rellax xl:block lg:block md:block absolute" style="bottom: -0.75rem; right: -0.75rem; width: 98%; height: 98%; z-index:0"></div>
                        <div class="card">
                            <figure class="card-img-top">
                                <img class="max-w-full h-auto" src="{{ asset('assets/fe/assets/img/avatars/t1.jpg') }}" alt="{{ asset('assets/fe/assets/img/avatars/t1@2x.jpg') }} 2x" loading="lazy">
                            </figure>
                            <div class="card-body px-6 py-5">
                                <h4 class="!mb-1">A</h4>
                                <p class="!mb-0 text-[.85rem]">Sekretaris</p>

                                <!-- Social icons -->
                                <div class="mt-3 flex items-center space-x-3">
                                    <a href="#" aria-label="Instagram" class="text-gray-500 hover:text-pink-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5zm0 2a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3H7zm5 3.5a4.5 4.5 0 1 1 0 9 4.5 4.5 0 0 1 0-9zm0 2a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM18.5 6a.9.9 0 1 1 0 1.8.9.9 0 0 1 0-1.8z"/></svg>
                                    </a>
                                    <a href="#" aria-label="Facebook" class="text-gray-500 hover:text-blue-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12a10 10 0 1 0-11.5 9.9v-7h-2.2V12h2.2V9.6c0-2.2 1.3-3.4 3.2-3.4.9 0 1.8.1 1.8.1v2h-1c-1 0-1.3.6-1.3 1.2V12h2.3l-.4 2.9h-1.9v7A10 10 0 0 0 22 12z"/></svg>
                                    </a>
                                    <a href="#" aria-label="LinkedIn" class="text-gray-500 hover:text-blue-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M4.98 3.5A2.5 2.5 0 1 1 4.96 8H4.98A2.5 2.5 0 0 1 4.98 3.5zM3 9h4v12H3zM9 9h3.8v1.7h.1c.5-.9 1.6-1.7 3.3-1.7 3.5 0 4.2 2.3 4.2 5.3V21H16v-5.1c0-1.2 0-2.7-1.6-2.7-1.6 0-1.9 1.3-1.9 2.6V21H9z"/></svg>
                                    </a>
                                    <a href="mailto:example@example.com" aria-label="Email" class="text-gray-500 hover:text-green-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20 4H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2zm0 4-8 5L4 8V6l8 5 8-5v2z"/></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            {{-- Following rows: all other members --}}
            <div class="flex flex-wrap mx-[-15px] grid-view md:mx-[-20px] lg:mx-[-20px] xl:mx-[-25px] !mt-[-40px] xl:!mt-0 lg:!mt-0">
                <!-- Example member card (duplicate for each member) -->
                <div class="md:w-6/12 lg:w-4/12 xl:w-3/12 w-full flex-[0_0_auto] !px-[15px] max-w-full md:!mt-[40px]">
                    <div class="!relative">
                        <div class="shape !rounded-[.4rem] !bg-[#edf2fc] rellax xl:block lg:block md:block absolute" style="bottom: -0.75rem; right: -0.75rem; width: 98%; height: 98%; z-index:0"></div>
                        <div class="card">
                            <figure class="card-img-top">
                                <img class="max-w-full h-auto" src="{{ asset('assets/fe/assets/img/avatars/t1.jpg') }}" alt="{{ asset('assets/fe/assets/img/avatars/t1@2x.jpg') }} 2x" loading="lazy">
                            </figure>
                            <div class="card-body px-6 py-5">
                                <h4 class="!mb-1">A</h4>
                                <p class="!mb-0 text-[.85rem]">Anggota</p>

                                <!-- Social icons -->
                                <div class="mt-3 flex items-center space-x-3">
                                    <a href="#" aria-label="Instagram" class="text-gray-500 hover:text-pink-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5zm0 2a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3H7zm5 3.5a4.5 4.5 0 1 1 0 9 4.5 4.5 0 0 1 0-9zm0 2a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM18.5 6a.9.9 0 1 1 0 1.8.9.9 0 0 1 0-1.8z"/></svg>
                                    </a>
                                    <a href="#" aria-label="Facebook" class="text-gray-500 hover:text-blue-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12a10 10 0 1 0-11.5 9.9v-7h-2.2V12h2.2V9.6c0-2.2 1.3-3.4 3.2-3.4.9 0 1.8.1 1.8.1v2h-1c-1 0-1.3.6-1.3 1.2V12h2.3l-.4 2.9h-1.9v7A10 10 0 0 0 22 12z"/></svg>
                                    </a>
                                    <a href="#" aria-label="LinkedIn" class="text-gray-500 hover:text-blue-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M4.98 3.5A2.5 2.5 0 1 1 4.96 8H4.98A2.5 2.5 0 0 1 4.98 3.5zM3 9h4v12H3zM9 9h3.8v1.7h.1c.5-.9 1.6-1.7 3.3-1.7 3.5 0 4.2 2.3 4.2 5.3V21H16v-5.1c0-1.2 0-2.7-1.6-2.7-1.6 0-1.9 1.3-1.9 2.6V21H9z"/></svg>
                                    </a>
                                    <a href="mailto:example@example.com" aria-label="Email" class="text-gray-500 hover:text-green-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20 4H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2zm0 4-8 5L4 8V6l8 5 8-5v2z"/></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="md:w-6/12 lg:w-4/12 xl:w-3/12 w-full flex-[0_0_auto] !px-[15px] max-w-full md:!mt-[40px]">
                    <div class="!relative">
                        <div class="shape !rounded-[.4rem] !bg-[#edf2fc] rellax xl:block lg:block md:block absolute" style="bottom: -0.75rem; right: -0.75rem; width: 98%; height: 98%; z-index:0"></div>
                        <div class="card">
                            <figure class="card-img-top">
                                <img class="max-w-full h-auto" src="{{ asset('assets/fe/assets/img/avatars/t1.jpg') }}" alt="{{ asset('assets/fe/assets/img/avatars/t1@2x.jpg') }} 2x" loading="lazy">
                            </figure>
                            <div class="card-body px-6 py-5">
                                <h4 class="!mb-1">A</h4>
                                <p class="!mb-0 text-[.85rem]">Anggota</p>

                                <!-- Social icons -->
                                <div class="mt-3 flex items-center space-x-3">
                                    <a href="#" aria-label="Instagram" class="text-gray-500 hover:text-pink-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5zm0 2a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3H7zm5 3.5a4.5 4.5 0 1 1 0 9 4.5 4.5 0 0 1 0-9zm0 2a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM18.5 6a.9.9 0 1 1 0 1.8.9.9 0 0 1 0-1.8z"/></svg>
                                    </a>
                                    <a href="#" aria-label="Facebook" class="text-gray-500 hover:text-blue-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12a10 10 0 1 0-11.5 9.9v-7h-2.2V12h2.2V9.6c0-2.2 1.3-3.4 3.2-3.4.9 0 1.8.1 1.8.1v2h-1c-1 0-1.3.6-1.3 1.2V12h2.3l-.4 2.9h-1.9v7A10 10 0 0 0 22 12z"/></svg>
                                    </a>
                                    <a href="#" aria-label="LinkedIn" class="text-gray-500 hover:text-blue-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M4.98 3.5A2.5 2.5 0 1 1 4.96 8H4.98A2.5 2.5 0 0 1 4.98 3.5zM3 9h4v12H3zM9 9h3.8v1.7h.1c.5-.9 1.6-1.7 3.3-1.7 3.5 0 4.2 2.3 4.2 5.3V21H16v-5.1c0-1.2 0-2.7-1.6-2.7-1.6 0-1.9 1.3-1.9 2.6V21H9z"/></svg>
                                    </a>
                                    <a href="mailto:example@example.com" aria-label="Email" class="text-gray-500 hover:text-green-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20 4H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2zm0 4-8 5L4 8V6l8 5 8-5v2z"/></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="md:w-6/12 lg:w-4/12 xl:w-3/12 w-full flex-[0_0_auto] !px-[15px] max-w-full md:!mt-[40px]">
                    <div class="!relative">
                        <div class="shape !rounded-[.4rem] !bg-[#edf2fc] rellax xl:block lg:block md:block absolute" style="bottom: -0.75rem; right: -0.75rem; width: 98%; height: 98%; z-index:0"></div>
                        <div class="card">
                            <figure class="card-img-top">
                                <img class="max-w-full h-auto" src="{{ asset('assets/fe/assets/img/avatars/t1.jpg') }}" alt="{{ asset('assets/fe/assets/img/avatars/t1@2x.jpg') }} 2x" loading="lazy">
                            </figure>
                            <div class="card-body px-6 py-5">
                                <h4 class="!mb-1">A</h4>
                                <p class="!mb-0 text-[.85rem]">Anggota</p>

                                <!-- Social icons -->
                                <div class="mt-3 flex items-center space-x-3">
                                    <a href="#" aria-label="Instagram" class="text-gray-500 hover:text-pink-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5zm0 2a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3H7zm5 3.5a4.5 4.5 0 1 1 0 9 4.5 4.5 0 0 1 0-9zm0 2a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM18.5 6a.9.9 0 1 1 0 1.8.9.9 0 0 1 0-1.8z"/></svg>
                                    </a>
                                    <a href="#" aria-label="Facebook" class="text-gray-500 hover:text-blue-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12a10 10 0 1 0-11.5 9.9v-7h-2.2V12h2.2V9.6c0-2.2 1.3-3.4 3.2-3.4.9 0 1.8.1 1.8.1v2h-1c-1 0-1.3.6-1.3 1.2V12h2.3l-.4 2.9h-1.9v7A10 10 0 0 0 22 12z"/></svg>
                                    </a>
                                    <a href="#" aria-label="LinkedIn" class="text-gray-500 hover:text-blue-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M4.98 3.5A2.5 2.5 0 1 1 4.96 8H4.98A2.5 2.5 0 0 1 4.98 3.5zM3 9h4v12H3zM9 9h3.8v1.7h.1c.5-.9 1.6-1.7 3.3-1.7 3.5 0 4.2 2.3 4.2 5.3V21H16v-5.1c0-1.2 0-2.7-1.6-2.7-1.6 0-1.9 1.3-1.9 2.6V21H9z"/></svg>
                                    </a>
                                    <a href="mailto:example@example.com" aria-label="Email" class="text-gray-500 hover:text-green-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20 4H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2zm0 4-8 5L4 8V6l8 5 8-5v2z"/></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="md:w-6/12 lg:w-4/12 xl:w-3/12 w-full flex-[0_0_auto] !px-[15px] max-w-full md:!mt-[40px]">
                    <div class="!relative">
                        <div class="shape !rounded-[.4rem] !bg-[#edf2fc] rellax xl:block lg:block md:block absolute" style="bottom: -0.75rem; right: -0.75rem; width: 98%; height: 98%; z-index:0"></div>
                        <div class="card">
                            <figure class="card-img-top">
                                <img class="max-w-full h-auto" src="{{ asset('assets/fe/assets/img/avatars/t1.jpg') }}" alt="{{ asset('assets/fe/assets/img/avatars/t1@2x.jpg') }} 2x" loading="lazy">
                            </figure>
                            <div class="card-body px-6 py-5">
                                <h4 class="!mb-1">A</h4>
                                <p class="!mb-0 text-[.85rem]">Anggota</p>

                                <!-- Social icons -->
                                <div class="mt-3 flex items-center space-x-3">
                                    <a href="#" aria-label="Instagram" class="text-gray-500 hover:text-pink-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5zm0 2a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3H7zm5 3.5a4.5 4.5 0 1 1 0 9 4.5 4.5 0 0 1 0-9zm0 2a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM18.5 6a.9.9 0 1 1 0 1.8.9.9 0 0 1 0-1.8z"/></svg>
                                    </a>
                                    <a href="#" aria-label="Facebook" class="text-gray-500 hover:text-blue-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12a10 10 0 1 0-11.5 9.9v-7h-2.2V12h2.2V9.6c0-2.2 1.3-3.4 3.2-3.4.9 0 1.8.1 1.8.1v2h-1c-1 0-1.3.6-1.3 1.2V12h2.3l-.4 2.9h-1.9v7A10 10 0 0 0 22 12z"/></svg>
                                    </a>
                                    <a href="#" aria-label="LinkedIn" class="text-gray-500 hover:text-blue-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M4.98 3.5A2.5 2.5 0 1 1 4.96 8H4.98A2.5 2.5 0 0 1 4.98 3.5zM3 9h4v12H3zM9 9h3.8v1.7h.1c.5-.9 1.6-1.7 3.3-1.7 3.5 0 4.2 2.3 4.2 5.3V21H16v-5.1c0-1.2 0-2.7-1.6-2.7-1.6 0-1.9 1.3-1.9 2.6V21H9z"/></svg>
                                    </a>
                                    <a href="mailto:example@example.com" aria-label="Email" class="text-gray-500 hover:text-green-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20 4H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2zm0 4-8 5L4 8V6l8 5 8-5v2z"/></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Duplicate the member card above for each member/sekretariat as needed -->
            </div>
            <hr>

            {{-- Following rows: all other Sekretariat --}}
            <div class="flex flex-wrap mx-[-15px] grid-view md:mx-[-20px] lg:mx-[-20px] xl:mx-[-25px] !mt-[-40px] xl:!mt-0 lg:!mt-0">
                <div class="md:w-6/12 lg:w-4/12 xl:w-3/12 w-full flex-[0_0_auto] !px-[15px] max-w-full md:!mt-[40px]">
                    <div class="!relative">
                        <div class="shape !rounded-[.4rem] !bg-[#edf2fc] rellax xl:block lg:block md:block absolute" style="bottom: -0.75rem; right: -0.75rem; width: 98%; height: 98%; z-index:0"></div>
                        <div class="card">
                            <figure class="card-img-top">
                                <img class="max-w-full h-auto" src="{{ asset('assets/fe/assets/img/avatars/t1.jpg') }}" alt="{{ asset('assets/fe/assets/img/avatars/t1@2x.jpg') }} 2x" loading="lazy">
                            </figure>
                            <div class="card-body px-6 py-5">
                                <h4 class="!mb-1">A</h4>
                                <p class="!mb-0 text-[.85rem]">Sekretariat</p>

                                <!-- Social icons -->
                                <div class="mt-3 flex items-center space-x-3">
                                    <a href="#" aria-label="Instagram" class="text-gray-500 hover:text-pink-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5zm0 2a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3H7zm5 3.5a4.5 4.5 0 1 1 0 9 4.5 4.5 0 0 1 0-9zm0 2a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM18.5 6a.9.9 0 1 1 0 1.8.9.9 0 0 1 0-1.8z"/></svg>
                                    </a>
                                    <a href="#" aria-label="Facebook" class="text-gray-500 hover:text-blue-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12a10 10 0 1 0-11.5 9.9v-7h-2.2V12h2.2V9.6c0-2.2 1.3-3.4 3.2-3.4.9 0 1.8.1 1.8.1v2h-1c-1 0-1.3.6-1.3 1.2V12h2.3l-.4 2.9h-1.9v7A10 10 0 0 0 22 12z"/></svg>
                                    </a>
                                    <a href="#" aria-label="LinkedIn" class="text-gray-500 hover:text-blue-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M4.98 3.5A2.5 2.5 0 1 1 4.96 8H4.98A2.5 2.5 0 0 1 4.98 3.5zM3 9h4v12H3zM9 9h3.8v1.7h.1c.5-.9 1.6-1.7 3.3-1.7 3.5 0 4.2 2.3 4.2 5.3V21H16v-5.1c0-1.2 0-2.7-1.6-2.7-1.6 0-1.9 1.3-1.9 2.6V21H9z"/></svg>
                                    </a>
                                    <a href="mailto:example@example.com" aria-label="Email" class="text-gray-500 hover:text-green-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20 4H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2zm0 4-8 5L4 8V6l8 5 8-5v2z"/></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="md:w-6/12 lg:w-4/12 xl:w-3/12 w-full flex-[0_0_auto] !px-[15px] max-w-full md:!mt-[40px]">
                    <div class="!relative">
                        <div class="shape !rounded-[.4rem] !bg-[#edf2fc] rellax xl:block lg:block md:block absolute" style="bottom: -0.75rem; right: -0.75rem; width: 98%; height: 98%; z-index:0"></div>
                        <div class="card">
                            <figure class="card-img-top">
                                <img class="max-w-full h-auto" src="{{ asset('assets/fe/assets/img/avatars/t1.jpg') }}" alt="{{ asset('assets/fe/assets/img/avatars/t1@2x.jpg') }} 2x" loading="lazy">
                            </figure>
                            <div class="card-body px-6 py-5">
                                <h4 class="!mb-1">A</h4>
                                <p class="!mb-0 text-[.85rem]">Sekretariat</p>

                                <!-- Social icons -->
                                <div class="mt-3 flex items-center space-x-3">
                                    <a href="#" aria-label="Instagram" class="text-gray-500 hover:text-pink-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5zm0 2a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3H7zm5 3.5a4.5 4.5 0 1 1 0 9 4.5 4.5 0 0 1 0-9zm0 2a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM18.5 6a.9.9 0 1 1 0 1.8.9.9 0 0 1 0-1.8z"/></svg>
                                    </a>
                                    <a href="#" aria-label="Facebook" class="text-gray-500 hover:text-blue-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12a10 10 0 1 0-11.5 9.9v-7h-2.2V12h2.2V9.6c0-2.2 1.3-3.4 3.2-3.4.9 0 1.8.1 1.8.1v2h-1c-1 0-1.3.6-1.3 1.2V12h2.3l-.4 2.9h-1.9v7A10 10 0 0 0 22 12z"/></svg>
                                    </a>
                                    <a href="#" aria-label="LinkedIn" class="text-gray-500 hover:text-blue-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M4.98 3.5A2.5 2.5 0 1 1 4.96 8H4.98A2.5 2.5 0 0 1 4.98 3.5zM3 9h4v12H3zM9 9h3.8v1.7h.1c.5-.9 1.6-1.7 3.3-1.7 3.5 0 4.2 2.3 4.2 5.3V21H16v-5.1c0-1.2 0-2.7-1.6-2.7-1.6 0-1.9 1.3-1.9 2.6V21H9z"/></svg>
                                    </a>
                                    <a href="mailto:example@example.com" aria-label="Email" class="text-gray-500 hover:text-green-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20 4H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2zm0 4-8 5L4 8V6l8 5 8-5v2z"/></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="md:w-6/12 lg:w-4/12 xl:w-3/12 w-full flex-[0_0_auto] !px-[15px] max-w-full md:!mt-[40px]">
                    <div class="!relative">
                        <div class="shape !rounded-[.4rem] !bg-[#edf2fc] rellax xl:block lg:block md:block absolute" style="bottom: -0.75rem; right: -0.75rem; width: 98%; height: 98%; z-index:0"></div>
                        <div class="card">
                            <figure class="card-img-top">
                                <img class="max-w-full h-auto" src="{{ asset('assets/fe/assets/img/avatars/t1.jpg') }}" alt="{{ asset('assets/fe/assets/img/avatars/t1@2x.jpg') }} 2x" loading="lazy">
                            </figure>
                            <div class="card-body px-6 py-5">
                                <h4 class="!mb-1">A</h4>
                                <p class="!mb-0 text-[.85rem]">Sekretariat</p>

                                <!-- Social icons -->
                                <div class="mt-3 flex items-center space-x-3">
                                    <a href="#" aria-label="Instagram" class="text-gray-500 hover:text-pink-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5zm0 2a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3H7zm5 3.5a4.5 4.5 0 1 1 0 9 4.5 4.5 0 0 1 0-9zm0 2a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM18.5 6a.9.9 0 1 1 0 1.8.9.9 0 0 1 0-1.8z"/></svg>
                                    </a>
                                    <a href="#" aria-label="Facebook" class="text-gray-500 hover:text-blue-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12a10 10 0 1 0-11.5 9.9v-7h-2.2V12h2.2V9.6c0-2.2 1.3-3.4 3.2-3.4.9 0 1.8.1 1.8.1v2h-1c-1 0-1.3.6-1.3 1.2V12h2.3l-.4 2.9h-1.9v7A10 10 0 0 0 22 12z"/></svg>
                                    </a>
                                    <a href="#" aria-label="LinkedIn" class="text-gray-500 hover:text-blue-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M4.98 3.5A2.5 2.5 0 1 1 4.96 8H4.98A2.5 2.5 0 0 1 4.98 3.5zM3 9h4v12H3zM9 9h3.8v1.7h.1c.5-.9 1.6-1.7 3.3-1.7 3.5 0 4.2 2.3 4.2 5.3V21H16v-5.1c0-1.2 0-2.7-1.6-2.7-1.6 0-1.9 1.3-1.9 2.6V21H9z"/></svg>
                                    </a>
                                    <a href="mailto:example@example.com" aria-label="Email" class="text-gray-500 hover:text-green-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20 4H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2zm0 4-8 5L4 8V6l8 5 8-5v2z"/></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="md:w-6/12 lg:w-4/12 xl:w-3/12 w-full flex-[0_0_auto] !px-[15px] max-w-full md:!mt-[40px]">
                    <div class="!relative">
                        <div class="shape !rounded-[.4rem] !bg-[#edf2fc] rellax xl:block lg:block md:block absolute" style="bottom: -0.75rem; right: -0.75rem; width: 98%; height: 98%; z-index:0"></div>
                        <div class="card">
                            <figure class="card-img-top">
                                <img class="max-w-full h-auto" src="{{ asset('assets/fe/assets/img/avatars/t1.jpg') }}" alt="{{ asset('assets/fe/assets/img/avatars/t1@2x.jpg') }} 2x" loading="lazy">
                            </figure>
                            <div class="card-body px-6 py-5">
                                <h4 class="!mb-1">A</h4>
                                <p class="!mb-0 text-[.85rem]">Sekretariat</p>

                                <!-- Social icons -->
                                <div class="mt-3 flex items-center space-x-3">
                                    <a href="#" aria-label="Instagram" class="text-gray-500 hover:text-pink-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5zm0 2a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3H7zm5 3.5a4.5 4.5 0 1 1 0 9 4.5 4.5 0 0 1 0-9zm0 2a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM18.5 6a.9.9 0 1 1 0 1.8.9.9 0 0 1 0-1.8z"/></svg>
                                    </a>
                                    <a href="#" aria-label="Facebook" class="text-gray-500 hover:text-blue-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12a10 10 0 1 0-11.5 9.9v-7h-2.2V12h2.2V9.6c0-2.2 1.3-3.4 3.2-3.4.9 0 1.8.1 1.8.1v2h-1c-1 0-1.3.6-1.3 1.2V12h2.3l-.4 2.9h-1.9v7A10 10 0 0 0 22 12z"/></svg>
                                    </a>
                                    <a href="#" aria-label="LinkedIn" class="text-gray-500 hover:text-blue-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M4.98 3.5A2.5 2.5 0 1 1 4.96 8H4.98A2.5 2.5 0 0 1 4.98 3.5zM3 9h4v12H3zM9 9h3.8v1.7h.1c.5-.9 1.6-1.7 3.3-1.7 3.5 0 4.2 2.3 4.2 5.3V21H16v-5.1c0-1.2 0-2.7-1.6-2.7-1.6 0-1.9 1.3-1.9 2.6V21H9z"/></svg>
                                    </a>
                                    <a href="mailto:example@example.com" aria-label="Email" class="text-gray-500 hover:text-green-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20 4H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2zm0 4-8 5L4 8V6l8 5 8-5v2z"/></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="md:w-6/12 lg:w-4/12 xl:w-3/12 w-full flex-[0_0_auto] !px-[15px] max-w-full md:!mt-[40px]">
                    <div class="!relative">
                        <div class="shape !rounded-[.4rem] !bg-[#edf2fc] rellax xl:block lg:block md:block absolute" style="bottom: -0.75rem; right: -0.75rem; width: 98%; height: 98%; z-index:0"></div>
                        <div class="card">
                            <figure class="card-img-top">
                                <img class="max-w-full h-auto" src="{{ asset('assets/fe/assets/img/avatars/t1.jpg') }}" alt="{{ asset('assets/fe/assets/img/avatars/t1@2x.jpg') }} 2x" loading="lazy">
                            </figure>
                            <div class="card-body px-6 py-5">
                                <h4 class="!mb-1">A</h4>
                                <p class="!mb-0 text-[.85rem]">Sekretariat</p>

                                <!-- Social icons -->
                                <div class="mt-3 flex items-center space-x-3">
                                    <a href="#" aria-label="Instagram" class="text-gray-500 hover:text-pink-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5zm0 2a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3H7zm5 3.5a4.5 4.5 0 1 1 0 9 4.5 4.5 0 0 1 0-9zm0 2a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM18.5 6a.9.9 0 1 1 0 1.8.9.9 0 0 1 0-1.8z"/></svg>
                                    </a>
                                    <a href="#" aria-label="Facebook" class="text-gray-500 hover:text-blue-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12a10 10 0 1 0-11.5 9.9v-7h-2.2V12h2.2V9.6c0-2.2 1.3-3.4 3.2-3.4.9 0 1.8.1 1.8.1v2h-1c-1 0-1.3.6-1.3 1.2V12h2.3l-.4 2.9h-1.9v7A10 10 0 0 0 22 12z"/></svg>
                                    </a>
                                    <a href="#" aria-label="LinkedIn" class="text-gray-500 hover:text-blue-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M4.98 3.5A2.5 2.5 0 1 1 4.96 8H4.98A2.5 2.5 0 0 1 4.98 3.5zM3 9h4v12H3zM9 9h3.8v1.7h.1c.5-.9 1.6-1.7 3.3-1.7 3.5 0 4.2 2.3 4.2 5.3V21H16v-5.1c0-1.2 0-2.7-1.6-2.7-1.6 0-1.9 1.3-1.9 2.6V21H9z"/></svg>
                                    </a>
                                    <a href="mailto:example@example.com" aria-label="Email" class="text-gray-500 hover:text-green-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20 4H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2zm0 4-8 5L4 8V6l8 5 8-5v2z"/></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="md:w-6/12 lg:w-4/12 xl:w-3/12 w-full flex-[0_0_auto] !px-[15px] max-w-full md:!mt-[40px]">
                    <div class="!relative">
                        <div class="shape !rounded-[.4rem] !bg-[#edf2fc] rellax xl:block lg:block md:block absolute" style="bottom: -0.75rem; right: -0.75rem; width: 98%; height: 98%; z-index:0"></div>
                        <div class="card">
                            <figure class="card-img-top">
                                <img class="max-w-full h-auto" src="{{ asset('assets/fe/assets/img/avatars/t1.jpg') }}" alt="{{ asset('assets/fe/assets/img/avatars/t1@2x.jpg') }} 2x" loading="lazy">
                            </figure>
                            <div class="card-body px-6 py-5">
                                <h4 class="!mb-1">A</h4>
                                <p class="!mb-0 text-[.85rem]">Sekretariat</p>

                                <!-- Social icons -->
                                <div class="mt-3 flex items-center space-x-3">
                                    <a href="#" aria-label="Instagram" class="text-gray-500 hover:text-pink-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5zm0 2a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3H7zm5 3.5a4.5 4.5 0 1 1 0 9 4.5 4.5 0 0 1 0-9zm0 2a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM18.5 6a.9.9 0 1 1 0 1.8.9.9 0 0 1 0-1.8z"/></svg>
                                    </a>
                                    <a href="#" aria-label="Facebook" class="text-gray-500 hover:text-blue-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12a10 10 0 1 0-11.5 9.9v-7h-2.2V12h2.2V9.6c0-2.2 1.3-3.4 3.2-3.4.9 0 1.8.1 1.8.1v2h-1c-1 0-1.3.6-1.3 1.2V12h2.3l-.4 2.9h-1.9v7A10 10 0 0 0 22 12z"/></svg>
                                    </a>
                                    <a href="#" aria-label="LinkedIn" class="text-gray-500 hover:text-blue-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M4.98 3.5A2.5 2.5 0 1 1 4.96 8H4.98A2.5 2.5 0 0 1 4.98 3.5zM3 9h4v12H3zM9 9h3.8v1.7h.1c.5-.9 1.6-1.7 3.3-1.7 3.5 0 4.2 2.3 4.2 5.3V21H16v-5.1c0-1.2 0-2.7-1.6-2.7-1.6 0-1.9 1.3-1.9 2.6V21H9z"/></svg>
                                    </a>
                                    <a href="mailto:example@example.com" aria-label="Email" class="text-gray-500 hover:text-green-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20 4H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2zm0 4-8 5L4 8V6l8 5 8-5v2z"/></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="md:w-6/12 lg:w-4/12 xl:w-3/12 w-full flex-[0_0_auto] !px-[15px] max-w-full md:!mt-[40px]">
                    <div class="!relative">
                        <div class="shape !rounded-[.4rem] !bg-[#edf2fc] rellax xl:block lg:block md:block absolute" style="bottom: -0.75rem; right: -0.75rem; width: 98%; height: 98%; z-index:0"></div>
                        <div class="card">
                            <figure class="card-img-top">
                                <img class="max-w-full h-auto" src="{{ asset('assets/fe/assets/img/avatars/t1.jpg') }}" alt="{{ asset('assets/fe/assets/img/avatars/t1@2x.jpg') }} 2x" loading="lazy">
                            </figure>
                            <div class="card-body px-6 py-5">
                                <h4 class="!mb-1">A</h4>
                                <p class="!mb-0 text-[.85rem]">Sekretariat</p>

                                <!-- Social icons -->
                                <div class="mt-3 flex items-center space-x-3">
                                    <a href="#" aria-label="Instagram" class="text-gray-500 hover:text-pink-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5zm0 2a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3H7zm5 3.5a4.5 4.5 0 1 1 0 9 4.5 4.5 0 0 1 0-9zm0 2a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM18.5 6a.9.9 0 1 1 0 1.8.9.9 0 0 1 0-1.8z"/></svg>
                                    </a>
                                    <a href="#" aria-label="Facebook" class="text-gray-500 hover:text-blue-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12a10 10 0 1 0-11.5 9.9v-7h-2.2V12h2.2V9.6c0-2.2 1.3-3.4 3.2-3.4.9 0 1.8.1 1.8.1v2h-1c-1 0-1.3.6-1.3 1.2V12h2.3l-.4 2.9h-1.9v7A10 10 0 0 0 22 12z"/></svg>
                                    </a>
                                    <a href="#" aria-label="LinkedIn" class="text-gray-500 hover:text-blue-700">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M4.98 3.5A2.5 2.5 0 1 1 4.96 8H4.98A2.5 2.5 0 0 1 4.98 3.5zM3 9h4v12H3zM9 9h3.8v1.7h.1c.5-.9 1.6-1.7 3.3-1.7 3.5 0 4.2 2.3 4.2 5.3V21H16v-5.1c0-1.2 0-2.7-1.6-2.7-1.6 0-1.9 1.3-1.9 2.6V21H9z"/></svg>
                                    </a>
                                    <a href="mailto:example@example.com" aria-label="Email" class="text-gray-500 hover:text-green-600">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20 4H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2zm0 4-8 5L4 8V6l8 5 8-5v2z"/></svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </section>
@endsection
