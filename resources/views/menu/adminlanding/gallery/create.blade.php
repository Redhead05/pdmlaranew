<form action="{{ route('adminlanding.gallery.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="modal fade" id="exampleModallg" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <!-- Modal Header -->
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Create Gallery Item</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <!-- Modal Body -->
                <div class="modal-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="row">
                        <!-- Left Column: Image -->
                        <div class="col-md-5">
                            <div class="mb-3 text-center">
                                <label class="form-label fs-15">Preview</label>
                                <div class="mb-2">
                                    <img id="gallery-preview" src="{{ asset('assets/logo_BANPDMJATIM.png') }}" alt="preview"
                                         style="max-width:100%; height:auto; border-radius:8px; box-shadow:0 4px 12px rgba(0,0,0,0.06);" />
                                    <!-- add video element -->
                                    <video id="gallery-video-preview" controls
                                           style="max-width:100%; height:auto; border-radius:8px; box-shadow:0 4px 12px rgba(0,0,0,0.06); display:none;"></video>
                                </div>
                                <div>
                                    <input type="file" accept="image/*,video/*" id="image" name="image" class="form-control" required>
                                </div>
                                <small class="text-muted">Max size: 50MB. Allowed: jpg, png, gif, webp, mp4, mov, avi, webm</small>
                            </div>
                        </div>

                        <!-- Right Column: Meta -->
                        <div class="col-md-7">
                            <div class="mb-3">
                                <label for="title" class="form-label fs-15">Title</label>
                                <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="description" class="form-label fs-15">Caption / Description</label>
                                <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="category" class="form-label fs-15">Category</label>
                                <select class="form-select" id="category" name="category_id">
                                    <option value="">-- None --</option>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fs-15 d-block">Status</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="is_active" id="active1" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="active1">Published</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="is_active" id="active0" value="0" {{ old('is_active') == '0' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="active0">Draft</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary text-white">Save</button>
                </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
    <script>
        // safe preview for image or video with null checks
        (function () {
            const input = document.getElementById('image');
            const img = document.getElementById('gallery-preview');
            const vid = document.getElementById('gallery-video-preview');

            if (!input) return;

            input.addEventListener('change', function (e) {
                const file = e.target.files && e.target.files[0];
                if (!file) return;

                const isVideo = file.type.startsWith('video/');

                if (isVideo) {
                    if (img) img.style.display = 'none';
                    if (vid) {
                        vid.style.display = 'block';
                        // revoke previous object URL if any
                        if (vid._objectUrl) {
                            URL.revokeObjectURL(vid._objectUrl);
                        }
                        vid._objectUrl = URL.createObjectURL(file);
                        vid.src = vid._objectUrl;
                    }
                } else {
                    if (vid) {
                        // stop and clear video
                        try { vid.pause(); } catch (e) {}
                        if (vid._objectUrl) {
                            URL.revokeObjectURL(vid._objectUrl);
                            vid._objectUrl = null;
                        }
                        vid.src = '';
                        vid.style.display = 'none';
                    }
                    if (img) {
                        img.style.display = 'block';
                        const reader = new FileReader();
                        reader.onload = function (ev) { img.src = ev.target.result; };
                        reader.readAsDataURL(file);
                    }
                }
            });
        })();
    </script>
@endpush
