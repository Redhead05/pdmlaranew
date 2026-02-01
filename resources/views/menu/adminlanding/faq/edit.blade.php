@isset($faq)
    @push('styles')
        <link href="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.snow.css" rel="stylesheet" />
    @endpush

    <!-- Edit Modal for single FAQ (`$faq`) -->
    <div class="modal fade" id="faqEditModal-{{ $faq->id }}" tabindex="-1" aria-labelledby="faqEditModalLabel-{{ $faq->id }}" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <form id="edit-faq-form-{{ $faq->id }}" action="{{ route('adminlanding.faq.update', $faq->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="modal-header">
                        <h5 class="modal-title" id="faqEditModalLabel-{{ $faq->id }}">Edit FAQ</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Question</label>
                            <input type="text" name="question" class="form-control" value="{{ old('question', $faq->question) }}" required maxlength="512">
                        </div>

                        <div class="form-group mb-4">
                            <label class="label text-secondary fs-14">Answer</label>
                            <div id="edit-toolbar-{{ $faq->id }}">
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
                            <div id="edit-editor-{{ $faq->id }}" style="height:200px; border:1px solid #D5D9E2;"></div>
                            <textarea name="answer" id="edit-answer-{{ $faq->id }}" style="display:none;">{{ old('answer', $faq->answer) }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label d-block">Status</label>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="is_active" value="1" {{ old('is_active', $faq->is_active ? '1' : '0') == '1' ? 'checked' : '' }}>
                                <label class="form-check-label">Published</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="is_active" value="0" {{ old('is_active', $faq->is_active ? '1' : '0') == '0' ? 'checked' : '' }}>
                                <label class="form-check-label">Draft</label>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary">Update</button>
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/quill@2.0.3/dist/quill.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                (function initEditModal{{ $faq->id }}() {
                    const editorSelector = '#edit-editor-{{ $faq->id }}';
                    const toolbarSelector = '#edit-toolbar-{{ $faq->id }}';
                    const taId = 'edit-answer-{{ $faq->id }}';
                    const formId = 'edit-faq-form-{{ $faq->id }}';
                    if (!document.querySelector(editorSelector)) return;

                    const quill = new Quill(editorSelector, {
                        theme: 'snow',
                        modules: { toolbar: toolbarSelector, syntax: true }
                    });

                    const initialHtml = {!! json_encode(old('answer', $faq->answer)) !!};
                    if (initialHtml) quill.clipboard.dangerouslyPasteHTML(initialHtml);

                    function sync() {
                        const ta = document.getElementById(taId);
                        if (ta) ta.value = quill.root.innerHTML;
                    }

                    const form = document.getElementById(formId);
                    if (form) form.addEventListener('submit', sync);

                    const modalEl = document.getElementById('faqEditModal-{{ $faq->id }}');
                    if (modalEl) {
                        modalEl.addEventListener('shown.bs.modal', function () {
                            quill.update();
                        });
                    }
                })();
            });
        </script>
    @endpush
@endisset
