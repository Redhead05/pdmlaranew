@php
    use Illuminate\Support\Facades\Route as RouteFacade;
    use Spatie\Permission\Models\Role;
    $auth = auth()->user();
    try {
        $allRoles = Role::pluck('name');
    } catch (\Throwable $e) {
        $allRoles = collect(['asesor','admin','adminlanding','user']);
    }
@endphp

@extends('app.layout')
@section('title', 'My Profile')

@push('styles')
    {{-- Leaflet CSS --}}
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <style>
        /* Ensure map has visible height even if parent layout toggles display */
        #map { min-height: 300px; height: 350px; width: 100%; }
    </style>
@endpush

@section('content')
<div class="container-fluid">
    <div class="main-content d-flex flex-column">
        <div class="card bg-white border-0 rounded-3 mb-4">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h1 class="mb-0">My Profile</h1>
                </div>

                {{-- toast notifications are shown via session('toast') and Bootstrap toast markup below --}}

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ url()->current() }}">
                    @csrf
                    @method('PATCH')

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Nama</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Password (kosongkan jika tidak ingin mengubah)</label>
                            <input type="password" name="password" class="form-control">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Konfirmasi Password</label>
                            <input type="password" name="password_confirmation" class="form-control">
                        </div>

                        <hr class="mt-0 mb-3" />

                        {{-- UserDetail fields --}}
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Jenis Kelamin</label>
                            <select name="gender" class="form-select">
                                <option value="" {{ old('gender', optional($user->detail)->gender)=='' ? 'selected' : '' }}>Pilih</option>
                                <option value="L" {{ old('gender', optional($user->detail)->gender)=='L' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="P" {{ old('gender', optional($user->detail)->gender)=='P' ? 'selected' : '' }}>Perempuan</option>
                                <option value="O" {{ old('gender', optional($user->detail)->gender)=='O' ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>

                        <div class="col-md-8 mb-3">
                            <label class="form-label">Alamat Rumah</label>
                            <input type="text" name="address_home" class="form-control" value="{{ old('address_home', optional($user->detail)->address_home) }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kota Rumah</label>
                            <input type="text" name="home_city" class="form-control" value="{{ old('home_city', optional($user->detail)->home_city) }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Alamat Kerja</label>
                            <input type="text" name="address_work" class="form-control" value="{{ old('address_work', optional($user->detail)->address_work) }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Kota Kerja</label>
                            <input type="text" name="work_city" class="form-control" value="{{ old('work_city', optional($user->detail)->work_city) }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tipe Asesor</label>
                            <select name="type_asesor" class="form-select">
                                <option value="" {{ old('type_asesor', optional($user->detail)->type_asesor)=='' ? 'selected' : '' }}>Pilih</option>
                                <option value="internal" {{ old('type_asesor', optional($user->detail)->type_asesor)=='internal' ? 'selected' : '' }}>Internal</option>
                                <option value="eksternal" {{ old('type_asesor', optional($user->detail)->type_asesor)=='eksternal' ? 'selected' : '' }}>Eksternal</option>
                            </select>
                        </div>

                        {{-- Asesor-only: latitude/longitude + map --}}
                        @if(auth()->check() && method_exists(auth()->user(), 'hasRole') && auth()->user()->hasRole('asesor'))
                            @php $locationEnabled = (bool) optional($user->detail)->location_enabled; @endphp
                            <div class="col-12 mb-3 position-relative">
                                <label class="form-label">Lokasi (klik peta untuk memilih)</label>
                                <div id="map" data-location-enabled="{{ $locationEnabled ? '1' : '0' }}" style="height: 350px; width: 100%;"></div>
                                @unless($locationEnabled)
                                    <div class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="background: rgba(255,255,255,0.6); z-index: 1000;">
                                        <div class="text-center">
                                            <p class="mb-0">Lokasi dinonaktifkan oleh BAN PDM JATIM</p>
                                        </div>
                                    </div>
                                @endunless
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Latitude</label>
                                <input type="text" id="latitude" name="latitude" inputmode="decimal" pattern="[0-9.,+-]*" class="form-control" value="{{ old('latitude', optional($user->detail)->latitude) }}" {{ $locationEnabled ? '' : 'readonly disabled' }}>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="form-label">Longitude</label>
                                <input type="text" id="longitude" name="longitude" inputmode="decimal" pattern="[0-9.,+-]*" class="form-control" value="{{ old('longitude', optional($user->detail)->longitude) }}" {{ $locationEnabled ? '' : 'readonly disabled' }}>
                            </div>
                        @endif

                        {{-- Admin-only fields: role dan is_active --}}
                        @if($auth && method_exists($auth, 'hasAnyRole') && $auth->hasAnyRole(['admin','adminlanding']))
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Role</label>
                                <select name="role" class="form-select">
                                    <option value="">-- Tidak Diganti --</option>
                                    @foreach($allRoles as $r)
                                        <option value="{{ $r }}" {{ (old('role') == $r || ($user->roles->first() && $user->roles->first()->name == $r)) ? 'selected' : '' }}>{{ $r }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-md-6 mb-3 d-flex align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="is_active" id="is_active" value="1" {{ old('is_active', $user->is_active) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="is_active">Active</label>
                                </div>
                            </div>
                        @endif

                    </div>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        <a href="{{ url()->previous() }}" class="btn btn-secondary ms-2">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
    <script>
        function ensureLeafletLoaded(callback) {
            // Ensure CSS
            var cssHref = 'https://unpkg.com/leaflet/dist/leaflet.css';
            var cssPresent = false;
            document.querySelectorAll('link[rel="stylesheet"]').forEach(function(link){
                if (link.href && link.href.indexOf('unpkg.com/leaflet') !== -1) cssPresent = true;
            });
            if (!cssPresent) {
                var lnk = document.createElement('link');
                lnk.rel = 'stylesheet';
                lnk.href = cssHref;
                document.head.appendChild(lnk);
            }

            if (typeof L !== 'undefined') {
                return callback();
            }

            // load script dynamically
            var existing = document.querySelector('script[data-leaflet-loader]');
            if (existing) {
                existing.addEventListener('load', callback);
                return;
            }

            var s = document.createElement('script');
            s.src = 'https://unpkg.com/leaflet/dist/leaflet.js';
            s.async = true;
            s.setAttribute('data-leaflet-loader', '1');
            s.onload = function() { callback(); };
            s.onerror = function() { console.error('Failed to load Leaflet.js'); };
            document.body.appendChild(s);
        }

        document.addEventListener('DOMContentLoaded', function() {
            var mapEl = document.getElementById('map');
            if (!mapEl) {
                console.log('Leaflet: #map element not found; map will not initialise.');
                return;
            }

            ensureLeafletLoaded(function() {
                try {
                    // Default coordinates (fallback)
                    var defaultLat = -7.250445;
                    var defaultLng = 112.768845;

                    var latInput = document.getElementById('latitude');
                    var lngInput = document.getElementById('longitude');

                    var initialLat = defaultLat;
                    var initialLng = defaultLng;

                    if (latInput && latInput.value !== '') {
                        var parsed = parseFloat(latInput.value);
                        if (!isNaN(parsed)) initialLat = parsed;
                    }
                    if (lngInput && lngInput.value !== '') {
                        var parsed2 = parseFloat(lngInput.value);
                        if (!isNaN(parsed2)) initialLng = parsed2;
                    }

                    var map = L.map('map').setView([initialLat, initialLng], 13);

                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        maxZoom: 19,
                        attribution: '© OpenStreetMap'
                    }).addTo(map);

                    setTimeout(function(){ try{ map.invalidateSize(); } catch(e){ console.warn('Leaflet: invalidateSize error', e); } }, 250);

                    // Determine if location editing is enabled for this user
                    var locationEnabled = (mapEl.dataset.locationEnabled === '1');

                    var marker = L.marker([initialLat, initialLng], { draggable: !!locationEnabled }).addTo(map);

                    if (locationEnabled) {
                        // Update inputs when marker moved
                        marker.on('dragend', function(e) {
                            var latlng = e.target.getLatLng();
                            if (latInput) latInput.value = latlng.lat.toFixed(7);
                            if (lngInput) lngInput.value = latlng.lng.toFixed(7);
                        });

                        // Click on map to move marker
                        map.on('click', function(e) {
                            var lat = e.latlng.lat.toFixed(7);
                            var lng = e.latlng.lng.toFixed(7);
                            marker.setLatLng([lat, lng]);
                            if (latInput) latInput.value = lat;
                            if (lngInput) lngInput.value = lng;
                        });
                    } else {
                        // If disabled, visually overlay is added by blade and inputs are readonly; ensure marker not draggable
                        try { marker.dragging && marker.dragging.disable(); } catch(e) {}
                    }

                    // If user typed coordinates manually, update marker
                    if (latInput) latInput.addEventListener('change', function() { updateMarkerFromInputs(); });
                    if (lngInput) lngInput.addEventListener('change', function() { updateMarkerFromInputs(); });

                    function updateMarkerFromInputs() {
                        var lat = latInput ? parseFloat(latInput.value) : NaN;
                        var lng = lngInput ? parseFloat(lngInput.value) : NaN;
                        if (!isNaN(lat) && !isNaN(lng)) {
                            marker.setLatLng([lat, lng]);
                            map.setView([lat, lng], 13);
                        }
                    }

                    // Client-side: normalize latitude/longitude inputs (comma -> dot) while typing and on submit
                    (function(){
                        function normalizeValue(v){
                            if (v === null || v === undefined) return v;
                            v = String(v).trim();
                            // replace comma with dot
                            v = v.replace(/,/g, '.');
                            // collapse multiple dots to single first dot
                            if ((v.match(/\./g) || []).length > 1) {
                                var parts = v.split('.');
                                v = parts.shift() + '.' + parts.join('');
                            }
                            // remove any chars except digits, dot, minus
                            v = v.replace(/[^0-9.\-+]/g, '');
                            // allow single leading + or -
                            if (v.length > 1) {
                                v = v.replace(/(?!^)[+\-]/g, '');
                            }
                            return v;
                        }

                        var latInput = document.getElementById('latitude');
                        var lngInput = document.getElementById('longitude');

                        function attachNormalize(el){
                            if (!el) return;
                            el.addEventListener('input', function(){
                                var val = normalizeValue(this.value);
                                if (this.value !== val) this.value = val;
                            });
                            // also normalize on blur to ensure good formatting
                            el.addEventListener('blur', function(){ this.value = normalizeValue(this.value); });
                        }

                        attachNormalize(latInput);
                        attachNormalize(lngInput);

                        // Normalize on form submit as a final guard
                        var profileForm = document.querySelector('form');
                        if (profileForm) {
                            profileForm.addEventListener('submit', function(){
                                if (latInput) latInput.value = normalizeValue(latInput.value);
                                if (lngInput) lngInput.value = normalizeValue(lngInput.value);
                            });
                        }
                    })();

                    console.log('Leaflet: map initialised at', initialLat, initialLng);
                } catch (err) {
                    console.error('Leaflet initialisation failed', err);
                }
            });
        });
    </script>
@endpush

@push('scripts')
    {{-- Toast container (Bootstrap) --}}
    <div aria-live="polite" aria-atomic="true" class="position-fixed bottom-0 end-0 p-3" style="z-index: 2000;">
        <div id="global-toast-container">
            @if(session('toast'))
                @php $toast = session('toast'); @endphp
                <div class="toast align-items-center text-bg-{{ $toast['type'] ?? 'success' }} border-0 show" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="d-flex">
                        <div class="toast-body">
                            <strong>{{ $toast['title'] ?? '' }}</strong><br />
                            {{ $toast['message'] ?? '' }}
                        </div>
                        <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var toastEl = document.querySelector('#global-toast-container .toast');
            if (!toastEl) return;
            try {
                // Use Bootstrap's Toast if available
                if (typeof bootstrap !== 'undefined' && bootstrap.Toast) {
                    var toast = new bootstrap.Toast(toastEl, { delay: 4000 });
                    toast.show();
                } else {
                    // fallback: simply ensure it is visible and auto-remove after delay
                    setTimeout(function() { toastEl.classList.remove('show'); toastEl.remove(); }, 4000);
                }
            } catch (e) {
                console.error('Toast show failed', e);
            }
        });
    </script>
@endpush
