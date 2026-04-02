<div class="modal fade" id="editModal-{{ $tahap->id }}" tabindex="-1" aria-labelledby="editModalLabel-{{ $tahap->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editModalLabel-{{ $tahap->id }}">Edit Tahap</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form method="POST" action="{{ route('admin.tahap.update', $tahap) }}">
                @csrf
                @method('PUT')

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

                    <div class="mb-3">
                        <label class="form-label">Tahap</label>
                        <input
                            type="text"
                            name="tahap"
                            class="form-control"
                            value="{{ old('tahap', $tahap->tahap) }}"
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Surat Keputusan</label>
                        <input
                            type="text"
                            name="surat_keputusan"
                            class="form-control"
                            value="{{ old('surat_keputusan', $tahap->surat_keputusan) }}"
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Allowed Kesanggupan \(\*\) \(CSV\)</label>
                        <input
                            type="text"
                            name="allowed_kesanggupan_csv"
                            class="form-control"
                            placeholder="2,3,4,5,6"
                            value="{{ old('allowed_kesanggupan_csv', is_array($tahap->allowed_kesanggupan) ? implode(',', $tahap->allowed_kesanggupan) : '') }}"
                            required
                        >
                        <div class="form-text">Example: `2,3,4,10`</div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Start Date</label>
                            <input
                                type="datetime-local"
                                name="start_date"
                                class="form-control"
                                value="{{ old('start_date', optional($tahap->start_date)->format('Y-m-d\TH:i')) }}"
                                required
                            >
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label">End Date</label>
                            <input
                                type="datetime-local"
                                name="end_date"
                                class="form-control"
                                value="{{ old('end_date', optional($tahap->end_date)->format('Y-m-d\TH:i')) }}"
                                required
                            >
                        </div>
                    </div>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update</button>
                </div>
            </form>
        </div>
    </div>
</div>
