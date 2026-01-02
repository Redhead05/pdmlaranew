<form action="{{ route('adminlanding.gallery.update', $gallery->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="modal fade" id="editModal-{{ $gallery->id }}" tabindex="-1" aria-labelledby="editModalLabel-{{ $gallery->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel-{{ $gallery->id }}">Edit: {{ $gallery->title }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

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
                        <div class="col-md-5 text-center">
                            @php
                                $previewSrc = $gallery->image ? asset('storage/' . $gallery->image) : asset('assets/logo_BANPDMJATIM.png');
                            @endphp
                            <label class="form-label fs-15">Preview</label>
                            <div class="mb-2">
                                <img id="gallery-preview-{{ $gallery->id }}" src="{{ $previewSrc }}" alt="preview" style="max-width:100%; height:auto; border-radius:8px;">
                            </div>
                            <div>
                                <input type="file" accept="image/*" id="image-{{ $gallery->id }}" name="image" class="form-control">
                            </div>
                            <small class="text-muted">Leave empty to keep existing image.</small>
                        </div>

                        <div class="col-md-7">
                            <div class="mb-3">
                                <label for="title-{{ $gallery->id }}" class="form-label fs-15">Title</label>
                                <input type="text" class="form-control" id="title-{{ $gallery->id }}" name="title" value="{{ old('title', $gallery->title) }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="description-{{ $gallery->id }}" class="form-label fs-15">Description</label>
                                <textarea class="form-control" id="description-{{ $gallery->id }}" name="description" rows="3">{{ old('description', $gallery->description) }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="category-{{ $gallery->id }}" class="form-label fs-15">Category</label>
                                <select class="form-select" id="category-{{ $gallery->id }}" name="category_id">
                                    <option value="">-- None --</option>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ (int) old('category_id', $gallery->category_id) === $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fs-15 d-block">Status</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="is_active" id="active1-{{ $gallery->id }}" value="1" {{ old('is_active', $gallery->is_active ? '1' : '0') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="active1-{{ $gallery->id }}">Published</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="is_active" id="active0-{{ $gallery->id }}" value="0" {{ old('is_active', $gallery->is_active ? '1' : '0') == '0' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="active0-{{ $gallery->id }}">Draft</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-danger text-white" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary text-white">Update</button>
                </div>
            </div>
        </div>
    </div>
</form>

@push('scripts')
    <script>
        (function () {
            const input = document.getElementById('image-{{ $gallery->id }}');
            const preview = document.getElementById('gallery-preview-{{ $gallery->id }}');
            if (input && preview) {
                input.addEventListener('change', function (e) {
                    const file = e.target.files && e.target.files[0];
                    if (!file) return;
                    const reader = new FileReader();
                    reader.onload = function (ev) { preview.src = ev.target.result; };
                    reader.readAsDataURL(file);
                });
            }
        })();
    </script>
@endpush
