<form id="create-news-form" action="{{ route('adminlanding.news.store') }}" method="POST" enctype="multipart/form-data">
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

                            {{-- Quill editor field --}}
                            <div class="form-group mb-4">
                                <label class="label text-secondary fs-14">Description</label>
                                <div id="toolbar-container">
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

                                    <div id="editor" style="height: 250px; border: 1px solid #D5D9E2;" class="rounded-bottom-2"></div>
                                </div>

                            </div>
                                {{-- hidden textarea yang dikirim ke server --}}
                                <textarea name="description" id="description" style="display:none;">{{ old('description') }}</textarea>

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
                    <button id="submit-btn" type="submit" class="btn btn-primary text-white">Save</button>
                </div>
            </div>
        </div>
    </div>
</form>

@push('styles')
    {{-- Quill CSS --}}
{{--    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">--}}
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/atom-one-dark.min.css"
    />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.css" />
@endpush

@push('scripts')
{{--    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>--}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const quill = new Quill('#editor', {
                modules: {
                    syntax: true,
                    toolbar: '#toolbar-container',
                },
                placeholder: '....',
                theme: 'snow',
            });

            // restore old content if present
            const oldHtml = {!! json_encode(old('description')) !!};
            if (oldHtml) {
                quill.clipboard.dangerouslyPasteHTML(oldHtml);
            }

            // copy Quill HTML into hidden textarea
            function syncQuillToTextarea() {
                const ta = document.getElementById('description');
                if (!ta) return;
                ta.value = quill.root.innerHTML;
            }

            // sync on form submit and submit button click (race prevention)
            const form = document.getElementById('create-news-form');
            if (form) {
                form.addEventListener('submit', syncQuillToTextarea);
            }
            const submitBtn = document.getElementById('submit-btn');
            if (submitBtn) {
                submitBtn.addEventListener('click', syncQuillToTextarea);
            }
        });
    </script>
@endpush
