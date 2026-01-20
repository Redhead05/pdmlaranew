@extends('frontend.layout.app')

@section('content')

    <section class="wrapper !bg-[#ffffff]">
        <div class="container pt-20 xl:pt-28 lg:pt-28 md:pt-28 pb-16 xl:pb-20 lg:pb-20 md:pb-20">
            <div class="flex flex-wrap mx-[-15px] !mb-3">
                <div class="md:w-10/12 lg:w-full xl:w-10/12 xxl:w-9/12 w-full flex-[0_0_auto] !px-[15px] max-w-full !mx-auto !text-center">
                    <h2 class="!text-[.75rem] uppercase !text-[#aab0bc] !mb-3 !tracking-[0.02rem] !leading-[1.35]">Struktur Organisasi</h2>
                    <h3 class="!text-[calc(1.305rem_+_0.66vw)] font-bold xl:!text-[1.8rem] !leading-[1.3] !mb-7 lg:!px-36 xl:!px-32">BAN PDM - JAWA TIMUR</h3>

                    @php
                        // pastikan variabel terdefinisi jika view dipanggil manual
                        $periods = $periods ?? collect();
                        $start = $start ?? null;
                        $end = $end ?? null;
                    @endphp

                    <div class="form-select-wrapper !mb-4">
                        <select id="period-select" class="form-select text-center" aria-label="Pilih periode">
                            <option value="">-- Pilih Periode --</option>
                            @foreach($periods as $p)
                                <option value="{{ $p['start'] }}|{{ $p['end'] }}" @if((int)$start === $p['start'] && (int)$end === $p['end']) selected @endif>
                                    {{ $p['label'] }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            var sel = document.getElementById('period-select');
                            if (!sel) return;
                            sel.addEventListener('change', function () {
                                var v = this.value;
                                var url = new URL(window.location.href);
                                if (!v) {
                                    url.searchParams.delete('start');
                                    url.searchParams.delete('end');
                                } else {
                                    var parts = v.split('|');
                                    url.searchParams.set('start', parts[0]);
                                    url.searchParams.set('end', parts[1]);
                                }
                                window.location.href = url.toString();
                            });
                        });
                    </script>

                </div>
            </div>

            {{-- First row: only Ketua and Sekretaris (dinamis) --}}
            <div class="flex flex-wrap mx-[-15px] !mb-6 justify-center">
                {{-- Ketua --}}
                <div class="md:w-6/12 lg:w-4/12 xl:w-3/12 w-full flex-[0_0_auto] !px-[15px] max-w-full md:!mt-[40px]">
                    <div class="!relative">
                        <div class="shape !rounded-[.4rem] !bg-[#edf2fc] rellax xl:block lg:block md:block absolute" style="bottom: -0.75rem; right: -0.75rem; width: 98%; height: 98%; z-index:0"></div>
                        <div class="card">
                            <figure class="card-img-top">
                                <img class="max-w-full h-auto"
                                     src="{{ isset($ketua) && $ketua->photo ? asset($ketua->photo) : asset('assets/fe/assets/img/avatars/t1.jpg') }}"
                                     alt="{{ $ketua->name ?? 'Ketua' }}" loading="lazy">
                            </figure>
                            <div class="card-body px-6 py-5">
                                <h4 class="!mb-1">{{ $ketua->name ?? 'Belum ditentukan' }}</h4>
                                <p class="!mb-0 text-[.85rem]">{{ $ketua->position ?? 'Ketua' }}</p>

                                <!-- Social icons -->
                                <div class="mt-3 flex items-center space-x-3">
                                    @if(isset($ketua) && $ketua->instagram)
                                        <a href="{{ $ketua->instagram }}" aria-label="Instagram" class="text-gray-500 hover:text-pink-600" target="_blank" rel="noopener noreferrer">
                                            <!-- svg instagram -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5zm0 2a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3H7zm5 3.5a4.5 4.5 0 1 1 0 9 4.5 4.5 0 0 1 0-9zm0 2a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM18.5 6a.9.9 0 1 1 0 1.8.9.9 0 0 1 0-1.8z"/></svg>
                                        </a>
                                    @endif
                                    @if(isset($ketua) && $ketua->facebook)
                                        <a href="{{ $ketua->facebook }}" aria-label="Facebook" class="text-gray-500 hover:text-blue-600" target="_blank" rel="noopener noreferrer">
                                            <!-- svg facebook -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12a10 10 0 1 0-11.5 9.9v-7h-2.2V12h2.2V9.6c0-2.2 1.3-3.4 3.2-3.4.9 0 1.8.1 1.8.1v2h-1c-1 0-1.3.6-1.3 1.2V12h2.3l-.4 2.9h-1.9v7A10 10 0 0 0 22 12z"/></svg>
                                        </a>
                                    @endif
                                    @if(isset($ketua) && $ketua->email)
                                        <a href="mailto:{{ $ketua->email }}" aria-label="Email" class="text-gray-500 hover:text-green-600">
                                            <!-- svg email -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20 4H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2zm0 4-8 5L4 8V6l8 5 8-5v2z"/></svg>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Sekretaris --}}
                <div class="md:w-6/12 lg:w-4/12 xl:w-3/12 w-full flex-[0_0_auto] !px-[15px] max-w-full md:!mt-[40px]">
                    <div class="!relative">
                        <div class="shape !rounded-[.4rem] !bg-[#edf2fc] rellax xl:block lg:block md:block absolute" style="bottom: -0.75rem; right: -0.75rem; width: 98%; height: 98%; z-index:0"></div>
                        <div class="card">
                            <figure class="card-img-top">
                                <img class="max-w-full h-auto"
                                     src="{{ isset($sekretaris) && $sekretaris->photo ? asset($sekretaris->photo) : asset('assets/fe/assets/img/avatars/t1.jpg') }}"
                                     alt="{{ $sekretaris->name ?? 'Sekretaris' }}" loading="lazy">
                            </figure>
                            <div class="card-body px-6 py-5">
                                <h4 class="!mb-1">{{ $sekretaris->name ?? 'Belum ditentukan' }}</h4>
                                <p class="!mb-0 text-[.85rem]">{{ $sekretaris->position ?? 'Sekretaris' }}</p>

                                <!-- Social icons -->
                                <div class="mt-3 flex items-center space-x-3">
                                    @if(isset($sekretaris) && $sekretaris->instagram)
                                        <a href="{{ $sekretaris->instagram }}" aria-label="Instagram" class="text-gray-500 hover:text-pink-600" target="_blank" rel="noopener noreferrer">
                                            <!-- svg instagram -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5zm0 2a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3H7zm5 3.5a4.5 4.5 0 1 1 0 9 4.5 4.5 0 0 1 0-9zm0 2a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM18.5 6a.9.9 0 1 1 0 1.8.9.9 0 0 1 0-1.8z"/></svg>
                                        </a>
                                    @endif
                                    @if(isset($sekretaris) && $sekretaris->facebook)
                                        <a href="{{ $sekretaris->facebook }}" aria-label="Facebook" class="text-gray-500 hover:text-blue-600" target="_blank" rel="noopener noreferrer">
                                            <!-- svg facebook -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12a10 10 0 1 0-11.5 9.9v-7h-2.2V12h2.2V9.6c0-2.2 1.3-3.4 3.2-3.4.9 0 1.8.1 1.8.1v2h-1c-1 0-1.3.6-1.3 1.2V12h2.3l-.4 2.9h-1.9v7A10 10 0 0 0 22 12z"/></svg>
                                        </a>
                                    @endif
                                    @if(isset($sekretaris) && $sekretaris->email)
                                        <a href="mailto:{{ $sekretaris->email }}" aria-label="Email" class="text-gray-500 hover:text-green-600">
                                            <!-- svg email -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20 4H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2zm0 4-8 5L4 8V6l8 5 8-5v2z"/></svg>
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <hr>

            {{-- Following rows: all other members (Anggota) --}}
            <div class="flex flex-wrap mx-[-15px] grid-view md:mx-[-20px] lg:mx-[-20px] xl:mx-[-25px] !mt-[-40px] xl:!mt-0 lg:!mt-0">
                @forelse($anggota as $member)
                    <div class="md:w-6/12 lg:w-4/12 xl:w-3/12 w-full flex-[0_0_auto] !px-[15px] max-w-full md:!mt-[40px]">
                        <div class="!relative">
                            <div class="shape !rounded-[.4rem] !bg-[#edf2fc] rellax xl:block lg:block md:block absolute" style="bottom: -0.75rem; right: -0.75rem; width: 98%; height: 98%; z-index:0"></div>
                            <div class="card">
                                <figure class="card-img-top">
                                    <img class="max-w-full h-auto" src="{{ $member->photo ? asset($member->photo) : asset('assets/fe/assets/img/avatars/t1.jpg') }}" alt="{{ $member->name }}" loading="lazy">
                                </figure>
                                <div class="card-body px-6 py-5">
                                    <h4 class="!mb-1">{{ $member->name }}</h4>
                                    <p class="!mb-0 text-[.85rem]">{{ $member->position }}</p>

                                    <!-- Social icons -->
                                    <div class="mt-3 flex items-center space-x-3">
                                        @if($member->instagram)
                                            <a href="{{ $member->instagram }}" aria-label="Instagram" class="text-gray-500 hover:text-pink-600" target="_blank" rel="noopener noreferrer">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5zm0 2a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3H7zm5 3.5a4.5 4.5 0 1 1 0 9 4.5 4.5 0 0 1 0-9zm0 2a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM18.5 6a.9.9 0 1 1 0 1.8.9.9 0 0 1 0-1.8z"/></svg>
                                            </a>
                                        @endif
                                        @if($member->facebook)
                                            <a href="{{ $member->facebook }}" aria-label="Facebook" class="text-gray-500 hover:text-blue-600" target="_blank" rel="noopener noreferrer">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12a10 10 0 1 0-11.5 9.9v-7h-2.2V12h2.2V9.6c0-2.2 1.3-3.4 3.2-3.4.9 0 1.8.1 1.8.1v2h-1c-1 0-1.3.6-1.3 1.2V12h2.3l-.4 2.9h-1.9v7A10 10 0 0 0 22 12z"/></svg>
                                            </a>
                                        @endif
                                        @if($member->email)
                                            <a href="mailto:{{ $member->email }}" aria-label="Email" class="text-gray-500 hover:text-green-600">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20 4H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2zm0 4-8 5L4 8V6l8 5 8-5v2z"/></svg>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="px-6 py-4">Belum ada anggota untuk periode ini.</p>
                @endforelse
            </div>

            <hr>

            {{-- Following rows: all other Sekretariat --}}
            <div class="flex flex-wrap mx-[-15px] grid-view md:mx-[-20px] lg:mx-[-20px] xl:mx-[-25px] !mt-[-40px] xl:!mt-0 lg:!mt-0">
                @forelse($sekretariat as $member)
                    <div class="md:w-6/12 lg:w-4/12 xl:w-3/12 w-full flex-[0_0_auto] !px-[15px] max-w-full md:!mt-[40px]">
                        <div class="!relative">
                            <div class="shape !rounded-[.4rem] !bg-[#edf2fc] rellax xl:block lg:block md:block absolute" style="bottom: -0.75rem; right: -0.75rem; width: 98%; height: 98%; z-index:0"></div>
                            <div class="card">
                                <figure class="card-img-top">
                                    <img class="max-w-full h-auto" src="{{ $member->photo ? asset($member->photo) : asset('assets/fe/assets/img/avatars/t1.jpg') }}" alt="{{ $member->name }}" loading="lazy">
                                </figure>
                                <div class="card-body px-6 py-5">
                                    <h4 class="!mb-1">{{ $member->name }}</h4>
                                    <p class="!mb-0 text-[.85rem]">{{ $member->position }}</p>

                                    <!-- Social icons -->
                                    <div class="mt-3 flex items-center space-x-3">
                                        @if($member->instagram)
                                            <a href="{{ $member->instagram }}" aria-label="Instagram" class="text-gray-500 hover:text-pink-600" target="_blank" rel="noopener noreferrer">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M7 2h10a5 5 0 0 1 5 5v10a5 5 0 0 1-5 5H7a5 5 0 0 1-5-5V7a5 5 0 0 1 5-5zm0 2a3 3 0 0 0-3 3v10a3 3 0 0 0 3 3h10a3 3 0 0 0 3-3V7a3 3 0 0 0-3-3H7zm5 3.5a4.5 4.5 0 1 1 0 9 4.5 4.5 0 0 1 0-9zm0 2a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5zM18.5 6a.9.9 0 1 1 0 1.8.9.9 0 0 1 0-1.8z"/></svg>
                                            </a>
                                        @endif
                                        @if($member->facebook)
                                            <a href="{{ $member->facebook }}" aria-label="Facebook" class="text-gray-500 hover:text-blue-600" target="_blank" rel="noopener noreferrer">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M22 12a10 10 0 1 0-11.5 9.9v-7h-2.2V12h2.2V9.6c0-2.2 1.3-3.4 3.2-3.4.9 0 1.8.1 1.8.1v2h-1c-1 0-1.3.6-1.3 1.2V12h2.3l-.4 2.9h-1.9v7A10 10 0 0 0 22 12z"/></svg>
                                            </a>
                                        @endif
                                        @if($member->email)
                                            <a href="mailto:{{ $member->email }}" aria-label="Email" class="text-gray-500 hover:text-green-600">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20 4H4a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2V6a2 2 0 0 0-2-2zm0 4-8 5L4 8V6l8 5 8-5v2z"/></svg>
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <p class="px-6 py-4">Belum ada sekretariat untuk periode ini.</p>
                @endforelse
            </div>
        </div>
    </section>
@endsection
