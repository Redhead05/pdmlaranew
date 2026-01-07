<form action="{{ route('adminlanding.news.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="modal fade" id="exampleModallg" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5">Create News Item</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">
                    @if ($errors->any())
                        <div class="alert alert-danger"><ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                        </div>
                    @endif

                    <div class="row">
                        <div class="col-md-5 text-center">
                            <label class="form-label fs-15">Preview</label>
                            <div class="mb-2">
                                <img id="gallery-preview" src="{{ asset('assets/logo_BANPDMJATIM.png') }}" alt="preview" style="max-width:100%; height:auto; border-radius:8px;" />
                            </div>
                            <div>
                                <input type="file" accept="image/*" id="image" name="image" class="form-control">
                            </div>
                            <small class="text-muted">Max size: 2MB. Thumbnail News : PNG</small>
                        </div>

                        <div class="col-md-7">
                            <div class="mb-3">
                                <label class="form-label fs-15">Title</label>
                                <input type="text" class="form-control" name="title" value="{{ old('title') }}" required>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fs-15">Caption / Description</label>
                                <textarea class="form-control" name="description" rows="3">{{ old('description') }}</textarea>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fs-15">Category</label>
                                <select class="form-select" name="category_id">
                                    <option value="">-- None --</option>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label class="form-label fs-15 d-block">Status</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="is_active" value="1" {{ old('is_active', '1') == '1' ? 'checked' : '' }}>
                                    <label class="form-check-label">Published</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="is_active" value="0" {{ old('is_active') == '0' ? 'checked' : '' }}>
                                    <label class="form-check-label">Draft</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

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
        (function () {
            const input = document.getElementById('image');
            const img = document.getElementById('gallery-preview');
            if (!input) return;
            input.addEventListener('change', function (e) {
                const file = e.target.files && e.target.files[0];
                if (!file) return;
                const reader = new FileReader();
                reader.onload = function (ev) { img.src = ev.target.result; };
                reader.readAsDataURL(file);
            });
        })();
    </script>
@endpush
