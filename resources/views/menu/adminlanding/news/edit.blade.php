<form action="{{ route('adminlanding.news.update', $news->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="modal fade" id="editModal-{{ $news->id }}" tabindex="-1" aria-labelledby="editModalLabel-{{ $news->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel-{{ $news->id }}">Edit: {{ $news->title }}</h5>
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
                                $detail = $news->detail ?? null;
                                $previewSrc = $detail && $detail->thumbnail ? Storage::url($detail->thumbnail) : asset('assets/logo_BANPDMJATIM.png');
                            @endphp

                            <label class="form-label fs-15">Preview</label>
                            <div class="mb-2">
                                <img id="news-preview-{{ $news->id }}" src="{{ $previewSrc }}" alt="preview" style="max-width:100%; height:auto; border-radius:8px;">
                            </div>
                            <div>
                                <input type="file" accept="image/*" id="image-{{ $news->id }}" name="image" class="form-control">
                            </div>
                            <small class="text-muted">Leave empty to keep existing thumbnail. Max size: 2MB.</small>
                        </div>

                        <div class="col-md-7">
                            <div class="mb-3">
                                <label for="title-{{ $news->id }}" class="form-label fs-15">Title</label>
                                <input type="text" class="form-control" id="title-{{ $news->id }}" name="title" value="{{ old('title', $news->title) }}" required>
                            </div>

                            <div class="mb-3">
                                <label for="description-{{ $news->id }}" class="form-label fs-15">Caption / Description</label>
                                <textarea class="form-control" id="description-{{ $news->id }}" name="description" rows="3">{{ old('description', optional($news->detail)->description) }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label for="category-{{ $news->id }}" class="form-label fs-15">Category</label>
                                <select class="form-select" id="category-{{ $news->id }}" name="category_id">
                                    <option value="">-- None --</option>
                                    @foreach ($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ (int) old('category_id', $news->category_id) === $cat->id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fs-15 d-block">Status</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="is_active" id="active1-{{ $news->id }}" value="1" {{ old('is_active', $news->is_active ? '1' : '0') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="active1-{{ $news->id }}">Published</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="is_active" id="active0-{{ $news->id }}" value="0" {{ old('is_active', $news->is_active ? '1' : '0') == '0' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="active0-{{ $news->id }}">Draft</label>
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
            const input = document.getElementById('image-{{ $news->id }}');
            const preview = document.getElementById('news-preview-{{ $news->id }}');
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
