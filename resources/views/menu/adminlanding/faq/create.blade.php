<div class="modal fade" id="faqCreateModal" tabindex="-1" aria-labelledby="faqCreateModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <form id="create-faq-form" action="{{ route('adminlanding.faq.store') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="faqCreateModalLabel">Create FAQ</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Question</label>
                        <input type="text" name="question" class="form-control" value="{{ old('question') }}" required maxlength="512">
                    </div>

                    <div class="form-group mb-4">
                        <label class="label text-secondary fs-14">Answer</label>
                        <div id="create-toolbar">
                            <span class="ql-formats">
                                <button class="ql-bold"></button>
                                <button class="ql-italic"></button>
                                <button class="ql-underline"></button>
                            </span>
                            <span class="ql-formats">
                                <button class="ql-list" value="ordered"></button>
                                <button class="ql-list" value="bullet"></button>
                            </span>
                            <span class="ql-formats">
                                <button class="ql-link"></button>
                                <button class="ql-image"></button>
                            </span>
                        </div>

                        <div id="create-editor" style="height:200px; border:1px solid #D5D9E2;"></div>

                        {{-- Hidden textarea that will receive Quill HTML --}}
                        <textarea name="answer" id="create-answer" style="display:none;">{{ old('answer', '') }}</textarea>
                    </div>

                    <div class="mb-3">
                        <label class="form-label d-block">Status</label>
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

                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Create</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </form>
        </div>
    </div>
</div>
@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/styles/atom-one-dark.min.css"
    />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.css" />
@endpush

@push('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.9.0/highlight.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/katex@0.16.9/dist/katex.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            (function initCreateFaqModal() {
                const editorSelector = '#create-editor';
                const toolbarSelector = '#create-toolbar';
                const taId = 'create-answer';
                const formId = 'create-faq-form';

                if (!document.querySelector(editorSelector)) return;

                const quill = new Quill(editorSelector, {
                    theme: 'snow',
                    modules: { toolbar: toolbarSelector, syntax: true }
                });

                // Load old content if present
                const initialHtml = {!! json_encode(old('answer', '')) !!};
                if (initialHtml) quill.clipboard.dangerouslyPasteHTML(initialHtml);

                function sync() {
                    const ta = document.getElementById(taId);
                    if (!ta) return;
                    // Use innerHTML; ensure non-null string
                    const html = quill.root ? quill.root.innerHTML : '';
                    ta.value = html === '<p><br></p>' ? '' : html;
                }

                const form = document.getElementById(formId);
                if (form) {
                    form.addEventListener('submit', function (ev) {
                        sync();
                        // no preventDefault: allow normal submit after syncing
                    });
                }

                // When modal shown, refresh Quill (fix rendering) and focus editor
                const modalEl = document.getElementById('faqCreateModal');
                if (modalEl) {
                    modalEl.addEventListener('shown.bs.modal', function () {
                        quill.update();
                        quill.focus();
                    });
                }
            })();
        });
    </script>
@endpush
