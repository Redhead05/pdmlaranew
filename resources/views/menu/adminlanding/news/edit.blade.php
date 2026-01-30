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

                                <div class="form-group mb-4">
                                    <div id="toolbar-container-{{ $news->id }}">
                                        <span class="ql-formats">
                                            <select class="ql-font"></select>
                                            <select class="ql-size"></select>
                                        </span>
                                                                    <span class="ql-formats">
                                            <button class="ql-bold"></button>
                                            <button class="ql-italic"></button>
                                            <button class="ql-underline"></button>
                                            <button class="ql-strike"></button>
                                        </span>
                                                                    <span class="ql-formats">
                                            <select class="ql-color"></select>
                                            <select class="ql-background"></select>
                                        </span>
                                                                    <span class="ql-formats">
                                            <button class="ql-script" value="sub"></button>
                                            <button class="ql-script" value="super"></button>
                                        </span>
                                                                    <span class="ql-formats">
                                            <button class="ql-header" value="1"></button>
                                            <button class="ql-header" value="2"></button>
                                            <button class="ql-blockquote"></button>
                                            <button class="ql-code-block"></button>
                                        </span>
                                                                    <span class="ql-formats">
                                            <button class="ql-list" value="ordered"></button>
                                            <button class="ql-list" value="bullet"></button>
                                            <button class="ql-indent" value="-1"></button>
                                            <button class="ql-indent" value="+1"></button>
                                        </span>
                                                                    <span class="ql-formats">
                                            <button class="ql-direction" value="rtl"></button>
                                            <select class="ql-align"></select>
                                        </span>
                                                                    <span class="ql-formats">
                                            <button class="ql-link"></button>
                                            <button class="ql-image"></button>
                                            <button class="ql-video"></button>
                                            <button class="ql-formula"></button>
                                        </span>
                                                                    <span class="ql-formats">
                                            <button class="ql-clean"></button>
                                        </span>
                                    </div>

                                    <div id="editor-{{ $news->id }}" style="height: 250px; border: 1px solid #D5D9E2;" class="rounded-bottom-2"></div>
                                </div>

                                {{-- hidden textarea yang dikirim ke server --}}
                                <textarea name="description" id="description-{{ $news->id }}" style="display:none;">{{ old('description', optional($news->detail)->description) }}</textarea>
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

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/atom-one-dark.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.css" />
@endpush

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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.js"></script>

    <script>
        (function () {
            // Image preview (existing)
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

            // Quill editor init + sync
            document.addEventListener('DOMContentLoaded', function () {
                const editorSelector = '#editor-{{ $news->id }}';
                const toolbarSelector = '#toolbar-container-{{ $news->id }}';
                const ta = document.getElementById('description-{{ $news->id }}');
                if (!document.querySelector(editorSelector)) return;

                const quill = new Quill(editorSelector, {
                    modules: {
                        syntax: true,
                        toolbar: toolbarSelector,
                    },
                    placeholder: '....',
                    theme: 'snow',
                });

                // restore old content if present (old input takes precedence)
                const oldHtml = {!! json_encode(old('description', optional($news->detail)->description)) !!};
                if (oldHtml) {
                    quill.clipboard.dangerouslyPasteHTML(oldHtml);
                }

                function syncQuillToTextarea() {
                    if (!ta) return;
                    ta.value = quill.root.innerHTML;
                }

                const form = document.getElementById('edit-news-form-{{ $news->id }}');
                if (form) {
                    form.addEventListener('submit', syncQuillToTextarea);
                    const submitBtn = form.querySelector('button[type="submit"]');
                    if (submitBtn) submitBtn.addEventListener('click', syncQuillToTextarea);
                }
            });
        })();
    </script>
@endpush
